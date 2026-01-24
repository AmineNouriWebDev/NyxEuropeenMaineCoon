<?php
require_once 'includes/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? 'admin';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? 'admin@example.com';

    if (empty($password)) {
        $message = '<div class="alert alert-danger">Veuillez entrer un mot de passe.</div>';
    } else {
        try {
            // Lecture du fichier SQL
            $sql = file_get_contents('database_setup.sql');
            
            // Exécution des requêtes (création de tables)
            $pdo->exec($sql);
            $message .= '<div class="alert alert-success">Tables créées avec succès.</div>';

            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Création de l'admin
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE password = ?, email = ?");
            $stmt->execute([$username, $hashed_password, $email, $hashed_password, $email]);

            $message .= '<div class="alert alert-success">Administrateur créé/mis à jour avec succès ! <a href="admin/login.php">Connexion</a></div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Erreur : ' . $e->getMessage() . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Nyx European Maine Coon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Installation & Setup</h4>
            </div>
            <div class="card-body">
                <?php echo $message; ?>
                <p>Ce script va créer les tables nécessaires et configurer le premier administrateur.</p>
                <form method="post">
                    <div class="mb-3">
                        <label>Nom d'utilisateur Admin</label>
                        <input type="text" name="username" class="form-control" value="admin" required>
                    </div>
                    <div class="mb-3">
                        <label>Email Admin</label>
                        <input type="email" name="email" class="form-control" value="admin@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe Admin</label>
                        <input type="password" name="password" class="form-control" placeholder="Choisissez un mot de passe fort" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Installer / Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
