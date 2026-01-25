<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM upcoming_litters WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: litters.php?msg=" . urlencode("Portée supprimée avec succès"));
    exit;
}

// Récupération avec Jointures pour noms parents
$sql = "SELECT l.*, f.name as father_name, m.name as mother_name 
        FROM upcoming_litters l
        LEFT JOIN chats f ON l.father_id = f.id
        LEFT JOIN chats m ON l.mother_id = m.id
        ORDER BY l.created_at DESC";
$litters = $pdo->query($sql)->fetchAll();

require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-6">
        <h2>💞 Portées à Venir</h2>
    </div>
    <div class="col-6 text-end text-right">
        <a href="litter_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter une Portée</a>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($litters)): ?>
            <p class="text-center text-muted py-4">Aucune portée prévue pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Saison / Titre</th>
                            <th>Parents</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($litters as $l): ?>
                            <tr>
                                <td class="font-weight-bold"><?php echo htmlspecialchars($l['season_text']); ?></td>
                                <td>
                                    <i class="fas fa-crown text-warning"></i> <?php echo htmlspecialchars($l['father_name']); ?>
                                    <br>
                                    <i class="fas fa-heart text-danger"></i> <?php echo htmlspecialchars($l['mother_name']); ?>
                                </td>
                                <td><?php echo substr(strip_tags($l['description']), 0, 50) . '...'; ?></td>
                                <td>
                                    <?php if ($l['is_active']): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Caché</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="litter_edit.php?id=<?php echo $l['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                                    <a href="?delete=<?php echo $l['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
