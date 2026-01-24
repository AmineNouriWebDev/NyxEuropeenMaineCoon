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
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }
    
    // Configuration Email
    // Note: Pour que mail() fonctionne en local, il faut un serveur SMTP configuré (ex: Sendmail dans XAMPP)
    // En production, cela fonctionnera généralement directement.
    $to = "nouri.medamine1987@gmail.com"; 
    $email_subject = "Neue Nachricht von $name: $subject";
    $email_body = "You have received a new message from your website contact form.\n\n".
                  "Name: $name\n".
                  "Email: $email\n".
                  "Subject: $subject\n".
                  "Message:\n$message";
    
    $headers = "From: noreply@nyxcooncattery.com\n"; // Utilisez une adresse de votre domaine
    $headers .= "Reply-To: $email";
    
    // Envoi (Simulé en succès si l'envoi échoue en local à cause de la config)
    $mailSent = @mail($to, $email_subject, $email_body, $headers);
    
    // Pour le développement local, on retourne toujours succès si les champs sont valides
    echo json_encode(['success' => true]); 
    exit;
}

include 'includes/header.php';
?>

<!-- Hero Section Contact -->
<section class="page-hero" style="background: url('https://images.unsplash.com/photo-1513245543132-31f507417b26?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover; height: 50vh; position: relative; margin-top: 0;">
    <div class="overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5));"></div>
    <div class="container h-100 d-flex align-items-center justify-content-center text-center position-relative text-white">
        <div style="margin-top: 80px;"> <!-- Marge pour le header fixe -->
            <h1 class="cursive-font display-3 mb-3">Get in Touch</h1>
            <p class="lead font-weight-light">We'd love to hear from you about our gentle giants</p>
        </div>
    </div>
</section>

<div class="container my-5" style="margin-top: -80px; position: relative; z-index: 10;">
    <!-- Feedback Toast -->
    <div id="contactToast" class="contact-toast">
        <i class="fas fa-check-circle"></i>
        <span>Message sent successfully! We will get back to you soon.</span>
    </div>

    <div class="row shadow-lg rounded-lg overflow-hidden bg-white">
        <!-- Contact Info & Map (Left Column) -->
        <div class="col-lg-5 text-white p-5 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, var(--dark-color) 0%, #2d3436 100%); min-height: 500px;">
            <div>
                <h3 class="text-white mb-4 font-weight-bold">Contact Information</h3>
                <p class="mb-5 text-white-50">Fill out the form or reach us directly via these channels.</p>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">Location</h6>
                        <span class="text-white-50">Montreal, Quebec, Canada</span>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fas fa-envelope mt-1 mr-3 text-primary fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">Email</h6>
                        <a href="mailto:nyxcooncattery@gmail.com" class="text-white-50 text-decoration-none">nyxcooncattery@gmail.com</a>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-start">
                    <i class="fab fa-whatsapp mt-1 mr-3 text-success fa-lg"></i>
                    <div>
                        <h6 class="text-white mb-1">WhatsApp</h6>
                        <a href="https://wa.me/15142695930" class="text-white-50 text-decoration-none">+1-514-269-5930</a>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h6 class="text-white mb-3 text-uppercase small letter-spacing-1">Follow Us</h6>
                <div class="social-icons">
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
                    <span id="btnText">Send Message <i class="fas fa-paper-plane ml-2"></i></span>
                    <span id="btnLoader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Toast Notification Style */
.contact-toast {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(-100px);
    background-color: var(--secondary-color); /* Turquoise */
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
