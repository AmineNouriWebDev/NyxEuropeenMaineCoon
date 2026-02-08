<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

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
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }
    
    try {
        // Insertion en base de données
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $subject, $message, date('Y-m-d H:i:s')]);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error during insertion");
        }
    } catch (Exception $e) {
        error_log("Contact Form Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
    exit;
}

include 'includes/header.php';
?>

<!-- Purple Hero Header -->
<div class="litter-hero">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title" style="font-family: 'Amatic SC', cursive;">Contact & Info</h1>
            <p class="hero-subtitle">We would love to hear from you</p>
        </div>
    </div>
</div>


<!-- Health Protocol Section (New) -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h2 class="display-4 font-weight-bold text-primary mb-3" style="font-family: 'Amatic SC', cursive;">Rigorous Health Protocols</h2>
                    <div class="separator mx-auto mb-4" style="width: 100px; height: 3px; background: var(--primary-color);"></div>
                </div>

                <div class="card shadow-lg border-0 rounded-lg overflow-hidden position-relative">
                    <!-- Decorative corner ribbon or icon could go here -->
                    <div class="card-body p-4 p-md-5">
                        <p class="lead text-center text-dark mb-5 font-weight-bold">
                            All our breeding cats undergo rigorous health testing before entering our breeding program.
                        </p>

                        <div class="row">
                            <div class="col-12">
                                <div class="media mb-4 align-items-center p-3 rounded hover-bg-light transition-all">
                                    <div class="icon-box mr-4 text-center rounded-circle bg-light-primary text-primary shadow-sm" style="width: 60px; height: 60px; line-height: 60px; flex-shrink: 0;">
                                        <i class="fas fa-vial fa-lg"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold text-dark">Complete Blood Count</h6>
                                        <p class="mb-0 text-muted">Screening for Feline Leukemia Virus (FeLV) and Feline Immunodeficiency Virus (FIV).</p>
                                    </div>
                                </div>

                                <div class="media mb-4 align-items-center p-3 rounded hover-bg-light transition-all">
                                    <div class="icon-box mr-4 text-center rounded-circle bg-light-danger text-danger shadow-sm" style="width: 60px; height: 60px; line-height: 60px; flex-shrink: 0;">
                                        <i class="fas fa-heartbeat fa-lg"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold text-dark">Cardiac Ultrasound</h6>
                                        <p class="mb-0 text-muted">Testing for Feline Hypertrophic Cardiomyopathy (HCM), certified by the Orthopedic Foundation for Animals (OFA).</p>
                                    </div>
                                </div>

                                <div class="media mb-4 align-items-center p-3 rounded hover-bg-light transition-all">
                                    <div class="icon-box mr-4 text-center rounded-circle bg-light-info text-info shadow-sm" style="width: 60px; height: 60px; line-height: 60px; flex-shrink: 0;">
                                        <i class="fas fa-x-ray fa-lg"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold text-dark">Hip Radiography</h6>
                                        <p class="mb-0 text-muted">Screening for hip dysplasia, certified by the Orthopedic Foundation for Animals (OFA).</p>
                                    </div>
                                </div>

                                <div class="media mb-4 align-items-center p-3 rounded hover-bg-light transition-all">
                                    <div class="icon-box mr-4 text-center rounded-circle bg-light-success text-success shadow-sm" style="width: 60px; height: 60px; line-height: 60px; flex-shrink: 0;">
                                        <i class="fas fa-dna fa-lg"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold text-dark">DNA Genetic Testing</h6>
                                        <p class="mb-0 text-muted">Screening for Hypertrophic Cardiomyopathy (HCM), Pyruvate Kinase Deficiency (PKDef), Spinal Muscular Atrophy (SMA), and Polycystic Kidney Disease (PKD). </p>
                                    </div>
                                </div>

                                <div class="media mb-4 align-items-center p-3 rounded hover-bg-light transition-all">
                                    <div class="icon-box mr-4 text-center rounded-circle bg-light-warning text-warning shadow-sm" style="width: 60px; height: 60px; line-height: 60px; flex-shrink: 0;">
                                        <i class="fas fa-microscope fa-lg"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 font-weight-bold text-dark">Preventive Care</h6>
                                        <p class="mb-0 text-muted">Digestive and respiratory PCR panels performed as needed, along with all necessary care to maintain optimal health.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 rounded bg-primary text-white text-center shadow-sm">
                            <i class="fas fa-project-diagram fa-2x mb-3 text-white-50"></i>
                            <h5 class="font-weight-bold">Pedigree Respect</h5>
                            <p class="mb-0">We rigorously ensure a coefficient of inbreeding respecting standards: <br><strong>less than 12% indirect and 0% direct (COI%).</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inline Styles for this specific section extras -->
<style>
.bg-light-primary { background-color: rgba(52, 152, 219, 0.1); }
.bg-light-danger { background-color: rgba(231, 76, 60, 0.1); }
.bg-light-info { background-color: rgba(22, 160, 133, 0.1); }
.bg-light-success { background-color: rgba(46, 204, 113, 0.1); }
.bg-light-warning { background-color: rgba(241, 196, 15, 0.1); }
.hover-bg-light:hover { background-color: #f8f9fa; transform: translateX(5px); }
.transition-all { transition: all 0.3s ease; }
</style>

<section class="purple-hero-bg py-5">
<div class="container my-5">
    <!-- Feedback Toast -->
    <div id="contactToast" class="contact-toast">
        <i class="fas fa-check-circle"></i>
        <span>Message sent successfully! We will answer you soon.</span>
    </div>

    <!-- Certificat Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <h2 class="section-title-start text-dark" style="font-family: 'Amatic SC', cursive;">Our Certification</h2>
                <div class="separator mx-auto mb-4" style="width: 100px; height: 3px; background: var(--primary-color);"></div>
            </div>
            <div class="certificate-container" id="certificateContainer">
                <img src="../img/certificat.jpg" alt="Nyx European Maine Coon Breeding Certificate" class="certificate-img img-fluid" id="certificateImg">
                <div class="mobile-zoom-hint">
                    <i class="fas fa-search-plus"></i>
                    <span>Tap to enlarge</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row shadow-lg rounded-lg overflow-hidden bg-white">
        <!-- Contact Info & Map (Left Column) -->
        <div class="col-lg-5 text-white p-5 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, var(--dark-color) 0%, #2d3436 100%); min-height: 500px;">
            <div>
                <h3 class="text-white mb-4 font-weight-bold">Contact Information</h3>
                <p class="mb-5 text-white-50">Fill out the form or contact us directly via these channels.</p>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3 text-white fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">Location</h6>
                        <span class="text-white">South Shore of Montreal, postal code: J5R 0K4</span>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-envelope mt-1 mr-3 text-white fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">Email</h6>
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
                <h6 class="text-white mb-3 text-uppercase small letter-spacing-1">Follow Us</h6>
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
            <h3 class="text-dark mb-4 font-weight-bold">Send us a Message</h3>
            <form id="contactForm">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">NAME <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-4 px-3" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">EMAIL <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-light border-0 py-4 px-3" placeholder="john@example.com" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-muted">SUBJECT</label>
                    <select name="subject" class="form-control bg-light border-0" style="height: 50px;">
                        <option>General Inquiry</option>
                        <option>Kitten Waiting List</option>
                        <option>Adoption Process</option>
                        <option>Breeding Rights Info</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-muted">MESSAGE <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control bg-light border-0 p-3" rows="5" placeholder="Tell us about yourself and what you are looking for..." required></textarea>
                </div>

                <button type="submit" class="btn btn-cat py-3 px-5 shadow-sm" id="submitBtn">
                    <span id="btnText" style="color: white !important;">Send Message <i class="fas fa-paper-plane ml-2"></i></span>
                    <span id="btnLoader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                </button>
            </form>
        </div>
    </div>
</div>
</section>

<!-- Modal Fullscreen pour le Certificat (Mobile uniquement) -->
<div id="certificateModal" class="certificate-modal">
    <span class="certificate-modal-close">&times;</span>
    <img class="certificate-modal-content" id="modalCertificateImg" src="./img/certificat.jpg" alt="Nyx European Maine Coon Breeding Certificate">
    <div class="certificate-modal-caption">Nyx European Maine Coon Breeding Certificate</div>
</div>


<!-- Our Vision (From About Page) -->
<section class="py-5 purple-hero-bg ">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="../img/about.jpeg" class="img-fluid rounded-lg shadow-lg" alt="Maine Coon Portrait">
            </div>
            <div class="col-md-6">
                <h2 class="section-title-start text-dark mb-4">More Than Just a Cattery</h2>
                <p class="lead text-dark">At Nyx European Maine Coon, we believe every kitten deserves to be raised as a family member from day one.</p>
                <p class="text-dark">Located on the South Shore of Montreal, our cattery specializes in breeding European Maine Coons, known for their wild look, impressive size, and gentle giant personality. We prioritize health, temperament, and breed standard conformity above all.</p>
                
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Family Raised</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-notes-medical fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Health Tested</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe-americas fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">European Lines</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold text-dark">Purebred Registered</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- CTA (From About Page) -->
<section class="py-5 text-center purple-hero-bg">
    <div class="container">
        <h2 class="text-dark mb-3">Ready to welcome a gentle giant?</h2>
        <p class="lead mb-4 text-dark">Discover our available kittens or learn more about the adoption process.</p>
        <a href="index.php#kittens" class="btn btn-cat rounded-pill px-4 py-2 font-weight-bold mr-3">View Kittens</a>
        <a href="adoption.php" class="btn btn-cat-secondary rounded-pill px-4 py-2 font-weight-bold text-white">How to Adopt</a>
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

/* Certificate Styles - Responsive */
.certificate-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.certificate-img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.certificate-img:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

/* Desktop - Grande taille */
@media (min-width: 1200px) {
    .certificate-img {
        max-width: 80%;
    }
}

/* Tablette - Taille moyenne */
@media (min-width: 768px) and (max-width: 1199px) {
    .certificate-img {
        max-width: 90%;
    }
}

/* Mobile - Pleine largeur avec padding */
@media (max-width: 767px) {
    .certificate-container {
        padding: 10px;
        position: relative;
        cursor: pointer;
    }
    
    .certificate-img {
        max-width: 100%;
        border-radius: 8px;
    }
    
    .certificate-img:active {
        transform: scale(0.98);
    }
    
    .mobile-zoom-hint {
        position: absolute;
        bottom: 30px;
        right: 30px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: none;
        animation: pulse 2s infinite;
    }
    
    .mobile-zoom-hint i {
        font-size: 14px;
    }
}

/* Cache l'indicateur de zoom sur desktop et tablette */
@media (min-width: 768px) {
    .mobile-zoom-hint {
        display: none;
    }
}

/* Animation pulse pour l'indicateur */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Modal Styles pour le certificat en plein écran */
.certificate-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.95);
    padding: 20px;
}

