<?php
require_once '../includes/config.php';
require_once 'includes/header.php';

// Suppression
if (isset($_GET['delete']) && isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    $id = $_GET['delete'];
    try {
        // Obtenir l'image pour supprimer
        $stmt = $pdo->prepare("SELECT image_path FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();
        if ($img) {
            $path = '../img/blog/' . $img;
            if (file_exists($path)) @unlink($path);
        }

        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Article supprimé.";
        $msgClass = "success";
    } catch (PDOException $e) {
        $msg = "Erreur : " . $e->getMessage();
        $msgClass = "danger";
    }
}

// Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Liste des articles
try {
    $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msgClass = "success";
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2>Gestion du Blog</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="blog_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nouvel Article</a>
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
                        <th>Titre</th>
                        <th>Extrait</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($posts) > 0): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <?php if($post['image_path']): ?>
                                        <img src="../img/blog/<?php echo htmlspecialchars($post['image_path']); ?>" alt="thumb" style="height: 30px; margin-right: 5px;">
                                    <?php endif; ?>
                                    <a href="../article.php?slug=<?php echo $post['slug']; ?>" target="_blank"><?php echo htmlspecialchars($post['title']); ?></a>
                                </td>
                                <td><?php echo substr(strip_tags($post['excerpt']), 0, 50) . '...'; ?></td>
                                <td>
                                    <?php if ($post['is_published']): ?>
                                        <span class="badge bg-success">Publié</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Brouillon</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="blog_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info text-white" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="blog.php?delete=<?php echo $post['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Supprimer cet article ?')" 
                                       title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Aucun article.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
