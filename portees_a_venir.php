<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des portées actives
$sql = "SELECT l.*, 
        f.name as father_name, f.id as father_id,
        m.name as mother_name, m.id as mother_id
        FROM upcoming_litters l
        LEFT JOIN chats f ON l.father_id = f.id
        LEFT JOIN chats m ON l.mother_id = m.id
        WHERE l.is_active = 1
        ORDER BY l.created_at DESC";
$litters = $pdo->query($sql)->fetchAll();

// Helper pour image principale
function get_main_image($pdo, $cat_id) {
    if (!$cat_id) return 'default.jpg';
    $stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE cat_id = ? ORDER BY sort_order LIMIT 1");
    $stmt->execute([$cat_id]);
    return $stmt->fetchColumn() ?: 'default.jpg';
}
?>

<!-- Spacer -->
<div style="height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);"></div>

<section class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>Portées <span style="color: var(--accent-color)">À Venir</span></h2>
            <p class="lead text-muted">Découvrez nos futurs mariages et réservez votre chaton de rêve.</p>
        </div>

        <?php if (empty($litters)): ?>
            <div class="text-center py-5">
                <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                <h3>Aucune portée annoncée pour le moment.</h3>
                <p>Revenez bientôt pour découvrir nos futurs mariages !</p>
            </div>
        <?php else: ?>
            <?php foreach ($litters as $litter): ?>
                <?php 
                $father_img = get_main_image($pdo, $litter['father_id']);
                $mother_img = get_main_image($pdo, $litter['mother_id']);
                ?>
                
                <div class="card shadow-lg border-0 rounded-lg overflow-hidden mb-5 litter-card">
                    <div class="row g-0">
                        <!-- King (Gauche) -->
                        <div class="col-md-3 position-relative">
                            <img src="<?php echo asset_url('img/' . $father_img); ?>" class="w-100 h-100" style="object-fit: cover; min-height: 400px;" alt="<?php echo htmlspecialchars($litter['father_name']); ?>">
                            <div class="position-absolute bottom-0 w-100 p-3 text-center text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h4 class="mb-0"><i class="fas fa-crown text-warning"></i> <?php echo htmlspecialchars($litter['father_name']); ?></h4>
                                <a href="kings.php?id=<?php echo $litter['father_id']; ?>" class="btn btn-sm btn-outline-light mt-2 rounded-pill">Voir Profil</a>
                            </div>
                        </div>

                        <!-- Info Centrale -->
                        <div class="col-md-6 p-4 d-flex flex-column justify-content-center text-center bg-white position-relative">
                            
                            <!-- Badges -->
                            <div class="mb-3">
                                <span class="badge badge-primary px-3 py-2 text-uppercase" style="font-size: 0.9rem; letter-spacing: 2px;">
                                    <?php echo htmlspecialchars($litter['season_text']); ?>
                                </span>
                            </div>

                            <h3 class="mb-4" style="font-family: 'Vijaya', serif; font-size: 2.5rem;">
                                <?php echo htmlspecialchars($litter['mother_name']); ?> <span class="text-muted">&</span> <?php echo htmlspecialchars($litter['father_name']); ?>
                            </h3>

                            <div class="litter-description text-muted mb-4">
                                <?php echo nl2br(htmlspecialchars($litter['description'])); ?>
                            </div>

                            <!-- Couleurs Probables -->
                            <?php if (!empty($litter['expected_colors'])): ?>
                            <div class="expected-colors bg-light p-3 rounded mb-4 text-left">
                                <h5 class="text-center mb-3"><i class="fas fa-palette text-primary"></i> Couleurs Probables</h5>
                                <div class="colors-list-content">
                                    <?php echo $litter['expected_colors']; // Contenu HTML autorisé ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-center gap-2 mb-3">
                                    <a href="queens.php?id=<?php echo $litter['mother_id']; ?>" class="btn btn-outline-danger rounded-pill shadow-sm">
                                        <i class="fas fa-venus mr-1"></i> Maman <?php echo htmlspecialchars($litter['mother_name']); ?>
                                    </a>
                                    <a href="kings.php?id=<?php echo $litter['father_id']; ?>" class="btn btn-outline-warning rounded-pill shadow-sm" style="color: #d35400; border-color: #d35400;">
                                        <i class="fas fa-mars mr-1"></i> Papa <?php echo htmlspecialchars($litter['father_name']); ?>
                                    </a>
                                </div>

                                <button onclick="openWaitingListModal(<?php echo $litter['id']; ?>, '<?php echo htmlspecialchars($litter['season_text']); ?>')" class="btn btn-cat btn-lg w-75 shadow hover-scale">
                                    <i class="fas fa-clipboard-list mr-2"></i> Rejoindre la Liste d'Attente
                                </button>
                            </div>
                        </div>

                        <!-- Queen (Droite) -->
                        <div class="col-md-3 position-relative">
                            <img src="<?php echo asset_url('img/' . $mother_img); ?>" class="w-100 h-100" style="object-fit: cover; min-height: 400px;" alt="<?php echo htmlspecialchars($litter['mother_name']); ?>">
                            <div class="position-absolute bottom-0 w-100 p-3 text-center text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h4 class="mb-0"><i class="fas fa-heart text-danger"></i> <?php echo htmlspecialchars($litter['mother_name']); ?></h4>
                                <a href="queens.php?id=<?php echo $litter['mother_id']; ?>" class="btn btn-sm btn-outline-light mt-2 rounded-pill">Voir Profil</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Liste d'Attente -->
