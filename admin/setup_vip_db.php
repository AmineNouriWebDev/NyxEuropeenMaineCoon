<?php
require_once '../includes/config.php';

echo "<h1>Configuration de la Table VIP Requests</h1>";

try {
    $sql = "CREATE TABLE IF NOT EXISTS vip_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(100) NOT NULL,
        country VARCHAR(100),
        address TEXT,
        city VARCHAR(100),
        postal_code VARCHAR(20),
        family_description TEXT,
        existing_pets TEXT,
        environment_type TEXT,
        hear_about_us VARCHAR(255),
        color_preferences VARCHAR(255),
        gender_preference VARCHAR(50),
        adoption_date_year INT,
        adoption_date_month INT,
        adoption_date_day INT,
        is_approved_deposit TINYINT DEFAULT 0,
        status ENUM('new', 'contacted', 'archived') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "<div style='color: green;'>✅ Table 'vip_requests' créée avec succès.</div>";

} catch (PDOException $e) {
    echo "<div style='color: red;'>❌ Erreur : " . $e->getMessage() . "</div>";
}
?>
<p><a href="../index.php">Retour accueil</a></p>
