<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupération et nettoyage
$fields = [
    'first_name', 'last_name', 'phone', 'email', 
    'address', 'city', 'postal_code', 'country',
    'family_description', 'existing_pets', 'environment_type',
    'hear_about_us', 'color_preferences', 'gender_preference', 
    'date_year', 'date_month', 'date_day', 'questions'
];

$data = [];
foreach ($fields as $field) {
    $data[$field] = trim($_POST[$field] ?? '');
}

// Validation basique
if (empty($data['email']) || empty($data['first_name']) || empty($data['last_name'])) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir les champs obligatoires.']);
    exit;
}

try {
    $sql = "INSERT INTO vip_requests (
        first_name, last_name, phone, email, 
        address, city, postal_code, country,
        family_description, existing_pets, environment_type,
        hear_about_us, color_preferences, gender_preference,
        adoption_date_year, adoption_date_month, adoption_date_day,
        is_approved_deposit
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data['first_name'], $data['last_name'], $data['phone'], $data['email'],
        $data['address'], $data['city'], $data['postal_code'], $data['country'],
        $data['family_description'], $data['existing_pets'], $data['environment_type'],
        $data['hear_about_us'], $data['color_preferences'], $data['gender_preference'],
        (int)$data['date_year'], (int)$data['date_month'], (int)$data['date_day']
    ]);

    // Send Admin Email (Optional but recommended)
    $to = "nouri.medamine1987@gmail.com";
    $subject = "Nouvelle demande VIP - " . $data['first_name'] . " " . $data['last_name'];
    $msg = "Nouvelle demande d'adoption VIP reçue. Veuillez vérifier l'administration.";
    // mail($to, $subject, $msg); 

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    error_log("VIP Request Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur base de données.']);
}
