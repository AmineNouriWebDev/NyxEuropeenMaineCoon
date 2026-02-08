<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
include 'includes/header.php';

// Récupération des chatons réservés ou vendus
$cats = get_cats_from_db($pdo, ['reserved', 'sold']);
?>

<!-- Spacer pour le menu fixe -->


<!-- Purple Hero Header -->
<div class="litter-hero text-center py-5">
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Reserved Kittens & Cats</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">These kittens have already found their forever family</p>
</div>

<!-- Reserved Section -->
<section class="kitten-section purple-hero-bg" id="reserved">
  <div class="container">
 

    <div class="row" id="cats-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3>No reserved kittens at the moment (all are available!).</h3>
          </div>
      <?php else: ?>
          <?php foreach ($cats as $cat): ?>
            <?php
            $cat_id = $cat['id'];
            $images = $cat['images'];
            $video_url = $cat['video_url'] ?? null;
            $age_display = calculate_age($cat['birth_date'] ?? null, 'en');
            $is_sold = $cat['status'] === 'sold';
            $status_text = $is_sold ? 'Sold' : 'Reserved';
            $status_color = $is_sold ? '#e74c3c' : '#f39c12';
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4 reserved-card-wrapper" style="opacity: 1 !important; visibility: visible !important; display: block !important; transform: none !important; min-height: 100px;">
              <div class="reserved-card" style="opacity: 1 !important; visibility: visible !important; transform: none !important; background: white;">
                <div class="reserved-card-slider">
                  <div id="carousel-<?php echo $cat_id; ?>" class="carousel slide" data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                      <?php foreach ($images as $k => $img): ?>
                        <li data-target="#carousel-<?php echo $cat_id; ?>" data-slide-to="<?php echo $k; ?>" class="<?php echo $k === 0 ? 'active' : ''; ?>"></li>
                      <?php endforeach; ?>
                      <?php if ($video_url): ?>
                        <li data-target="#carousel-<?php echo $cat_id; ?>" data-slide-to="<?php echo count($images); ?>"></li>
                      <?php endif; ?>
                    </ol>
                    <div class="carousel-inner">
                      <?php foreach ($images as $k => $img): ?>
                        <div class="carousel-item <?php echo $k === 0 ? 'active' : ''; ?>">
                          <img src="<?php echo cat_image_url($img); ?>" class="d-block w-100" alt="<?php echo $cat['name']; ?>" style="filter: grayscale(20%);">
                        </div>
                      <?php endforeach; ?>

                      <?php if ($video_url): ?>
                        <div class="carousel-item">
                          <div class="video-thumbnail-container" onclick="openVideoModal('<?php echo $video_url; ?>')">
                            <img src="<?php echo get_youtube_thumbnail(get_youtube_id($video_url)); ?>" class="d-block w-100" alt="Video">
                            <div class="play-button"><i class="fas fa-play"></i></div>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1 || $video_url): ?>
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
                  
                  <!-- Couleur et Effets Spéciaux (Style chat_details.php) -->
                  <div class="mb-3" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                      <?php 
                      // Récupérer le nom de la couleur depuis la table colors
                      $color_name_en = '';
                      if (!empty($cat['color_code'])) {
                          $stmt_color = $pdo->prepare("SELECT name_en FROM colors WHERE code = ?");
                          $stmt_color->execute([$cat['color_code']]);
                          $color_name_en = $stmt_color->fetchColumn();
                      }
                      
                      if (!empty($color_name_en) || !empty($cat['color_code'])): 
                      ?>
                          <span style="color: #b3b3b3ff; font-size: 1.1rem; font-weight: 700;">
                              <?php echo htmlspecialchars($color_name_en ?? ''); ?>
                              <?php if (!empty($cat['color_code'])): ?>
                                  <span style="color: #6c757d;">(<?php echo htmlspecialchars($cat['color_code']); ?>)</span>
                              <?php endif; ?>
                          </span>
                      <?php endif; ?>
                      
                      <?php 
                      // Afficher les effets spéciaux
                      $full_desc = ($cat['color'] ?? '') . ' ' . ($cat['special_effect'] ?? '');
                      $effects = [
                          'SMOKE(s)' => stripos($full_desc, 'smoke') !== false,
                          'SILVER(s)' => stripos($full_desc, 'silver') !== false,
                          'SHADED(s)' => stripos($full_desc, 'shaded') !== false,
                          'CHINCHILLA(s)' => stripos($full_desc, 'chinchilla') !== false
                      ];
                      
                      foreach($effects as $label => $active) {
                          if ($active) {
                              echo '<span style="background: #000; color: #fff !important; padding: 4px 12px; border-radius: 4px; font-size: 0.9rem; font-weight: 500;">' . $label . '</span>';
                          }
                      }
                      ?>
                  </div>
                  
                  <div class="reserved-card-info-grid">
                    <!-- Sexe -->
                    <div class="info-item">
                      <i class="fas fa-venus-mars text-dark"></i>
                      <span><?php echo strtolower($cat['gender']) == 'male' ? 'Male' : 'Female'; ?></span>
                    </div>

                    <!-- Âge -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt text-dark"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Qualité -->
                    <div class="info-item">
                      <i class="fas fa-cat text-dark"></i>
                      <span>
                        <?php 
                        $quality_map = [
                            'Animal de compagnie' => 'Pet Only',
                            'Animal d\'élevage' => 'Breeding quality',
                            'Animal de compagnie ou d\'élevage' => 'Pet or for breeding',
                            'Pet Quality' => 'Pet Quality',
                            'Breeder Quality' => 'Breeder Quality',
                            'Show Quality' => 'Show Quality'
                        ];
                        echo htmlspecialchars($quality_map[$cat['quality']] ?? $cat['quality']); 
                        ?>
                      </span>
                    </div>

                    <!-- Type de pattes -->
                    <div class="info-item">
                      <i class="fas fa-paw text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['paw_type'] === 'Polydactyle' ? 'Polydactyl' : ($cat['paw_type'] === 'Régulières' ? 'Regular' : $cat['paw_type'])); ?></span>
                    </div>
                  </div>
                  
                  <!-- Boutons Parents -->
                  <div class="reserved-card-actions mt-3 d-flex gap-2" style="gap: 10px;">
                    <?php if (!empty($cat['father_id']) && !empty($cat['father_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['father_id']; ?>" class="btn-cat btn-sm flex-fill" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-crown text-warning"></i> See Dad
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($cat['mother_id']) && !empty($cat['mother_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['mother_id']; ?>" class="btn-cat btn-cat-secondary btn-sm flex-fill" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-heart text-danger"></i> See Mom
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
