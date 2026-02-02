<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Traitement AJAX du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    
    // Récupération des données sécurisées
    $name = strip_tags(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['subject'] ?? ''));
    $message = strip_tags(trim($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs requis.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format d\'email invalide.']);
        exit;
    }
    
    try {
        // Insertion en base de données
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $subject, $message, date('Y-m-d H:i:s')]);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Erreur lors de l'insertion");
        }
    } catch (Exception $e) {
        error_log("Contact Form Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer.']);
    }
    exit;
}

include 'includes/header.php';
?>

<!-- Purple Hero Header -->
<div class="litter-hero">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title" style="font-family: 'Amatic SC', cursive;">Contacts & Infos</h1>
            <p class="hero-subtitle">Nous serions ravis de vous entendre</p>
        </div>
    </div>
</div>

<section class="purple-hero-bg py-5">
<div class="container my-5">
    <!-- Feedback Toast -->
    <div id="contactToast" class="contact-toast">
        <i class="fas fa-check-circle"></i>
        <span>Message envoyé avec succès ! Nous vous répondrons bientôt.</span>
    </div>

    <div class="row shadow-lg rounded-lg overflow-hidden bg-white">
        <!-- Contact Info & Map (Left Column) -->
        <div class="col-lg-5 text-white p-5 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, var(--dark-color) 0%, #2d3436 100%); min-height: 500px;">
            <div>
                <h3 class="text-white mb-4 font-weight-bold">Informations de Contact</h3>
                <p class="mb-5 text-white-50">Remplissez le formulaire ou contactez-nous directement via ces canaux.</p>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">Localisation</h6>
                        <span class="text-white">Rive sud de Montréal, code postal : J5R 0K4</span>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-envelope mt-1 mr-3 text-primary fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">E-mail</h6>
                        <a href="mailto:nyxcooncattery@gmail.com" class="text-white text-decoration-none">nyxcooncattery@gmail.com</a>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fab fa-whatsapp mt-1 mr-3 text-success fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">WhatsApp</h6>
                        <a href="https://wa.me/15142695930" class="text-white text-decoration-none">+1-514-269-5930</a>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h6 class="text-white mb-3 text-uppercase small letter-spacing-1">Suivez-Nous</h6>
                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=61581523927046" target="_blank" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/nyxcoon_cattery_montreal/" target="_blank" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@nyx_coon_cattery" target="_blank" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/@chatterienyxcooneurop%C3%A9enmainec" target="_blank" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- Contact Form (Right Column) -->
        <div class="col-lg-7 bg-white p-5">
            <h3 class="text-dark mb-4 font-weight-bold">Envoyez-nous un Message</h3>
            <form id="contactForm">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">NOM <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-4 px-3" placeholder="Jean Dupont" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">E-MAIL <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-light border-0 py-4 px-3" placeholder="jean@example.com" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-muted">SUJET</label>
                    <select name="subject" class="form-control bg-light border-0" style="height: 50px;">
                        <option>Demande Générale</option>
                        <option>Liste d'Attente Chaton</option>
                        <option>Processus d'Adoption</option>
                        <option>Info Droits de Reproduction</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-muted">MESSAGE <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control bg-light border-0 p-3" rows="5" placeholder="Parlez-nous de vous et de ce que vous recherchez..." required></textarea>
                </div>

                <button type="submit" class="btn btn-cat py-3 px-5 shadow-sm" id="submitBtn">
                    <span id="btnText" style="color: white !important;">Envoyer le Message <i class="fas fa-paper-plane ml-2"></i></span>
                    <span id="btnLoader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Envoi...</span>
                </button>
            </form>
        </div>
    </div>
</div>
</section>

<!-- Our Vision (From About Page) -->
<section class="py-5 purple-hero-bg ">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-lg shadow-lg" alt="Maine Coon Portrait">
            </div>
            <div class="col-md-6">
                <h2 class="section-title-start text-dark mb-4">Plus Qu'un Simple Élevage</h2>
                <p class="lead text-dark">Chez Nyx European Maine Coon, nous croyons que chaque chaton mérite d'être élevé comme un membre de la famille dès le premier jour.</p>
                <p class="text-dark">Situé au cœur de Montréal, notre élevage se spécialise dans l'élevage de Maine Coons européens, connus pour leur apparence sauvage, leur taille impressionnante et leur personnalité de gentil géant. Nous priorisons la santé, le tempérament et la conformité au standard avant tout.</p>
                
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Élevé en Famille</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-notes-medical fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Testé pour la Santé</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe-americas fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Lignées Européennes</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Enregistré</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section (From About Page) -->
<section class="py-5 purple-hero-bg">
    <div class="container text-center">
        <h2 class="mb-5 text-dark">Nos Valeurs Fondamentales</h2>
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

<!-- CTA (From About Page) -->
<section class="py-5 text-center purple-hero-bg">
    <div class="container">
        <h2 class="text-dark mb-3">Prêt à accueillir un géant ?</h2>
        <p class="lead mb-4 text-dark">Découvrez nos chatons disponibles ou apprenez-en plus sur le processus d'adoption.</p>
        <a href="index.php#kittens" class="btn btn-cat rounded-pill px-4 py-2 font-weight-bold mr-3">Voir les Chatons</a>
        <a href="adoption.php" class="btn btn-cat-secondary rounded-pill px-4 py-2 font-weight-bold">Comment Adopter</a>
    </div>
</section>

<style>
/* Toast Notification Style */
.contact-toast {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(-100px);
    background-color: var(--primary-color); /* Mauve */
    color: white;
    padding: 15px 30px;
    border-radius: 50px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    font-weight: 600;
    opacity: 0;
}

.contact-toast.show {
    transform: translateX(-50%) translateY(100px); /* Ajusté pour descendre plus bas que le header */
    opacity: 1;
}

.contact-toast i {
    font-size: 1.5rem;
}
</style>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const toast = document.getElementById('contactToast');
    
    // Show Loading
    btn.disabled = true;
    btnText.style.display = 'none';
    btnLoader.style.display = 'inline-block';
    
    // Create FormData
    const formData = new FormData(form);
    
    fetch('contact.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show Success Custom Toast
            toast.classList.add('show');
            form.reset();
            
            // Hide after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur s\'est produite. Veuillez réessayer plus tard.');
    })
    .finally(() => {
        // Reset Button
        btn.disabled = false;
        btnText.style.display = 'inline-block';
        btnLoader.style.display = 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
