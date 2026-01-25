<?php
require_once '../includes/config.php';

echo "<h1>Configuration de la table Démandes d'Adoption</h1>";

try {
    $sql = "CREATE TABLE IF NOT EXISTS adoption_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cat_id VARCHAR(50),
        cat_name VARCHAR(100),
        visitor_name VARCHAR(100) NOT NULL,
        visitor_email VARCHAR(100) NOT NULL,
        visitor_phone VARCHAR(20),
        message TEXT,
        status ENUM('new', 'read', 'archived') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>
            ✅ Table 'adoption_requests' créée ou déjà existante avec succès !
          </div>";

} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>
            ❌ Erreur : " . $e->getMessage() . "
          </div>";
}
?>
<p><a href="../index.php">Retour à l'accueil</a></p>
