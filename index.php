<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des chatons disponibles
$cats = get_cats_from_db($pdo, 'available');
?>

<script>
  // Ajouter la classe 'home' au body pour cette page uniquement
  document.body.classList.add('home');
</script>

<!-- Hero Section avec nouvelle vidéo -->
<section id="hero-section" style="position: relative; height: 100vh; width: 100%; overflow: hidden;">
  <div class="video-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
    <video autoplay muted loop playsinline id="hero-video" style="min-width: 100%; min-height: 100%; width: auto; height: auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover;">
      <source src="img/video_hero.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <!-- Fallback image -->
   
  </div>
  <div class="hero-overlay"></div>
  
  <div class="hero-content" style="position: relative; z-index: 3; height: 100%; display: flex; align-items: center; justify-content: flex-start; padding: 0 20px;">
    <div style="width: 100%; margin-top: 0px;">
      <!-- Contenu aligné à l'extrémité gauche -->
      <div class="row ml-0 mr-0">
        <div class="col-lg-6 col-md-8 text-left pl-0">
          <!-- Logo Principal replace Text -->
          <img id="heroLogo" src="img/logo_principal.png" alt="Nyx Maine Coon" style="width: 100%; max-width: 300px; height: auto; display: block; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); transition: opacity 0.3s ease, transform 0.3s ease;">
          
          
     

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Social Links Section (Moved from Header) -->
<section class="social-links-section py-4" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
  <div class="container">
    <div class="d-flex justify-content-center align-items-center">
      <div class="social-links">
        <a href="https://www.tiktok.com/@nyx_coon_cattery" target="_blank" class="social-icon tiktok" title="TikTok" style="width: 50px; height: 50px; font-size: 24px; margin: 0 10px;">
          <i class="fab fa-tiktok"></i>
        </a>
        <a href="https://www.youtube.com/@chatterienyxcooneuropéenmainec" target="_blank" class="social-icon youtube" title="YouTube" style="width: 50px; height: 50px; font-size: 24px; margin: 0 10px;">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="https://www.facebook.com/profile.php?id=61581523927046" target="_blank" class="social-icon facebook" title="Facebook" style="width: 50px; height: 50px; font-size: 24px; margin: 0 10px;">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.instagram.com/nyxcoon_cattery_montreal/" target="_blank" class="social-icon instagram" title="Instagram" style="width: 50px; height: 50px; font-size: 24px; margin: 0 10px;">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://wa.me/15142695930" target="_blank" class="social-icon whatsapp" title="WhatsApp" style="width: 50px; height: 50px; font-size: 24px; margin: 0 10px;">
          <i class="fab fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Kittens Section -->