<div id="waitingModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; justify-content: center; align-items: center; backdrop-filter: blur(5px);">
    <div class="modal-content bg-white p-0 rounded-lg shadow-lg position-relative overflow-hidden" style="max-width: 500px; width: 90%; animation: zoomIn 0.3s ease;">
        
        <div class="modal-header p-4 text-white" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); max-height: 100px; display: flex; justify-content: space-between; align-items: center;">
            <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Liste d'Attente</h4>
            <button onclick="closeWaitingModal()" class="btn text-white" style="font-size: 1.5rem;">&times;</button>
        </div>

        <div class="modal-body p-4">
            <p class="text-muted text-center mb-4">Inscription pour la portée : <strong id="modalLitterName" class="text-primary"></strong></p>
            
            <form id="waitingForm">
                <input type="hidden" name="litter_id" id="litterIdInput">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Votre Nom</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="email@exemple.com">
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Téléphone</label>
                    <input type="tel" name="phone" class="form-control" placeholder="(555) 555-5555">
                </div>
                <div class="form-group mb-4">
                    <label class="font-weight-bold">Vos Préférences / Message</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Préférence de sexe, couleur, caractère..."></textarea>
                </div>
                <button type="submit" class="btn btn-cat w-100 py-3">Confirmer l'inscription</button>
            </form>
        </div>
    </div>
</div>

<script>
function openWaitingListModal(litterId, seasonText) {
    document.getElementById('waitingModal').style.display = 'flex';
    document.getElementById('modalLitterName').textContent = seasonText;
    document.getElementById('litterIdInput').value = litterId;
}

function closeWaitingModal() {
    document.getElementById('waitingModal').style.display = 'none';
}

// Fermeture si clic exterieur
window.onclick = function(event) {
    const modal = document.getElementById('waitingModal');
    if (event.target == modal) {
        closeWaitingModal();
    }
}

// AJAX Submission
document.getElementById('waitingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Envoi en cours...';
    btn.disabled = true;

    const formData = new FormData(this);
    formData.append('action', 'join_waiting_list');

    fetch('ajax_litter_waitlist.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            alert('Inscription réussie ! Nous vous contacterons bientôt.');
            closeWaitingModal();
            this.reset();
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Une erreur est survenue.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>

<style>
@keyframes zoomIn {
    from {transform: scale(0.9); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
.hover-scale { transition: transform 0.3s; }
.hover-scale:hover { transform: scale(1.05); }
.expected-colors ul { padding-left: 20px; column-count: 2; column-gap: 20px; }
@media (max-width: 768px) {
    .expected-colors ul { column-count: 1; }
    .col-md-3 img { min-height: 250px !important; }
}
</style>

<?php include 'includes/footer.php'; ?>
