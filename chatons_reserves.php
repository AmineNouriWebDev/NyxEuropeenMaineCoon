<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des chatons réservés ou vendus
$cats = get_cats_from_db($pdo, ['reserved', 'sold']);
?>

<!-- Spacer pour le menu fixe -->
<div style="height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);"></div>

<!-- Reserved Section -->
<section class="kitten-section" id="reserved">
  <div class="container">
    <div class="section-title">
      <h2>Chatons <span style="color: var(--accent-color)">Réservés</span></h2>
      <p class="mt-3" style="max-width: 600px; margin: 0 auto">
        Ces chatons ont déjà trouvé leur famille pour la vie.
      </p>
    </div>

    <div class="row" id="cats-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3>Aucun chaton réservé pour le moment (tous sont disponibles !).</h3>
          </div>
      <?php else: ?>
          <?php foreach ($cats as $cat): ?>
            <?php
            $cat_id = $cat['id'];
            $images = $cat['images'];
            $is_sold = $cat['status'] === 'sold';
            $status_text = $is_sold ? 'Vendu' : 'Réservé';
            $status_class = $is_sold ? 'sold' : 'reserved'; // Classes CSS à définir si besoin, sinon style inline
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4 kitten-card-wrapper" style="opacity: 0.9;">
              <div class="kitten-card">
                <div class="kitten-image-slider">
                  <?php if(!empty($images)): ?>
                  <img src="<?php echo cat_image_url($images[0]); ?>" class="d-block w-100" alt="<?php echo $cat['name']; ?>" style="filter: grayscale(30%);">
                  <?php endif; ?>
                  
                  <div class="kitten-status" style="background: <?php echo $is_sold ? '#e74c3c' : '#f39c12'; ?>;">
                      <?php echo $status_text; ?>
                  </div>
                </div>
                
                <div class="kitten-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <span class="kitten-gender <?php echo strtolower($cat['gender']); ?>">
                      <i class="text-white fas fa-<?php echo strtolower($cat['gender']) == 'male' ? 'mars' : 'venus'; ?>"></i>
                    </span>
                  </div>
                  
                  <div class="kitten-info-grid">
                    <div class="info-item">
                      <i class="fas fa-palette"></i>
                      <span><?php echo htmlspecialchars($cat['color']); ?></span>
                    </div>
                    <?php if (!empty($cat['father_name'])): ?>
                    <div class="info-item">
                        <i class="fas fa-crown"></i>
                        <span>Père: <?php echo htmlspecialchars($cat['father_name']); ?></span>
                    </div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="kitten-actions mt-3 text-center">
                    <span class="badge badge-secondary p-2">Non Disponible</span>
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
