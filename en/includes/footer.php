<!-- Video Modal -->
<div class="video-modal" id="videoModal">
  <div class="modal-content">
    <button class="modal-close" onclick="closeVideoModal()">
      <i class="fas fa-times"></i>
    </button>
    <div class="video-container">
      <iframe
        id="videoPlayer"
        src=""
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div class="image-modal" id="imageModal">
  <button class="modal-close-img" onclick="closeImageModal()">
    <i class="fas fa-times"></i>
  </button>
  <img class="modal-content-img" id="fullImage" src="" alt="Full Screen Kitten" />
</div>

<!-- Footer -->
<footer class="cat-footer">
  <div class="container">
    <div class="footer-logo">
      <div class="logo-cat">
        <img src="../img/logo-footer.png" alt="Nyx European Maine Coon Logo" />
      </div>
      <div class="logo-text" style="font-size: 2.5rem; margin-top: 10px; ">
        Nyx European <span>Maine Coon</span>
      </div>
    </div>


    <div class="footer-links">
      <a href="index.php">Available Kittens & Cats</a>
      <a href="kings.php">Kings</a>
      <a href="queens.php">Queens</a>
      <a href="chatons_reserves.php">Reserved Kittens & Cats</a>
      <a href="portees_a_venir.php">Upcoming Litters</a>
      <a href="adoption.php">Adoption Process</a>
      <a href="contact.php">Contact & Info</a>
      <a href="about.php">Under Construction</a>
    </div>

    <div class="text-center mt-4">
      <div class="social-links justify-content-center">
        <a href="https://www.tiktok.com/@nyx_coon_cattery" target="_blank" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
        <a href="https://www.youtube.com/@chatterienyxcooneurop%C3%A9enmainec" target="_blank" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61581523927046" target="_blank" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/nyxcoon_cattery_montreal/" target="_blank" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
        <a href="https://wa.me/15142695930" target="_blank" class="social-icon whatsapp"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>

    <div class="copyright">
      <p>
        © <?php echo date('Y'); ?> Nyx European Maine Coon by <a href="https://maxsolving.com" target="_blank" style="color: var(--primary-color)">MaxSolving</a>. All rights reserved. | Dedicated to
        breeding healthy and happy Maine Coon companions.
      </p>
      <p class="mt-2">
        <i class="fas fa-heart" style="color: var(--primary-color)"></i>
        Each kitten is raised with love and care
      </p>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Custom Script - CHEMIN CORRIGÉ -->
<script src="../js/script.js"></script>

<!-- Initialisation des galleries -->
<script>
  const galleries = {};
</script>
<!-- Cookie Consent Banner -->
<style>
#cookieConsentContainer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
    z-index: 9999;
    padding: 15px 20px;
    transform: translateY(100%); /* Hidden by default */
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    border-top: 3px solid #764ba2;
}
#cookieConsentContainer.show-banner {
    transform: translateY(0);
}
.cookie-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: wrap;
    gap: 15px;
}
.cookie-text {
    font-size: 0.95rem;
    color: #333;
    flex: 1;
    min-width: 300px;
}
.cookie-buttons {
    display: flex;
    gap: 10px;
}
.btn-cookie-accept {
    background-color: #764ba2; /* var(--primary-color) */
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}
.btn-cookie-accept:hover {
    background-color: #2d3436; /* var(--dark-color) */
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(45, 52, 54, 0.3);
}
.btn-cookie-decline {
    background-color: transparent;
    color: #666;
    border: 1px solid #ccc;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}
.btn-cookie-decline:hover {
    background-color: #f8f9fa;
    color: #333;
    border-color: #999;
}
@media (max-width: 768px) {
    .cookie-content { flex-direction: column; text-align: center; }
    .cookie-text { min-width: 100%; }
    .cookie-buttons { width: 100%; justify-content: center; }
}
</style>
<div id="cookieConsentContainer">
  <div class="cookie-content">
    <div class="cookie-text">
      <i class="fas fa-cookie-bite" style="color: #667eea; margin-right: 8px;"></i>
      This site uses cookies to offer you the best experience. By continuing your navigation, you accept the use of cookies.
    </div>
    <div class="cookie-buttons">
      <button id="btnCookieDecline" class="btn-cookie-decline">Decline</button>
      <button id="btnCookieAccept" class="btn-cookie-accept">Accept</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (!localStorage.getItem('cookieConsent')) {
            document.getElementById('cookieConsentContainer').classList.add('show-banner');
        }
    }, 2000); // Delay appearance for smoother UX

    document.getElementById('btnCookieAccept').addEventListener('click', function() {
        localStorage.setItem('cookieConsent', 'accepted');
        document.getElementById('cookieConsentContainer').classList.remove('show-banner');
    });

    document.getElementById('btnCookieDecline').addEventListener('click', function() {
        localStorage.setItem('cookieConsent', 'declined');
        document.getElementById('cookieConsentContainer').classList.remove('show-banner');
    });
});
</script>
</body>
</html>