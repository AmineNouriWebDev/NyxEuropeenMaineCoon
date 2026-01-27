<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des Queens
$selected_id = $_GET['id'] ?? null;
if ($selected_id) {
    // Si un ID est spécifié, on récupère uniquement ce Queen
    $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ? AND status = 'queen'");
    $stmt->execute([$selected_id]);
    $cat = $stmt->fetch();
    $cats = $cat ? [$cat] : [];
    
    // Récupérer les images
    if ($cat) {
        $cat['images'] = get_cat_images($pdo, $cat['id']);
        $cats[0]['images'] = $cat['images'];
    }
} else {
    // Sinon on récupère toutes les Queens
    $cats = get_cats_from_db($pdo, 'queen');
}
?>

<!-- Spacer pour le menu fixe -->
<div style="height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);"></div>

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

<!-- Queens Section -->
<section class="kitten-section" id="queens">
  <div class="container">
    <div class="section-title">
      <?php if ($selected_id && !empty($cats)): ?>
          <div class="alert alert-info d-inline-block">
              <i class="fas fa-crown"></i> Profil de la Mère
          </div>
          <h2 class="mt-2 text-primary"><?php echo htmlspecialchars($cats[0]['name']); ?></h2>
          <div class="mt-3">
              <a href="queens.php" class="btn btn-outline-primary btn-sm rounded-pill">
                  <i class="fas fa-th-large"></i> Voir toutes les Queens
              </a>
          </div>
      <?php else: ?>
          <h2>Nos <span style="color: var(--primary-color)">Queens</span></h2>
          <p class="mt-3" style="max-width: 600px; margin: 0 auto">
            Nos magnifiques femelles, la fondation de notre chatterie. Douceur et beauté réunies.
          </p>
      <?php endif; ?>
    </div>

    <div class="row" id="cats-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3><?php echo $selected_id ? 'Cette Queen est introuvable.' : 'Aucune Queen présentée pour le moment.'; ?></h3>
              <?php if ($selected_id): ?>
                  <a href="queens.php" class="btn btn-primary mt-3">Voir toutes les Queens</a>
              <?php endif; ?>
          </div>
      <?php else: ?>
          <?php foreach ($cats as $cat): ?>
            <?php
            $cat_id = $cat['id'];
            $images = $cat['images'];
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
                  <div class="kitten-status available" style="background: var(--primary-color);">Queen</div>
                  <?php if ($cat['for_sale']): ?>
                  <div class="kitten-status" style="background: #e74c3c; position: absolute; top: 60px; right: 10px;">
                    <i class="fas fa-tag"></i> Disponible
                  </div>
                  <?php endif; ?>
                </div>
                
                <div class="kitten-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <span class="kitten-gender female">
                      <i class="fas fa-venus text-white"></i>
                    </span>
                  </div>
                  
                  <div class="kitten-info-grid">
                    <div class="info-item">
                      <i class="fas fa-birthday-cake"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    <div class="info-item">
                      <i class="fas fa-palette"></i>
                      <span><?php echo format_cat_color($cat); ?></span>
                    </div>
                    <?php if (!empty($cat['paw_type'])): ?>
                    <div class="info-item">
                        <i class="fas fa-paw"></i>
                        <span><?php echo htmlspecialchars($cat['paw_type']); ?></span>
                    </div>
                    <?php endif; ?>
                  </div>
                  
                  <?php if ($cat['for_sale']): ?>
                  <div class="mt-3 p-2" style="background: #f8f9fa; border-radius: 8px; border-left: 3px solid var(--primary-color);">
                    <small class="text-muted"><i class="fas fa-home text-success"></i> Disponible à la Retraite</small>
                    <div>
                      <strong><?php echo number_format($cat['retirement_price_cad'], 2); ?> $CAD</strong>
                      <span class="text-muted">/ <?php echo number_format($cat['retirement_price_usd'], 2); ?> $USD</span>
                    </div>
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
