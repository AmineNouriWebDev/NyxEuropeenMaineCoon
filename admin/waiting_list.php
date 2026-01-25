<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

// Action: Changer le statut
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT status FROM waiting_list WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();
    
    $new = ($current == 'new') ? 'contacted' : 'new';
    $pdo->prepare("UPDATE waiting_list SET status = ? WHERE id = ?")->execute([$new, $id]);
    header("Location: waiting_list.php?msg=Statut mis à jour");
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Requête liste avec infos portée + parents
$sql = "SELECT w.*, 
        l.season_text, 
        f.name as father_name, 
        m.name as mother_name
        FROM waiting_list w
        LEFT JOIN upcoming_litters l ON w.litter_id = l.id
        LEFT JOIN chats f ON l.father_id = f.id
        LEFT JOIN chats m ON l.mother_id = m.id
        ORDER BY w.created_at DESC 
        LIMIT $limit OFFSET $offset";
$entries = $pdo->query($sql)->fetchAll();

// Total pour pagination
$total = $pdo->query("SELECT COUNT(*) FROM waiting_list")->fetchColumn();
$pages = ceil($total / $limit);

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📋 Liste d'Attente (Portées)</h2>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($entries)): ?>
            <p class="text-center text-muted py-5">Aucune inscription pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Portée Concernée</th>
                            <th>Client</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $e): ?>
                            <tr class="<?php echo $e['status'] == 'new' ? 'table-warning fw-bold' : ''; ?>">
                                <td class="text-center">
                                    <?php if ($e['status'] == 'new'): ?>
                                        <span class="badge bg-warning text-dark">Nouveau</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Contacté</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($e['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($e['season_text']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($e['mother_name']); ?> & <?php echo htmlspecialchars($e['father_name']); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($e['visitor_name']); ?></strong><br>
                                    <a href="mailto:<?php echo htmlspecialchars($e['visitor_email']); ?>"><?php echo htmlspecialchars($e['visitor_email']); ?></a><br>
                                    <a href="tel:<?php echo htmlspecialchars($e['visitor_phone']); ?>"><?php echo htmlspecialchars($e['visitor_phone']); ?></a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#msg-<?php echo $e['id']; ?>">Lire</button>
                                    <div class="collapse mt-2 small bg-light p-2 rounded" id="msg-<?php echo $e['id']; ?>">
                                        <?php echo nl2br(htmlspecialchars($e['message'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="?toggle=1&id=<?php echo $e['id']; ?>" class="btn btn-sm <?php echo $e['status'] == 'new' ? 'btn-success' : 'btn-outline-secondary'; ?>">
                                        <?php echo $e['status'] == 'new' ? '<i class="fas fa-check"></i> Traiter' : '<i class="fas fa-undo"></i> Non lu'; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
