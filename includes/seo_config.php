<?php
/**
 * includes/seo_config.php
 * Configuration centralisée pour le SEO (Français & Anglais)
 * Gère les titres, descriptions, canonicals, et Open Graph
 */

// Détection de la langue via l'URL (si '/en/' est dans le chemin)
$is_english = (strpos($_SERVER['REQUEST_URI'], '/en/') !== false);

// URL de base canonique (sans paramètres)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$canonical_url = $protocol . '://' . $host . $path;

// Variables par défaut
$meta_title = $is_english 
    ? "Nyx European Maine Coon - Breeder in Montreal" 
    : "Nyx European Maine Coon - Élevage à Montréal";

$meta_description = $is_english
    ? "Discover our European Maine Coon kittens raised with love in Montreal. Health, wild look, and exceptional temperament guaranteed."
    : "Découvrez nos chatons Maine Coon Européens élevés avec amour à Montréal. Santé, look sauvage et caractère exceptionnel garantis.";

$meta_keywords = $is_english
    ? "Maine Coon, kittens, breeder, Montreal, cats, adoption, European Maine Coon"
    : "Maine Coon, chatons, élevage, Montréal, chats, adoption, Maine Coon Européens";

// Image par défaut pour le partage
$og_image = 'img/logo_principal.png';
if (function_exists('asset_url')) {
    $og_image = asset_url($og_image);
}

// Page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// Logique par page
switch ($current_page) {
    case 'index.php':
        // Accueil
        if ($is_english) {
            $meta_title = "Available Kittens & Cats | Nyx European Maine Coon";
            $meta_description = "Adopt a European Maine Coon kitten in Montreal. Check our available kittens with wild looks and great health.";
        } else {
            $meta_title = "Chatons & Chats Disponibles | Nyx European Maine Coon";
            $meta_description = "Adoptez un chaton Maine Coon Européen à Montréal. Consultez nos chatons disponibles avec un look sauvage et une excellente santé.";
        }
        break;

    case 'kings.php':
        if ($is_english) {
            $meta_title = "Our Kings | Nyx European Maine Coon";
            $meta_description = "Meet our magnificent European Maine Coon males. Selected for their size, type, and health.";
        } else {
            $meta_title = "Nos Rois (Kings) | Nyx European Maine Coon";
            $meta_description = "Rencontrez nos magnifiques mâles Maine Coon Européens. Sélectionnés pour leur gabarit, leur type et leur santé.";
        }
        break;

    case 'queens.php':
        if ($is_english) {
            $meta_title = "Our Queens | Nyx European Maine Coon";
            $meta_description = "Discover our beautiful European Maine Coon females, the mothers of our exceptional kittens.";
        } else {
            $meta_title = "Nos Reines (Queens) | Nyx European Maine Coon";
            $meta_description = "Découvrez nos superbes femelles Maine Coon Européennes, les mamans de nos chatons exceptionnels.";
        }
        break;

    case 'chatons_reserves.php':
        if ($is_english) {
            $meta_title = "Reserved Kittens | Nyx European Maine Coon";
            $meta_description = "See our previously adopted Maine Coon kittens who have found their forever homes.";
        } else {
            $meta_title = "Chatons Réservés | Nyx European Maine Coon";
            $meta_description = "Voir nos chatons Maine Coon précédemment adoptés qui ont trouvé leur famille pour la vie.";
        }
        break;

    case 'portees_a_venir.php':
        if ($is_english) {
            $meta_title = "Upcoming Litters | Nyx European Maine Coon";
            $meta_description = "Plan your adoption! Check out our upcoming European Maine Coon litters and join the waitlist.";
        } else {
            $meta_title = "Portées à Venir | Nyx European Maine Coon";
            $meta_description = "Planifiez votre adoption ! Découvrez nos futures portées de Maine Coon Européens et rejoignez la liste d'attente.";
        }
        break;

    case 'adoption.php':
        if ($is_english) {
            $meta_title = "Adoption Process | Nyx European Maine Coon";
            $meta_description = "Everything you need to know about adopting a kitten from Nyx Maine Coon. Prices, conditions, and steps.";
        } else {
            $meta_title = "Processus d'Adoption | Nyx European Maine Coon";
            $meta_description = "Tout ce qu'il faut savoir pour adopter un chaton chez Nyx Maine Coon. Prix, conditions et étapes.";
        }
        break;

    case 'contact.php':
        if ($is_english) {
            $meta_title = "Contact Us | Nyx European Maine Coon";
            $meta_description = "Get in touch with us for any questions about our Maine Coon kittens or our cattery in Montreal.";
        } else {
            $meta_title = "Contactez-nous | Nyx European Maine Coon";
            $meta_description = "Entrez en contact avec nous pour toute question sur nos chatons Maine Coon ou notre chatterie à Montréal.";
        }
        break;
        
    case 'about.php':
        if ($is_english) {
            $meta_title = "About Us | Nyx European Maine Coon";
        } else {
            $meta_title = "À Propos | Nyx European Maine Coon";
        }
        break;

    case 'chat_details.php':
        // Gestion dynamique pour les détails d'un chat
        // On suppose que $cat est déjà disponible car header.php est inclus après la récupération des données dans chat_details.php
        if (isset($cat) && is_array($cat)) {
            $cat_name = isset($cat['name']) ? htmlspecialchars($cat['name']) : 'Chaton';
            $cat_color = isset($cat['color_name_fr']) ? htmlspecialchars($cat['color_name_fr']) : '';
            $cat_breed = "Maine Coon"; 
            
            if ($is_english) {
                // Essayer de récupérer le nom de couleur EN si dispo (ajustement possible si passé dans $cat)
                // Pour l'instant on utilise le nom par défaut ou FR s'il n'y a pas de trad chargée ici
                $meta_title = "$cat_name - European Maine Coon | Details";
                $meta_description = "Discover $cat_name, a beautiful $cat_breed. See photos, characteristics and availability.";
            } else {
                $meta_title = "$cat_name - Maine Coon Européen | Détails";
                $meta_description = "Découvrez $cat_name, un magnifique $cat_breed $cat_color. Voir photos, caractéristiques et disponibilité.";
            }
            
            // Image spécifique pour le partage (première image du chat si dispo)
            // On doit refaire une requête légère ou utiliser les images si déjà chargées
            // Dans votre chat_details.php, $images est chargé. On utilise la première.
            if (isset($images) && !empty($images) && is_array($images)) {
                if (function_exists('asset_url')) {
                    $og_image = asset_url('img/' . $images[0]);
                } else {
                     $og_image = 'img/' . $images[0];
                }
            }
            
            // URL Canonique spécifique avec l'ID
            if (isset($cat['id'])) {
                $canonical_url .= '?id=' . $cat['id'];
            }
        }
        break;
}
?>
