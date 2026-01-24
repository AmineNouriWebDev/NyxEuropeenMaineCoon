// Auto-slide chaque 3 secondes
const autoSlideInterval = 3000;
let autoSlideTimers = {};

function startAutoSlide(galleryName) {
  if (autoSlideTimers[galleryName]) {
    clearInterval(autoSlideTimers[galleryName]);
  }

  autoSlideTimers[galleryName] = setInterval(() => {
    nextSlide(galleryName);
  }, autoSlideInterval);
}

function stopAutoSlide(galleryName) {
  if (autoSlideTimers[galleryName]) {
    clearInterval(autoSlideTimers[galleryName]);
  }
}

function updateGallery(galleryName) {
  // Ensure galleries object exists (it should be defined in the main file)
  if (typeof galleries === 'undefined' || !galleries[galleryName]) return;

  const gallery = galleries[galleryName];
  const container = document.getElementById(`gallery-${galleryName}`);
  if (!container) return;

  const kittenGallery = container.closest(".kitten-gallery");
  const slides = container.querySelectorAll(".gallery-slide");
  const indicators = document.querySelectorAll(
    `#gallery-${galleryName} ~ .gallery-indicators .gallery-indicator`,
  );

  // Masquer toutes les slides
  slides.forEach((slide) => {
    slide.classList.remove("active");
  });

  // Afficher la slide active
  if (slides[gallery.currentSlide]) {
    slides[gallery.currentSlide].classList.add("active");

    // Set cursor classes
    if (slides[gallery.currentSlide].classList.contains("video-slide")) {
      kittenGallery.classList.add("is-video");
      kittenGallery.classList.remove("is-image");
    } else {
      kittenGallery.classList.add("is-image");
      kittenGallery.classList.remove("is-video");
    }

    // Si on arrive à une vidéo, arrêter l'auto-slide temporairement
    if (slides[gallery.currentSlide].classList.contains("video-slide")) {
      stopAutoSlide(galleryName);
      // Redémarrer après 10 secondes si l'utilisateur ne clique pas
      setTimeout(() => startAutoSlide(galleryName), 10000);
    }
  }

  // Mettre à jour les indicateurs
  indicators.forEach((indicator, index) => {
    indicator.classList.toggle("active", index === gallery.currentSlide);
  });
}

function nextSlide(galleryName) {
  if (typeof galleries === 'undefined' || !galleries[galleryName]) return;
  const gallery = galleries[galleryName];
  gallery.currentSlide = (gallery.currentSlide + 1) % gallery.totalSlides;
  updateGallery(galleryName);
}

function prevSlide(galleryName) {
  if (typeof galleries === 'undefined' || !galleries[galleryName]) return;
  const gallery = galleries[galleryName];
  gallery.currentSlide =
    (gallery.currentSlide - 1 + gallery.totalSlides) %
    gallery.totalSlides;
  updateGallery(galleryName);
}

function goToSlide(galleryName, slideIndex) {
  if (typeof galleries === 'undefined' || !galleries[galleryName]) return;
  const gallery = galleries[galleryName];
  gallery.currentSlide = slideIndex;
  updateGallery(galleryName);

  // Arrêter temporairement l'auto-slide quand l'utilisateur interagit
  stopAutoSlide(galleryName);
  setTimeout(() => startAutoSlide(galleryName), 10000);
}

// Video Modal
function openVideoModal(videoUrl) {
  const modal = document.getElementById("videoModal");
  const player = document.getElementById("videoPlayer");

  // Extraire l'ID de la vidéo YouTube
  const videoId = videoUrl.split("/").pop().split("?")[0];
  const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;

  player.src = embedUrl;
  modal.classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeVideoModal() {
  const modal = document.getElementById("videoModal");
  const player = document.getElementById("videoPlayer");

  modal.classList.remove("active");
  player.src = "";
  document.body.style.overflow = "auto";
}

// Image Modal
function openImageModal(imageUrl) {
  const modal = document.getElementById("imageModal");
  const fullImage = document.getElementById("fullImage");

  fullImage.src = imageUrl;
  modal.classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeImageModal() {
  const modal = document.getElementById("imageModal");
  const fullImage = document.getElementById("fullImage");

  modal.classList.remove("active");
  setTimeout(() => {
    fullImage.src = "";
  }, 300); // Clear after fade out
  document.body.style.overflow = "auto";
}

// Fermer la modal avec ESC
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    closeVideoModal();
    closeImageModal();
  }
});

