<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Hero Section About -->
<!-- Purple Hero Header -->
<div class="litter-hero text-center py-5">
    <h1 class="font-weight-bold display-4" style="margin-top: 200px; font-family: 'Amatic SC', cursive;">Notre Histoire</h1>
    <p class="lead" style="color: rgba(255,255,255,0.9);">Passion, Excellence et Amour pour les Maine Coons</p>
</div>

<!-- Our Vision -->
<section class="py-5 purple-hero-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-lg shadow-lg" alt="Maine Coon Portrait">
            </div>
            <div class="col-md-6">
                <h2 class="section-title-start text-white mb-4">Plus Qu'un Simple Élevage</h2>
                <p class="lead text-white">Chez Nyx European Maine Coon, nous croyons que chaque chaton mérite d'être élevé comme un membre de la famille dès le premier jour.</p>
                <p class="text-white">Situé au cœur de Montréal, notre élevage se spécialise dans l'élevage de Maine Coons européens, connus pour leur apparence sauvage, leur taille impressionnante et leur personnalité de gentil géant. Nous priorisons la santé, le tempérament et la conformité au standard avant tout.</p>
                
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x mr-3" style="color: white;"></i>
                            <span class="font-weight-bold text-white">Élevé en Famille</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-notes-medical fa-2x mr-3" style="color: white;"></i>
                            <span class="font-weight-bold text-white">Testé pour la Santé</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe-americas fa-2x mr-3" style="color: white;"></i>
                            <span class="font-weight-bold text-white">Lignées Européennes</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate fa-2x mr-3" style="color: white;"></i>
                            <span class="font-weight-bold text-white">Enregistré</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 purple-hero-bg">
    <div class="container text-center">
        <h2 class="mb-5">Nos Valeurs Fondamentales</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-heartbeat fa-2x"></i>
                    </div>
                    <h4>Santé d'Abord</h4>
                    <p>Tous nos chats reproducteurs sont testés ADN pour HCM, SMA et PKDef. Nous effectuons des échographies cardiaques régulières pour assurer les lignées les plus saines possibles.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-brain fa-2x"></i>
                    </div>
                    <h4>Socialisation</h4>
                    <p>Nos chatons sont élevés au sein de la famille, pas en cages. Ils sont exposés quotidiennement aux bruits domestiques, aux enfants et à d'autres animaux pour s'assurer qu'ils sont confiants et affectueux.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                    <h4>Excellence</h4>
                    <p>Nous visons le "look sauvage" typique des lignées européennes : museaux forts, grandes oreilles avec de lourdes pointes de lynx et une ossature substantielle.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center purple-hero-bg">
    <div class="container">
        <h2 class="text-white mb-3">Prêt à accueillir un géant ?</h2>
        <p class="lead mb-4">Découvrez nos chatons disponibles ou apprenez-en plus sur le processus d'adoption.</p>
        <a href="index.php#kittens" class="btn btn-light rounded-pill px-4 py-2 font-weight-bold text-dark mr-3">Voir les Chatons</a>
        <a href="adoption.php" class="btn btn-outline-light rounded-pill px-4 py-2 font-weight-bold">Comment Adopter</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
