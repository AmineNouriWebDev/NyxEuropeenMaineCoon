<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

$title = "Gestion des Portées";
$isEditing = false;
$id = null;
$father_id = '';
$mother_id = '';
$season_text = '';
$description = '';
$expected_colors = '';
$is_active = 1;
$msg = '';

// Récupération Kings et Queens pour les Selects
$kings = $pdo->query("SELECT id, name FROM chats WHERE gender='Male' AND status='king' ORDER BY name")->fetchAll();
$queens = $pdo->query("SELECT id, name FROM chats WHERE gender='Female' AND status='queen' ORDER BY name")->fetchAll();

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
        $description = $litter['description'];
        $expected_colors = $litter['expected_colors'];
        $is_active = $litter['is_active'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $father_id = $_POST['father_id'];
    $mother_id = $_POST['mother_id'];
    $season_text = $_POST['season_text'];
    $description = $_POST['description'];
    $expected_colors = $_POST['expected_colors']; // Format libre ou HTML
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($isEditing) {
        $stmt = $pdo->prepare("UPDATE upcoming_litters SET father_id=?, mother_id=?, season_text=?, description=?, expected_colors=?, is_active=? WHERE id=?");
        $stmt->execute([$father_id, $mother_id, $season_text, $description, $expected_colors, $is_active, $id]);
        $msg = "Portée mise à jour avec succès.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO upcoming_litters (father_id, mother_id, season_text, description, expected_colors, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$father_id, $mother_id, $season_text, $description, $expected_colors, $is_active]);
        $id = $pdo->lastInsertId();
        $isEditing = true;
        $msg = "Nouvelle portée créée avec succès.";
    }
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

            <div class="mb-3">
                <label class="form-label">Titre / Saison (ex: HIVER 2026)</label>
                <input type="text" name="season_text" class="form-control" value="<?php echo htmlspecialchars($season_text); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description du mariage</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Le prochain mariage dans notre chatterie..."><?php echo htmlspecialchars($description); ?></textarea>
                <small class="text-muted">Vous pouvez utiliser du texte simple.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Couleurs Probables (HTML autorisé)</label>
                <textarea name="expected_colors" class="form-control" rows="6" placeholder="<ul><li>Black Smoke</li><li>Red Solid</li></ul>"><?php echo htmlspecialchars($expected_colors); ?></textarea>
                <small class="text-muted">Utilisez &lt;ul&gt;&lt;li&gt;Couleur&lt;/li&gt;&lt;/ul&gt; pour une liste à puces.</small>
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
            selector: 'textarea[name="description"]',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
