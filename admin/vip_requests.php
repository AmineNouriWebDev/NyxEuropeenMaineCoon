<?php
require_once '../includes/config.php';
require_once 'includes/auth_check.php';

// Toggle Status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT status FROM vip_requests WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();
    $new = ($current == 'new') ? 'contacted' : 'new';
    $pdo->prepare("UPDATE vip_requests SET status = ? WHERE id = ?")->execute([$new, $id]);
    header("Location: vip_requests.php?msg=Statut mis à jour");
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM vip_requests ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$requests = $pdo->query($sql)->fetchAll();

$total = $pdo->query("SELECT COUNT(*) FROM vip_requests")->fetchColumn();
$pages = ceil($total / $limit);

require_once 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2>💎 Demandes Adoption VIP</h2>
        <p class="text-muted">Gérez les candidatures reçues via le formulaire complet.</p>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <?php if (empty($requests)): ?>
            <p class="text-center py-5">Aucune demande VIP pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Candidat</th>
                            <th>Ville/Pays</th>
                            <th>Préférences</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                            <tr class="<?php echo $r['status'] == 'new' ? 'table-info fw-bold' : ''; ?>">
                                <td>
                                    <?php if ($r['status'] == 'new'): ?>
                                        <span class="badge bg-info text-dark">Nouveau</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Contacté</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($r['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></strong><br>
                                    <small><a href="mailto:<?php echo htmlspecialchars($r['email']); ?>"><?php echo htmlspecialchars($r['email']); ?></a></small><br>
                                    <small><?php echo htmlspecialchars($r['phone']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($r['city']); ?><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($r['country']); ?></span>
                                </td>
                                <td>
                                    <strong>Genre:</strong> <?php echo $r['gender_preference'] == 'Male' ? 'Mâle' : ($r['gender_preference'] == 'Female' ? 'Femelle' : 'Indifférent'); ?><br>
                                    <strong>Couleurs:</strong> <?php echo htmlspecialchars(substr($r['color_preferences'], 0, 20)) . '...'; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary mb-1" onclick='openDetails(<?php echo json_encode($r); ?>)'>
                                        <i class="fas fa-eye"></i> Détails
                                    </button>
                                    <a href="?toggle=1&id=<?php echo $r['id']; ?>" class="btn btn-sm <?php echo $r['status'] == 'new' ? 'btn-success' : 'btn-outline-secondary'; ?>">
                                        <i class="fas fa-check"></i>
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

<!-- Modal Détails -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Détails de la candidature</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Infos Personnelles</h6>
                        <p id="modalInfos"></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Adresse</h6>
                        <p id="modalAddress"></p>
                    </div>
                </div>
                <hr>
                <h6>Famille & Quotidien</h6>
                <div class="bg-light p-3 rounded mb-3" id="modalFamily"></div>
                
                <h6>Animaux actuels</h6>
                <div class="bg-light p-3 rounded mb-3" id="modalPets"></div>
                
                <h6>Environnement</h6>
                <div class="bg-light p-3 rounded mb-3" id="modalEnvironment"></div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Connu par</h6>
                        <p id="modalHear"></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Date souhaitée</h6>
                        <p id="modalDate"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openDetails(data) {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    
    document.getElementById('modalInfos').innerHTML = `
        <strong>${data.first_name} ${data.last_name}</strong><br>
        Email: ${data.email}<br>
        Tél: ${data.phone}
    `;
    
    document.getElementById('modalAddress').innerHTML = `
        ${data.address}<br>
        ${data.postal_code} ${data.city}<br>
        ${data.country}
    `;
    
    document.getElementById('modalFamily').textContent = data.family_description;
    document.getElementById('modalPets').textContent = data.existing_pets;
    document.getElementById('modalEnvironment').textContent = data.environment_type;
    
    document.getElementById('modalHear').textContent = data.hear_about_us;
    document.getElementById('modalDate').textContent = `${data.adoption_date_day ? data.adoption_date_day + '/' : ''}${data.adoption_date_month}/${data.adoption_date_year}`;

    // Ajout Préférences manquantes
    const genderMap = {'Male': 'Mâle', 'Female': 'Femelle', 'None': 'Aucune préférence'};
    const genderText = genderMap[data.gender_preference] || data.gender_preference;

    // Création section Préférences pour le modal car absente du HTML de base
    let prefsHtml = `
        <hr>
        <h6>Préférences & Engagements</h6>
        <p><strong>Couleurs préférées :</strong> ${data.color_preferences}</p>
        <p><strong>Genre préféré :</strong> ${genderText}</p>
        <p><strong>Accord Dépôt 300$ :</strong> <i class="fas fa-check text-success"></i> Oui (Accepté)</p>
    `;
    
    // On ajoute cette section à la fin du corps du modal si elle n'y est pas déjà (on append)
    // Mais pour faire propre, on l'ajoute dynamiquement
    const modalBody = document.querySelector('#detailsModal .modal-body');
    // On nettoie d'abord les éventuels ajouts précédents
    const existingPrefs = modalBody.querySelector('.prefs-section');
    if (existingPrefs) existingPrefs.remove();

    const div = document.createElement('div');
    div.className = 'prefs-section';
    div.innerHTML = prefsHtml;
    modalBody.appendChild(div);

    modal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
