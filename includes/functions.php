<?php
// functions.php - Fonctions utilitaires

/**
 * Échapper les sorties HTML
 */
function esc($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Formater le prix
 */
function format_price($price)
{
    return number_format($price, 0, ',', ' ') . ' €';
}

/**
 * Récupérer les chats depuis la BDD par statut
 */
function get_cats_from_db($pdo, $status = 'available')
{
    try {
        $sql = "SELECT c.*, 
                       f.name AS father_name, 
                       m.name AS mother_name,
                       c.for_sale,
                       c.sale_type,
                       c.stud_price_cad,
                       c.stud_price_usd,
                       c.retirement_price_cad,
                       c.retirement_price_usd,
                       c.sale_description
                FROM chats c
                LEFT JOIN chats f ON c.father_id = f.id
                LEFT JOIN chats m ON c.mother_id = m.id";
        
        if (is_array($status)) {
            $placeholders = str_repeat('?,', count($status) - 1) . '?';
            $sql .= " WHERE c.status IN ($placeholders)";
            $params = $status;
        } else {
            $sql .= " WHERE c.status = ?";
            $params = [$status];
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $cats = $stmt->fetchAll();

        // Pour chaque chat, récupérer les images
        foreach ($cats as &$cat) {
            $cat['images'] = get_cat_images($pdo, $cat['id']);
        }

        return $cats;
    } catch (PDOException $e) {
        error_log("Erreur get_cats_from_db: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupérer les images d'un chat
 */
function get_cat_images($pdo, $cat_id)
{
    try {
        $sql = "SELECT image_path FROM cat_images WHERE cat_id = ? ORDER BY sort_order";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cat_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } catch (PDOException $e) {
        error_log("Erreur get_cat_images: " . $e->getMessage());
        return [];
    }
}

/**
 * Générer l'URL d'une image
 */
function cat_image_url($image_path)
{
    if (strpos($image_path, 'http') === 0) {
        return $image_path;
    }
    return asset_url('img/' . ltrim($image_path, '/'));
}

/**
 * Extraire l'ID YouTube d'une URL
 */
function get_youtube_id($url)
{
    if (empty($url)) return null;

    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    preg_match($pattern, $url, $matches);
    return $matches[1] ?? null;
}

/**
 * Générer l'URL de thumbnail YouTube
 */
function get_youtube_thumbnail($youtube_id)
{
    return "https://img.youtube.com/vi/{$youtube_id}/maxresdefault.jpg";
}
/**
 * Nettoyer le HTML (Anti-XSS)
 * Utilise DOMDocument pour ne garder que les balises et attributs autorisés.
 */
function sanitize_html($html)
{
    if (empty($html)) return '';

    $dom = new DOMDocument();
    // Hack pour l'encodage UTF-8 et éviter que DOMDocument n'encode les caractères spéciaux des URLs
    // En PHP 8.2, mb_convert_encoding avec HTML-ENTITIES est déprécié. On utilise mb_encode_numericentity.
    $html = mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8');
    
    // Configuration libxml
    libxml_use_internal_errors(true);
    // Option importante : HTML_PARSE_NOIMPLIED | HTML_PARSE_NODEFDTD ne suffit parfois pas
    // On wrap dans un div temporaire pour être sûr que ça parse bien les fragments
    $dom->loadHTML("<div>$html</div>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $allowed_tags = [
        'p', 'br', 'b', 'i', 'u', 'strong', 'em', 'a', 'img', 
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 
        'ul', 'ol', 'li', 'blockquote', 'code', 'pre', 'span', 'div'
    ];
    
    $allowed_attrs = ['src', 'href', 'title', 'alt', 'class', 'style', 'target', 'width', 'height'];

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//*');

    foreach ($nodes as $node) {
        if (!in_array($node->nodeName, $allowed_tags)) {
            $node->parentNode->removeChild($node);
        } else {
            // Nettoyage des attributs
            if ($node->hasAttributes()) {
                $attrs_to_remove = [];
                foreach ($node->attributes as $attr) {
                    if (!in_array($attr->nodeName, $allowed_attrs)) {
                        $attrs_to_remove[] = $attr->nodeName;
                    }
                    // Vérification sécu liens (javascript:)
                    if (in_array($attr->nodeName, ['src', 'href'])) {
                        $value = strtolower(trim($attr->nodeValue));
                        if (strpos($value, 'javascript:') === 0 || strpos($value, 'data:') === 0 && $attr->nodeName == 'href') {
                            $attrs_to_remove[] = $attr->nodeName;
                        }
                    }
                }
                foreach ($attrs_to_remove as $attr) {
                    $node->removeAttribute($attr);
                }
            }
        }
    }

    // Récupérer le contenu nettoyé (on a wrappé dans un div, donc on prend son contenu)
    // Mais comme on a parcouru tous les nœuds, le nettoyage est fait sur l'arbre.
    // On sauvegarde le HTML du premier enfant (notre <div> wrapper) et on enlève le div extérieur.
    
    $container = $dom->getElementsByTagName('div')->item(0);
    $output = '';
    if ($container) {
         foreach ($container->childNodes as $child) {
             $output .= $dom->saveHTML($child);
         }
    } else {
        $output = $dom->saveHTML(); // Fallback
    }
    
    return $output;
}

/**
 * Récupérer un chat par ID
 */
function get_cat_by_id($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erreur get_cat_by_id: " . $e->getMessage());
        return null;
    }
}

/**
 * Calculer l'âge à partir de la date de naissance
 */
function calculate_age($birthDate) {
    if (empty($birthDate)) return 'N/A';
    
    $birth = new DateTime($birthDate);
    $now = new DateTime();
    $interval = $birth->diff($now);
    
    if ($interval->y >= 1) {
        return $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
    } elseif ($interval->m >= 1) {
        $days = $interval->d;
        $str = $interval->m . ' mois';
        if ($days > 0) {
            $str .= ' et ' . $days . ' jour' . ($days > 1 ? 's' : '');
        }
        return $str;
    } else {
        return $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
    }
}

/**
 * Formate l'affichage de la couleur avec les effets spéciaux
 */
function format_cat_color($cat) {
    // Si la colonne special_effect n'existe pas ou vide, on retourne la couleur legacy
    if (empty($cat['special_effect'])) {
        return htmlspecialchars($cat['color']);
    }

    $color_display = htmlspecialchars($cat['color']);
    $effects = explode(',', $cat['special_effect']); // Stocké avec virgules en BDD
    $effects_html = '';
    
    foreach ($effects as $eff) {
        $eff = trim($eff);
        if ($eff) {
            // Badge style
            $effects_html .= '<span class="badge badge-dark text-uppercase font-weight-bold mr-1" style="font-size: 0.9em; background-color: #000; color: #fff; padding: 4px 8px; border-radius: 4px;">' . htmlspecialchars($eff) . '</span> ';
            
            // On retire l'effet du nom complet pour éviter la duplication "SMOKE SMOKE Noir"
            // On remplace "SMOKE" par vide de façon insensible à la casse
            $color_display = str_ireplace($eff, '', $color_display);
        }
    }
    
    return $effects_html . trim($color_display);
}
