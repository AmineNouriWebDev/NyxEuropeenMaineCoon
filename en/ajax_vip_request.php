<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Récupération et nettoyage
$fields = [
    'first_name', 'last_name', 'phone', 'email', 
    'existing_pets', 'environment_type',
    'hear_about_us', 'color_preferences', 'gender_preference', 
    'date_year', 'date_month', 'date_day', 'questions'
];

$data = [];
foreach ($fields as $field) {
    $data[$field] = trim($_POST[$field] ?? '');
}

// Validation basique
if (empty($data['email']) || empty($data['first_name']) || empty($data['last_name'])) {
    echo json_encode(['success' => false, 'message' => 'Please fill in the required fields.']);
    exit;
}

try {
    $sql = "INSERT INTO vip_requests (
        first_name, last_name, phone, email, 
        existing_pets, environment_type,
        hear_about_us, color_preferences, gender_preference,
        adoption_date_year, adoption_date_month, adoption_date_day,
        questions,
        created_at,
        is_approved_deposit
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data['first_name'], $data['last_name'], $data['phone'], $data['email'],
        $data['existing_pets'], $data['environment_type'],
        $data['hear_about_us'], $data['color_preferences'], $data['gender_preference'],
        (int)$data['date_year'], (int)$data['date_month'], (int)$data['date_day'],
        $data['questions'],
        date('Y-m-d H:i:s')
    ]);

    // Send Admin Email (Optional but recommended)
    $to = "nouri.medamine1987@gmail.com";
    $subject = "New VIP Request - " . $data['first_name'] . " " . $data['last_name'];
    $msg = "New VIP adoption request received. Please check the administration.";
    // mail($to, $subject, $msg); 

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    error_log("VIP Request Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
