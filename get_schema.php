<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'includes/config.php';

function getTableCreateSql($pdo, $tableName) {
    try {
        $stmt = $pdo->query("SHOW CREATE TABLE $tableName");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Create Table'] . ";\n\n";
    } catch (Exception $e) {
        return "Error getting schema for $tableName: " . $e->getMessage() . "\n\n";
    }
}

echo "=== DATABASE SCHEMA ===\n";
echo getTableCreateSql($pdo, 'chats');
echo getTableCreateSql($pdo, 'upcoming_litters');
echo getTableCreateSql($pdo, 'colors');
?>
