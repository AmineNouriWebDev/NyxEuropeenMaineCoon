<?php
require_once '../includes/config.php';
require_once 'includes/header.php';

// Récupération des statistiques
try {
    // Nombre de chats
    $stmt = $pdo->query("SELECT COUNT(*) FROM chats");
    $catCount = $stmt->fetchColumn();

    // Nombre d'admins
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $adminCount = $stmt->fetchColumn();

    // Nombre d'articles
    $stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
    $blogCount = $stmt->fetchColumn();

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erreur BDD: ' . $e->getMessage() . '</div>';
    $catCount = $adminCount = $blogCount = 0;
}
?>

<div class="row">
    <div class="col-12 mb-4">
        <h2>Tableau de bord</h2>
        <p class="text-muted">Bienvenue dans l'administration de votre chatterie.</p>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-4 mb-4">
        <div class="card bg-white h-100 border-left-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase text-muted small font-weight-bold mb-1">Nos Chats</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $catCount; ?></div>
                    </div>
                    <div class="text-primary fs-1">
                        <i class="fas fa-cat"></i>
                    </div>
                </div>
                <a href="cats.php" class="btn btn-sm btn-outline-primary mt-3">Gérer les chats</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase text-muted small font-weight-bold mb-1">Articles Blog</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $blogCount; ?></div>
                    </div>
                    <div class="text-success fs-1">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <a href="blog.php" class="btn btn-sm btn-outline-success mt-3">Gérer le blog</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase text-muted small font-weight-bold mb-1">Administrateurs</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $adminCount; ?></div>
                    </div>
                    <div class="text-info fs-1">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
                <a href="users.php" class="btn btn-sm btn-outline-info mt-3">Gérer les admins</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Liens Rapides</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="cat_edit.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus-circle me-2"></i> Ajouter un nouveau chat
                    </a>
                    <a href="blog_edit.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-pen-nib me-2"></i> Rédiger un article
                    </a>
                    <a href="../" target="_blank" class="list-group-item list-group-item-action">
                        <i class="fas fa-globe me-2"></i> Voir le site en ligne
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
