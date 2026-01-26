<?php
require_once '../includes/config.php';

echo "<h1>Configuration de la Table Couleurs et Migration</h1>";

try {
    // 1. Création table colors
    $sql1 = "CREATE TABLE IF NOT EXISTS colors (
        code VARCHAR(10) PRIMARY KEY,
        name_fr VARCHAR(100) NOT NULL,
        name_en VARCHAR(100) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql1);
    echo "✅ Table 'colors' créée.<br>";

    // 2. Colonnes dans chats
    // On vérifie si les colonnes existent
    $cols = $pdo->query("SHOW COLUMNS FROM chats")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('color_code', $cols)) {
        $pdo->exec("ALTER TABLE chats ADD COLUMN color_code VARCHAR(10) AFTER color");
        echo "✅ Colonne 'color_code' ajoutée.<br>";
    }
    
    if (!in_array('special_effect', $cols)) {
        $pdo->exec("ALTER TABLE chats ADD COLUMN special_effect VARCHAR(100) AFTER color_code");
        echo "✅ Colonne 'special_effect' ajoutée.<br>";
    }

    // 3. Peuplement des couleurs
    $colors_data = [
        ['w', 'blanc'],
        ['n', 'noir'],
        ['n01', 'noir van'],
        ['n02', 'noir harlequin'],
        ['n03', 'noir bicolore'],
        ['n09', 'noir et blanc'],
        ['n11', 'noir ombré'],
        ['n12', 'noir shell'],
        ['n21', 'noir tabby'],
        ['n22', 'noir tigré classique'],
        ['n23', 'noir mackerel tigré'],
        ['n24', 'noir spotted tigré'],
        ['n25', 'noir ticked tigré'],
        ['a', 'gris'],
        ['a01', 'gris van'],
        ['a02', 'gris harlequin'],
        ['a03', 'gris bicolore'],
        ['a09', 'gris et blanc'],
        ['a11', 'gris ombré'],
        ['a12', 'gris shell'],
        ['a21', 'gris tabby'],
        ['a22', 'gris tigré classique'],
        ['a23', 'gris mackerel tigré'],
        ['a24', 'gris spotted tigré'],
        ['a25', 'gris ticked tigré'],
        ['d', 'roux'],
        ['d01', 'roux van'],
        ['d02', 'roux harlequin'],
        ['d03', 'roux bicolore'],
        ['d09', 'roux et blanc'],
        ['d11', 'roux ombré'],
        ['d12', 'roux shell'],
        ['d21', 'roux tabby'],
        ['d22', 'roux tigré classique'],
        ['d23', 'roux mackerel tigré'],
        ['d24', 'roux spotted tigré'],
        ['d25', 'roux ticked tigré'],
        ['e', 'crème'],
        ['e01', 'crème van'],
        ['e02', 'crème harlequin'],
        ['e03', 'crème bicolore'],
        ['e09', 'crème et blanc'],
        ['e11', 'crème ombré'],
        ['e12', 'crème shell'],
        ['e21', 'crème tabby'],
        ['e22', 'crème tigré classique'],
        ['e23', 'crème mackerel tigré'],
        ['e24', 'crème spotted tigré'],
        ['e25', 'crème ticked tigré'],
        ['f', 'noir tortie'],
        ['f01', 'noir tortie van'],
        ['f02', 'noir tortie harlequin'],
        ['f03', 'noir tortie bicolore'],
        ['f09', 'noir tortie et blanc'],
        ['f11', 'noir tortie ombré'],
        ['f12', 'noir tortie shell'],
        ['f21', 'noir tortie tabby'],
        ['f22', 'noir tortie tigré classique'],
        ['f23', 'noir tortie mackerel tigré'],
        ['f24', 'noir tortie spotted tigré'],
        ['f25', 'noir tortie ticked tigré'],
        ['g', 'gris tortie'],
        ['g01', 'gris tortie van'],
        ['g02', 'gris tortie harlequin'],
        ['g03', 'gris tortie bicolore'],
        ['g09', 'gris tortie et blanc'],
        ['g11', 'gris tortie ombré'],
        ['g12', 'gris tortie shell'],
        ['g21', 'gris tortie tabby'],
        ['g22', 'gris tortie tigré classique'],
        ['g23', 'gris tortie mackerel tigré'],
        ['g24', 'gris spotted tigré'],
        ['g25', 'gris ticked tigré']
    ];

    $stmt = $pdo->prepare("INSERT INTO colors (code, name_fr) VALUES (?, ?) ON DUPLICATE KEY UPDATE name_fr = VALUES(name_fr)");
    foreach ($colors_data as $c) {
        $stmt->execute([$c[0], $c[1]]);
    }
    echo "✅ Les couleurs ont été insérées/mises à jour.<br>";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>
<p><a href="../index.php">Retour accueil</a></p>
