<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Purple Hero Header -->
<div class="litter-hero text-center py-5">
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Boutique</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">Bientôt disponible</p>
</div>

<section class="py-5 purple-hero-bg" style="min-height: 50vh;">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <i class="fas fa-hammer fa-5x text-muted mb-4"></i>
                <h2 class="text-dark mb-4">Cette page est en construction</h2>
                <p class="lead text-dark">Nous travaillons actuellement sur notre boutique en ligne. Revenez bientôt pour découvrir nos produits exclusifs !</p>
                
                <a href="index.php" class="btn btn-cat mt-4 px-5 py-3 rounded-pill">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
