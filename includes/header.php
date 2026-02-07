<!doctype html>
<html lang="fr">

<head>
  <?php include 'includes/seo_config.php'; ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- SEO & Meta Tags Dynamiques -->
  <title><?php echo $meta_title; ?></title>
  <meta name="description" content="<?php echo $meta_description; ?>">
  <meta name="keywords" content="<?php echo $meta_keywords; ?>">
  <link rel="canonical" href="<?php echo $canonical_url; ?>">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo $canonical_url; ?>">
  <meta property="og:title" content="<?php echo $meta_title; ?>">
  <meta property="og:description" content="<?php echo $meta_description; ?>">
  <meta property="og:image" content="<?php echo $og_image; ?>">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="<?php echo $canonical_url; ?>">
  <meta property="twitter:title" content="<?php echo $meta_title; ?>">
  <meta property="twitter:description" content="<?php echo $meta_description; ?>">
  <meta property="twitter:image" content="<?php echo $og_image; ?>">

  <!-- PWA & Mobile -->
  <link rel="manifest" href="site.webmanifest">
  <meta name="theme-color" content="#667eea">
  
  <!-- Verifications (Placeholders) -->
  <!-- <meta name="google-site-verification" content="VOTRE_CODE_ICI" /> -->
  <meta name="google-site-verification" content="DisyY1epBoa1xefepS7DuJS-feY_hTT4aH47w9b7OzM" />
  <!-- Google Fonts - Amatic SC pour titres décoratifs -->
  <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet" />
  
  <!-- Charm & Fuzzy Bubbles -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Grandstander:wght@100..900&display=swap" rel="stylesheet">
  
  <!-- Preload Critical Resources -->
  <!-- <link rel="preload" href="img/logo_principal.png" as="image"> -->

  <!-- Schema.org Organization -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "AnimalShelter",
    "name": "Nyx European Maine Coon",
    "alternateName": "Chatterie Nyx Coon",
    "url": "<?php echo SITE_URL; ?>",
    "logo": "<?php echo asset_url('img/logo_principal.png'); ?>",
    "sameAs": [
      "https://www.facebook.com/profile.php?id=61581523927046",
      "https://www.instagram.com/nyxcoon_cattery_montreal/",
      "https://www.tiktok.com/@nyx_coon_cattery"
    ],
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Montréal",
      "addressRegion": "QC",
      "addressCountry": "CA"
    }
  }
  </script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

  <!-- Owl Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="img/logo_principal.png">
  
</head>

