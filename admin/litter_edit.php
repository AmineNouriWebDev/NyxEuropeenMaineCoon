<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

$title = "Gestion des Portées";
$isEditing = false;
$id = null;
$father_id = '';
$mother_id = '';
$season_text = '';
$season_text_en = '';
$description = '';
$description_en = '';
$expected_colors = '';
$expected_effects = '';
$is_active = 1;
$msg = $_GET['msg'] ?? '';

// Récupération Kings et Queens pour les Selects
$kings = $pdo->query("SELECT id, name FROM chats WHERE gender='Male' AND status='king' ORDER BY name")->fetchAll();
$queens = $pdo->query("SELECT id, name FROM chats WHERE gender='Female' AND status='queen' ORDER BY name")->fetchAll();

// Récupération des couleurs triées par code
$colors = $pdo->query("SELECT code, name_fr FROM colors ORDER BY code ASC")->fetchAll();

if (isset($_GET['id'])) {
    $isEditing = true;
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM upcoming_litters WHERE id = ?");
    $stmt->execute([$id]);
    $litter = $stmt->fetch();
    if ($litter) {
        $father_id = $litter['father_id'];
        $mother_id = $litter['mother_id'];
        $season_text = $litter['season_text'];
        $season_text_en = $litter['season_text_en'];
        $description = $litter['description'];
        $description_en = $litter['description_en'];
        $expected_colors = $litter['expected_colors'];
        $expected_effects = $litter['expected_effects'];
        $is_active = $litter['is_active'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $father_id = $_POST['father_id'];
    $mother_id = $_POST['mother_id'];
    $season_text = $_POST['season_text'];
    $season_text_en = $_POST['season_text_en'] ?? '';
    $description = $_POST['description'];
    $description_en = $_POST['description_en'] ?? '';
    
    // Process color codes from checkboxes
    $color_codes = $_POST['color_codes'] ?? [];
    $expected_colors = implode(', ', $color_codes); // Store as comma-separated string
    
    // Process special effects from checkboxes
    $special_effects = $_POST['special_effects'] ?? [];
    $expected_effects = implode(',', $special_effects); // Store as comma-separated string
    
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($isEditing) {
        $stmt = $pdo->prepare("UPDATE upcoming_litters SET father_id=?, mother_id=?, season_text=?, season_text_en=?, description=?, description_en=?, expected_colors=?, expected_effects=?, is_active=? WHERE id=?");
        $stmt->execute([$father_id, $mother_id, $season_text, $season_text_en, $description, $description_en, $expected_colors, $expected_effects, $is_active, $id]);
        $msg = "Portée mise à jour avec succès.";
        // Redirect back to same page
        header("Location: litter_edit.php?id=$id&msg=" . urlencode($msg));
    } else {
        $stmt = $pdo->prepare("INSERT INTO upcoming_litters (father_id, mother_id, season_text, season_text_en, description, description_en, expected_colors, expected_effects, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$father_id, $mother_id, $season_text, $season_text_en, $description, $description_en, $expected_colors, $expected_effects, $is_active]);

        $id = $pdo->lastInsertId();
        $msg = "Nouvelle portée créée avec succès.";
        // Redirect to list page to clear form
        header("Location: litters.php?msg=" . urlencode($msg));
    }
    exit;
}

require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><?php echo $isEditing ? 'Modifier la Portée' : 'Nouvelle Portée à Venir'; ?></h2>
        <a href="litters.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    </div>
</div>

<?php if ($msg): ?>
    <div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Père (King)</label>
                    <select name="father_id" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($kings as $k): ?>
                            <option value="<?php echo $k['id']; ?>" <?php if($father_id == $k['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($k['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mère (Queen)</label>
                    <select name="mother_id" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($queens as $q): ?>
                            <option value="<?php echo $q['id']; ?>" <?php if($mother_id == $q['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($q['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Titre / Saison <span class="badge bg-secondary">FR</span> (ex: HIVER 2026)</label>
                    <input type="text" name="season_text" class="form-control" value="<?php echo htmlspecialchars($season_text); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Title / Season <span class="badge bg-primary">EN</span> (ex: WINTER 2026)</label>
                    <input type="text" name="season_text_en" class="form-control" value="<?php echo htmlspecialchars($season_text_en); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description du mariage <span class="badge bg-secondary">FR</span></label>
                <textarea name="description" id="descriptionEditor" class="form-control" rows="5" placeholder="Le prochain mariage dans notre chatterie..."><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Marriage Description <span class="badge bg-primary">EN</span></label>
                <textarea name="description_en" id="descriptionEditorEn" class="form-control" rows="5" placeholder="Next breeding in our cattery..."><?php echo htmlspecialchars($description_en); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Couleurs Probables</label>
                <div class="card p-3 mb-2" style="max-height: 300px; overflow-y: auto;">
                    <div class="row">
                        <?php foreach ($colors as $color): ?>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="color_codes[]" value="<?php echo $color['code']; ?>" id="color_<?php echo $color['code']; ?>" <?php echo (strpos($expected_colors, $color['code']) !== false) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="color_<?php echo $color['code']; ?>">
                                    <strong><?php echo $color['code']; ?></strong> - <?php echo htmlspecialchars($color['name_fr']); ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <small class="text-muted">Cochez les couleurs probables pour cette portée.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Effets Spéciaux Probables</label>
                <div class="card p-3 mb-2">
                    <div class="d-flex flex-wrap gap-3">
                        <?php
                        $effects = ['SMOKE', 'SILVER', 'SHADED', 'CHINCHILLA'];
                        $currentEffects = !empty($expected_effects) ? explode(',', $expected_effects) : [];
                        ?>
                        <?php foreach ($effects as $effect): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="special_effects[]" value="<?php echo $effect; ?>" id="effect_<?php echo $effect; ?>" <?php echo in_array($effect, $currentEffects) ? 'checked' : ''; ?>>
                            <label class="form-check-label font-weight-bold" for="effect_<?php echo $effect; ?>">
                                <?php echo $effect; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <small class="text-muted">Cochez les effets spéciaux probables pour cette portée.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="isActive" <?php if($is_active) echo 'checked'; ?>>
                <label class="form-check-label" for="isActive">Afficher sur le site</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Enregistrer</button>
        </form>
    </div>
</div>

<!-- TinyMCE CDN (Version Gratuite sans API Key) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#descriptionEditor, #descriptionEditorEn',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false,
            // Strip all HTML attributes and tags that could cause display issues
            valid_elements: 'p,br,strong,em,u,h1,h2,h3,h4,ul,ol,li',
            valid_styles: {},
            entity_encoding: 'raw'
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
