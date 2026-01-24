<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<!-- Hero Section About -->
<section class="page-hero" style="background: url('img/about-bg.jpg') no-repeat center center/cover; height: 60vh; position: relative;">
    <div class="overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4);"></div>
    <div class="container h-100 d-flex align-items-center justify-content-center text-center position-relative text-white">
        <div>
            <h1 class="cursive-font display-1">Our Story</h1>
            <p class="lead">Passion, Excellence & Love for Maine Coons</p>
        </div>
    </div>
</section>

<!-- Our Vision -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-lg shadow-lg" alt="Maine Coon Portrait">
            </div>
            <div class="col-md-6">
                <h2 class="section-title-start text-dark mb-4">More Than Just a Cattery</h2>
                <p class="lead">At Nyx European Maine Coon, we believe that every kitten deserves to be raised as a family member from day one.</p>
                <p>Located in the heart of Montreal, our cattery specializes in raising European Maine Coons, known for their feral look, impressive size, and gentle giant personality. We prioritize health, temperament, and standard conformity above all else.</p>
                
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x mr-3" style="color: var(--primary-color);"></i>
                            <span class="font-weight-bold">Family Raised</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-notes-medical fa-2x mr-3" style="color: var(--secondary-color);"></i>
                            <span class="font-weight-bold">Health Tested</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe-americas fa-2x mr-3" style="color: var(--accent-color);"></i>
                            <span class="font-weight-bold">European Lines</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate fa-2x mr-3" style="color: var(--cat-eye-green);"></i>
                            <span class="font-weight-bold">Registered</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="mb-5">Our Core Values</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-heartbeat fa-2x"></i>
                    </div>
                    <h4>Health First</h4>
                    <p>All our breeding cats are DNA tested for HCM, SMA, and PKDef. We perform regular cardiac ultrasounds to ensure the healthiest lines possible.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-brain fa-2x"></i>
                    </div>
                    <h4>Socialization</h4>
                    <p>Our kittens are raised underfoot, not in cages. They are exposed to daily household noises, kids, and other pets to ensure they are confident and affectionate.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm h-100 kitten-card">
                    <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                    <h4>Excellence</h4>
                    <p>We strive for the "wild look" typical of European lines: strong muzzles, large ears with heavy lynx tips, and substantial boning.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center text-white" style="background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <h2 class="text-white mb-3">Ready to welcome a giant?</h2>
        <p class="lead mb-4">Check out our available kittens or learn more about the adoption process.</p>
        <a href="index.php#kittens" class="btn btn-light rounded-pill px-4 py-2 font-weight-bold text-dark mr-3">View Kittens</a>
        <a href="adoption.php" class="btn btn-outline-light rounded-pill px-4 py-2 font-weight-bold">How to Adopt</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