<body>
  <!-- Menu Navigation Transparent -->
  <nav id="main-nav">
    <!-- Section 1 : Réseaux Sociaux + Logos Associations -->
    <div class="top-section">
      <div class="container-fluid position-relative">
        <div class="d-flex justify-content-center align-items-center">
          <!-- Logos Associations - Centrés -->
          <div class="association-logos">
            <a href="https://www.wcf-bestcat.de/" target="_blank"><img src="img/icones/l1.png" alt="Association 1" class="assoc-logo"></a>
            <a href="https://tica.org/" target="_blank"><img src="img/icones/l2.png" alt="Association 2" class="assoc-logo"></a>
            <a href="https://www.cca-afc.com/" target="_blank"><img src="img/icones/l3.png" alt="Association 3" class="assoc-logo"></a>
          </div>
        </div>

        <!-- Language Switcher - Absolu à Droite -->
        <div class="language-switcher-wrapper" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%);">
             <?php 
               $current_page = basename($_SERVER['PHP_SELF']);
               $query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
             ?>
             <a href="<?php echo 'en/' . $current_page . $query_string; ?>" class="lang-toggle-btn" title="Switch to English">
                <img src="https://flagcdn.com/24x18/us.png" alt="USA" class="flag-icon">
                <span class="lang-text">English Version</span>
             </a>
        </div>
      </div>
    </div>

    <!-- Section 2 : Logo + Navigation Buttons -->
    <div class="nav-section">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <!-- Logo Principal -->
          <div class="main-logo">
            <a href="index.php">
              <img src="img/logo_principal.png" alt="Nyx Maine Coon Logo">
            </a>
          </div>

          <!-- Navigation Buttons -->
          <div class="nav-buttons">
            <a href="index.php" class="nav-btn">
              <span>CHATONS & CHATS</span>
              <span>DISPONIBLES</span>
            </a>
            <a href="kings.php" class="nav-btn">
              <span>KINGS</span>
            </a>
            <a href="queens.php" class="nav-btn">
              <span>QUEENS</span>
            </a>
            <a href="chatons_reserves.php" class="nav-btn">
              <span>CHATONS & CHATS</span>
              <span>RÉSERVÉS</span>
            </a>
            <a href="portees_a_venir.php" class="nav-btn">
              <span>PORTÉES</span>
              <span>À VENIR</span>
            </a>
            <a href="adoption.php" class="nav-btn">
              <span>PROCESSUS</span>
              <span>D'ADOPTION</span>
            </a>
            <a href="contact.php" class="nav-btn">
              <span>CONTACTS & INFO</span>
            </a>
            <a href="about.php" class="nav-btn">
              <span>EN CONSTRUCTION</span>
             
            </a>
          </div>
        </div>
      </div>
    </div>

  </nav>

    <!-- Mobile Logo (visible uniquement en mobile) -->
    <div class="mobile-logo">
      <img src="img/logo_principal.png" alt="Nyx Maine Coon">
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu" id="mobileMenu">
      <button class="mobile-menu-close" id="mobileMenuClose">
        <i class="fas fa-times"></i>
      </button>
      <div class="mobile-menu-content">
        <!-- Logos Associations en HAUT -->
        <div class="mobile-associations-top">
          <div class="association-logos justify-content-center mb-4">
            <a href="https://www.wcf-bestcat.de/" target="_blank"><img src="img/icones/l1.png" alt="WCF" class="assoc-logo"></a>
            <a href="https://tica.org/" target="_blank"><img src="img/icones/l2.png" alt="TICA" class="assoc-logo"></a>
            <a href="https://www.cca-afc.com/" target="_blank"><img src="img/icones/l3.png" alt="CCA" class="assoc-logo"></a>
          </div>
          <!-- Language Switcher Mobile -->
          <div class="d-flex justify-content-center mb-4">
             <?php 
               $current_page = basename($_SERVER['PHP_SELF']);
               $query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
             ?>
             <a href="<?php echo 'en/' . $current_page . $query_string; ?>" class="lang-toggle-btn" title="Switch to English">
                <img src="https://flagcdn.com/24x18/us.png" alt="USA" class="flag-icon">
                <span class="lang-text" style="color: #2d3436 !important;">English Version</span>
             </a>
          </div>
        </div>
        
        <!-- Navigation Links -->
        <a href="index.php" class="mobile-nav-link">CHATONS & CHATS DISPONIBLES</a>
        <a href="kings.php" class="mobile-nav-link">KINGS</a>
        <a href="queens.php" class="mobile-nav-link">QUEENS</a>
        <a href="chatons_reserves.php" class="mobile-nav-link">CHATONS & CHATS RÉSERVÉS</a>
        <a href="portees_a_venir.php" class="mobile-nav-link">PORTÉES À VENIR</a>
        <a href="adoption.php" class="mobile-nav-link">PROCESSUS D'ADOPTION</a>
        <a href="contact.php" class="mobile-nav-link">CONTACTS & INFO</a>
        <a href="about.php" class="mobile-nav-link">EN CONSTRUCTION</a>
        
        <!-- Réseaux Sociaux en BAS -->
        <div class="mobile-social-bottom mt-4">
          <div class="social-links justify-content-center">
            <a href="https://www.tiktok.com/@nyx_coon_cattery" target="_blank" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
            <a href="https://www.youtube.com/@chatterienyxcooneuropéenmainec" target="_blank" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
            <a href="https://www.facebook.com/profile.php?id=61581523927046" target="_blank" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/nyxcoon_cattery_montreal/" target="_blank" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/15142695930" target="_blank" class="social-icon whatsapp"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>
      </div>
    </div>

  <script>
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuClose = document.getElementById('mobileMenuClose');

    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.add('active');
      document.body.classList.add('menu-open'); // Ajouter classe pour cacher le bouton burger
    });

    mobileMenuClose.addEventListener('click', () => {
      mobileMenu.classList.remove('active');
      document.body.classList.remove('menu-open'); // Retirer classe pour afficher le bouton burger
    });

    // Close on link click
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        document.body.classList.remove('menu-open'); // Retirer classe pour afficher le bouton burger
      });
    });
  </script>