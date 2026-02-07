<!doctype html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../../includes/seo_config.php'; ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- SEO & Dynamic Meta Tags -->
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
  <link rel="manifest" href="../site.webmanifest">
  <meta name="theme-color" content="#667eea">
  
  <!-- Icons & Favicons -->
  <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
  <link rel="shortcut icon" href="../img/logo_principal.png">
  
  <!-- Verifications (Placeholders) -->
  <!-- <meta name="google-site-verification" content="YOUR_CODE_HERE" /> -->
  <meta name="google-site-verification" content="DisyY1epBoa1xefepS7DuJS-feY_hTT4aH47w9b7OzM" />

  <!-- Google Fonts - Amatic SC for decorative titles -->
  <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet" />
  
  <!-- Charm & Fuzzy Bubbles -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Grandstander:wght@100..900&display=swap" rel="stylesheet">
  
  <!-- Schema.org Organization -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "AnimalShelter",
    "name": "Nyx European Maine Coon",
    "alternateName": "Nyx Coon Cattery",
    "url": "<?php echo SITE_URL; ?>/en/",
    "logo": "<?php echo asset_url('img/logo_principal.png'); ?>",
    "sameAs": [
      "https://www.facebook.com/profile.php?id=61581523927046",
      "https://www.instagram.com/nyxcoon_cattery_montreal/",
      "https://www.tiktok.com/@nyx_coon_cattery"
    ],
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Montreal",
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
  <link rel="stylesheet" href="../css/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="../img/logo_principal.png">
  
</head>

<body>
  <!-- Transparent Navigation Menu -->
  <nav id="main-nav">
    <!-- Section 1: Social Media + Association Logos -->
    <div class="top-section">
      <div class="container-fluid position-relative">
        <div class="d-flex justify-content-center align-items-center">
          <!-- Association Logos - Centered -->
          <div class="association-logos">
            <a href="https://www.wcf-bestcat.de/" target="_blank"><img src="../img/icones/l1.png" alt="Association 1" class="assoc-logo"></a>
            <a href="https://tica.org/" target="_blank"><img src="../img/icones/l2.png" alt="Association 2" class="assoc-logo"></a>
            <a href="https://www.cca-afc.com/" target="_blank"><img src="../img/icones/l3.png" alt="Association 3" class="assoc-logo"></a>
          </div>
        </div>

        <!-- Language Switcher - Absolute Right -->
        <div class="language-switcher-wrapper" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%);">
             <?php 
               $current_page = basename($_SERVER['PHP_SELF']);
               $query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
             ?>
             <a href="<?php echo '../' . $current_page . $query_string; ?>" class="lang-toggle-btn" title="Passer en Français">
                <img src="https://flagcdn.com/24x18/fr.png" alt="FR" class="flag-icon">
                <span class="lang-text">Version Française</span>
             </a>
        </div>
      </div>
    </div>

    <!-- Section 2: Logo + Navigation Buttons -->
    <div class="nav-section">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <!-- Main Logo -->
          <div class="main-logo">
            <a href="index.php">
              <img src="../img/logo_principal.png" alt="Nyx Maine Coon Logo">
            </a>
          </div>

          <!-- Navigation Buttons -->
          <div class="nav-buttons">
            <a href="index.php" class="nav-btn">
              <span>AVAILABLE KITTENS</span>
              <span>& CATS</span>
            </a>
            <a href="kings.php" class="nav-btn">
              <span>KINGS</span>
            </a>
            <a href="queens.php" class="nav-btn">
              <span>QUEENS</span>
            </a>
            <a href="chatons_reserves.php" class="nav-btn">
              <span>RESERVED KITTENS</span>
              <span>& CATS</span>
            </a>
            <a href="portees_a_venir.php" class="nav-btn">
              <span>UPCOMING</span>
              <span>LITTERS</span>
            </a>
            <a href="adoption.php" class="nav-btn">
              <span>ADOPTION</span>
              <span>PROCESS</span>
            </a>
            <a href="contact.php" class="nav-btn">
              <span>CONTACT</span>
              <span>& INFO</span>
            </a>
            <a href="about.php" class="nav-btn">
              <span>UNDER CONSTRUCTION</span>
            </a>
          </div>
        </div>
      </div>
    </div>

  </nav>

    <!-- Mobile Logo (visible only on mobile) -->
    <div class="mobile-logo">
      <img src="../img/logo_principal.png" alt="Nyx Maine Coon">
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
        <!-- Association Logos TOP -->
        <div class="mobile-associations-top">
          <div class="association-logos justify-content-center mb-4">
            <a href="https://www.wcf-bestcat.de/" target="_blank"><img src="../img/icones/l1.png" alt="WCF" class="assoc-logo"></a>
            <a href="https://tica.org/" target="_blank"><img src="../img/icones/l2.png" alt="TICA" class="assoc-logo"></a>
            <a href="https://www.cca-afc.com/" target="_blank"><img src="../img/icones/l3.png" alt="CCA" class="assoc-logo"></a>
          </div>
          <!-- Language Switcher Mobile -->
          <div class="d-flex justify-content-center mb-4">
             <?php 
               $current_page = basename($_SERVER['PHP_SELF']);
               $query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
             ?>
             <a href="<?php echo '../' . $current_page . $query_string; ?>" class="lang-toggle-btn" title="Passer en Français">
                <img src="https://flagcdn.com/24x18/fr.png" alt="FR" class="flag-icon">
                <span class="lang-text" style="color: #2d3436 !important;">Version Française</span>
             </a>
          </div>
        </div>
        
        <!-- Navigation Links -->
        <a href="index.php" class="mobile-nav-link">AVAILABLE KITTENS & CATS</a>
        <a href="kings.php" class="mobile-nav-link">KINGS</a>
        <a href="queens.php" class="mobile-nav-link">QUEENS</a>
        <a href="chatons_reserves.php" class="mobile-nav-link">RESERVED KITTENS & CATS</a>
        <a href="portees_a_venir.php" class="mobile-nav-link">UPCOMING LITTERS</a>
        <a href="adoption.php" class="mobile-nav-link">ADOPTION PROCESS</a>
        <a href="contact.php" class="mobile-nav-link">CONTACT & INFO</a>
        <a href="about.php" class="mobile-nav-link">UNDER CONSTRUCTION</a>
        
        <!-- Social Networks BOTTOM -->
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
      document.body.classList.add('menu-open'); // Add class to hide burger button
    });

    mobileMenuClose.addEventListener('click', () => {
      mobileMenu.classList.remove('active');
      document.body.classList.remove('menu-open'); // Remove class to show burger button
    });

    // Close on link click
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        document.body.classList.remove('menu-open'); // Remove class to show burger button
      });
    });
  </script>