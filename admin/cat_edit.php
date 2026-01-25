<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

$id = $_GET['id'] ?? null;
$cat = null;
$isEditing = false;
$msg = '';
$msgClass = '';

// Si ID fourni, on récupère le chat
if ($id) {
    // Dans le cas de l'ID string (slug), c'est une string
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
    $slug_id = $_POST['slug_id'] ?: strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    
    // Logique TYPE (Chaton vs King vs Queen)
    $cat_type = $_POST['cat_type']; // 'kitten', 'king', 'queen'
    
    if ($cat_type === 'king') {
        $gender = 'Male';
        $status = 'king';
        $mother_id = null;
        $father_id = null;
    } elseif ($cat_type === 'queen') {
        $gender = 'Female';
        $status = 'queen';
        $mother_id = null;
        $father_id = null;
    } else { // kitten
        $gender = $_POST['gender']; // Choix manuel pour chaton
        $status = $_POST['kitten_status']; // available, reserved, sold
        $mother_id = !empty($_POST['mother_id']) ? $_POST['mother_id'] : null;
        $father_id = !empty($_POST['father_id']) ? $_POST['father_id'] : null;
    }

    $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
    $color = ($_POST['color_select'] === 'Other' || !empty($_POST['color_custom'])) ? $_POST['color_custom'] : $_POST['color_select'];
    $quality = $_POST['quality'];
    $paw_type = $_POST['paw_type'];
    
    // Prix
    $price_cad = !empty($_POST['price_cad']) ? $_POST['price_cad'] : null;
    $old_price_cad = !empty($_POST['old_price_cad']) ? $_POST['old_price_cad'] : null;
    $price_usd = !empty($_POST['price_usd']) ? $_POST['price_usd'] : null;
    $old_price_usd = !empty($_POST['old_price_usd']) ? $_POST['old_price_usd'] : null;
    
    $video_url = $_POST['video_url'];
    $description = sanitize_html($_POST['description'] ?? '');

    // DEBUG TEMPORAIRE - À SUPPRIMER APRÈS
    error_log("=== DEBUG CAT FORM ===");
    error_log("cat_type: " . $cat_type);
    error_log("gender: " . $gender);
    error_log("status: " . $status);
    error_log("name: " . $name);
    error_log("=====================");

    try {
        $pdo->beginTransaction();

        if ($isEditing) {
            $sql = "UPDATE chats SET name=?, gender=?, birth_date=?, color=?, quality=?, paw_type=?, price_cad=?, old_price_cad=?, price_usd=?, old_price_usd=?, mother_id=?, father_id=?, video_url=?, status=?, description=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $gender, $birth_date, $color, $quality, $paw_type, $price_cad, $old_price_cad, $price_usd, $old_price_usd, $mother_id, $father_id, $video_url, $status, $description, $id]);
            $msg = "Chat mis à jour avec succès.";
        } else {
            // Check ID
            $check = $pdo->prepare("SELECT COUNT(*) FROM chats WHERE id = ?");
            $check->execute([$slug_id]);
            if ($check->fetchColumn() > 0) $slug_id .= '_' . time();

            $sql = "INSERT INTO chats (id, name, gender, birth_date, color, quality, paw_type, price_cad, old_price_cad, price_usd, old_price_usd, mother_id, father_id, video_url, status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$slug_id, $name, $gender, $birth_date, $color, $quality, $paw_type, $price_cad, $old_price_cad, $price_usd, $old_price_usd, $mother_id, $father_id, $video_url, $status, $description]);
            $id = $slug_id;
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
                        $stmt = $pdo->prepare("INSERT INTO cat_images (cat_id, image_path, sort_order) VALUES (?, ?, ?)");
                        $stmt->execute([$id, $newFileName, $i]);
                    }
                }
            }
        }

        // Suppression d'images sélectionnées
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
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
        header("Location: cats.php?msg=" . urlencode($msg));
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Erreur : " . $e->getMessage();
        $msgClass = "danger";
    }
}

// Récupération des images existantes pour l'édition
$images = [];
if ($isEditing && $cat) { 
    $stmt = $pdo->prepare("SELECT * FROM cat_images WHERE cat_id = ? ORDER BY sort_order");
    $stmt->execute([$cat['id']]);
    $images = $stmt->fetchAll();
}

