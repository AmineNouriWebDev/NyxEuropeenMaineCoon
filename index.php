<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des chatons disponibles
$cats = get_cats_from_db($pdo, 'available');
?>

<!-- Hero Section avec nouvelle vidéo -->
<section id="hero-section" style="position: relative; height: 100vh; width: 100%; overflow: hidden;">
  <div class="video-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
    <video autoplay muted loop playsinline id="hero-video" style="min-width: 100%; min-height: 100%; width: auto; height: auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover;">
      <source src="img/hero.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <!-- Fallback image -->
    <img src="https://images.unsplash.com/photo-1514888286974-6d03bde4ba48?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" alt="Maine Coon Hero" class="video-fallback" style="width: 100%; height: 100%; object-fit: cover;" />
  </div>
  <div class="hero-overlay"></div>
  
  <div class="hero-content" style="position: relative; z-index: 3; height: 100%; display: flex; align-items: center; justify-content: flex-start; padding: 0 20px;">
    <div style="width: 100%; margin-top: 65px;">
      <!-- Contenu aligné à l'extrémité gauche -->
      <div class="row ml-0 mr-0">
        <div class="col-lg-6 col-md-8 text-left pl-0">
          <h1 class="hero-title cursive-font" style="text-align: left;font-size: 3em">
            Trouvez Votre Compagnon <span class="highlight">Parfait</span>
          </h1>
          <p class="hero-subtitle" style="text-align: left;">
            Découvrez la majesté et la nature douce de nos chatons Maine Coon
            de race pure. Chacun est élevé avec amour, soin et dévouement pour
            trouver son foyer pour toujours.
          </p>
          <div class="mt-4" style="text-align: left;">
            <a href="#kittens" class="btn-cat mr-3 pulse">
              <i class="fas fa-paw"></i> Voir Nos Chatons
            </a>
            <a href="adoption.php" class="btn-cat btn-cat-secondary">
              <i class="fas fa-heart"></i> Processus d'Adoption
            </a>
          </div>
          
     

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Kittens Section -->
<section class="kitten-section" id="kittens">
  <div class="container">
    <div class="section-title">
      <h2>
        Nos <span style="color: var(--primary-color)">Chatons</span> Disponibles
      </h2>
      <p class="mt-3" style="max-width: 600px; margin: 0 auto">
        Chaque chaton est socialisé, examiné et prêt à apporter de la joie à votre foyer
      </p>
    </div>

    <!-- Kitten Cards -->
    <div class="row" id="kittens-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3>Aucun chaton disponible pour le moment.</h3>
              <p>Revenez bientôt ou consultez nos <a href="portees_a_venir.php">portées à venir</a> !</p>
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
                  <!-- Badge "Disponible" supprimé car tous les chatons de cette page sont disponibles -->
                </div>
                
                <div class="kitten-details">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <span class="kitten-gender <?php echo strtolower($cat['gender']); ?>">
                      <i class="text-white fas fa-<?php echo strtolower($cat['gender']) == 'male' ? 'mars' : 'venus'; ?>"></i>
                    </span>
                  </div>
                  
                  <div class="kitten-info-grid">
                    <!-- Âge avec icône calendrier -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Couleur avec icône palette -->
                    <div class="info-item">
                      <i class="fas fa-palette"></i>
                      <span><?php echo format_cat_color($cat); ?></span>
                    </div>
                    
                    <!-- Qualité avec icône paw -->
                    <div class="info-item">
                      <i class="fas fa-paw"></i>
                      <span><?php echo htmlspecialchars($cat['quality']); ?></span>
                    </div>

                    <!-- Type de pattes (si défini et différent de Régulières) -->
                    <?php if (!empty($cat['paw_type']) && $cat['paw_type'] !== 'Régulières'): ?>
                    <div class="info-item">
                        <i class="fas fa-hand-paper"></i>
                        <span><?php echo htmlspecialchars($cat['paw_type']); ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Parents -->
                    <!-- Parents supprimés de l'accueil (gardés pour détails) -->
                  </div>
                  
                  <!-- Prix réorganisés : CAD puis USD avec anciens prix -->
                  <!-- Prix stylisés -->
                  <div class="kitten-price-container mt-3 px-3 py-2" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #eee;">
                      <?php if (!empty($cat['price_cad'])): ?>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <span style="font-size: 1.2em; margin-right: 8px;">🇨🇦</span>
                                <span class="font-weight-bold text-dark">CAD</span>
                            </div>
                            <div class="text-right">
                                <?php if (!empty($cat['old_price_cad'])): ?>
                                    <small class="text-muted mr-1" style="text-decoration: line-through;"><?php echo number_format($cat['old_price_cad'], 0, ',', ' '); ?> $</small>
                                <?php endif; ?>
                                <span class="font-weight-bold ml-1" style="color: #2c3e50; font-size: 1.1em;"><?php echo number_format($cat['price_cad'], 0, ',', ' '); ?> $</span>
                            </div>
                        </div>
                      <?php endif; ?>
                      
                      <?php if (!empty($cat['price_usd'])): ?>
                        <div class="d-flex justify-content-between align-items-center pt-1" style="border-top: 1px dashed #e0e0e0;">
                            <div class="d-flex align-items-center">
                                <span style="font-size: 1.2em; margin-right: 8px;">🇺🇸</span>
                                <span class="font-weight-bold text-muted" style="font-size: 0.9em;">USD</span>
                            </div>
                            <div class="text-right">
                                <?php if (!empty($cat['old_price_usd'])): ?>
                                    <small class="text-muted mr-1" style="text-decoration: line-through;"><?php echo number_format($cat['old_price_usd'], 0, ',', ' '); ?> $</small>
                                <?php endif; ?>
                                <span class="font-weight-bold ml-1 text-muted" style="font-size: 1em;"><?php echo number_format($cat['price_usd'], 0, ',', ' '); ?> $</span>
                            </div>
                        </div>
                      <?php endif; ?>
                  </div>

                  <div class="kitten-actions mt-3">
                    <a href="chat_details.php?id=<?php echo $cat['id']; ?>" class="btn-cat btn-sm">Voir Détails</a>
                    <a href="javascript:void(0);" onclick="openInquiryModal('<?php echo $cat['id']; ?>', '<?php echo addslashes(htmlspecialchars($cat['name'])); ?>')" class="btn-cat btn-cat-secondary btn-sm">Se Renseigner</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Featured Stats -->
    <div class="row mt-5 pt-5">
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--primary-color)">200+</div>
        <div>Familles Heureuses</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--secondary-color)">15</div>
        <div>Ans d'Expérience</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--accent-color)">100%</div>
        <div>Garantie Santé</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--cat-eye-green)">5★</div>
        <div>Avis Clients</div>
      </div>
    </div>
  </div>
