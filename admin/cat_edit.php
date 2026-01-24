<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php'; // Sécurité

$id = $_GET['id'] ?? null;
$cat = null;
$isEditing = false;
$msg = '';
$msgClass = '';

// Si ID fourni, on récupère le chat
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch();
    if ($cat) $isEditing = true;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php'; // Pour sanitize_html
    
    // Récupération des données
    $name = $_POST['name'];
    $slug_id = $_POST['slug_id'] ?: strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name)); // Génération ID si vide
    $gender = $_POST['gender'];
    $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
    $age_text = $_POST['age_text'];
    $age_text = $_POST['age_text'];
    
    // Logique couleur hybride (Select ou Custom)
    $color = $_POST['color_select'];
    if ($color === 'Other' || !empty($_POST['color_custom'])) {
        $color = $_POST['color_custom'];
    }
    
    $quality = $_POST['quality'];
    $weight = $_POST['weight'];
    $price = $_POST['price'];
    $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : null;
    $video_url = $_POST['video_url'];
    $status = $_POST['status'];
    
    // Description Riche
    $description = sanitize_html($_POST['description'] ?? '');

    try {
        $pdo->beginTransaction();

        if ($isEditing) {
            // Update
            $sql = "UPDATE chats SET name=?, gender=?, birth_date=?, age_text=?, color=?, quality=?, weight=?, price=?, old_price=?, video_url=?, status=?, description=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $gender, $birth_date, $age_text, $color, $quality, $weight, $price, $old_price, $video_url, $status, $description, $id]);
            $msg = "Chat mis à jour avec succès.";
        } else {
            // Create
            // Check if ID exists
            $check = $pdo->prepare("SELECT COUNT(*) FROM chats WHERE id = ?");
            $check->execute([$slug_id]);
            if ($check->fetchColumn() > 0) {
                // ID exists, append timestamp
                $slug_id .= '_' . time();
            }

            $sql = "INSERT INTO chats (id, name, gender, birth_date, age_text, color, quality, weight, price, old_price, video_url, status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$slug_id, $name, $gender, $birth_date, $age_text, $color, $quality, $weight, $price, $old_price, $video_url, $status, $description]);
            $id = $slug_id; // Set ID for image upload
            $isEditing = true;
            $msg = "Chat créé avec succès.";
        }
        $msgClass = "success";

        // Traitement des Images
        if (!empty($_FILES['images']['name'][0])) {
            $total = count($_FILES['images']['name']);
            for ($i = 0; $i < $total; $i++) {
                if ($_FILES['images']['error'][$i] === 0) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $fileName = $_FILES['images']['name'][$i];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $newFileName = $id . '_' . uniqid() . '.' . $fileExt;
                    
                    // Upload vers dossier img
                    $uploadDir = '../img/';
                    if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                        // DB Insert
                        $stmt = $pdo->prepare("INSERT INTO cat_images (cat_id, image_path, sort_order) VALUES (?, ?, ?)");
                        $stmt->execute([$id, $newFileName, $i]);
                    }
                }
            }
        }

        // Suppression d'images sélectionnées
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
                // Get path
                $stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE id = ?");
                $stmt->execute([$imgId]);
                $imgPath = $stmt->fetchColumn();
                
                if ($imgPath) {
                    $file = '../img/' . $imgPath;
                    if (file_exists($file)) @unlink($file);
                }
                
                $stmt = $pdo->prepare("DELETE FROM cat_images WHERE id = ?");
                $stmt->execute([$imgId]);
            }
        }

        $pdo->commit();
        
        // Redirection PRG (Post-Redirect-Get) pour éviter resoumission
        header('Location: cats.php?msg=' . urlencode($msg));
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Erreur : " . $e->getMessage();
        $msgClass = "danger";
    }
}

// Récupération des images existantes
$images = [];
if ($cat) {
    require_once '../includes/functions.php'; // Pour get_cat_images si besoin, ou requête directe
    $stmt = $pdo->prepare("SELECT * FROM cat_images WHERE cat_id = ? ORDER BY sort_order");
    $stmt->execute([$cat['id']]);
    $images = $stmt->fetchAll();
}

