<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Header -->
<section class="py-5 text-center bg-light">
    <div class="container mt-5">
        <h1 class="cursive-font" style="font-size: 3.5rem; color: var(--secondary-color);">Adoption Process</h1>
        <p class="lead text-muted">How to bring your dream kitten home</p>
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
                    <h3 class="m-0">Inquire & Apply</h3>
                </div>
                <p>Browse our available kittens or upcoming litters. Fill out our adoption application form or contact us directly. We want to know about you, your lifestyle, and what you are looking for in a companion.</p>
            </div>

            <!-- Step 2 -->
            <div class="col-md-6 mb-5 order-md-2 text-center">
                <img src="https://images.unsplash.com/photo-1561948955-570b270e7c36?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-circle shadow" style="width: 250px; height: 250px; object-fit: cover;" alt="Step 2">
            </div>
            <div class="col-md-6 mb-5 order-md-1">
                <div class="d-flex align-items-center mb-3">
                    <span class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 40px; height: 40px;">2</span>
                    <h3 class="m-0">Reservation</h3>
                </div>
                <p>Once approved, you can reserve your chosen kitten with a deposit. This deposit is deducted from the final price. You'll receive a reservation contract and weekly updates/photos of your growing baby.</p>
            </div>

            <!-- Step 3 -->
            <div class="col-md-6 mb-5 text-center">
                <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-circle shadow" style="width: 250px; height: 250px; object-fit: cover;" alt="Step 3">
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold mr-3" style="width: 40px; height: 40px;">3</span>
                    <h3 class="m-0">Go Home Day!</h3>
                </div>
                <p>Kittens go home between 12-14 weeks of age. We arrange a pickup date. If you are not local (Montreal), we can discuss safe transport options (flight nanny).</p>
            </div>
        </div>
    </div>
</section>

<!-- Whats Included -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="mb-5">What's Included?</h2>
        <div class="row">
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-syringe fa-3x text-info mb-3"></i>
                <h5>Vaccinations</h5>
                <p class="small text-muted">Core vaccines up to date</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-microchip fa-3x text-warning mb-3"></i>
                <h5>Microchip</h5>
                <p class="small text-muted">Permanent ID registration</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-file-medical-alt fa-3x text-danger mb-3"></i>
                <h5>Health Record</h5>
                <p class="small text-muted">Passport & Vet certificate</p>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                <h5>Starter Kit</h5>
                <p class="small text-muted">Food, toys & blanket</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        <div class="accordion shadow-sm" id="faqAccordion">
            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne">
                            Do you ship kittens?
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                    <div class="card-body">
                        We prefer in-person pickup, but we can arrange a "flight nanny" who will fly with the kitten in cabin to your nearest airport. We do not ship cargo for the safety of our cats.
                    </div>
                </div>
            </div>
            
            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo">
                            What is the price of a kitten?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#faqAccordion">
                    <div class="card-body">
                        Our prices generally range from $2500 to $3500 depending on color, type, and purpose (pet vs breeding). Please contact us for specific pricing on available kittens.
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-1">
                <div class="card-header bg-white p-3" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-dark font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseThree">
                            Do you offer a health guarantee?
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#faqAccordion">
                    <div class="card-body">
                        Yes! All our kittens come with a 2-year genetic health guarantee covering HCM, SMA, and other hereditary conditions, as well as a 72-hour viral guarantee.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
