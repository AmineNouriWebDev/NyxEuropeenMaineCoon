<?php
require_once '../includes/config.php';
require_once 'includes/header.php';

// Suppression d'un chat
if (isset($_GET['delete']) && isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $id = $_GET['delete'];
    try {
        // Supprimer les images physiques d'abord
        $stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE cat_id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($images as $img) {
            $path = '../img/' . $img;
            if (file_exists($path)) @unlink($path);
        }

        // Supprimer de la BDD (ON DELETE CASCADE s'occupe de cat_images)
        $stmt = $pdo->prepare("DELETE FROM chats WHERE id = ?");
        $stmt->execute([$id]);
        
        $msg = "Chat supprimé avec succès.";
        $msgClass = "success";
    } catch (PDOException $e) {
        $msg = "Erreur lors de la suppression : " . $e->getMessage();
        $msgClass = "danger";
    }
}

// Génération token CSRF pour suppression
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupération de tous les chats
try {
    $stmt = $pdo->query("SELECT * FROM chats ORDER BY created_at DESC");
    $cats = $stmt->fetchAll();
} catch (PDOException $e) {
    $cats = [];
    $error = $e->getMessage();
}

// Message depuis redirection
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msgClass = "success";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2>Gestion des Chats</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="cat_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un chat</a>
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
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Status</th>
                        <th>Genre</th>
                        <th>Prix</th>
                        <th>Date Ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($cats) > 0): ?>
                        <?php foreach ($cats as $cat): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($cat['name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($cat['color']); ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $statusBadge = 'secondary';
                                    if ($cat['status'] == 'available') $statusBadge = 'success';
                                    if ($cat['status'] == 'reserved') $statusBadge = 'warning';
                                    if ($cat['status'] == 'sold') $statusBadge = 'danger';
                                    ?>
                                    <span class="badge bg-<?php echo $statusBadge; ?>">
                                        <?php echo ucfirst($cat['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($cat['gender']); ?></td>
                                <td><?php echo number_format($cat['price'], 0, ',', ' '); ?> €</td>
                                <td><?php echo date('d/m/Y', strtotime($cat['created_at'])); ?></td>
                                <td>
                                    <a href="cat_edit.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-info text-white" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="cats.php?delete=<?php echo $cat['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chat ? Cette action est irréversible.')" 
                                       title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucun chat trouvé. <a href="cat_edit.php">Ajoutez-en un !</a></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
