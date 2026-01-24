<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$cats = get_cats_from_db($pdo);

if (empty($cats)) {
  $cats = [
    [
      'id' => 'luna',
      'name' => 'Luna',
      'gender' => 'Female',
      'age' => '4 months',
      'color' => 'Blue Smoke Tortie',
      'quality' => 'Pet & Breeding Quality',
      'weight' => 'Expected: 15-18 lbs',
      'price' => 2950,
      'old_price' => null,
      'images' => ['1.jpg', '2.jpg', '3.jpg'],
      'video_url' => 'https://www.youtube.com/embed/g69awDfW054'
    ],
    [
      'id' => 'thor',
      'name' => 'Thor',
      'gender' => 'Male',
      'age' => '5 months',
      'color' => 'Red Tabby',
      'quality' => 'Pet Quality',
      'weight' => 'Expected: 20-22 lbs',
      'price' => 3550,
      'old_price' => 3950,
      'images' => ['4.jpg', '5.jpg', '6.jpg'],
      'video_url' => 'https://www.youtube.com/embed/-VwNjeZXsMY'
    ],
    [
      'id' => 'nala',
      'name' => 'Nala',
      'gender' => 'Female',
      'age' => '2 months',
      'color' => 'Black Smoke',
      'quality' => 'Breeding Quality',
      'weight' => 'Expected: 14-16 lbs',
      'price' => 3750,
      'old_price' => null,
      'images' => ['7.jpg', '8.jpg', '9.jpg'],
      'video_url' => 'https://www.youtube.com/embed/g_LNu6Aaxvk'
    ]
  ];
}

$hasCats = !empty($cats);
?>

