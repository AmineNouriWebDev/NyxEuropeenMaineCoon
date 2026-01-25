<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Fonction pour marquer comme lu/traité
if (isset($_GET['action']) && $_GET['action'] == 'toggle_status' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT status FROM adoption_requests WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();
    
    $new_status = ($current == 'new') ? 'read' : 'new';
    $stmt = $pdo->prepare("UPDATE adoption_requests SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);
    
    header("Location: requests.php?msg=" . urlencode("Statut mis à jour avec succès."));
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Récupération des demandes
$stmt = $pdo->prepare("SELECT * FROM adoption_requests ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$requests = $stmt->fetchAll();

// Compte total
$total_stmt = $pdo->query("SELECT COUNT(*) FROM adoption_requests");
$total_requests = $total_stmt->fetchColumn();
$total_pages = ceil($total_requests / $limit);

require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2>📋 Gestion des Demandes d'Adoption</h2>
        <p class="text-muted">Consultez les demandes reçues via le site.</p>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (empty($requests)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <p>Aucune demande d'adoption pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Chaton</th>
                            <th>Visiteur</th>
                            <th>Contact</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req): ?>
                            <tr class="<?php echo $req['status'] == 'new' ? 'table-warning fw-bold' : ''; ?>">
                                <td class="text-center">
                                    <?php if ($req['status'] == 'new'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> Nouveau</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Traité</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></td>
                                <td>
                                    <?php if ($req['cat_id']): ?>
                                        <a href="../chat_details.php?id=<?php echo $req['cat_id']; ?>" target="_blank">
                                            <?php echo htmlspecialchars($req['cat_name']); ?> <i class="fas fa-external-link-alt small"></i>
                                        </a>
                                    <?php else: ?>
                                        <em>Général</em>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($req['visitor_name']); ?></td>
                                <td>
                                    <div class="small">
                                        <i class="fas fa-envelope"></i> <a href="mailto:<?php echo htmlspecialchars($req['visitor_email']); ?>"><?php echo htmlspecialchars($req['visitor_email']); ?></a>
                                        <?php if ($req['visitor_phone']): ?>
                                            <br><i class="fas fa-phone"></i> <a href="tel:<?php echo htmlspecialchars($req['visitor_phone']); ?>"><?php echo htmlspecialchars($req['visitor_phone']); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#msg-<?php echo $req['id']; ?>">
                                        Lire le message
                                    </button>
                                    <div class="collapse mt-2 p-2 bg-light rounded small" id="msg-<?php echo $req['id']; ?>">
                                        <?php echo nl2br(htmlspecialchars($req['message'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($req['status'] == 'new'): ?>
                                        <a href="requests.php?action=toggle_status&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-success" title="Marquer comme traité">
                                            <i class="fas fa-check"></i> Traiter
                                        </a>
                                    <?php else: ?>
                                        <a href="requests.php?action=toggle_status&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Marquer comme non lu">
                                            <i class="fas fa-undo"></i> Non lu
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($req['visitor_phone']): ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $req['visitor_phone']); ?>" target="_blank" class="btn btn-sm btn-success" title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
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
