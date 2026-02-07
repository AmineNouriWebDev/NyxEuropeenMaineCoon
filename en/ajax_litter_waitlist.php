<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$litter_id = $_POST['litter_id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($litter_id)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in the required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO waiting_list (litter_id, visitor_name, visitor_email, visitor_phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$litter_id, $name, $email, $phone, $message]);
    
    // Notification mail (optional)
    $to = "nouri.medamine1987@gmail.com";
    $subject = "New waiting list registration - " . $name;
    $body = "Name: $name\nEmail: $email\nMessage: $message";
    // mail($to, $subject, $body);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
