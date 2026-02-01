<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des Kings
$selected_id = $_GET['id'] ?? null;
if ($selected_id) {
    // Si un ID est spécifié, on récupère uniquement ce King
    $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ? AND status = 'king'");
    $stmt->execute([$selected_id]);
    $cat = $stmt->fetch();
    $cats = $cat ? [$cat] : []; // On met dans un tableau pour garder la boucle foreach
    
    // Récupérer les images pour ce chat spécifique (se fait déjà dans la boucle mais bon)
    if ($cat) {
        $cat['images'] = get_cat_images($pdo, $cat['id']);
        $cats[0]['images'] = $cat['images'];
    }
} else {
    // Sinon on récupère tous les Kings
    $cats = get_cats_from_db($pdo, 'king');
}
?>

<!-- Spacer pour le menu fixe -->
<!-- Purple Hero Header -->


<?php if (isset($_GET['msg'])): ?>
<div class="container mt-3">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($_GET['msg']); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>
<?php endif; ?>

<!-- Purple Hero Header -->
<div class="litter-hero text-center py-5">
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Nos Kings</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">Nos superbes mâles reproducteurs, sélectionnés pour leur gabarit, leur santé et leur tempérament exceptionnel</p>
</div>

<!-- Kings Section -->
<section class="kitten-section purple-hero-bg" id="kings">
  <div class="container">
    <div class="section-title">
      <?php if ($selected_id && !empty($cats)): ?>
          <div class="alert alert-info d-inline-block">
              <i class="fas fa-crown"></i> Profil du Père
          </div>
          <h2 class="mt-2 text-primary"><?php echo htmlspecialchars($cats[0]['name']); ?></h2>
          <div class="mt-3">
              <a href="kings.php" class="btn btn-outline-primary btn-sm rounded-pill">
                  <i class="fas fa-th-large"></i> Voir tous les Kings
              </a>
          </div>
      <?php else: ?>
          <h2>Nos <span style="color: var(--primary-color)">Kings</span></h2>
          <p class="mt-3" style="max-width: 600px; margin: 0 auto">
            Nos superbes mâles reproducteurs, sélectionnés pour leur gabarit, leur santé et leur tempérament exceptionnel.
          </p>
      <?php endif; ?>
    </div>

    <div class="row" id="cats-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3><?php echo $selected_id ? 'Ce King est introuvable.' : 'Aucun King présenté pour le moment.'; ?></h3>
              <?php if ($selected_id): ?>
                  <a href="kings.php" class="btn btn-primary mt-3">Voir tous les Kings</a>
              <?php endif; ?>
          </div>
      <?php else: ?>
          <?php foreach ($cats as $cat): ?>
            <?php
            $cat_id = $cat['id'];
            $images = $cat['images'];
            $video_url = $cat['video_url'] ?? null;
            $age_display = calculate_age($cat['birth_date'] ?? null);
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4 kitten-card-wrapper">
              <div class="kitten-card">
                <div class="kitten-image-slider">
                  <div id="carousel-<?php echo $cat_id; ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                    <ol class="carousel-indicators">
                      <?php foreach ($images as $k => $img): ?>
                        <li data-target="#carousel-<?php echo $cat_id; ?>" data-slide-to="<?php echo $k; ?>" class="<?php echo $k === 0 ? 'active' : ''; ?>"></li>
                      <?php endforeach; ?>
                    </ol>
                    <div class="carousel-inner">
                      <?php foreach ($images as $k => $img): ?>
                        <div class="carousel-item <?php echo $k === 0 ? 'active' : ''; ?>">
                          <img src="<?php echo cat_image_url($img); ?>" class="d-block w-100" alt="<?php echo $cat['name']; ?>" onclick="openImageModal(this.src)">
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                      <a class="carousel-control-prev" href="#carousel-<?php echo $cat_id; ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carousel-<?php echo $cat_id; ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    <?php endif; ?>
                  </div>
                  <div class="kitten-status available" style="background: var(--primary-color);">King</div>
                  <?php if ($cat['for_sale']): ?>
                  
                  <?php endif; ?>
                </div>
                
                <div class="kitten-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <!-- Sexe : Icone Mixte + Texte (comme index) -->
                  </div>
                  
                  <div class="kitten-info-grid">
                    <!-- Sexe -->
                    <div class="info-item">
                      <i class="fas fa-venus-mars text-dark"></i>
                      <span>Mâle</span>
                    </div>

                    <!-- Âge -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt text-dark"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Qualité -->
                    <div class="info-item">
                      <i class="fas fa-cat text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['quality'] ?? 'King'); ?></span>
                    </div>

                    <!-- Type de pattes -->
                    <div class="info-item">
                      <i class="fas fa-paw text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['paw_type'] ?? 'Régulières'); ?></span>
                    </div>

                    <!-- Couleur -->
                    <div class="info-item">
                      <i class="fas fa-palette text-dark"></i>
                      <span>
                        <?php echo format_cat_color($cat); ?>
                      </span>
                    </div>
                    
                    <!-- Effets Spéciaux -->
                    <div class="info-item special-effects-row" style="grid-column: 1 / -1; display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; font-size: 0.85em;">
                        <?php 
                        $full_desc = ($cat['color'] ?? '') . ' ' . ($cat['special_effect'] ?? '');
                        $effects = [
                            'SMOKE(s)' => stripos($full_desc, 'smoke') !== false,
                            'SILVER(s)' => stripos($full_desc, 'silver') !== false,
                            'SHADED(s)' => stripos($full_desc, 'shaded') !== false,
                            'CHINCHILLA(s)' => stripos($full_desc, 'chinchilla') !== false
                        ];
                        
                        foreach($effects as $label => $active): 
                        ?>
                        <div class="effect-checkbox d-flex align-items-center">
                            <i class="far fa-<?php echo $active ? 'check-square' : 'square'; ?> mr-1 text-dark"></i>
                            <span class="<?php echo $active ? 'font-weight-bold text-dark' : 'text-muted'; ?>"><?php echo $label; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                  </div>
                  
                  <?php if ($cat['for_sale']): ?>
                  <div class="mt-3 p-2" style="background: #f8f9fa; border-radius: 8px; border-left: 3px solid var(--primary-color);">
                    <?php if ($cat['sale_type'] === 'stud' || $cat['sale_type'] === 'both'): ?>
                    <div class="mb-2">
                      <small class="text-muted"><i class="fas fa-paw text-info"></i> Saillie</small>
                      <div>
                        <strong><?php echo number_format($cat['stud_price_cad'], 0, ',', ' '); ?> $CAD</strong>
                        <span class="text-muted">/ <?php echo number_format($cat['stud_price_usd'], 0, ',', ' '); ?> $USD</span>
                      </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($cat['sale_type'] === 'retirement' || $cat['sale_type'] === 'both'): ?>
                    <div>
                      <small class="text-muted"><i class="fas fa-home text-success"></i> Retraite</small>
                      <div>
                        <strong class="text-muted"><?php echo number_format($cat['retirement_price_cad'], 0, ',', ' '); ?> $CAD</strong>
                        <span class="text-muted">/ <?php echo number_format($cat['retirement_price_usd'], 0, ',', ' '); ?> $USD</span>
                      </div>
                    </div>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                  
                  <div class="kitten-actions mt-3">
                    <a href="chat_details.php?id=<?php echo $cat['id']; ?>" class="btn-cat btn-sm">Voir Détails</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
