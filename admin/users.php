<?php
require_once '../includes/config.php';
require_once 'includes/header.php';

// Suppression d'un admin
if (isset($_GET['delete']) && isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $id = $_GET['delete'];
    
    // Empêcher de se supprimer soi-même
    if ($id == $_SESSION['admin_id']) {
        $msg = "Vous ne pouvez pas supprimer votre propre compte.";
        $msgClass = "danger";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $msg = "Administrateur supprimé avec succès.";
            $msgClass = "success";
        } catch (PDOException $e) {
            $msg = "Erreur lors de la suppression : " . $e->getMessage();
            $msgClass = "danger";
        }
    }
}

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupération des admins
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
}

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msgClass = "success";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2>Gestion des Administrateurs</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="user_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un administrateur</a>
    </div>
</div>

<?php if (isset($msg)): ?>
<div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
    <?php echo $msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info text-white" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                    <a href="users.php?delete=<?php echo $user['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')" 
                                       title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
