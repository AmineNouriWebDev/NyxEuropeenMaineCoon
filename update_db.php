<?php
require_once 'includes/config.php';

echo "Updating Database...\n";

// 1. Add description column
try {
    $pdo->exec("ALTER TABLE chats ADD COLUMN description TEXT DEFAULT NULL");
    echo "[OK] Column 'description' added to table 'chats'.\n";
} catch (PDOException $e) {
    // Code 42S21 = Column already exists
    if ($e->getCode() == '42S21') {
        echo "[INFO] Column 'description' already exists.\n";
    } else {
        echo "[ERROR] Adding column: " . $e->getMessage() . "\n";
    }
}

// 2. Create inquiries table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        cat_id VARCHAR(50), 
        name VARCHAR(100) NOT NULL, 
        email VARCHAR(100) NOT NULL, 
        phone VARCHAR(20), 
        message TEXT, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "[OK] Table 'inquiries' created/verified.\n";
} catch (PDOException $e) {
    echo "[ERROR] Creating table: " . $e->getMessage() . "\n";
}

echo "Database update completed.";
?>
