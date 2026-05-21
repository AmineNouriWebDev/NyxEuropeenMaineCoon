<?php
// config.php - Configuration multi-environnement automatique

// Détection automatique : Si localhost ou IP locale, on est en local
$whitelist = ['127.0.0.1', '::1', 'localhost'];
$http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('IS_LOCAL', (php_sapi_name() === 'cli') || in_array($http_host, $whitelist) || strpos($http_host, 'localhost') !== false);

// Cloudflare Turnstile Keys (Local Testing vs Production)
if (IS_LOCAL) {
    // Clés de test Cloudflare (toujours valides pour le développement local)
    define('TURNSTILE_SITE_KEY', '1x00000000000000000000AA');
    define('TURNSTILE_SECRET_KEY', '1x0000000000000000000000000000000AA');
} else {
    // Vos clés réelles pour la production (nyxcooncattery.com)
    define('TURNSTILE_SITE_KEY', '0x4AAAAAAC03RNl2Mza8QVL6');
    define('TURNSTILE_SECRET_KEY', '0x4AAAAAAC03RALWMeAoeyOjHxFH28bwsy8');
}

if (IS_LOCAL) {
    // CONFIGURATION LOCALE
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_NAME', getenv('DB_NAME') ?: 'nyxcooncattery_db');
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
    // DÉTECTION AUTOMATIQUE DU CHEMIN (Pour supporter localhost/cat/)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    // Normalisation Windows (remplacer \ par /)
    $scriptDir = str_replace('\\', '/', $scriptDir);
    // On nettoie pour avoir la racine du projet
    $scriptDir = str_replace(['/admin', '/includes', '/en'], '', $scriptDir);
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

date_default_timezone_set('America/Montreal');
