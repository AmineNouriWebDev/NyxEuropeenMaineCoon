<?php
// sitemap.php - Génération dynamique du sitemap XML
require_once 'includes/config.php';
require_once 'includes/functions.php';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// 1. Pages statiques
$pages = [
    '' => 'daily', // Page d'accueil (index.php)
    'kings.php' => 'weekly',
    'queens.php' => 'weekly',
    'chatons_reserves.php' => 'weekly',
    'portees_a_venir.php' => 'weekly',
    'adoption.php' => 'monthly',
    'contact.php' => 'yearly',
    // Versions anglaises
    'en/' => 'daily',
    'en/kings.php' => 'weekly',
    'en/queens.php' => 'weekly',
    'en/chatons_reserves.php' => 'weekly',
    'en/portees_a_venir.php' => 'weekly',
    'en/adoption.php' => 'monthly',
    'en/contact.php' => 'yearly',
];

foreach ($pages as $page => $freq) {
    if ($page === '') {
        $url = SITE_URL; // Base URL propre sans slash final selon config
    } else {
        $url = SITE_URL . '/' . $page;
    }
    
    echo '<url>';
    echo '<loc>' . htmlspecialchars($url) . '</loc>';
    echo '<changefreq>' . $freq . '</changefreq>';
    echo '<priority>' . ($page === '' || $page === 'en/' ? '1.0' : '0.8') . '</priority>';
    echo '</url>';
}

// 2. Chatons Dynamiques (Détails)
// Récupérer tous les chats actifs visible (available, reserved, sold, king, queen)
try {
    $stmt = $pdo->query("SELECT id, status FROM chats ORDER BY id DESC");
    while ($row = $stmt->fetch()) {
        $priority = '0.6';
        if ($row['status'] === 'available') {
            $priority = '0.9'; // Plus important si disponible
        }
        
        // Version FR
        echo '<url>';
        echo '<loc>' . htmlspecialchars(SITE_URL . '/chat_details.php?id=' . $row['id']) . '</loc>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>' . $priority . '</priority>';
        echo '</url>';
        
        // Version EN
        echo '<url>';
        echo '<loc>' . htmlspecialchars(SITE_URL . '/en/chat_details.php?id=' . $row['id']) . '</loc>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>' . $priority . '</priority>';
        echo '</url>';
    }
} catch (PDOException $e) {
    // Silencieux en production pour le sitemap
}

echo '</urlset>';
?>