// Fermer la modal en cliquant à l'extérieur
document
  .getElementById("videoModal")
  .addEventListener("click", function (e) {
    if (e.target === this) {
      closeVideoModal();
    }
  });

document
  .getElementById("imageModal")
  .addEventListener("click", function (e) {
    if (e.target === this) {
      closeImageModal();
    }
  });

// Global click listener for gallery sliders and nav
document.addEventListener("click", function (e) {
  // Ignore clicks on buttons/controls
  if (
    e.target.closest(".gallery-nav-btn") ||
    e.target.closest(".gallery-indicators") ||
    e.target.closest(".btn-cat")
  ) {
    return;
  }

  const gallery = e.target.closest(".kitten-gallery");
  if (gallery) {
    const slider = gallery.querySelector(".gallery-slider");
    const activeSlide = slider.querySelector(".gallery-slide.active");

    if (activeSlide) {
      // Check if it's a video slide
      if (activeSlide.classList.contains("video-slide")) {
        // Try to get URL from data attribute first
        const videoUrl = activeSlide.getAttribute("data-video-url");
        if (videoUrl) {
          openVideoModal(videoUrl);
        }
      } else {
        // It's an image slide
        const img = activeSlide.querySelector("img");
        if (img) {
          openImageModal(img.src);
        }
      }
    }
  }
});

// Initialiser les galeries et démarrer l'auto-slide
document.addEventListener("DOMContentLoaded", function () {
  // Initialiser chaque galerie
  if (typeof galleries !== 'undefined') {
    Object.keys(galleries).forEach((galleryName) => {
      updateGallery(galleryName);
      startAutoSlide(galleryName);
    });
  }

  // Arrêter l'auto-slide quand l'utilisateur survole une galerie
  document.querySelectorAll(".kitten-gallery").forEach((gallery) => {
    gallery.addEventListener("mouseenter", function () {
      const slider = this.querySelector(".gallery-slider");
      if (slider) {
        const galleryId = slider.id;
        const galleryName = galleryId.replace("gallery-", "");
        stopAutoSlide(galleryName);
      }
    });

    gallery.addEventListener("mouseleave", function () {
      const slider = this.querySelector(".gallery-slider");
      if (slider) {
        const galleryId = slider.id;
        const galleryName = galleryId.replace("gallery-", "");
        startAutoSlide(galleryName);
      }
    });
  });

  // Détection et amélioration vidéo pour mobile
  improveVideoForMobile();
});

// Animation d'apparition des cartes
function animateCards() {
  const cards = document.querySelectorAll(".kitten-card");
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(50px)";
    card.style.transition = "opacity 0.8s ease, transform 0.8s ease";

    setTimeout(() => {
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, 200 * index);
  });
}

function filterKittens() {
  try {
    // Récupération sécurisée des filtres actifs
    const getActiveFilter = (group) => {
      const el = document.querySelector(`.filter-group[data-filter-group="${group}"] .filter-option.active`);
      return el ? el.getAttribute('data-filter').trim().toLowerCase() : 'all';
    };

    const activeGender = getActiveFilter('gender');
    const activeColor = getActiveFilter('color');
    const activeQuality = getActiveFilter('quality');

    console.log("Filtering:", { activeGender, activeColor, activeQuality });

    const items = document.querySelectorAll('.kitten-item');

    items.forEach(item => {
      // Récupération et nettoyage des données de l'item
      const itemGender = (item.getAttribute('data-gender') || '').trim().toLowerCase();
      const itemColor = (item.getAttribute('data-color') || '').trim().toLowerCase();
      const itemQuality = (item.getAttribute('data-quality') || '').trim().toLowerCase();

      // Correspondance
      // Gender : Strict sauf si 'all'
      const matchGender = (activeGender === 'all') || (itemGender === activeGender);

      // Color : Contient le mot clé (ex: 'blue smoke' match 'smoke')
      const matchColor = (activeColor === 'all') || itemColor.includes(activeColor);

      // Quality : Contient le mot clé (ex: 'pet & breeding' match 'breeding')
      const matchQuality = (activeQuality === 'all') || itemQuality.includes(activeQuality);

      if (matchGender && matchColor && matchQuality) {
        item.style.setProperty('display', 'block', 'important');
        // Reset animation
        item.style.opacity = '1';
        item.style.transform = 'scale(1)';
      } else {
        item.style.setProperty('display', 'none', 'important');
      }
    });
  } catch (err) {
    console.error("Filter Error:", err);
  }
}

