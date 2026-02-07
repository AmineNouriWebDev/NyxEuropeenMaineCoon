<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Récupération et nettoyage des données
$cat_id = $_POST['cat_id'] ?? '';
$cat_name = $_POST['cat_name'] ?? '';
$visitor_name = trim($_POST['visitor_name'] ?? '');
$visitor_email = trim($_POST['visitor_email'] ?? '');
$visitor_phone = trim($_POST['visitor_phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation basique
if (empty($visitor_name) || empty($visitor_email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields (Name, Email, Message).']);
    exit;
}

if (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO adoption_requests (cat_id, cat_name, visitor_name, visitor_email, visitor_phone, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$cat_id, $cat_name, $visitor_name, $visitor_email, $visitor_phone, $message, date('Y-m-d H:i:s')]);

    echo json_encode(['success' => true, 'message' => 'Your request has been sent successfully! We will contact you soon.']);
} catch (PDOException $e) {
    error_log("Adoption request error : " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while saving. Please try again.']);
}
