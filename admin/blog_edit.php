<?php
require_once '../includes/config.php';
require_once '../includes/functions.php'; // Pour sanitize_html
require_once 'includes/auth_check.php'; // Sécurité active (Header déplacé plus bas)

$id = $_GET['id'] ?? null;
$post = null;
$isEditing = false;
$msg = '';
$msgClass = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if ($post) $isEditing = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = $_POST['slug'] ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // NETTOYAGE SÉCURISÉ DU CONTENU HTML
    $content = sanitize_html($_POST['content']);
    
    // Assurer l'unicité du Slug
    $baseSlug = $slug;
    $counter = 1;
    while(true) {
        if ($isEditing) {
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ? AND id != ?");
            $checkStmt->execute([$slug, $id]);
        } else {
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ?");
            $checkStmt->execute([$slug]);
        }
        
        if ($checkStmt->fetchColumn() == 0) break;
        
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    $excerpt = $_POST['excerpt'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    // Upload Image à la Une (Cover)
    $imagePath = $post['image_path'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $fileName = $_FILES['image']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = 'blog_' . time() . '.' . $fileExt;
        $uploadDir = '../img/blog/';
        
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName)) {
            if ($imagePath && file_exists($uploadDir . $imagePath)) {
                unlink($uploadDir . $imagePath);
            }
            $imagePath = $newFileName;
        }
    }

    try {
        if ($isEditing) {
            $stmt = $pdo->prepare("UPDATE blog_posts SET title=?, slug=?, content=?, excerpt=?, image_path=?, is_published=?, updated_at=NOW() WHERE id=?");
            $stmt->execute([$title, $slug, $content, $excerpt, $imagePath, $is_published, $id]);
            $msg = "Article mis à jour avec succès.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, content, excerpt, image_path, author_id, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $content, $excerpt, $imagePath, $_SESSION['admin_id'], $is_published]);
            $id = $pdo->lastInsertId();
            $msg = "Article créé avec succès.";
        }
        
        // Redirection PRG
        header('Location: blog.php?msg=' . urlencode($msg));
        exit;

    } catch (PDOException $e) {
        $msg = "Erreur : " . $e->getMessage();
        $msgClass = "danger";
    }
}

// INCLUSION DU HEADER (HTML START) UNIQUEMENT APRÈS LA LOGIQUE
require_once 'includes/header.php';
?>

<!-- TinyMCE CDN (Version Open Source gratuite) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

<div class="row mb-4">
    <div class="col-12">
        <h2><?php echo $isEditing ? 'Modifier l\'article' : 'Rédiger un article'; ?></h2>
        <a href="blog.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
    <?php echo $msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="row">
        <!-- Colonne Principale -->
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Titre de l'article</label>
                        <input type="text" class="form-control form-control-lg" name="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" placeholder="Saisissez un titre accrocheur..." required>
                    </div>
                    
                    <div class="mb-3">
                        <!-- Éditeur TinyMCE -->
                        <textarea id="myEditor" name="content"><?php echo $post['content'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3 bg-light p-3 rounded">
                        <label class="form-label small text-uppercase text-muted">Extrait (Référencement SEO)</label>
                        <textarea class="form-control" name="excerpt" rows="2" placeholder="Un court résumé qui apparaîtra dans les listes..."><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Publication</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" <?php echo ($post['is_published'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="isPublished">Publier</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Slug URL (Optionnel)</label>
                        <input type="text" class="form-control form-control-sm" name="slug" value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>" placeholder="Auto-généré si vide">
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem; line-height: 1.2;">
                            C'est la partie de l'adresse web de l'article.<br>
                            <em>Ex: mon-super-article</em><br>
                            Laissez vide pour le créer automatiquement à partir du titre.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Image de Couverture</h6>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($post['image_path'])): ?>
                        <div class="mb-3">
                            <img src="../img/blog/<?php echo htmlspecialchars($post['image_path']); ?>" class="img-fluid rounded shadow-sm" alt="Cover">
                        </div>
                    <?php else: ?>
                        <div class="mb-3 p-4 bg-light rounded text-muted">
                            <i class="fas fa-image fa-2x"></i>
                            <p class="small mb-0">Aucune image</p>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" class="form-control form-control-sm" name="image" accept="image/*">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#myEditor',
            height: 600,
            plugins: 'image link lists table media wordcount code help fullscreen preview',
            toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | removeformat | help',
            menubar: 'file edit view insert format tools table',
            branding: false,
            // Configuration Upload Image
            images_upload_url: 'upload_blog_image.php',
            automatic_uploads: true,
            images_reuse_filename: true,
            file_picker_types: 'image',
            
            // Forcer URLs absolues (Important pour l'affichage Front/Back)
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            
            // Style le contenu pour qu'il ressemble au site (Optionnel)
            content_style: `
                body { font-family: 'Poppins', sans-serif; font-size: 16px; line-height: 1.6; color: #333; }
                img { max-width: 100%; height: auto; }
            `,
            
            // Custom file picker pour upload manuel via bouton
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    
                    // Upload via FormData
                    var formData = new FormData();
                    formData.append('file', file);
                    
                    fetch('upload_blog_image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        cb(result.location, { title: file.name });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                };

                input.click();
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
