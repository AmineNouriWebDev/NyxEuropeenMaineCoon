<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
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
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires (Nom, Email, Message).']);
    exit;
}

if (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO adoption_requests (cat_id, cat_name, visitor_name, visitor_email, visitor_phone, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$cat_id, $cat_name, $visitor_name, $visitor_email, $visitor_phone, $message]);

    echo json_encode(['success' => true, 'message' => 'Votre demande a été envoyée avec succès ! Nous vous contacterons bientôt.']);
} catch (PDOException $e) {
    error_log("Erreur demande adoption : " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.']);
}