// Récupération des listes Parents pour les Selects
$stmt = $pdo->query("SELECT id, name FROM chats WHERE gender = 'Male' AND status = 'king' ORDER BY name");
$fathers = $stmt->fetchAll();

$stmt = $pdo->query("SELECT id, name FROM chats WHERE gender = 'Female' AND status = 'queen' ORDER BY name");
$mothers = $stmt->fetchAll();

// IMPORTANT: INCLURE LE HEADER ICI POUR LE STYLE
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
                    
                    <!-- Nouveau : SÉLECTEUR DE TYPE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type de Fiche</label>
                        <?php 
                        // Déterminer le type actuel
                        $currentStatus = $cat['status'] ?? '';
                        $currentType = 'kitten';
                        if ($currentStatus === 'king') $currentType = 'king';
                        if ($currentStatus === 'queen') $currentType = 'queen';
                        ?>
                        <select class="form-select" name="cat_type" id="catTypeSelect" onchange="updateFormFields()">
                            <option value="kitten" <?php echo $currentType == 'kitten' ? 'selected' : ''; ?>>Chaton</option>
                            <option value="king" <?php echo $currentType == 'king' ? 'selected' : ''; ?>>King (Mâle Reproducteur)</option>
                            <option value="queen" <?php echo $currentType == 'queen' ? 'selected' : ''; ?>>Queen (Femelle Reproductrice)</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Genre (Visible seulement pour chaton) -->
                    <div class="col-md-4 mb-3" id="genderGroup">
                        <label class="form-label">Genre</label>
                        <select class="form-select" name="gender">
                            <option value="Male" <?php echo ($cat['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Mâle</option>
                            <option value="Female" <?php echo ($cat['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Femelle</option>
                        </select>
                    </div>
                    
                    <!-- Statut (Visible seulement pour chaton) -->
                    <div class="col-md-4 mb-3" id="statusGroup">
                        <label class="form-label">Statut Chaton</label>
                        <select class="form-select" name="kitten_status">
                            <option value="available" <?php echo ($cat['status'] ?? '') == 'available' ? 'selected' : ''; ?>>Disponible</option>
                            <option value="reserved" <?php echo ($cat['status'] ?? '') == 'reserved' ? 'selected' : ''; ?>>Réservé</option>
                            <option value="sold" <?php echo ($cat['status'] ?? '') == 'sold' ? 'selected' : ''; ?>>Vendu</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type de Pattes</label>
                        <select class="form-select" name="paw_type">
                            <option value="Régulières" <?php echo ($cat['paw_type'] ?? '') == 'Régulières' ? 'selected' : ''; ?>>Régulières</option>
                            <option value="Polydactiles" <?php echo ($cat['paw_type'] ?? '') == 'Polydactiles' ? 'selected' : ''; ?>>Polydactiles</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="birth_date" value="<?php echo $cat['birth_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qualité</label>
                        <select class="form-select" name="quality">
                            <option value="Pet Quality" <?php echo ($cat['quality'] ?? '') == 'Pet Quality' ? 'selected' : ''; ?>>Compagnie (Pet Quality)</option>
                            <option value="Breeding Quality" <?php echo ($cat['quality'] ?? '') == 'Breeding Quality' ? 'selected' : ''; ?>>Élevage (Breeding Quality)</option>
                            <option value="Pet & Breeding Quality" <?php echo ($cat['quality'] ?? '') == 'Pet & Breeding Quality' ? 'selected' : ''; ?>>Compagnie & Élevage</option>
                        </select>
                    </div>
                </div>

                <!-- Parents (Caché pour King/Queen) -->
                <div class="row" id="parentsGroup">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Père (King) <em class="text-muted">(Optionnel)</em></label>
                        <select class="form-select" name="father_id">
                            <option value="">-- Sélectionner le Père --</option>
                            <?php if (empty($fathers)): ?>
                                <option value="" disabled>⚠️ Aucun King dans la base</option>
                            <?php else: ?>
                                <?php foreach ($fathers as $father): ?>
                                    <option value="<?php echo $father['id']; ?>" <?php echo ($cat['father_id'] ?? '') == $father['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($father['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (empty($fathers)): ?>
                            <small class="text-warning">
                                <i class="fas fa-info-circle"></i> 
                                Ajoutez d'abord un King depuis le formulaire (Type de Fiche : King)
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mère (Queen) <em class="text-muted">(Optionnel)</em></label>
                        <select class="form-select" name="mother_id">
                            <option value="">-- Sélectionner la Mère --</option>
                            <?php if (empty($mothers)): ?>
                                <option value="" disabled>⚠️ Aucune Queen dans la base</option>
                            <?php else: ?>
                                <?php foreach ($mothers as $mother): ?>
                                    <option value="<?php echo $mother['id']; ?>" <?php echo ($cat['mother_id'] ?? '') == $mother['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($mother['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (empty($mothers)): ?>
                            <small class="text-warning">
                                <i class="fas fa-info-circle"></i> 
                                Ajoutez d'abord une Queen depuis le formulaire (Type de Fiche : Queen)
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <script>
                function updateFormFields() {
                    const type = document.getElementById('catTypeSelect').value;
                    const genderGroup = document.getElementById('genderGroup');
                    const statusGroup = document.getElementById('statusGroup');
                    const parentsGroup = document.getElementById('parentsGroup');
                    // On masque par défaut
                    genderGroup.style.display = 'none';
                    statusGroup.style.display = 'none';
                    parentsGroup.style.display = 'none';

                    if (type === 'kitten') {
                        // Afficher tout pour chaton
                        genderGroup.style.display = 'block';
                        statusGroup.style.display = 'block';
                        parentsGroup.style.display = 'flex';
                    }
                }
                // Run on load
                document.addEventListener('DOMContentLoaded', updateFormFields);
                </script>

                <?php
                // Logique Robe (Colors)
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
                            <option value="Other" <?php echo $isCustomColor ? 'selected' : ''; ?>>Autre...</option>
                        </select>
                        <input type="text" class="form-control <?php echo $isCustomColor ? '' : 'd-none'; ?>" id="colorCustom" name="color_custom" value="<?php echo $currentColor; ?>">
                    </div>
                </div>

                <script>
                function toggleColorInput(select) {
                    const customInput = document.getElementById('colorCustom');
                    if (select.value === 'Other') {
                        customInput.classList.remove('d-none');
                        customInput.value = '';
                    } else {
                        customInput.classList.add('d-none');
                        customInput.value = select.value;
                    }
                }
                </script>

                <!-- Prix CAD et USD -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body p-2">
                                <h6 class="card-title">Prix ($CAD)</h6>
                                <div class="mb-2">
                                    <label class="small">Prix Actuel</label>
                                    <input type="number" step="0.01" class="form-control" name="price_cad" value="<?php echo $cat['price_cad'] ?? ''; ?>">
                                </div>
                                <div>
                                    <label class="small">Ancien Prix (Avant solde)</label>
                                    <input type="number" step="0.01" class="form-control" name="old_price_cad" value="<?php echo $cat['old_price_cad'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body p-2">
                                <h6 class="card-title">Prix ($USD)</h6>
                                <div class="mb-2">
                                    <label class="small">Prix Actuel</label>
                                    <input type="number" step="0.01" class="form-control" name="price_usd" value="<?php echo $cat['price_usd'] ?? ''; ?>">
                                </div>
                                <div>
                                    <label class="small">Ancien Prix (Avant solde)</label>
                                    <input type="number" step="0.01" class="form-control" name="old_price_usd" value="<?php echo $cat['old_price_usd'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lien Vidéo YouTube</label>
                    <input type="url" class="form-control" name="video_url" value="<?php echo $cat['video_url'] ?? ''; ?>">
                </div>
            </div>
        </div>
        
        <!-- Zone Description -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Description Détaillée (Article)</h6>
            </div>
            <div class="card-body">
                <textarea id="catDescriptionEditor" name="description"><?php echo $cat['description'] ?? ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- Colonne droite : Images -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Images</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Ajouter des images</label>
                    <input type="file" class="form-control" name="images[]" multiple accept="image/*">
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
        
        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-4">
            <i class="fas fa-save me-2"></i> <?php echo $isEditing ? 'Enregistrer' : 'Créer'; ?>
        </button>
    </div>
</form>

<!-- TinyMCE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#catDescriptionEditor',
            height: 400,
            plugins: 'image link lists table media wordcount code help',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | removeformat',
            branding: false
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
