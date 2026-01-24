<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Démarrage session pour vérifier admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$slug = $_GET['slug'] ?? '';
$post = null;

try {
    if ($isAdmin) {
        // Admin voit tout
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ?");
    } else {
        // Public voit seulement publié
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1");
    }
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
} catch (PDOException $e) {
    // Error handling
}

if (!$post) {
    header('Location: blog.php');
    exit;
}

include 'includes/header.php';
?>

<?php if ($isAdmin && !$post['is_published']): ?>
<div class="bg-warning text-dark text-center py-2 font-weight-bold">
    <i class="fas fa-eye"></i> MODE PRÉVISUALISATION - Cet article n'est pas encore visible par le public.
    <a href="admin/blog_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-dark ms-2">Modifier</a>
</div>
<?php endif; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
                </ol>
            </nav>

            <h1 class="mb-4 cursive-font" style="font-size: 2.5rem; color: var(--primary-color);"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="mb-4 text-muted">
                <i class="far fa-calendar-alt"></i> Published on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
            </div>

            <?php if ($post['image_path']): ?>
                <img src="<?php echo asset_url('img/blog/' . $post['image_path']); ?>" class="img-fluid rounded mb-4 w-100" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <?php endif; ?>

            <style>
                .blog-content img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 8px;
                    margin: 10px 0;
                }
                .blog-content {
                    line-height: 1.8;
                    color: #333;
                    overflow-wrap: break-word; /* Empêche les longs mots de casser le layout */
                }
                .blog-content h2, .blog-content h3 {
                    color: var(--primary-color);
                    margin-top: 1.5em;
                }
                .blog-content blockquote {
                    border-left: 4px solid var(--secondary-color);
                    padding-left: 1rem;
                    font-style: italic;
                    color: #555;
                }
            </style>

            <div class="blog-content">
                <?php echo $post['content']; // Le contenu HTML est déjà nettoyé par sanitize_html à l'enregistrement ?>
            </div>

            <hr class="my-5">
            
            <div class="text-center">
                <a href="blog.php" class="btn btn-cat btn-cat-secondary">Back to blog</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
