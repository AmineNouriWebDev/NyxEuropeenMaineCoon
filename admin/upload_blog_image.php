<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php'; // Sécurité : Seuls les admins peuvent upload

// Configure upload directory
$uploadDir = '../img/blog/content/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Allowed origins (pour CORS si besoin, ici local)
$accepted_origins = [SITE_URL, "http://localhost"];

if (isset($_FILES['file']['name'])) {
    if (!$_FILES['file']['error']) {
        $name = md5(rand(100, 200));
        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . end($ext);
        $destination = $uploadDir . $filename;
        $location = $_FILES["file"]["tmp_name"];
        
        // Validation simple type mime
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $location);
        
        if (in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            move_uploaded_file($location, $destination);
            
            // Réponse JSON pour TinyMCE
            // Retourner chemin relatif par rapport à la racine du site
            // TinyMCE aime bien les chemins relatifs ou absolus complets.
            // On utilise le chemin web absolu (ex: /cat/img/...) pour éviter les problèmes de ../
            
            // Calcul du chemin WEB vers l'image
            // Si le script est dans /cat/admin/upload...php
            // On veut /cat/img/blog/content/filename
            
            $webPath = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath($destination));
            // Correction slashes Windows
            $webPath = str_replace('\\', '/', $webPath);
            
            // Fallback si realpath échoue (hébergeurs bizarres)
            if (empty($webPath)) {
                 $webPath = asset_url('img/blog/content/' . $filename);
            }

            echo json_encode([
                'location' => $webPath
            ]);
        } else {
            header("HTTP/1.1 400 Invalid extension.");
            echo json_encode(['error' => 'Type de fichier non autorisé.']);
        }
    } else {
        header("HTTP/1.1 500 Server Error");
        echo json_encode(['error' => 'Erreur upload serveur.']);
    }
} else {
    header("HTTP/1.1 400 Input Missing");
    echo json_encode(['error' => 'Aucun fichier reçu.']);
}
