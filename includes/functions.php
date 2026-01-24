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
 * Récupérer les chats depuis la BDD
 */
function get_cats_from_db($pdo)
{
    try {
        $sql = "SELECT * FROM chats WHERE status = 'available' ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);
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
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    
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