.certificate-modal-content {
    margin: auto;
    display: block;
    width: 100%;
    max-width: 100%;
    height: auto;
    animation: zoomIn 0.3s;
}

.certificate-modal-close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
    z-index: 10001;
}

.certificate-modal-close:hover,
.certificate-modal-close:focus {
    color: #bbb;
}

.certificate-modal-caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 20px 0;
    font-size: 14px;
}

@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Ajustements pour mobile dans le modal */
@media (max-width: 767px) {
    .certificate-modal {
        padding: 10px;
    }
    
    .certificate-modal-close {
        top: 10px;
        right: 20px;
        font-size: 35px;
    }
    
    .certificate-modal-caption {
        font-size: 12px;
        padding: 15px 0;
    }
}
</style>

<script>
// Modal pour le certificat (Mobile uniquement)
function isMobile() {
    return window.innerWidth <= 767;
}

const certificateContainer = document.getElementById('certificateContainer');
const certificateImg = document.getElementById('certificateImg');
const modal = document.getElementById('certificateModal');
const modalImg = document.getElementById('modalCertificateImg');
const closeBtn = document.querySelector('.certificate-modal-close');

// Ouvrir le modal uniquement sur mobile
if (certificateContainer && certificateImg) {
    certificateContainer.addEventListener('click', function() {
        if (isMobile()) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
        }
    });
}

// Fermer le modal
if (closeBtn) {
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// Fermer le modal en cliquant en dehors de l'image
if (modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
}

// Gérer le redimensionnement de la fenêtre
window.addEventListener('resize', function() {
    if (!isMobile() && modal.style.display === 'block') {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

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
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
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
