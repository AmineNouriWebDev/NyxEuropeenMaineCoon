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
        <img src="img/logo-footer.png" alt="Nyx European Maine Coon Logo" />
      </div>
      <div class="logo-text" style="font-size: 2.5rem; margin-top: 10px; font-family: 'Fuzzy Bubbles', cursive;">
        Nyx European <span>Maine Coon</span>
      </div>
    </div>


    <div class="footer-links">
      <a href="index.php">Chatons & Chats Disponibles</a>
      <a href="kings.php">Kings</a>
      <a href="queens.php">Queens</a>
      <a href="chatons_reserves.php">Chatons & Chats Réservés</a>
      <a href="portees_a_venir.php">Portées à Venir</a>
      <a href="adoption.php">Processus d'Adoption</a>
      <a href="contact.php">Contacts & Infos</a>
      <a href="about.php">En Construction</a>
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
        © <?php echo date('Y'); ?> Nyx European Maine Coon by <a href="https://maxsolving.com" target="_blank" style="color: var(--primary-color)">MaxSolving</a>. Tous droits réservés. | Dédié à
        l'élevage de compagnons Maine Coon sains et heureux.
      </p>
      <p class="mt-2">
        <i class="fas fa-heart" style="color: var(--primary-color)"></i>
        Chaque chaton est élevé avec amour et soin
      </p>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Custom Script - CHEMIN CORRIGÉ -->
<script src="js/script.js"></script>

<!-- Initialisation des galleries -->
<script>
  const galleries = {};
</script>
</body>

</html>