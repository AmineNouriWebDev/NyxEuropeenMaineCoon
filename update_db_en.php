<?php
// update_db_en.php
$_SERVER['HTTP_HOST'] = 'localhost'; // Mock for CLI
require_once 'includes/config.php';

try {
    echo "Updating database schema for English support...\n";

    // 1. Add columns to 'chats'
    // Check if exists first to avoid error (though ADD COLUMN IF NOT EXISTS is MariaDB 10.2+)
    // We'll just try/catch or use silent execution
    
    $alterChats = "
        ALTER TABLE chats 
        ADD COLUMN description_en TEXT DEFAULT NULL AFTER description,
        ADD COLUMN sale_description_en TEXT DEFAULT NULL AFTER sale_description;
    ";
    
    try {
        $pdo->exec($alterChats);
        echo "✅ Added description_en and sale_description_en to 'chats'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
             echo "⚠️ Columns already exist in 'chats'.\n";
        } else {
             throw $e;
        }
    }

    // 2. Add columns to 'upcoming_litters'
    $alterLitters = "
        ALTER TABLE upcoming_litters 
        ADD COLUMN season_text_en VARCHAR(255) DEFAULT NULL AFTER season_text,
        ADD COLUMN description_en TEXT DEFAULT NULL AFTER description;
    ";

    try {
        $pdo->exec($alterLitters);
        echo "✅ Added season_text_en and description_en to 'upcoming_litters'.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
             echo "⚠️ Columns already exist in 'upcoming_litters'.\n";
        } else {
             throw $e;
        }
    }

    echo "Database update completed successfully.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
