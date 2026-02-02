<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des chatons réservés ou vendus
$cats = get_cats_from_db($pdo, ['reserved', 'sold']);
?>

<!-- Spacer pour le menu fixe -->


<!-- Purple Hero Header -->
<div class="litter-hero text-center py-5">
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Chatons Réservés</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">Ces chatons ont déjà trouvé leur famille pour la vie</p>
</div>

<!-- Reserved Section -->
<section class="kitten-section purple-hero-bg" id="reserved">
  <div class="container">
 

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
            
            <div class="col-lg-4 col-md-6 mb-4 reserved-card-wrapper" style="opacity: 1 !important; visibility: visible !important; display: block !important; transform: none !important; min-height: 100px;">
              <div class="reserved-card" style="opacity: 1 !important; visibility: visible !important; transform: none !important; background: white;">
                <div class="reserved-card-slider">
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
                  
                  <div class="reserved-card-status" style="background: <?php echo $status_color; ?>;">
                      <?php echo $status_text; ?>
                  </div>
                </div>
                
                <div class="reserved-card-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="reserved-card-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <!-- Sexe (index style) -->
                  </div>
                  
                  <div class="reserved-card-info-grid">
                    <!-- Sexe -->
                    <div class="info-item">
                      <i class="fas fa-venus-mars text-dark"></i>
                      <span><?php echo strtolower($cat['gender']) == 'male' ? 'Mâle' : 'Femelle'; ?></span>
                    </div>

                    <!-- Âge -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt text-dark"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Qualité -->
                    <div class="info-item">
                      <i class="fas fa-cat text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['quality']); ?></span>
                    </div>

                    <!-- Type de pattes -->
                    <div class="info-item">
                      <i class="fas fa-paw text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['paw_type'] ?? 'Régulières'); ?></span>
                    </div>

                    <!-- Couleur -->
                    <div class="info-item">
                      <i class="fas fa-palette text-dark"></i>
                      <span><?php echo format_cat_color($cat); ?></span>
                    </div>
                    
                    <!-- Effets Spéciaux -->
                    <div class="info-item special-effects-row" style="grid-column: 1 / -1; display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px;">
                        <?php 
                        $full_desc = ($cat['color'] ?? '') . ' ' . ($cat['special_effect'] ?? '');
                        $effects = [
                            'SMOKE(s)' => stripos($full_desc, 'smoke') !== false,
                            'SILVER(s)' => stripos($full_desc, 'silver') !== false,
                            'SHADED(s)' => stripos($full_desc, 'shaded') !== false,
                            'CHINCHILLA(s)' => stripos($full_desc, 'chinchilla') !== false
                        ];
                        
                        foreach($effects as $label => $active) {
                            if ($active) {
                                echo '<span class="special-effect-badge text-white">' . $label . '</span>';
                            }
                        }
                        ?>
                    </div>
                  </div>
                  
                  <!-- Boutons Parents -->
                  <div class="reserved-card-actions mt-3 d-flex gap-2" style="gap: 10px;">
                    <?php if (!empty($cat['father_id']) && !empty($cat['father_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['father_id']; ?>" class="btn-cat btn-sm flex-fill" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-crown text-warning"></i> Voir Papa
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($cat['mother_id']) && !empty($cat['mother_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['mother_id']; ?>" class="btn-cat btn-cat-secondary btn-sm flex-fill" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-heart text-danger"></i> Voir Maman
                      </a>
                    <?php endif; ?>
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