<section class="kitten-section purple-hero-bg" id="kittens">
  <div class="container">
    <!-- Excellence Card (centered) -->
    <div class="row justify-content-center mb-5">
      <div class="col-md-6 col-lg-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
          <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
            <i class="fas fa-trophy fa-2x"></i>
          </div>
          <h4>Excellence</h4>
          <p>Nous visons le "look sauvage" typique des lignées européennes : museaux forts, grandes oreilles avec de lourdes pointes de lynx et une ossature substantielle.</p>
        </div>
      </div>
    </div>
    
    <div class="section-title">
      <h2>
        Nos <span style="color: var(--accent-color)">Chatons & Chats</span> Disponibles
      </h2>
      <p class="mt-3" style="max-width: 600px; margin: 0 auto">
        Chaque chaton est socialisé, examiné et prêt à apporter de la joie à votre foyer
      </p>
    </div>

    <!-- Kitten Cards -->
    <div class="row" id="kittens-grid">
      <?php if(empty($cats)): ?>
          <div class="col-12 text-center p-5">
              <h3>Tous nos petits Nyx Coon on trouvé un foyer.</h3>
              <p>Ne perdez pas votre chance et inscrivez-vous sur la liste de priorité 👉 <a href="portees_a_venir.php">portées à venir</a> !</p>
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
                    <!-- Sexe : Icone Mixte + Texte -->
                    <div class="info-item">
                      <i class="fas fa-venus-mars text-dark"></i>
                      <span><?php echo strtolower($cat['gender']) == 'male' ? 'Mâle' : 'Femelle'; ?></span>
                    </div>

                    <!-- Âge avec icône calendrier noir -->
                    <div class="info-item">
                      <i class="fas fa-calendar-alt text-dark"></i>
                      <span><?php echo $age_display; ?></span>
                    </div>
                    
                    <!-- Qualité avec icône chat noir -->
                    <div class="info-item">
                      <i class="fas fa-cat text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['quality']); ?></span>
                    </div>

                    <!-- Type de pattes avec icône patte -->
                    <div class="info-item">
                      <i class="fas fa-paw text-dark"></i>
                      <span><?php echo htmlspecialchars($cat['paw_type'] ?? 'Régulières'); ?></span>
                    </div>
                  </div>
                  
                  <!-- Prix réorganisés : CAD puis USD avec anciens prix -->
                  <!-- Prix stylisés -->
                  <div class="kitten-price-container mt-3 px-3 py-2" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #eee;">
                      <?php if (!empty($cat['price_cad'])): ?>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 20px; margin-right: 8px; vertical-align: middle;">
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
                                <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 20px; margin-right: 8px; vertical-align: middle;">
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

                  <!-- Boutons Parents -->
                  <div class="d-flex justify-content-between mt-3" style="gap: 10px;">
                    <?php if (!empty($cat['father_id']) && !empty($cat['father_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['father_id']; ?>" class="btn-cat btn-sm flex-fill text-center" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-crown text-warning mr-1"></i> Voir Papa
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($cat['mother_id']) && !empty($cat['mother_name'])): ?>
                      <a href="chat_details.php?id=<?php echo $cat['mother_id']; ?>" class="btn-cat btn-cat-secondary btn-sm flex-fill text-center" style="font-size: 0.8rem; padding: 8px 12px;">
                        <i class="fas fa-heart text-danger mr-1"></i> Voir Maman
                      </a>
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
<script>
window.addEventListener('DOMContentLoaded', function() {
    const section = document.querySelector('#kittens');
    const container = section.querySelector('.container');
    
    console.log('=== SECTION BACKGROUND ===');
    console.log('Section BG:', window.getComputedStyle(section).background);
    
    console.log('\n=== CONTAINER BACKGROUND ===');
    console.log('Container BG:', window.getComputedStyle(container).background);
    
    console.log('\n=== PSEUDO-ÉLÉMENTS ===');
    const before = window.getComputedStyle(section, '::before');
    const after = window.getComputedStyle(section, '::after');
    console.log('::before content:', before.content);
    console.log('::before BG:', before.background);
    console.log('::after content:', after.content);
    console.log('::after BG:', after.background);
    
    // Lister TOUS les enfants avec background
    console.log('\n=== TOUS LES ENFANTS ===');
    section.querySelectorAll('*').forEach((el, i) => {
        const bg = window.getComputedStyle(el).background;
        if (bg && bg !== 'rgba(0, 0, 0, 0) none repeat scroll 0% 0% / auto padding-box border-box') {
            console.log(`Élément ${i} [${el.tagName}.${el.className}]:`, bg);
        }
    });
});

// Masquer le logo principal au scroll sur mobile et tablette uniquement
function handleLogoScroll() {
    // Vérifier si on est sur mobile ou tablette (largeur <= 1024px)
    if (window.innerWidth <= 1024) {
        const heroLogo = document.getElementById('heroLogo');
        const scrollPosition = window.scrollY;
        const fadeStart = 50; // Commence à disparaître après 50px
        const fadeEnd = 150; // Complètement invisible après 150px
        
        // Gérer la classe 'scrolled' sur le body pour le pseudo-élément
        if (scrollPosition >= fadeEnd) {
            document.body.classList.add('scrolled');
        } else {
            document.body.classList.remove('scrolled');
        }
        
        if (heroLogo) {
            if (scrollPosition <= fadeStart) {
                // Pleinement visible
                heroLogo.style.opacity = '1';
                heroLogo.style.transform = 'translateY(0)';
            } else if (scrollPosition >= fadeEnd) {
                // Complètement masqué
                heroLogo.style.opacity = '0';
                heroLogo.style.transform = 'translateY(-20px)';
            } else {
                // Transition progressive
                const progress = (scrollPosition - fadeStart) / (fadeEnd - fadeStart);
                heroLogo.style.opacity = (1 - progress).toString();
                heroLogo.style.transform = `translateY(-${progress * 20}px)`;
            }
        }
    }
}

// Écouter le scroll
window.addEventListener('scroll', handleLogoScroll);

// Vérifier au chargement de la page
window.addEventListener('load', handleLogoScroll);

// Réinitialiser si on redimensionne la fenêtre
window.addEventListener('resize', function() {
    const heroLogo = document.getElementById('heroLogo');
    if (window.innerWidth > 1024) {
        // Sur desktop, toujours visible
        document.body.classList.remove('scrolled');
        if (heroLogo) {
            heroLogo.style.opacity = '1';
            heroLogo.style.transform = 'translateY(0)';
        }
    } else {
        // Sur mobile/tablette, recalculer
        handleLogoScroll();
    }
});
</script>
    
<?php include 'includes/footer.php'; ?>