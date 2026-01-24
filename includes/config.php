<?php
// config.php - Configuration multi-environnement automatique

// Détection automatique : Si localhost ou IP locale, on est en local
$whitelist = ['127.0.0.1', '::1', 'localhost'];
define('IS_LOCAL', in_array($_SERVER['HTTP_HOST'], $whitelist) || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if (IS_LOCAL) {
    // CONFIGURATION LOCALE
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'nyxcooncattery_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    // DÉTECTION AUTOMATIQUE DU CHEMIN (Pour supporter localhost/cat/)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    // Normalisation Windows (remplacer \ par /)
    $scriptDir = str_replace('\\', '/', $scriptDir);
    // On nettoie pour avoir la racine du projet
    $scriptDir = str_replace(['/admin', '/includes'], '', $scriptDir);
    // Supprimer le slash final s'il existe (sauf si racine pure)
    $scriptDir = rtrim($scriptDir, '/');
    
    // Si à la racine, ça peut être vide
    $baseUrl = $scriptDir;
    
    define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $baseUrl);
} else {
    // CONFIGURATION PRODUCTION (VPS)
    define('DB_HOST', 'mysql');
    define('DB_NAME', 'nyxcooncattery_db');
    define('DB_USER', 'deposark_user');
    define('DB_PASS', 'Azerty1234***');
    define('SITE_URL', 'https://nyxcooncattery.com');
}

// Fonctions de chemins SIMPLES
function base_url($path = '')
{
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset_url($path = '')
{
    return base_url($path);
}

// Connexion PDO
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if (IS_LOCAL) {
        die("❌ Erreur BDD locale: " . $e->getMessage());
    } else {
        error_log("Erreur BDD production: " . $e->getMessage());
        die("Erreur de connexion. Veuillez réessayer.");
    }
}

date_default_timezone_set('Europe/Paris');
