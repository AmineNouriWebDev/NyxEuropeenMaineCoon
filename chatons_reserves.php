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
            $video_url = $cat['video_url'] ?? null;
            $age_display = calculate_age($cat['birth_date'] ?? null);
            $is_sold = $cat['status'] === 'sold';
            $status_text = $is_sold ? 'Vendu' : 'Réservé';
            $status_color = $is_sold ? '#e74c3c' : '#f39c12';
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4 kitten-card-wrapper" style="opacity: 0.95;">
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
                          <img src="<?php echo cat_image_url($img); ?>" class="d-block w-100" alt="<?php echo $cat['name']; ?>" style="filter: grayscale(20%);">
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
                  
                  <div class="kitten-status" style="background: <?php echo $status_color; ?>;">
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
                      <i class="fas fa-calendar-alt"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>

                    <div class="info-item">
                      <i class="fas fa-palette"></i>
                      <span><?php echo format_cat_color($cat); ?></span>
                    </div>
                    
                    <div class="info-item">
                      <i class="fas fa-paw"></i>
                      <span><?php echo htmlspecialchars($cat['quality']); ?></span>
                    </div>

                    <?php if (!empty($cat['paw_type']) && $cat['paw_type'] !== 'Régulières'): ?>
                    <div class="info-item">
                        <i class="fas fa-hand-paper"></i>
                        <span><?php echo htmlspecialchars($cat['paw_type']); ?></span>
                    </div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="kitten-actions mt-3">
                    <a href="chat_details.php?id=<?php echo $cat['id']; ?>" class="btn-cat btn-sm w-100">Voir Détails</a>
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
