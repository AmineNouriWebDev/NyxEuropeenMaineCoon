<?php
require_once 'includes/config.php';
$stmt = $pdo->query("DESCRIBE chats");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($columns);
echo "</pre>";
?>
