<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="litter-hero  text-center "style="padding-top: 200px;">
    <div class="container">
        <h1 class="display-4 font-weight-bold" style="font-family: 'Vijaya', serif;">Adoption Process</h1>
        <p class="lead" style="color: rgba(255,255,255,0.9);">Join the NYX COON Family</p>
    </div>
</section>

<!-- 1. Processus Steps -->
<section class="py-5 purple-hero-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                    <!-- Intro Paragraph (Glassmorphism Style) -->
                <div class="intro-glass-card mb-5 text-center text-white position-relative bg-white" style="padding: 3rem 2rem; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <!-- Background Backdrop -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 25px; border: 1px solid rgba(255, 255, 255, 0.2); z-index: 0;"></div>
                    
                    <div class="position-relative" style="z-index: 1;">
                        <div class="mb-3">
                            <i class="fas fa-crown text-warning fa-2x mb-2"></i>
                        </div>
                        <h3 class="font-weight-bold mb-4" style="font-family: 'Vijaya', serif; font-size: 2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                            Priority and Commitment List
                        </h3>
                        <p class="lead mb-4" style="font-size: 1.2rem; line-height: 1.6; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                            This is the ideal list for those dreaming of welcoming a magnificent 
                            <span class="font-weight-bold" style="color: #fff; border-bottom: 2px solid rgba(255,255,255,0.3);">NYX COON</span> 
                            kitten in the near future.
                        </p>
                        <div class="d-inline-block px-4 py-3 rounded-lg" style="background: rgba(0,0,0,0.2); border-radius: 15px;">
                            <p class="mb-0 font-italic" style="font-size: 1.05rem; color: rgba(255,255,255,0.95);">
                                <i class="fas fa-quote-left mr-2 opacity-50"></i>
                                Registration involves a commitment: a deposit will be required to join. 
                                In return, you get priority access to our litters.
                                <i class="fas fa-quote-right ml-2 opacity-50"></i>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="accordion" id="stepsAccordion">
                    <?php 
                    $steps = [
                        1 => [
                            'title' => "Read Conditions and Pricing",
                            'desc' => "The cost of a Maine Coon kitten varies based on factors like pedigree, lineage, and breeder reputation. Additional info available on request. Prices range from $3000 to $4500 (tax included). Additional fees apply for polydactyl, odd-eyed, blue-eyed, or white-mitten kittens. It is best to request this directly when choosing your kitten."
                        ],
                        2 => [
                            'title' => "Why Choose the Priority List",
                            'desc' => "Why a priority list with deposit?
- The priority list reserves you a selection privilege on upcoming litters
- To ensure we find good families for our kittens
- To establish a bond with families before adoption"
                        ],
                        3 => [
                            'title' => "Fill out the Application Form",
                            'desc' => '<a href="#vipForm" class="btn btn-sm btn-primary mt-2">Access the form below <i class="fas fa-arrow-down"></i></a>'
                        ],
                        4 => [
                            'title' => "Presentation of Available Kittens Based on Your Priority Order (Around 6-8 Weeks Old)",
                            'desc' => "When it's your turn to choose, you will be contacted personally. You will have a 24-hour reflection period to make your choice. The next step is signing the sales contract."
                        ],
                        5 => [
                            'title' => "Virtual Visit and Final Choice (Deposit)",
                            'desc' => "If desired, we can show you kittens via virtual visit to help with selection. Then, contract signing and a $1000 deposit will be required."
                        ],
                        6 => [
                            'title' => "Weekly Growth Updates",
                            'desc' => "Get ready to watch your kitten grow with new photos every week until departure! You will see them growing and socializing with others."
                        ],
                        7 => [
                            'title' => "Pickup Around 14 Weeks",
                            'desc' => "At departure, your little Nyx Coon will be ready to discover new horizons. They will be socialized with children, dogs, and cats. Get ready for a playful and happy kitten !"
                        ]
                    ];
                    foreach($steps as $num => $step): ?>
                    <div class="card mb-3 border-0 shadow-sm rounded overflow-hidden">
                        <div class="card-header bg-white p-0 border-0" id="heading<?php echo $num; ?>">
                            <button class="btn btn-block text-left d-flex align-items-center p-4 focus-none step-btn" type="button" data-toggle="collapse" data-target="#collapse<?php echo $num; ?>" aria-expanded="false" aria-controls="collapse<?php echo $num; ?>" style="text-decoration: none; color: inherit;">
                                <div class="step-num bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold flex-shrink-0" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                    <?php echo $num; ?>
                                </div>
                                <h5 class="step-title font-weight-bold text-dark mb-0 flex-grow-1 pr-3"><?php echo $step['title']; ?></h5>
                                <i class="fas fa-chevron-down text-primary transition-icon" style="font-size: 1.2rem;"></i>
                            </button>
                        </div>
                        <div id="collapse<?php echo $num; ?>" class="collapse" aria-labelledby="heading<?php echo $num; ?>" data-parent="#stepsAccordion">
                            <div class="card-body bg-light pl-5 ml-4 text-muted" style="border-top: 1px solid rgba(0,0,0,0.05);">
                                <div style="white-space: pre-line; padding-left: 15px; border-left: 3px solid var(--primary-color);">
                                    <?php echo $step['desc']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>

                    <style>
                        .step-btn:focus { box-shadow: none; }
                        .step-btn[aria-expanded="true"] .transition-icon { transform: rotate(180deg); }
                        .transition-icon { transition: transform 0.3s ease; }
                        .step-btn:hover { background-color: #f8f9fa; }
                    </style>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Liste VIP - Diamants -->
<section class="py-5 purple-hero-bg position-relative" id="vip-list">
    <div class="container">
        <!-- (Section Intro removed as per request) -->

        <!-- Texte Explicatif Formulaire -->
        <div class="alert alert-info shadow-sm border-0 rounded-lg p-4 mb-5">
            <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Important</h4>
            <p>Every adoption request is carefully reviewed. We care deeply about finding perfect families for our precious felines. It is important to fill out this form carefully: it helps us get to know you better.</p>
            <p class="mb-0"><em>If you don't hear back after 48 hours, feel free to call us at +1(514) 269-5930.</em></p>
        </div>

        <!-- 3. Formulaire -->
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <div class="card-header text-white p-4 purple-hero-bg">
                <h3 class="mb-0"><i class="fas fa-paw mr-2"></i> I want to add my name to the priority list</h3>
            </div>
            <div class="card-body p-5">
                <form id="vipForm">
                    <!-- Identité -->
                    <h5 class="text-secondary mb-3 border-bottom pb-2">Your Contact Info</h5>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Phone *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <!-- Mieux vous connaître -->
                    <h5 class="text-secondary mb-3 mt-4 border-bottom pb-2">Get to Know You</h5>

                    <div class="form-group mb-4">
                        <label>How many pets do you have at home? *</label>
                        <textarea name="existing_pets" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label>What type of environment will the kitten live in? *</label>
                        <small class="form-text text-muted mb-2">(e.g., indoor only, outdoor access, secured enclosure, etc.)</small>
                        <textarea name="environment_type" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label>Where did you hear about NYX COON? *</label>
                        <input type="text" name="hear_about_us" class="form-control" required>
                    </div>

                    <!-- Préférences -->
                    <h5 class="text-secondary mb-3 mt-4 border-bottom pb-2">Your Future Companion</h5>
                    
                    <div class="form-group mb-3">
                        <label>Do you have any color preferences? *</label>
                        <input type="text" name="color_preferences" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Gender preference? *</label>
                        <select name="gender_preference" class="form-control" required>
                            <option value="">-- Choose --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="None">No preference</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>From what date would you like to welcome a NYX COON? *</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="date_year" class="form-control" placeholder="Year" min="2024" required>
                            <input type="number" name="date_month" class="form-control" placeholder="Month" min="1" max="12" required>
                            <input type="number" name="date_day" class="form-control" placeholder="Day" min="1" max="31">
                        </div>
                    </div>

                    <!-- Champ Libre -->
                    <div class="form-group mb-4">
                        <label>Questions or additional information (Optional)</label>
                        <textarea name="questions" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Engagements -->
                    <div class="bg-light p-4 rounded mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="checkPriority" required>
                            <label class="form-check-label text-dark" for="checkPriority">
                                I understand that this list operates by priority and that the breeder always retains a choice on available kittens. *
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkDeposit" required>
                            <label class="form-check-label text-dark" for="checkDeposit">
                                If my application is approved, I agree to pay a $300 deposit (deductible) by transfer to be on the priority list *
                            </label>
                        </div>
                    </div>

                    <!-- Cloudflare Turnstile -->
                    <div class="form-group mb-4 text-center">
                        <div class="cf-turnstile d-inline-block" data-sitekey="<?php echo TURNSTILE_SITE_KEY; ?>"></div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-cat btn-lg py-3 px-5 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- 4. FAQ -->
<section class="py-5 purple-hero-bg">
    <div class="container">
        <h2 class="text-center mb-5 font-weight-bold">Frequently Asked Questions</h2>
        
        <div class="accordion" id="faqAccordion">
            <div class="card border-0 mb-3 shadow-sm rounded">
                <div class="card-header bg-white" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-3 text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne">
                            Why are visits to the cattery not allowed?
                            <i class="fas fa-chevron-down text-primary"></i>
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                    <div class="card-body bg-white text-muted">
                        <p>At NYX COON, the health and safety of our kittens are our top priority.</p>
                        <p>Although we understand your desire to visit, here is why it is not possible:</p>
                        <ul class="list-unstyled pl-3 border-left border-primary ml-2">
                            <li class="mb-2"><strong>1. Risk of contamination:</strong> Even with the best intentions, you can unknowingly carry fur, allergens, bacteria (typhus, coryza), or parasites via your clothes or shoes.</li>
                            <li class="mb-2"><strong>2. Stress for moms:</strong> Our pregnant or nursing females need absolute calm.</li>
                            <li><strong>3. Sanitary security:</strong> We maintain a sterile environment for newborns whose immune systems are immature.</li>
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
.divider-custom { height: 4px; width: 100px; background: rgba(255, 255, 255, 0.5); margin: 20px auto; border-radius: 2px; }
</style>

<script>
document.getElementById('vipForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    btn.disabled = true;

    const formData = new FormData(this);

    fetch('ajax_vip_request.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            alert('Your request was sent successfully! We will contact you within 48h.');
            this.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('A technical error occurred.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
