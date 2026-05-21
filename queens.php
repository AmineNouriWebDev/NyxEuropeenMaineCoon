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
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Nos Queens</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">Nos magnifiques femelles, la fondation de notre chatterie. Douceur et beauté réunies.</p>
</div>

<!-- Queens Section -->
<section class="kitten-section purple-hero-bg py-0" id="queens">
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
         
      <?php endif; ?>
    </div>

    <!-- Health Information Text -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm" style="background: #fff; border-left: 5px solid var(--primary-color);">
                <div class="card-body text-left">
                    <p class="mb-2">
                        <i class="fas fa-notes-medical text-primary mr-2"></i>
                        Tous nos chats reproducteurs subissent des tests d'ADN et de santé pour FeLV, FIV, SMA, PL, PKdef et PKD
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-heartbeat text-danger mr-2"></i>
                        Ils ont aussi passé un test génétique pour la dysplasie des hanches (HD) et une échographie cardiaque pour la cardiomyopathie hypertrophique féline (CMH), tous deux certifés par l'Orthopedic Foundation for Animals (OFA)
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-dna text-success mr-2"></i>
                        Nous prenons soin de respecter les pedigrees pour avoir un coefficient de consanguinité qui respecte la norme (Moins de 12% indirect et 0% direct, COI%)
                    </p>
                </div>
            </div>
        </div>
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
            $video_url = $cat['video_url'] ?? null;
            $age_display = calculate_age($cat['birth_date'] ?? null, 'fr');
            ?>
            
            <div class="col-lg-4 col-md-6 mb-4 kitten-card-wrapper">
              <div class="kitten-card">
                <div class="kitten-image-slider">
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
                          <img src="<?php echo cat_image_url($img); ?>" class="d-block w-100" alt="<?php echo $cat['name']; ?>" onclick="openImageModal(this.src)">
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
                  <div class="kitten-status available" style="background: var(--primary-color);color: white !important;">Queen</div>
                  <?php if ($cat['for_sale']): ?>
                  
                  <?php endif; ?>
                </div>
                
                <div class="kitten-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <!-- Sexe (index style) -->
                  </div>
                  
                  <!-- Couleur et Effets Spéciaux (Style chat_details.php) -->
                  <div class="mb-3" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                      <?php 
                      // Récupérer le nom de la couleur depuis la table colors
                      $color_name_fr = '';
                      if (!empty($cat['color_code'])) {
                          $stmt_color = $pdo->prepare("SELECT name_fr FROM colors WHERE code = ?");
                          $stmt_color->execute([$cat['color_code']]);
                          $color_name_fr = $stmt_color->fetchColumn();
                      }
                      
                      if (!empty($color_name_fr) || !empty($cat['color_code'])): 
                      ?>
                          <span style="color: #b3b3b3ff; font-size: 1.1rem; font-weight: 700;">
                              <?php echo htmlspecialchars($color_name_fr ?? ''); ?>
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
                  
                  <div class="kitten-info-grid">
                    <!-- Sexe -->
                    <div class="info-item">
                      <i class="fas fa-venus-mars text-dark"></i>
                      <span>Femelle</span>
                    </div>

                    <!-- Âge -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt text-dark"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Qualité -->
                    <div class="info-item">
                      <i class="fas fa-cat text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['quality'] ?? 'Queen'); ?></span>
                    </div>

                    <!-- Type de pattes -->
                    <div class="info-item">
                      <i class="fas fa-paw text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['paw_type'] ?? 'Régulières'); ?></span>
                    </div>
                  </div>
                  
                  <?php if ($cat['for_sale']): ?>
                  <div class="kitten-price-container mt-3 px-3 py-2" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #eee;">
                    <div>
                      <small class="text-muted d-block mb-1"><i class="fas fa-home text-success"></i> Disponible à la Retraite</small>
                      <div class="d-flex align-items-center mb-2">
                        <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 20px; margin-right: 8px; vertical-align: middle;">
                        <span class="h3 text-primary font-weight-bold mb-0"><?php echo number_format((float)$cat['retirement_price_cad'], 0, ',', ' '); ?> $</span>
                        <?php if (!empty($cat['old_retirement_price_cad'])): ?>
                            <span class="old-price ml-3 text-muted"><?php echo number_format((float)$cat['old_retirement_price_cad'], 0, ',', ' '); ?> $</span>
                        <?php endif; ?>
                      </div>
                      <div class="d-flex align-items-center">
                        <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 20px; margin-right: 8px; vertical-align: middle;">
                        <span class="h4 text-secondary font-weight-bold mb-0"><?php echo number_format((float)$cat['retirement_price_usd'], 0, ',', ' '); ?> $</span>
                        <?php if (!empty($cat['old_retirement_price_usd'])): ?>
                            <span class="old-price ml-3 text-muted"><?php echo number_format((float)$cat['old_retirement_price_usd'], 0, ',', ' '); ?> $</span>
                        <?php endif; ?>
                      </div>
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