// INCLUSION DU HEADER APRÈS LA LOGIQUE
require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><?php echo $isEditing ? 'Modifier ' . htmlspecialchars($cat['name']) : 'Ajouter un nouveau chat'; ?></h2>
        <a href="cats.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
    <?php echo $msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="row">
    <!-- Colonne gauche : Infos principales -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations du Chat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom du Chat</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $cat['name'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Identifiant Unique (optionnel, auto-généré)</label>
                        <input type="text" class="form-control" name="slug_id" value="<?php echo $cat['id'] ?? ''; ?>" <?php echo $isEditing ? 'readonly' : ''; ?>>
                        <?php if($isEditing): ?><small class="text-muted">L'identifiant ne peut pas être modifié.</small><?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Genre</label>
                        <select class="form-select" name="gender" required>
                            <option value="Male" <?php echo ($cat['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Mâle (Male)</option>
                            <option value="Female" <?php echo ($cat['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Femelle (Female)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="status" required>
                            <option value="available" <?php echo ($cat['status'] ?? '') == 'available' ? 'selected' : ''; ?>>Disponible</option>
                            <option value="reserved" <?php echo ($cat['status'] ?? '') == 'reserved' ? 'selected' : ''; ?>>Réservé</option>
                            <option value="sold" <?php echo ($cat['status'] ?? '') == 'sold' ? 'selected' : ''; ?>>Vendu (Sold)</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de naissance (si connue)</label>
                        <input type="date" class="form-control" name="birth_date" value="<?php echo $cat['birth_date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Age (Texte affiché)</label>
                        <input type="text" class="form-control" name="age_text" value="<?php echo $cat['age_text'] ?? ''; ?>" placeholder="ex: 4 months" required>
                    </div>
                </div>

                <?php
                // Liste officielle des robes Maine Coon
                $colors = [
                    "Solid Colors" => ["Black", "Blue", "White", "Red", "Cream"],
                    "Tabby Patterns" => ["Classic Tabby", "Mackerel Tabby", "Spotted Tabby"],
                    "Smoke & Shaded" => ["Black Smoke", "Blue Smoke", "Red Smoke", "Cream Smoke", "Tortie Smoke", "Shaded Silver", "Shaded Golden"],
                    "Bi-Color & Parti-Color" => ["Black & White", "Blue & White", "Red & White", "Cream & White", "Tabby & White", "Smoke & White", "Tortie & White", "Torbie & White"],
                    "Tortoiseshell (Tortie)" => ["Black Tortie", "Blue Tortie", "Black Smoke Tortie", "Blue Smoke Tortie"],
                    "Torbie" => ["Brown Torbie", "Blue Torbie", "Silver Torbie", "Smoke Torbie"]
                ];
                
                $currentColor = $cat['color'] ?? '';
                $isCustomColor = $currentColor && !in_array($currentColor, array_merge(...array_values($colors)));
                ?>

                <div class="mb-3">
                    <label class="form-label">Couleur (Robe)</label>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="colorSelect" name="color_select" onchange="toggleColorInput(this)">
                            <option value="">-- Sélectionner une robe --</option>
                            <?php foreach ($colors as $group => $opts): ?>
                                <optgroup label="<?php echo $group; ?>">
                                    <?php foreach ($opts as $opt): ?>
                                        <option value="<?php echo $opt; ?>" <?php echo ($currentColor == $opt) ? 'selected' : ''; ?>>
                                            <?php echo $opt; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                            <option value="Other" <?php echo $isCustomColor ? 'selected' : ''; ?>>Autre (Personnalisé)...</option>
                        </select>
                        
                        <!-- Champ Custom caché par défaut sauf si Autre ou valeur inconnue -->
                        <input type="text" class="form-control <?php echo $isCustomColor ? '' : 'd-none'; ?>" 
                               id="colorCustom" name="color_custom" 
                               value="<?php echo $currentColor; ?>" 
                               placeholder="Précisez la couleur...">
                    </div>
                </div>

                <script>
                function toggleColorInput(select) {
                    const customInput = document.getElementById('colorCustom');
                    if (select.value === 'Other') {
                        customInput.classList.remove('d-none');
                        customInput.focus();
                        customInput.value = ''; // Clean pour nouvelle saisie
                    } else {
                        customInput.classList.add('d-none');
                        customInput.value = select.value; // Copie la valeur sélectionnée
                    }
                }
                
                // Au chargement, si on est en mode édition standard, on s'assure que le custom a la valeur
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('colorSelect');
                    const customInput = document.getElementById('colorCustom');
                    if (select.value !== 'Other' && select.value !== '') {
                        customInput.value = select.value;
                    }
                });
                </script>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qualité</label>
                        <select class="form-select" name="quality">
                            <option value="Pet Quality" <?php echo ($cat['quality'] ?? '') == 'Pet Quality' ? 'selected' : ''; ?>>Compagnie (Pet Quality)</option>
                            <option value="Breeding Quality" <?php echo ($cat['quality'] ?? '') == 'Breeding Quality' ? 'selected' : ''; ?>>Élevage (Breeding Quality)</option>
                            <option value="Pet & Breeding Quality" <?php echo ($cat['quality'] ?? '') == 'Pet & Breeding Quality' ? 'selected' : ''; ?>>Compagnie & Élevage</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Poids / Estimation</label>
                        <input type="text" class="form-control" name="weight" value="<?php echo $cat['weight'] ?? 'Expected: '; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prix (€)</label>
                        <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $cat['price'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ancien Prix (si promo)</label>
                        <input type="number" step="0.01" class="form-control" name="old_price" value="<?php echo $cat['old_price'] ?? ''; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lien Vidéo YouTube</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                        <input type="url" class="form-control" name="video_url" value="<?php echo $cat['video_url'] ?? ''; ?>" placeholder="https://www.youtube.com/...">
                    </div>
                    <small class="text-muted">Lien complet ou embed</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Colonne droite : Images -->
    <div class="col-lg-4">
        <!-- Boite Images (Déplacée en haut) -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Images</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Ajouter des images</label>
                    <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    <small class="text-muted">Vous pouvez sélectionner plusieurs fichiers.</small>
                </div>

                <?php if (!empty($images)): ?>
                    <hr>
                    <h6>Images actuelles</h6>
                    <div class="row">
                        <?php foreach ($images as $img): ?>
                            <div class="col-6 mb-3 position-relative">
                                <img src="../img/<?php echo htmlspecialchars($img['image_path']); ?>" class="img-fluid rounded border" alt="Cat Image">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo $img['id']; ?>" id="del_<?php echo $img['id']; ?>">
                                    <label class="form-check-label text-danger small" for="del_<?php echo $img['id']; ?>">
                                        Supprimer
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
            <i class="fas fa-save me-2"></i> <?php echo $isEditing ? 'Enregistrer les modifications' : 'Créer le Chat'; ?>
        </button>
    </div>
    
    <!-- Zone Description (Pleine largeur en bas) -->
    <div class="col-12 mt-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Description Détaillée (Article)</h6>
            </div>
            <div class="card-body">
                <textarea id="catDescriptionEditor" name="description"><?php echo $cat['description'] ?? ''; ?></textarea>
            </div>
        </div>
    </div>
</form>

<!-- TinyMCE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#catDescriptionEditor',
            height: 500,
            plugins: 'image link lists table media wordcount code help fullscreen preview',
            toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | removeformat | help',
            branding: false,
            // Configuration identique au blog pour l'upload d'images
            images_upload_url: 'upload_blog_image.php',
            automatic_uploads: true,
            images_reuse_filename: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            content_style: `body { font-family: 'Poppins', sans-serif; font-size: 16px; line-height: 1.6; color: #333; } img { max-width: 100%; height: auto; }`
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