</section>


<!-- Modal Se Renseigner -->
<div id="inquiryModal" class="inquiry-modal">
  <div class="inquiry-modal-content">
    <div class="inquiry-modal-header">
      <h2 id="modalCatName">Se renseigner sur ...</h2>
      <span class="close-modal" onclick="closeInquiryModal()">&times;</span>
    </div>
    <div class="inquiry-modal-body">
      <form id="inquiryForm" onsubmit="submitInquiry(event)">
        <input type="hidden" id="inquiryCatId" name="cat_id">
        <input type="hidden" id="inquiryCatNameName" name="cat_name">
        
        <div class="form-group">
          <label for="visitorName">Votre Nom</label>
          <input type="text" id="visitorName" name="visitor_name" required placeholder="Votre nom complet">
        </div>
        
        <div class="form-group">
          <label for="visitorPhone">Téléphone</label>
          <input type="tel" id="visitorPhone" name="visitor_phone" placeholder="Votre numéro de téléphone">
        </div>
        
        <div class="form-group">
          <label for="visitorEmail">Email</label>
          <input type="email" id="visitorEmail" name="visitor_email" required placeholder="votre.email@exemple.com">
        </div>
        
        <div class="form-group">
          <label for="visitorMessage">Message</label>
          <textarea id="visitorMessage" name="message" rows="4" required placeholder="Bonjour, je suis intéressé(e) par ce chaton..."></textarea>
        </div>
        
        <button type="submit" class="btn-submit">Envoyer ma demande</button>
      </form>
    </div>
  </div>
</div>

<script>
function openInquiryModal(catId, catName) {
  document.getElementById('inquiryModal').style.display = 'flex';
  document.getElementById('modalCatName').textContent = 'Se renseigner sur ' + catName;
  document.getElementById('inquiryCatId').value = catId;
  document.getElementById('inquiryCatNameName').value = catName;
  document.body.style.overflow = 'hidden'; // Empêcher le scroll
}

function closeInquiryModal() {
  document.getElementById('inquiryModal').style.display = 'none';
  document.body.style.overflow = 'auto'; // Réactiver le scroll
}

// Fermer si on clique en dehors
window.onclick = function(event) {
  const modal = document.getElementById('inquiryModal');
  if (event.target == modal) {
    closeInquiryModal();
  }
}

function submitInquiry(event) {
  event.preventDefault();
  
  const form = document.getElementById('inquiryForm');
  const formData = new FormData(form);
  const btn = form.querySelector('button[type="submit"]');
  const originalText = btn.textContent;
  
  btn.textContent = 'Envoi en cours...';
  btn.disabled = true;
  
  fetch('ajax_inquiry.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      closeInquiryModal();
      form.reset();
    } else {
      alert('Erreur : ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Une erreur est survenue lors de l\'envoi.');
  })
  .finally(() => {
    btn.textContent = originalText;
    btn.disabled = false;
  });
}
</script>

<?php include 'includes/footer.php'; ?>