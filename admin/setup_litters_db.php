<?php
require_once '../includes/config.php';

echo "<h1>Configuration de la table Portées à Venir & Liste d'Attente</h1>";

try {
    // Table upcoming_litters
    $sql1 = "CREATE TABLE IF NOT EXISTS upcoming_litters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        father_id VARCHAR(50) NOT NULL,
        mother_id VARCHAR(50) NOT NULL,
        season_text VARCHAR(100),
        description TEXT,
        expected_colors TEXT,
        is_active TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql1);
    echo "<div style='color: green;'>✅ Table 'upcoming_litters' configurée.</div>";

    // Table waiting_list
    $sql2 = "CREATE TABLE IF NOT EXISTS waiting_list (
        id INT AUTO_INCREMENT PRIMARY KEY,
        litter_id INT,
        visitor_name VARCHAR(100),
        visitor_email VARCHAR(100),
        visitor_phone VARCHAR(20),
        message TEXT,
        status ENUM('new', 'contacted', 'archived') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql2);
    echo "<div style='color: green;'>✅ Table 'waiting_list' configurée.</div>";

} catch (PDOException $e) {
    echo "<div style='color: red;'>❌ Erreur : " . $e->getMessage() . "</div>";
}
?>
<p><a href="../index.php">Retour accueil</a></p>
