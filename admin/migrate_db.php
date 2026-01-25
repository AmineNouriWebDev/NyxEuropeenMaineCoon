<?php
require_once '../includes/config.php';

try {
    echo "<h2>Début de la migration de la base de données...</h2>";

    // 1. Ajout des colonnes pour les prix USD et renommage logique (on garde price pour CAD pour compatibilité ou on ajoute explicitement)
    // On va ajouter price_cad (copie de price) et price_usd.
    // Pour éviter de casser le code existant immédiatement, on peut garder 'price' comme 'price_cad' implicitement, 
    // mais pour la clarté demandée "afficher les prix en dollar canadien et en dollar américain", on va créer des colonnes explicites.
    
    $cols = [
        "ADD COLUMN price_cad DECIMAL(10,2) NULL AFTER price",
        "ADD COLUMN old_price_cad DECIMAL(10,2) NULL AFTER old_price",
        "ADD COLUMN price_usd DECIMAL(10,2) NULL AFTER price_cad",
        "ADD COLUMN old_price_usd DECIMAL(10,2) NULL AFTER price_usd",
        "ADD COLUMN paw_type ENUM('Régulières', 'Polydactiles') DEFAULT 'Régulières' AFTER gender",
        "ADD COLUMN mother_id VARCHAR(255) NULL AFTER id",
        "ADD COLUMN father_id VARCHAR(255) NULL AFTER mother_id",
        "ADD COLUMN birth_date DATE NULL AFTER gender" // Au cas où il n'existe pas encore
    ];

    foreach ($cols as $sql) {
        try {
            $pdo->exec("ALTER TABLE chats $sql");
            echo "Exécuté: $sql <br>";
        } catch (PDOException $e) {
            echo "Ignoré (existe déjà ou erreur): " . $e->getMessage() . "<br>";
        }
    }

    // 2. Migration des données existantes (price -> price_cad)
    $pdo->exec("UPDATE chats SET price_cad = price WHERE price_cad IS NULL");
    $pdo->exec("UPDATE chats SET old_price_cad = old_price WHERE old_price_cad IS NULL");
    echo "Données de prix migrées vers CAD.<br>";

    // 3. Suppression des colonnes obsolètes (Optionnel, on peut les garder pour backup au début)
    // $pdo->exec("ALTER TABLE chats DROP COLUMN weight"); 
    // $pdo->exec("ALTER TABLE chats DROP COLUMN age_text");
    echo "Colonnes obsolètes (weight, age_text) conservées pour backup pour l'instant.<br>";

    echo "<h3>Migration terminée avec succès !</h3>";
    echo "<a href='cats.php'>Retour à la liste des chats</a>";

} catch (PDOException $e) {
    die("Erreur fatale de migration : " . $e->getMessage());
}
?>
