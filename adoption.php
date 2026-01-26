<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Spacer pour le menu fixe -->
<div style="height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);"></div>

<!-- Hero Section -->
<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="display-4 font-weight-bold" style="font-family: 'Vijaya', serif;">Processus d'Adoption</h1>
        <p class="lead text-muted">Rejoignez la famille Aristocoons</p>
    </div>
</section>

<!-- 1. Processus Steps -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="timeline-steps">
                    <?php 
                    $steps = [
                        1 => "Prendre connaissance des conditions et prix",
                        2 => "Pourquoi optez pour la liste de priorité",
                        3 => "Remplir le formulaire de demande",
                        4 => "Choisissez votre coup de cœur (vers 3-4 semaines)",
                        5 => "Visite virtuelle et choix final (Dépôt 25%)",
                        6 => "Suivi hebdomadaire de la croissance",
                        7 => "Récupération vers 12-14 semaines"
                    ];
                    foreach($steps as $num => $desc): ?>
                    <div class="step mb-4 d-flex align-items-center bg-white p-3 rounded shadow-sm hover-lift">
                        <div class="step-num bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 50px; height: 50px; font-size: 1.5rem; min-width: 50px;">
                            <?php echo $num; ?>
                        </div>
                        <div class="step-desc h5 mb-0 text-dark">
                            <?php echo $desc; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Liste VIP - Diamants -->
