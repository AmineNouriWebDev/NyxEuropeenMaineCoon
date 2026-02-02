<?php
require_once 'includes/auth_check.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: contact_messages.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

try {
    // Count total messages
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM contact_messages");
    $total = $totalStmt->fetchColumn();
    $totalPages = ceil($total / $perPage);

    // Fetch messages for current page
    $stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur de base de données : " . $e->getMessage();
    $messages = [];
    $total = 0;
    $totalPages = 0;
}

include 'includes/header.php';
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Messages de Contact</h1>
</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Message supprimé avec succès.</div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Liste des messages reçus</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th width="20%">Nom</th>
                        <th width="20%">Email</th>
                        <th width="20%">Sujet</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                                <td><?= htmlspecialchars($msg['name']) ?></td>
                                <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></td>
                                <td><?= htmlspecialchars($msg['subject']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewMessageModal<?= $msg['id'] ?>">
                                        <i class="fas fa-eye"></i> Voir
                                    </button>
                                    <a href="?delete=<?= $msg['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message de <?= htmlspecialchars($msg['name']) ?> ?');">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal View Message -->
                            <div class="modal fade" id="viewMessageModal<?= $msg['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $msg['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel<?= $msg['id'] ?>">Message de <?= htmlspecialchars($msg['name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Sujet:</strong> <?= htmlspecialchars($msg['subject']) ?>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <strong>Message:</strong>
                                                <div class="p-3 bg-light border rounded mt-2" style="white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=RE: <?= urlencode($msg['subject']) ?>" class="btn btn-primary">
                                                <i class="fas fa-reply"></i> Répondre
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucun message trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Helper to keep existing query params but change page -->
                <?php
                function getPageUrl($p) {
                    $params = $_GET;
                    $params['page'] = $p;
                    return '?' . http_build_query($params);
                }
                ?>
                
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= getPageUrl($page - 1) ?>">Précédent</a>
                </li>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= getPageUrl($i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= getPageUrl($page + 1) ?>">Suivant</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
