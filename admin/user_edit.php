<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

$id = $_GET['id'] ?? null;
$user = null;
$isEditing = false;
$msg = '';
$msgClass = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if ($user) $isEditing = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation basique
    if (empty($username) || empty($email)) {
        $msg = "Veuillez remplir les champs obligatoires.";
        $msgClass = "danger";
    } else {
        try {
            if ($isEditing) {
                // Update
                if (!empty($password)) {
                    // Update avec mot de passe
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
                    $stmt->execute([$username, $email, $hashed_password, $id]);
                } else {
                    // Update sans changer mot de passe
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=? WHERE id=?");
                    $stmt->execute([$username, $email, $id]);
                }
                $msg = "Administrateur mis à jour.";
            } else {
                // Create
                if (empty($password)) {
                    $msg = "Le mot de passe est obligatoire pour un nouvel utilisateur.";
                    $msgClass = "danger";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password]);
                    $id = $pdo->lastInsertId();
                    $isEditing = true;
                    $msg = "Administrateur créé avec succès.";
                }
            }
            if(empty($msgClass)) $msgClass = "success";
            
            // Redirection PRG
            header('Location: users.php?msg=' . urlencode($msg));
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $msg = "Erreur : Ce nom d'utilisateur ou email existe déjà.";
            } else {
                $msg = "Erreur BDD : " . $e->getMessage();
            }
            $msgClass = "danger";
        }
    }
}

// HEADER APRÈS LOGIQUE
require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><?php echo $isEditing ? 'Modifier Administrateur' : 'Ajouter Administrateur'; ?></h2>
        <a href="users.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
    <?php echo $msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $user['username'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mot de passe <?php echo $isEditing ? '(Laisser vide pour ne pas changer)' : ''; ?></label>
                        <input type="password" class="form-control" name="password" <?php echo $isEditing ? '' : 'required'; ?>>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