<!-- Hero Section -->
<section id="hero-section">
  <div class="cat-pattern"></div>
  <div class="video-container-mobile">
    <video autoplay muted loop playsinline class="hero-video">
      <source src="img/hero.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <!-- Fallback image -->
    <img src="https://images.unsplash.com/photo-1514888286974-6d03bde4ba48?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" alt="Maine Coon Hero" class="video-fallback" />
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="container">
      <h1 class="hero-title cursive-font">
        Find Your <span class="highlight">Purrfect</span> Companion
      </h1>
      <p class="hero-subtitle">
        Experience the majesty and gentle nature of our purebred Maine Coon
        kittens. Each one is raised with love, care, and dedication to
        finding their forever home.
      </p>
      <div class="mt-4">
        <a href="#kittens" class="btn-cat mr-3 pulse">
          <i class="fas fa-paw"></i> Meet Our Kittens
        </a>
        <button class="btn-cat btn-cat-secondary">
          <i class="fas fa-heart"></i> Adoption Process
        </button>
      </div>
      <div class="mt-5">
        <div class="d-flex justify-content-center">
          <div class="text-center mx-4">
            <i class="fas fa-home fa-2x mb-2" style="color: var(--accent-color)"></i>
            <div class="cursive-font" style="font-size: 1.5rem">Loving Homes</div>
          </div>
          <div class="text-center mx-4">
            <i class="fas fa-shield-alt fa-2x mb-2" style="color: var(--secondary-color)"></i>
            <div class="cursive-font" style="font-size: 1.5rem">Health Guarantee</div>
          </div>
          <div class="text-center mx-4">
            <i class="fas fa-star fa-2x mb-2" style="color: var(--primary-color)"></i>
            <div class="cursive-font" style="font-size: 1.5rem">Purebred Excellence</div>
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
        Our Available <span style="color: var(--primary-color)">Kittens</span>
      </h2>
      <p class="mt-3" style="max-width: 600px; margin: 0 auto">
        Each kitten is socialized, health-checked, and ready to bring joy to your home
      </p>
    </div>

    <?php
    // Analyse des données pour générer les filtres dynamiquement
    $availableGenders = [];
    $availableColors = [];
    $availableTypes = [];

    foreach ($cats as $cat) {
        // Gender (Valeur exacte)
        $g = trim($cat['gender']);
        if ($g) $availableGenders[] = ucfirst(strtolower($g));

        // Color (Valeur complète pour correspondre au Select Admin)
        $c = trim($cat['color']);
        if ($c) $availableColors[] = ucfirst(strtolower($c));

        // Type / Quality (Extraire mots-clés simples)
        // On peut simplifier aussi si on veut juste Pet / Breeding
        $q = strtolower($cat['quality']);
        if (strpos($q, 'pet') !== false) $availableTypes[] = 'Pet';
        if (strpos($q, 'breeding') !== false) $availableTypes[] = 'Breeding';
    }

    // Dédoublonnage et Tri
    $availableGenders = array_unique($availableGenders);
    sort($availableGenders);
    
    $availableColors = array_unique($availableColors);
    sort($availableColors);
    
    $availableTypes = array_unique($availableTypes);
    sort($availableTypes);
    ?>

    <!-- Filtres Dynamiques -->
    <div class="filters-container">
      <div class="row">
        <!-- Gender Filter -->
        <div class="col-md-4">
          <div class="filter-group" data-filter-group="gender">
            <div class="filter-title"><i class="fas fa-venus-mars"></i> Gender</div>
            <div class="filter-options">
              <div class="filter-option active" data-filter="all">All</div>
              <?php foreach ($availableGenders as $g): ?>
                  <div class="filter-option" data-filter="<?php echo strtolower($g); ?>"><?php echo $g; ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Color Filter -->
        <div class="col-md-4">
          <div class="filter-group" data-filter-group="color">
            <div class="filter-title"><i class="fas fa-palette"></i> Color</div>
            <div class="filter-options">
              <div class="filter-option active" data-filter="all">All Colors</div>
              <?php foreach ($availableColors as $c): ?>
                  <div class="filter-option" data-filter="<?php echo strtolower($c); ?>"><?php echo $c; ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Type Filter -->
        <div class="col-md-4">
          <div class="filter-group" data-filter-group="quality">
            <div class="filter-title"><i class="fas fa-paw"></i> Type</div>
            <div class="filter-options">
              <div class="filter-option active" data-filter="all">All</div>
              <?php foreach ($availableTypes as $t): ?>
                  <div class="filter-option" data-filter="<?php echo strtolower($t); ?>"><?php echo $t; ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kitten Cards -->
    <div class="row" id="kittens-grid">
      <?php foreach ($cats as $cat): ?>
        <?php
        $cat_id = $cat['id'];
        $images = $cat['images'];
        $video_url = $cat['video_url'] ?? null;

        // Extraire ID YouTube
        $youtube_id = null;
        if ($video_url) {
          $parts = explode('/', $video_url);
          $videoId = explode('?', end($parts))[0];
          $youtube_id = $videoId;
        }
        ?>
        <div class="col-lg-4 col-md-6 mb-4 kitten-item" 
             data-gender="<?php echo strtolower(trim($cat['gender'])); ?>" 
             data-color="<?php echo strtolower(trim($cat['color'])); ?>" 
             data-quality="<?php echo strtolower(trim($cat['quality'])); ?>">
          <div class="kitten-card">
            <div class="kitten-gallery">
              <?php if ($video_url): ?>
                <div class="video-badge"><i class="fas fa-video"></i> 1 video</div>
              <?php endif; ?>

              <!-- Gallery Slider -->
              <div class="gallery-slider" id="gallery-<?php echo $cat_id; ?>">
                <?php $slideIndex = 0; ?>
                <?php foreach ($images as $img): ?>
                  <div class="gallery-slide <?php echo $slideIndex === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo esc(cat_image_url($img)); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?> <?php echo $slideIndex + 1; ?>" />
                  </div>
                  <?php $slideIndex++; ?>
                <?php endforeach; ?>

                <?php if ($youtube_id): ?>
                  <div class="gallery-slide video-slide" data-video-url="<?php echo htmlspecialchars($video_url); ?>">
                    <img src="<?php echo esc(get_youtube_thumbnail($youtube_id)); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?> Video" class="video-thumbnail" />
                    <div class="play-btn"><i class="fas fa-play"></i></div>
                  </div>
                <?php $slideIndex++;
                endif; ?>
              </div>

              <!-- Navigation -->
              <div class="gallery-nav">
                <button class="gallery-nav-btn" onclick="prevSlide('<?php echo $cat_id; ?>')">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-nav-btn" onclick="nextSlide('<?php echo $cat_id; ?>')">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>

              <!-- Indicators -->
              <div class="gallery-indicators">
                <?php
                $totalSlides = count($images) + ($video_url ? 1 : 0);
                for ($i = 0; $i < $totalSlides; $i++):
                ?>
                  <div class="gallery-indicator <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide('<?php echo $cat_id; ?>', <?php echo $i; ?>)"></div>
                <?php endfor; ?>
              </div>
            </div>

            <div class="kitten-info">
              <h3 class="kitten-name"><?php echo htmlspecialchars($cat['name']); ?></h3>
              <ul class="kitten-details">
                <li><i class="fas fa-<?php echo strtolower($cat['gender']) === 'female' ? 'venus' : 'mars'; ?>"></i>
                  <?php echo htmlspecialchars($cat['gender']); ?> • <?php echo htmlspecialchars($cat['age_text']); ?>
                </li>
                <li><i class="fas fa-paw"></i> <?php echo htmlspecialchars($cat['color']); ?></li>
                <li><i class="fas fa-heart"></i> <?php echo htmlspecialchars($cat['quality']); ?></li>
                <li><i class="fas fa-weight"></i> <?php echo htmlspecialchars($cat['weight']); ?></li>
              </ul>
              <div class="kitten-price">
                <?php if ($cat['old_price']): ?>
                  <span style="text-decoration: line-through; color: #999; margin-right: 10px;">
                    $<?php echo number_format($cat['old_price']); ?>
                  </span>
                <?php endif; ?>
                <strong>$<?php echo number_format($cat['price']); ?></strong>
              </div>
              <div class="kitten-actions">
                <a href="cat_details.php?id=<?php echo $cat_id; ?>" class="btn-cat text-decoration-none text-white text-center" style="flex: 1">
                  <i class="fas fa-eye"></i> View Details
                </a>
                <a href="cat_details.php?id=<?php echo $cat_id; ?>&action=inquire" class="btn-cat btn-cat-secondary text-decoration-none text-white text-center" style="flex: 1">
                  <i class="fas fa-envelope"></i> Inquire
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Featured Stats -->
    <div class="row mt-5 pt-5">
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--primary-color)">200+</div>
        <div>Happy Families</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--secondary-color)">15</div>
        <div>Years Experience</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--accent-color)">100%</div>
        <div>Health Guarantee</div>
      </div>
      <div class="col-md-3 col-6 text-center">
        <div class="cursive-font" style="font-size: 3rem; color: var(--cat-eye-green)">5★</div>
        <div>Customer Reviews</div>
      </div>
    </div>
  </div>
</section>

<script>
  // Configuration des galleries
  const galleries = {
    <?php foreach ($cats as $cat): ?> '<?php echo $cat['id']; ?>': {
        currentSlide: 0,
        totalSlides: <?php echo count($cat['images']) + ($cat['video_url'] ? 1 : 0); ?>
      },
    <?php endforeach; ?>
  };
</script>

<?php include 'includes/footer.php'; ?>