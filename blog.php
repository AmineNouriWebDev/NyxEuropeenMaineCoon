<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Pagination simple
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Récupération des articles publiés
try {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE is_published = 1");
    $totalPosts = $countStmt->fetchColumn();
    $totalPages = ceil($totalPosts / $limit);

    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindParam(1, $limit, PDO::PARAM_INT);
    $stmt->bindParam(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Blog error: " . $e->getMessage());
    $posts = [];
}
?>

<section class="section-title mt-5">
    <div class="container">
         <h1 class="text-center cursive-font" style="font-size: 3rem; color: var(--primary-color);">Blog de l'Élevage</h1>
         <p class="text-center text-muted">Conseils, actualités et histoires sur nos Maine Coons.</p>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 kitten-card"> <!-- Utilisation de kitten-card pour le style -->
                    <?php if($post['image_path']): ?>
                        <div style="height: 200px; overflow: hidden;">
                            <img src="<?php echo asset_url('img/blog/' . $post['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <small class="text-muted"><?php echo date('F d, Y', strtotime($post['created_at'])); ?></small>
                        <h5 class="card-title mt-2 font-weight-bold">
                            <a href="article.php?slug=<?php echo $post['slug']; ?>" class="text-dark text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 100)) . '...'; ?></p>
                        <a href="article.php?slug=<?php echo $post['slug']; ?>" class="btn-cat btn-sm">Lire la suite</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($posts)): ?>
            <div class="col-12 text-center">
                <p>Pas encore d'articles. Revenez bientôt !</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