<section class="py-5 bg-white position-relative" id="vip-list">
    <div class="container">
        <!-- Titre Diamants -->
        <div class="text-center mb-5">
            <h2 class="display-4" style="color: var(--primary-color); font-family: 'Amatic SC', cursive;">
                <i class="fas fa-gem mx-3 fa-xs"></i> LISTE D'ATTENTE VIP <i class="fas fa-gem mx-3 fa-xs"></i>
            </h2>
            <div class="divider-custom"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-muted mb-5 lead-text">
                <p>Voici la liste idéale pour ceux et celles qui rêvent d'accueillir un magnifique chaton Aristocoons dans un avenir rapproché.</p>
                <p>Cette inscription implique un engagement de votre part : un dépôt vous sera demandé afin d'y figurer. En contrepartie, vous bénéficierez d'un <strong>accès prioritaire</strong> à nos portées.</p>
                <p>À chaque naissance, vous recevrez des photos en exclusivité. Lorsque les chatons seront prêts à être réservés, vous serez contacté(e) personnellement. Vous disposerez ensuite d'un délai de <strong>24 heures</strong> pour faire votre choix en toute tranquillité.</p>
                <p>De plus, votre inscription vous donne accès à notre <span class="text-primary"><i class="fab fa-facebook-square"></i> groupe Facebook privé</span> réservé aux membres.</p>
            </div>
        </div>

        <!-- Texte Explicatif Formulaire -->
        <div class="alert alert-info shadow-sm border-0 rounded-lg p-4 mb-5">
            <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Important</h4>
            <p>Chaque demande d'adoption est examinée avec soin. Nous avons à cœur de trouver des familles parfaites pour nos précieux félins. Il est donc important de remplir ce formulaire avec attention : il nous permettra de mieux vous connaître.</p>
            <p class="mb-0"><em>Si vous n'avez pas de nouvelles après 48 heures, n'hésitez pas à nous téléphoner au +1(514) 269-5930.</em></p>
        </div>

        <!-- 3. Formulaire -->
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <div class="card-header bg-primary text-white p-4">
                <h3 class="mb-0"><i class="fas fa-paw mr-2"></i> Formulaire de Candidature VIP</h3>
            </div>
            <div class="card-body p-5">
                <form id="vipForm">
                    <!-- Identité -->
                    <h5 class="text-secondary mb-3 border-bottom pb-2">Vos Coordonnées</h5>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Prénom *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nom de famille *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Téléphone *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>E-mail *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <h5 class="text-secondary mb-3 mt-4 border-bottom pb-2">Votre Adresse</h5>
                    <div class="row">
                        <div class="col-12 form-group mb-3">
                            <label>Adresse *</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Ville *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Code Postal *</label>
                            <input type="text" name="postal_code" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Pays/Région *</label>
                            <input type="text" name="country" class="form-control" required>
                        </div>
                    </div>

                    <!-- Questions Détaillées -->
                    <h5 class="text-secondary mb-3 mt-4 border-bottom pb-2">Mieux vous connaître</h5>
                    
                    <div class="form-group mb-4">
                        <label>Dites-nous qui vous êtes, parlez-nous de votre famille et de votre quotidien *</label>
                        <textarea name="family_description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label>Combien d'animaux avez-vous à la maison? *</label>
                        <textarea name="existing_pets" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label>Dans quel type d'environnement vivra le chaton ? *</label>
                        <small class="form-text text-muted mb-2">(ex. : uniquement à l'intérieur, accès à l'extérieur, en enclos sécurisé, etc.)</small>
                        <textarea name="environment_type" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label>Où avez-vous entendu parler des Aristocoons ? *</label>
                        <input type="text" name="hear_about_us" class="form-control" required>
                    </div>

                    <!-- Préférences -->
                    <h5 class="text-secondary mb-3 mt-4 border-bottom pb-2">Votre Futur Compagnon</h5>
                    
                    <div class="form-group mb-3">
                        <label>Avez-vous une ou des couleurs que vous préférez ? *</label>
                        <input type="text" name="color_preferences" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Préférence pour le genre ? *</label>
                        <select name="gender_preference" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            <option value="Male">Mâle</option>
                            <option value="Female">Femelle</option>
                            <option value="None">Aucune préférence</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>À partir de quelle date souhaitez-vous accueillir un Aristocoons ? *</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="date_year" class="form-control" placeholder="Année" min="2024" required>
                            <input type="number" name="date_month" class="form-control" placeholder="Mois" min="1" max="12" required>
                            <input type="number" name="date_day" class="form-control" placeholder="Jour" min="1" max="31">
                        </div>
                    </div>

                    <!-- Champ Libre -->
                    <div class="form-group mb-4">
                        <label>Questions ou informations complémentaires (Optionnel)</label>
                        <textarea name="questions" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Engagements -->
                    <div class="bg-light p-4 rounded mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="checkPriority" required>
                            <label class="form-check-label" for="checkPriority">
                                Je comprends que cette liste fonctionne par ordre de priorité et que l'éleveur se conserve toujours un choix sur les chatons disponibles. *
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkDeposit" required>
                            <label class="form-check-label" for="checkDeposit">
                                Si ma demande est approuvée, je suis d'accord à verser un dépôt de 300$ (déductible) par virement afin de devenir membre VIP. *
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-cat btn-lg py-3 px-5 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Envoyer ma candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- 4. FAQ -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 font-weight-bold">Questions Fréquemment Posées</h2>
        
        <div class="accordion" id="faqAccordion">
            <div class="card border-0 mb-3 shadow-sm rounded">
                <div class="card-header bg-white" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-3 text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne">
                            Pourquoi les visites à l'élevage ne sont pas autorisées ?
                            <i class="fas fa-chevron-down text-primary"></i>
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                    <div class="card-body bg-white text-muted">
                        <p>Chez SybelleCoon (Aristocoons), la santé et la sécurité de nos chatons passent en priorité.</p>
                        <p>Bien que nous comprenions votre envie de venir visiter, voici pourquoi cela n'est pas possible :</p>
                        <ul class="list-unstyled pl-3 border-left border-primary ml-2">
                            <li class="mb-2"><strong>1. Risque de contamination :</strong> Même avec les meilleures intentions, vous pouvez sans le savoir transporter des poils, allergènes, bactéries (typhus, coryza) ou parasites via vos vêtements ou chaussures.</li>
                            <li class="mb-2"><strong>2. Stress des mamans :</strong> Nos femelles gestantes ou allaitantes ont besoin de calme absolu.</li>
                            <li><strong>3. Sécurité sanitaire :</strong> Nous maintenons un environnement stérile pour les nouveaux-nés dont le système immunitaire est immature.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Autres questions peuvent être ajoutées ici -->
        </div>
    </div>
</section>

<style>
.hover-lift { transition: transform 0.3s; }
.hover-lift:hover { transform: translateY(-5px); }
.lead-text p { font-size: 1.1rem; line-height: 1.8; }
.divider-custom { height: 4px; width: 100px; background: var(--accent-color); margin: 20px auto; border-radius: 2px; }
</style>

<script>
document.getElementById('vipForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
    btn.disabled = true;

    const formData = new FormData(this);

    fetch('ajax_vip_request.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            alert('Votre demande a été envoyée avec succès ! Nous vous recontacterons sous 48h.');
            this.reset();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Une erreur technique est survenue.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
