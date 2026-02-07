<?php
/**
 * generate_icons.php
 * Script utilitaire pour créer toutes les tailles d'icônes nécessaires
 * à partir de votre logo principal.
 */

$source_file = 'img/favicon.png';
$output_dir = 'img/';

if (!file_exists($source_file)) {
    die("Erreur : Le fichier source $source_file n'existe pas.");
}

$icons = [
    'favicon-16x16.png' => 16,
    'favicon-32x32.png' => 32,
    'apple-touch-icon.png' => 180,
    'android-chrome-192x192.png' => 192,
    'android-chrome-512x512.png' => 512
];

function resize_image($source_path, $destination_path, $size) {
    list($width, $height) = getimagesize($source_path);
    $img_source = imagecreatefrompng($source_path);
    
    $img_dest = imagecreatetruecolor($size, $size);
    
    // Garder la transparence
    imagealphablending($img_dest, false);
    imagesavealpha($img_dest, true);
    $transparent = imagecolorallocatealpha($img_dest, 255, 255, 255, 127);
    imagefilledrectangle($img_dest, 0, 0, $size, $size, $transparent);
    
    imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $size, $size, $width, $height);
    
    imagepng($img_dest, $destination_path);
    imagedestroy($img_source);
    imagedestroy($img_dest);
}

echo "Génération des icônes en cours...<br>";

foreach ($icons as $filename => $size) {
    resize_image($source_file, $output_dir . $filename, $size);
    echo "✓ Créé : $filename ({$size}x{$size})<br>";
}

echo "<br>Terminé ! Vous pouvez maintenant supprimer ce fichier (generate_icons.php) et faire votre git push.";
?>