document.querySelectorAll(".filter-option").forEach((option) => {
  option.addEventListener("click", function () {
    // Remove active class from all options in the same group
    this.parentElement
      .querySelectorAll(".filter-option")
      .forEach((opt) => {
        opt.classList.remove("active");
      });
    // Add active class to clicked option
    this.classList.add("active");

    // Trigger filter
    filterKittens();
  });
});

// Smooth scroll for buttons
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// Animate elements on scroll
function animateOnScroll() {
  const elements = document.querySelectorAll(".kitten-card");
  elements.forEach((element) => {
    const elementPosition = element.getBoundingClientRect().top;
    const screenPosition = window.innerHeight / 1.2;

    if (elementPosition < screenPosition) {
      element.style.opacity = "1";
      element.style.transform = "translateY(0)";
    }
  });
}

// Set initial state for animation
document.querySelectorAll(".kitten-card").forEach((card) => {
  card.style.opacity = "0";
  card.style.transform = "translateY(20px)";
  card.style.transition = "opacity 0.5s ease, transform 0.5s ease";
});

// Run animation on load and scroll
window.addEventListener("load", animateCards);
window.addEventListener("scroll", animateOnScroll);

// NOUVEAU : Amélioration vidéo pour mobile
function improveVideoForMobile() {
  const video = document.querySelector(".hero-video");
  const fallback = document.querySelector(".video-fallback");

  if (!video) return;

  if (window.innerWidth < 768) {
    // Sur mobile, vérifier si la vidéo peut être lue
    video.style.display = "block";
    video.classList.add("active");

    // Essayer de jouer la vidéo
    const playPromise = video.play();

    if (playPromise !== undefined) {
      playPromise.catch(() => {
        // Si la vidéo ne peut pas être lue, afficher l'image de secours
        if (fallback) {
          video.style.display = "none";
          video.classList.remove("active");
          fallback.style.display = "block";
        }
      });
    }
  } else {
    // Sur PC, s'assurer que la vidéo est visible
    video.style.display = "block";
    video.classList.add("active");
    if (fallback) fallback.style.display = "none";

    // Suppression du filtre JS qui altérait la qualité
    video.style.filter = "none";
  }
}

// Redimensionnement de la fenêtre
window.addEventListener("resize", improveVideoForMobile);

// Paw print cursor effect
document.addEventListener("mousemove", function (e) {
  if (Math.random() > 0.98) {
    // Random chance to show paw
    const paw = document.createElement("div");
    paw.innerHTML = "🐾";
    paw.style.position = "fixed";
    paw.style.left = e.pageX + "px";
    paw.style.top = e.pageY + "px";
    paw.style.fontSize = "20px";
    paw.style.opacity = "0.7";
    paw.style.pointerEvents = "none";
    paw.style.zIndex = "9999";
    paw.style.transform = `rotate(${Math.random() * 360}deg)`;
    document.body.appendChild(paw);

    // Remove after animation
    setTimeout(() => {
      paw.style.transition = "all 1s ease";
      paw.style.opacity = "0";
      paw.style.transform += " translateY(-20px)";
      setTimeout(() => paw.remove(), 1000);
    }, 100);
  }
});

// NOUVEAU : Effet de chargement amélioré
window.addEventListener("load", function () {
  document.body.style.opacity = "0";
  document.body.style.transition = "opacity 0.5s ease";

  setTimeout(() => {
    document.body.style.opacity = "1";
  }, 100);
});

// Menu Mobile Interaction
$(document).ready(function () {
  const menuBtn = $("#mobileMenuBtn");
  const nav = $("#catNav");
  const overlay = $("#navOverlay");

  function toggleMenu() {
    menuBtn.toggleClass("active");
    nav.toggleClass("active");
    overlay.toggleClass("active");

    if (nav.hasClass("active")) {
      $("body").css("overflow", "hidden");
      menuBtn.find("i").removeClass("fa-bars").addClass("fa-times");
    } else {
      $("body").css("overflow", "");
      menuBtn.find("i").removeClass("fa-times").addClass("fa-bars");
    }
  }

  menuBtn.click(function () {
    toggleMenu();
  });

  overlay.click(function () {
    toggleMenu();
  });

  // Fermer le menu au clic sur un lien
  $(".nav-item-custom a").click(function () {
    if (nav.hasClass("active")) {
      toggleMenu();
    }
  });
});