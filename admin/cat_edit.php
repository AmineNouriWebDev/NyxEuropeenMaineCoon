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
    
    // NOUVELLE LOGIQUE COULEURS
    $color_code = $_POST['color_code'];
    $special_effects_arr = $_POST['special_effects'] ?? [];
    $special_effect = implode(',', $special_effects_arr); // CSV pour la BDD

    // Récupérer le nom FR pour le champ legacy 'color'
    $stmtColor = $pdo->prepare("SELECT name_fr FROM colors WHERE code = ?");
    $stmtColor->execute([$color_code]);
    $color_name = $stmtColor->fetchColumn();

    // Construction string legacy (Ex: "SMOKE Noir")
    $effects_display = implode(' ', $special_effects_arr);
    $color = trim($effects_display . ' ' . $color_name);

    $quality = $_POST['quality'];
    $paw_type = $_POST['paw_type'];
    
    // Prix
    $price_cad = !empty($_POST['price_cad']) ? $_POST['price_cad'] : null;
    $old_price_cad = !empty($_POST['old_price_cad']) ? $_POST['old_price_cad'] : null;
    $price_usd = !empty($_POST['price_usd']) ? $_POST['price_usd'] : null;
    $old_price_usd = !empty($_POST['old_price_usd']) ? $_POST['old_price_usd'] : null;
    
    $video_url = $_POST['video_url'];
    $description = sanitize_html($_POST['description'] ?? '');
    
    // Sale fields (pour Kings et Queens)
    $for_sale = isset($_POST['for_sale']) ? 1 : 0;
    $sale_type = !empty($_POST['sale_type']) ? $_POST['sale_type'] : null;
    $stud_price_cad = !empty($_POST['stud_price_cad']) ? $_POST['stud_price_cad'] : null;
    $stud_price_usd = !empty($_POST['stud_price_usd']) ? $_POST['stud_price_usd'] : null;
    $retirement_price_cad = !empty($_POST['retirement_price_cad']) ? $_POST['retirement_price_cad'] : null;
    $retirement_price_usd = !empty($_POST['retirement_price_usd']) ? $_POST['retirement_price_usd'] : null;
    $sale_description = !empty($_POST['sale_description']) ? $_POST['sale_description'] : null;

    // DEBUG
    error_log("Color Code: $color_code, Effects: $special_effect, Legacy: $color");

    try {
        $pdo->beginTransaction();

        if ($isEditing) {
            $sql = "UPDATE chats SET name=?, gender=?, birth_date=?, color=?, color_code=?, special_effect=?, quality=?, paw_type=?, price_cad=?, old_price_cad=?, price_usd=?, old_price_usd=?, mother_id=?, father_id=?, video_url=?, status=?, description=?, for_sale=?, sale_type=?, stud_price_cad=?, stud_price_usd=?, retirement_price_cad=?, retirement_price_usd=?, sale_description=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $gender, $birth_date, $color, $color_code, $special_effect, $quality, $paw_type, $price_cad, $old_price_cad, $price_usd, $old_price_usd, $mother_id, $father_id, $video_url, $status, $description, $for_sale, $sale_type, $stud_price_cad, $stud_price_usd, $retirement_price_cad, $retirement_price_usd, $sale_description, $id]);
            $msg = "Chat mis à jour avec succès.";
        } else {
            // Check ID
            $check = $pdo->prepare("SELECT COUNT(*) FROM chats WHERE id = ?");
            $check->execute([$slug_id]);
            if ($check->fetchColumn() > 0) $slug_id .= '_' . time();

            $sql = "INSERT INTO chats (id, name, gender, birth_date, color, color_code, special_effect, quality, paw_type, price_cad, old_price_cad, price_usd, old_price_usd, mother_id, father_id, video_url, status, description, for_sale, sale_type, stud_price_cad, stud_price_usd, retirement_price_cad, retirement_price_usd, sale_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$slug_id, $name, $gender, $birth_date, $color, $color_code, $special_effect, $quality, $paw_type, $price_cad, $old_price_cad, $price_usd, $old_price_usd, $mother_id, $father_id, $video_url, $status, $description, $for_sale, $sale_type, $stud_price_cad, $stud_price_usd, $retirement_price_cad, $retirement_price_usd, $sale_description]);
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
                
                <?php
                // Logique Robe (Colors) - Nouvelle Version BDD
                $colors = $pdo->query("SELECT * FROM colors ORDER BY code ASC")->fetchAll();
                
                $currentColorCode = $cat['color_code'] ?? '';
                $currentEffect = $cat['special_effect'] ?? '';
                
                // Si pas de code mais une couleur texte (ancien système), on essaie de trouver ou on met Autre
                // Mais pour l'instant on garde vide si pas de code
                ?>

                <div class="mb-3">
                    <label class="form-label">Couleur (Robe)</label>
                    <select class="form-select" name="color_code" required>
                        <option value="">-- Sélectionner une robe --</option>
                        <?php foreach ($colors as $c): ?>
                            <option value="<?php echo $c['code']; ?>" <?php echo ($currentColorCode == $c['code']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name_fr']); ?> (<?php echo $c['code']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Effet Spécial (S'affiche en gras devant la couleur)</label>
                    <div class="d-flex flex-wrap gap-3">
                        <?php
                        $effects = ['SMOKE', 'SILVER', 'SHADED', 'CHINCHILLA'];
                        // On suppose qu'un seul effet est sélectionné à la fois ? Ou plusieurs ? 
                        // Le user dit "4 cases à cocher", donc potentiellement plusieurs.
                        // Mais stockons-les séparés par virgule si plusieurs ou juste le dernier.
                        // "on affiche l'effet spécial davant la couleur" -> Singulier ?
                        // Allons pour des radio boutons si c'est exclusif, ou checkbox.
                        // "4 cases à cocher" -> Checkbox.
                        // Mais comment stocker dans un VARCHAR(100)? JSON ou CSV?
                        // Pour faire simple : CSV.
                        $currentEffects = explode(',', $currentEffect); 
                        ?>
                        <?php foreach ($effects as $eff): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="special_effects[]" value="<?php echo $eff; ?>" id="eff_<?php echo $eff; ?>" <?php echo in_array($eff, $currentEffects) ? 'checked' : ''; ?>>
                                <label class="form-check-label font-weight-bold" for="eff_<?php echo $eff; ?>">
                                    <?php echo $eff; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

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
                
                <!-- Section Vente (Kings et Queens uniquement) -->
                <div id="saleSection" style="display: none;">
                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-tag"></i> Disponibilité à la Vente</h6>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="for_sale" id="forSaleCheck" value="1" <?php echo ($cat['for_sale'] ?? 0) ? 'checked' : ''; ?> onchange="toggleSaleFields()">
                        <label class="form-check-label font-weight-bold" for="for_sale Check">
                            Disponible à la vente
                        </label>
                    </div>
                    
                    <div id="saleFieldsContainer" style="display: none;">
                        <!-- Type de vente (Kings uniquement) -->
                        <div id="saleTypeGroup" class="mb-3">
                            <label class="form-label font-weight-bold">Type de vente</label>
                            <?php $currentSaleType = $cat['sale_type'] ?? ''; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sale_type" id="saleTypeStud" value="stud" <?php echo $currentSaleType === 'stud' ? 'checked' : ''; ?> onchange="togglePriceFields()">
                                <label class="form-check-label" for="saleTypeStud">
                                    <i class="fas fa-paw text-info"></i> Saillie uniquement
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sale_type" id="saleTypeRetirement" value="retirement" <?php echo $currentSaleType === 'retirement' ? 'checked' : ''; ?> onchange="togglePriceFields()">
                                <label class="form-check-label" for="saleTypeRetirement">
                                    <i class="fas fa-home text-success"></i> Retraite uniquement
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sale_type" id="saleTypeBoth" value="both" <?php echo $currentSaleType === 'both' ? 'checked' : ''; ?> onchange="togglePriceFields()">
                                <label class="form-check-label" for="saleTypeBoth">
                                    <i class="fas fa-star text-warning"></i> Saillie ET Retraite
                                </label>
                            </div>
                        </div>
                        
                        <!-- Prix Saillie -->
                        <div id="studPriceFields" style="display: none;">
                            <div class="card bg-light mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-paw text-info"></i> Prix Saillie</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="small">Prix Saillie (CAD)</label>
                                            <input type="number" step="0.01" class="form-control" name="stud_price_cad" value="<?php echo $cat['stud_price_cad'] ?? ''; ?>" placeholder="0.00">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="small">Prix Saillie (USD)</label>
                                            <input type="number" step="0.01" class="form-control" name="stud_price_usd" value="<?php echo $cat['stud_price_usd'] ?? ''; ?>" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prix Retraite -->
                        <div id="retirementPriceFields" style="display: none;">
                            <div class="card bg-light mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-home text-success"></i> Prix Retraite</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="small">Prix Retraite (CAD)</label>
                                            <input type="number" step="0.01" class="form-control" name="retirement_price_cad" value="<?php echo $cat['retirement_price_cad'] ?? ''; ?>" placeholder="0.00">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="small">Prix Retraite (USD)</label>
                                            <input type="number" step="0.01" class="form-control" name="retirement_price_usd" value="<?php echo $cat['retirement_price_usd'] ?? ''; ?>" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description Vente -->
                        <div class="mb-3">
                            <label class="form-label">Description pour la vente <small class="text-muted">(Optionnel)</small></label>
                            <textarea class="form-control" name="sale_description" rows="3" placeholder="Informations supplémentaires sur la vente..."><?php echo $cat['sale_description'] ?? ''; ?></textarea>
                            <small class="form-text text-muted">Cette description s'affichera sur la page de détails du chat.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        // Toggle sale fields based on for_sale checkbox
        function toggleSaleFields() {
            const forSaleCheck = document.getElementById('forSaleCheck');
            const saleFieldsContainer = document.getElementById('saleFieldsContainer');
            saleFieldsContainer.style.display = forSaleCheck.checked ? 'block' : 'none';
            if (forSaleCheck.checked) {
                togglePriceFields();
            }
        }
        
        // Toggle price fields based on sale_type selection
        function togglePriceFields() {
            const saleType = document.querySelector('input[name="sale_type"]:checked');
            const studFields = document.getElementById('studPriceFields');
            const retirementFields = document.getElementById('retirementPriceFields');
            
            if (!saleType) return;
            
            studFields.style.display = 'none';
            retirementFields.style.display = 'none';
            
            if (saleType.value === 'stud') {
                studFields.style.display = 'block';
            } else if (saleType.value === 'retirement') {
                retirementFields.style.display = 'block';
            } else if (saleType.value === 'both') {
                studFields.style.display = 'block';
                retirementFields.style.display = 'block';
            }
        }
        
        // Update form display when cat type changes
        function updateFormFields() {
            const type = document.getElementById('catTypeSelect').value;
            const genderGroup = document.getElementById('genderGroup');
            const statusGroup = document.getElementById('statusGroup');
            const parentsGroup = document.getElementById('parentsGroup');
            const saleSection = document.getElementById('saleSection');
            const saleTypeGroup = document.getElementById('saleTypeGroup');
            
            // Hide by default
            genderGroup.style.display = 'none';
            statusGroup.style.display = 'none';
            parentsGroup.style.display = 'none';
            saleSection.style.display = 'none';

            if (type === 'kitten') {
                // Show kitten-specific fields
                genderGroup.style.display = 'block';
                statusGroup.style.display = 'block';
                parentsGroup.style.display = 'flex';
            } else if (type === 'king') {
                // Show sale section for kings with all options
                saleSection.style.display = 'block';
                saleTypeGroup.style.display = 'block';
                toggleSaleFields();
            } else if (type === 'queen') {
                // Show sale section for queens but hide stud option
                saleSection.style.display = 'block';
                saleTypeGroup.style.display = 'none';
                // Auto-select retirement for queens
                document.getElementById('saleTypeRetirement').checked = true;
                toggleSaleFields();
            }
        }
        
        // Run on load
        document.addEventListener('DOMContentLoaded', function() {
            updateFormFields();
            toggleSaleFields();
            togglePriceFields();
        });
        </script>
        
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
