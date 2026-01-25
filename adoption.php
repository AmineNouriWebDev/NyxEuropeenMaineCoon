<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Header -->
<section class="py-5 text-center bg-light">
    <div class="container mt-5">
        <h1 class="cursive-font" style="font-size: 3.5rem; color: var(--secondary-color);">Processus d'Adoption</h1>
        <p class="lead text-muted">Comment ramener le chaton de vos rêves à la maison</p>
    </div>
</section>

<!-- Steps Timeline -->
<section class="py-5">
    <div class="container">
        <div class="row items-center">
            <!-- Step 1 -->
            <div class="col-md-6 mb-5 text-center">
                <img src="https://images.unsplash.com/photo-1513245543132-31f507417b26?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-circle shadow" style="width: 250px; height: 250px; object-fit: cover;" alt="Step 1">
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 40px; height: 40px;">1</span>
                    <h3 class="m-0">Se Renseigner et Postuler</h3>
                </div>
                <p>Parcourez nos chatons disponibles ou les portées à venir. Remplissez notre formulaire de demande d'adoption ou contactez-nous directement. Nous voulons en savoir plus sur vous, votre style de vie et ce que vous recherchez chez un compagnon.</p>
            </div>

            <!-- Step 2 -->
            <div class="col-md-6 mb-5 order-md-2 text-center">
                <img src="https://images.unsplash.com/photo-1561948955-570b270e7c36?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-circle shadow" style="width: 250px; height: 250px; object-fit: cover;" alt="Step 2">
            </div>
            <div class="col-md-6 mb-5 order-md-1">
                <div class="d-flex align-items-center mb-3">
                    <span class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 40px; height: 40px;">2</span>
                    <h3 class="m-0">Réservation</h3>
                </div>
                <p>Une fois approuvé, vous pouvez réserver le chaton choisi avec un dépôt. Ce dépôt est déduit du prix final. Vous recevrez un contrat de réservation et des mises à jour/photos hebdomadaires de votre bébé en croissance.</p>
            </div>

            <!-- Step 3 -->
            <div class="col-md-6 mb-5 text-center">
                <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-circle shadow" style="width: 250px; height: 250px; object-fit: cover;" alt="Step 3">
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 40px; height: 40px;">3</span>
                    <h3 class="m-0">Jour du Retour à la Maison !</h3>
                </div>
                <p>Les chatons rentrent à la maison entre 12 et 14 semaines. Nous organisons une date de récupération. Si vous n'êtes pas local (Montréal), nous pouvons discuter des options de transport sécurisées (accompagnateur de vol).</p>
            </div>
        </div>
    </div>
</section>

<!-- Whats Included -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="mb-5">Qu'est-ce qui est Inclus ?</h2>
        <div class="row">
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-syringe fa-3x text-info mb-3"></i>
                <h5>Vaccinations</h5>
                <p class="small text-muted">Vaccins de base à jour</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-microchip fa-3x text-warning mb-3"></i>
                <h5>Puce Électronique</h5>
                <p class="small text-muted">Enregistrement d'identification permanente</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-file-medical-alt fa-3x text-danger mb-3"></i>
                <h5>Dossier Médical</h5>
                <p class="small text-muted">Passeport et certificat vétérinaire</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                <h5>Kit de Démarrage</h5>
                <p class="small text-muted">Nourriture, jouets et couverture</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Questions Fréquemment Posées</h2>
        <div class="accordion shadow-sm" id="faqAccordion">
            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne">
                            Expédiez-vous des chatons ?
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                    <div class="card-body">
                        Nous préférons la récupération en personne, mais nous pouvons organiser un "accompagnateur de vol" qui voyagera avec le chaton en cabine jusqu'à votre aéroport le plus proche. Nous n'expédions pas en cargo pour la sécurité de nos chats.
                    </div>
                </div>
            </div>
            
            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo">
                            Quel est le prix d'un chaton ?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#faqAccordion">
                    <div class="card-body">
                        Nos prix varient généralement de 2500 $ à 3500 $ selon la couleur, le type et l'objectif (animal de compagnie ou reproduction). Veuillez nous contacter pour les prix spécifiques des chatons disponibles.
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseThree">
                            Offrez-vous une garantie santé ?
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#faqAccordion">
                    <div class="card-body">
                        Oui ! Tous nos chatons sont accompagnés d'une garantie santé génétique de 2 ans couvrant HCM, SMA et d'autres maladies héréditaires, ainsi qu'une garantie virale de 72 heures.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
