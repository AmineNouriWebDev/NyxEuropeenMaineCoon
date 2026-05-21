<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Récupération du chat avec les noms des parents
$stmt = $pdo->prepare("
    SELECT c.*, 
           f.name AS father_name, f.id AS father_id,
           m.name AS mother_name, m.id AS mother_id,
           c.for_sale,
           c.sale_type,
           c.stud_price_cad,
           c.stud_price_usd,
           c.retirement_price_cad,
           c.retirement_price_usd,
           c.sale_description
    FROM chats c
    LEFT JOIN chats f ON c.father_id = f.id
    LEFT JOIN chats m ON c.mother_id = m.id
    WHERE c.id = ?
");
$stmt->execute([$id]);
$cat = $stmt->fetch();

if ($cat) {
    ensure_cat_has_color_code($cat, $pdo);
    
    // Fetch color name_fr from colors table
    if (!empty($cat['color_code'])) {
        $stmt_color = $pdo->prepare("SELECT name_fr FROM colors WHERE code = ?");
        $stmt_color->execute([$cat['color_code']]);
        $cat['color_name_fr'] = $stmt_color->fetchColumn();
    }
}

if (!$cat) {
    header('Location: index.php');
    exit;
}

// Récupération des images
$stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE cat_id = ? ORDER BY sort_order");
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire de contact (Fallback si JS désactivé)
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inquire') {
    $visitor_name = trim($_POST['visitor_name']);
    $visitor_email = trim($_POST['visitor_email']);
    $visitor_phone = trim($_POST['visitor_phone']);
    $message = trim($_POST['message']);
    
    if (!empty($visitor_name) && !empty($visitor_email)) {
        // Vérification Turnstile
        $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
        if (!verify_turnstile($turnstile_response)) {
            $msg = "captcha_error";
        } else {
            try {
            $stmt = $pdo->prepare("INSERT INTO adoption_requests (cat_id, cat_name, visitor_name, visitor_email, visitor_phone, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$cat['id'], $cat['name'], $visitor_name, $visitor_email, $visitor_phone, $message, date('Y-m-d H:i:s')]);
            $msg = "success";

            // Notification n8n
            send_n8n_notification([
                'type' => 'Demande de renseignement (Chaton)',
                'name' => $visitor_name,
                'email' => $visitor_email,
                'phone' => $visitor_phone,
                'subject' => "Intérêt pour : " . $cat['name'],
                'message' => "Chaton : " . $cat['name'] . "\n\n Message : " . $message
            ]);
            } catch (Exception $e) {
                $msg = "error";
            }
        }
    }
}

include 'includes/header.php';
?>

<style>
    /* Force opaque background for header sections on this page */
    .top-section, .nav-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        position: relative; /* Ensure it stacks correctly if needed */
        z-index: 1000;
    }
    .top-section .social-icon {
        border-color: rgba(255,255,255,0.3);
    }
</style>

<!-- Returns to standard header with spacer -->
<div style="height: 100px;"></div>

<!-- Main Section with Purple Background -->
<section class="kitten-section purple-hero-bg" style="padding-top: 20px; min-height: 100vh;">


<div class="container" style ="margin-top: 130px;">
    

    <?php if ($msg == 'success'): ?>
        <div class="alert alert-success">Votre demande a été envoyée avec succès !</div>
    <?php elseif ($msg == 'captcha_error'): ?>
        <div class="alert alert-danger">Échec de la validation de sécurité (Captcha). Veuillez réessayer.</div>
    <?php endif; ?>

    <div class="row">
        <!-- Galerie Photos -->
        <div class="col-lg-7 mb-5">
            <div class="position-relative overflow-hidden rounded-lg shadow-lg mb-3 kitten-gallery-container" style="height: 500px; border-radius: 20px;">
                <img id="mainImage" src="<?php echo asset_url('img/' . ($images[0] ?? 'default.jpg')); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                
                <?php if ($cat['status'] !== 'available'): ?>
                    <div class="kitten-status <?php echo $cat['status']; ?>"style="color:white !important;">
                        <?php 
                        if ($cat['status'] == 'reserved') echo 'Réservé';
                        elseif ($cat['status'] == 'sold') echo 'Vendu';
                        else echo ucfirst($cat['status']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="row px-1">
                <?php foreach ($images as $img): ?>
                    <div class="col-3 px-1 mb-2">
                        <img src="<?php echo asset_url('img/' . $img); ?>" class="img-fluid rounded cursor-pointer border shadow-sm thumb-img" 
                             style="height: 100px; width: 100%; object-fit: cover; opacity: 0.7; transition: 0.3s; border-radius: 10px;"
                             onclick="changeImage(this.src)"
                             onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Description Riche -->
            <?php if (!empty($cat['description'])): ?>
            <div class="card shadow-sm border-0 rounded-lg p-4 mt-4">
                <h3 class="mb-4 text-primary" style="font-family: 'Vijaya', serif;">À propos de <?php echo htmlspecialchars($cat['name']); ?></h3>
                <div class="blog-content text-dark" >
                    <?php echo $cat['description']; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Informations -->
        <div class="col-lg-5">
            <h1 class="display-4 font-weight-bold mb-2" style="font-family: 'Vijaya', serif;"><?php echo htmlspecialchars($cat['name']); ?></h1>
            
            <!-- Color Information Display -->
            <div class="mb-3" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <?php if (!empty($cat['color_name_fr']) || !empty($cat['color_code'])): ?>
                    <span style="color: #b3b3b3ff; font-size: 1.1rem; font-weight: 700; ">
                        <?php echo htmlspecialchars($cat['color_name_fr'] ?? ''); ?>
                        <?php if (!empty($cat['color_code'])): ?>
                            <span style="color: #6c757d;">(<?php echo htmlspecialchars($cat['color_code']); ?>)</span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
                
                <?php 
                // Display special effects
                $full_desc = ($cat['color'] ?? '') . ' ' . ($cat['special_effect'] ?? '');
                $effects = [
                    'SMOKE(s)' => stripos($full_desc, 'smoke') !== false,
                    'SILVER(s)' => stripos($full_desc, 'silver') !== false,
                    'SHADED(s)' => stripos($full_desc, 'shaded') !== false,
                    'CHINCHILLA(s)' => stripos($full_desc, 'chinchilla') !== false
                ];
                
                foreach($effects as $label => $active) {
                    if ($active) {
                        echo '<span style="background: #000; color: #fff !important; padding: 4px 12px; border-radius: 4px; font-size: 0.9rem; font-weight: 500;">' . $label . '</span>';
                    }
                }
                ?>
            </div>
            
            
            <!-- Prix (masqué si vide) -->
            <?php if (!empty($cat['price_cad']) || !empty($cat['price_usd'])): ?>
            <div class="kitten-price-container mb-4">
                <?php if (!empty($cat['price_cad'])): ?>
                <div class="d-flex align-items-center mb-2">
                    <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 20px; margin-right: 8px; vertical-align: middle;">
                    <span class="h3 text-primary font-weight-bold mb-0"><?php echo number_format((float)$cat['price_cad'], 0, ',', ' '); ?> $</span>
                    <?php if (!empty($cat['old_price_cad'])): ?>
                        <span class="old-price ml-3 text-muted"><?php echo number_format((float)$cat['old_price_cad'], 0, ',', ' '); ?> $</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($cat['price_usd'])): ?>
                <div class="d-flex align-items-center">
                    <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 20px; margin-right: 8px; vertical-align: middle;">
                    <span class="h4 text-secondary font-weight-bold mb-0"><?php echo number_format((float)$cat['price_usd'], 0, ',', ' '); ?> $</span>
                    <?php if (!empty($cat['old_price_usd'])): ?>
                        <span class="old-price ml-3 text-muted"><?php echo number_format((float)$cat['old_price_usd'], 0, ',', ' '); ?> $</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Détails Grid -->
            <div class="kitten-info-grid mb-4">
                <div class="info-item text-dark">
                    <i class="fas fa-venus-mars text-dark"></i>
                    <span class="text-dark"><?php echo $cat['gender'] == 'Male' ? ' Mâle' : 'Femelle'; ?></span>
                </div>
                <div class="info-item text-dark">
                    <i class="fas fa-calendar-alt text-dark"></i>
                    <span class="text-dark"><?php echo calculate_age($cat['birth_date']); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-cat text-dark"></i>
                    <span class="text-dark"><?php echo htmlspecialchars($cat['quality']); ?></span>
                </div>
                <?php if (!empty($cat['paw_type'])): ?>
                <div class="info-item">
                    <i class="fas fa-paw text-dark"></i>
                    <span class="text-dark"><?php echo htmlspecialchars($cat['paw_type']); ?></span>
                </div>
                <?php endif; ?>

                

            </div>

            <!-- Parents -->
            <!-- Parents (Masqué pour King/Queen) -->
            <?php if (!in_array($cat['status'], ['king', 'queen'])): ?>
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: #fff5f5;">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="fas fa-users"></i> Parents</h5>
                    <div class="row">
                        <div class="col-6">
                            <?php if (!empty($cat['father_name'])): ?>
                                <p class="mb-1 text-muted small">Père (King)</p>
                                <a href="<?php echo 'chat_details.php?id=' . $cat['father_id']; ?>" class="font-weight-bold text-dark text-decoration-none">
                                    <i class="fas fa-crown text-warning"></i> <?php echo htmlspecialchars($cat['father_name']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Père non renseigné</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <?php if (!empty($cat['mother_name'])): ?>
                                <p class="mb-1 text-muted small">Mère (Queen)</p>
                                <a href="<?php echo 'chat_details.php?id=' . $cat['mother_id']; ?>" class="font-weight-bold text-dark text-decoration-none">
                                    <i class="fas fa-heart text-danger"></i> <?php echo htmlspecialchars($cat['mother_name']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Mère non renseignée</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Services Disponibles (pour les chats à vendre) -->
            <?php if ($cat['for_sale']): ?>
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">
                        <i class="fas fa-handshake"></i> Services Disponibles
                    </h5>
                    
                    <?php if ($cat['sale_type'] === 'stud' || $cat['sale_type'] === 'both'): ?>
                    <div class="mb-3 p-3" style="background: white; border-radius: 10px; border-left: 4px solid #3498db;">
                        <h6 class="font-weight-bold mb-2">
                            <i class="fas fa-paw text-info"></i> Service de Saillie
                        </h6>
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 16px; margin-right: 5px; vertical-align: middle;">
                            <span class="text-muted font-weight-bold mr-3" style="font-size: 1.25rem;">
                                <?php echo number_format((float)$cat['stud_price_cad'], 0, ',', ' '); ?> $
                            </span>
                            <?php if (!empty($cat['old_stud_price_cad'])): ?>
                                <span class="old-price mr-3 text-muted"><?php echo number_format((float)$cat['old_stud_price_cad'], 0, ',', ' '); ?> $</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 16px; margin-right: 5px; vertical-align: middle;">
                            <span class="text-muted font-weight-bold">
                                <?php echo number_format((float)$cat['stud_price_usd'], 0, ',', ' '); ?> $
                            </span>
                            <?php if (!empty($cat['old_stud_price_usd'])): ?>
                                <span class="old-price ml-2 text-muted"><?php echo number_format((float)$cat['old_stud_price_usd'], 0, ',', ' '); ?> $</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($cat['sale_description'])): ?>
                        <p class="text-muted mb-0 small"><?php echo nl2br(htmlspecialchars($cat['sale_description'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($cat['sale_type'] === 'retirement' || $cat['sale_type'] === 'both'): ?>
                    <div class="p-3" style="background: white; border-radius: 10px; border-left: 4px solid #27ae60;">
                        <h6 class="font-weight-bold mb-2">
                            <i class="fas fa-home text-success"></i> Disponible à la Retraite
                        </h6>
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 16px; margin-right: 5px; vertical-align: middle;">
                            <span class="text-muted font-weight-bold" style="font-size: 1.1rem; ">
                                <?php echo number_format((float)$cat['retirement_price_cad'], 0, ',', ' '); ?> $
                            </span>
                            <?php if (!empty($cat['old_retirement_price_cad'])): ?>
                                <span class="old-price mr-3 text-muted"><?php echo number_format((float)$cat['old_retirement_price_cad'], 0, ',', ' '); ?> $</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 16px; margin-right: 5px; vertical-align: middle;">
                            <span class="text-muted font-weight-bold">
                                <?php echo number_format((float)$cat['retirement_price_usd'], 0, ',', ' '); ?> $
                            </span>
                            <?php if (!empty($cat['old_retirement_price_usd'])): ?>
                                <span class="old-price ml-2 text-muted"><?php echo number_format((float)$cat['old_retirement_price_usd'], 0, ',', ' '); ?> $</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($cat['sale_description']) && ($cat['sale_type'] !== 'both')): ?>
                        <p class="text-muted mb-0 small"><?php echo nl2br(htmlspecialchars($cat['sale_description'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                   
                </div>
            </div>
            <?php endif; ?>

            <!-- Boutons Actions -->
            <div class="d-grid gap-3 mb-4">
                <button class="btn btn-cat w-100 py-3 mb-2 shadow-lg" onclick="document.getElementById('inquiryFormColumn').scrollIntoView({behavior: 'smooth'})">
                    <i class="fas fa-envelope mr-2"></i> Se renseigner
                </button>
                
                <a href="https://wa.me/15142695930?text=Bonjour, je souhaite réserver le chaton <?php echo urlencode($cat['name']); ?>" target="_blank" class="btn btn-success w-100 py-3 shadow-lg rounded-pill font-weight-bold">
                    <i class="fab fa-whatsapp mr-2"></i> Réservez par WhatsApp
                </a>
            </div>

            <!-- Informations Complémentaires (Nouveau Bloc) -->
            <!-- Informations Complémentaires (Masqué pour King/Queen) -->
            <?php if (!in_array($cat['status'], ['king', 'queen'])): ?>
            <div class="accordion shadow-sm rounded-lg overflow-hidden text-dark" id="accordionInfo">
                
                <!-- 1. Inclus avec l'adoption -->
                <div class="card border-0 mb-1">
                    <div class="card-header bg-white" id="headingIncluded">
                        <h2 class="mb-0">
                            <div class="text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-2">
                                <span><i class="fas fa-gift text-success mr-2"></i> Inclus avec l'adoption</span>
                                <i class="fas fa-chevron-down small"></i>
                            </div>
                        </h2>
                    </div>
                    <div id="collapseIncluded" class="collapse show">
                        <div class="card-body bg-light">
                            <p class="mb-2">En choisissant un de nos précieux, vous choisissez aussi un chaton :</p>
                            <ul class="list-unstyled mb-3 pl-2">
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Vacciné x 2 (8 et 12 semaines)</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Enregistré avec TICA</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Vermifugé</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Micropucé</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Socialisé avec enfants, chats et chiens</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Trousse de départ incluant nourriture, jouets, couverture avec l'odeur de la fratrie et plus</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Carnet de santé établi par un vétérinaire certifié</li>
                                <li class="mb-1"><i class="fas fa-check text-success mr-2"></i> Suivi post adoption</li>
                                <li class="mb-1"><i class="fas fa-shield-alt text-primary mr-2"></i> Garantie de santé de 10 jours (maladies virales)*</li>
                                <li class="mb-1"><i class="fas fa-shield-alt text-primary mr-2"></i> Garantie de santé de 1 an (malformations/héréditaire)*</li>
                                
                            </ul>
                            <small class="text-muted font-italic">*Selon les conditions de votre contrat de vente légal.</small>
                        </div>
                    </div>
                </div>

               

                <!-- 3. Options de paiement -->
                <div class="card border-0 mb-1">
                    <div class="card-header bg-white" id="headingPayment">
                        <h2 class="mb-0">
                            <div class="text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-2">
                                <span><i class="fas fa-credit-card text-warning mr-2"></i> Options de paiement</span>
                                <i class="fas fa-chevron-down small"></i>
                            </div>
                        </h2>
                    </div>
                    <div id="collapsePayment" class="collapse show">
                        <div class="card-body bg-light">
                            <div class="row">
                                <!-- Colonne gauche : modes de paiement -->
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0 pl-2">
                                        <li class="mb-2"><i class="fas fa-money-bill-wave text-success mr-2"></i> En argent</li>
                                        <li class="mb-2"><i class="fas fa-university text-info mr-2"></i> Virement bancaire</li>
                                        <li class="mb-2">
                                            <i class="fas fa-credit-card text-secondary mr-2"></i> Carte de débit
                                        </li>
                                    </ul>
                                </div>
                                <!-- Colonne droite : cartes de crédit -->
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fab fa-cc-mastercard text-dark" style="font-size: 24px; margin-right: 8px;"></i>
                                            <span>Crédit Master Card</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fab fa-cc-visa text-dark" style="font-size: 24px; margin-right: 8px;"></i>
                                            <span>Crédit Visa</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fab fa-cc-amex text-dark" style="font-size: 24px; margin-right: 8px;"></i>
                                            <span>Crédit American Express</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- 2. Livraison -->
                <div class="card border-0 mb-1">
                    <div class="card-header bg-white" id="headingDelivery">
                        <h2 class="mb-0">
                            <div class="text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-2">
                                <span><i class="fas fa-truck text-secondary mr-2"></i> Livraison</span>
                                <i class="fas fa-chevron-down small"></i>
                            </div>
                        </h2>
                    </div>
                   
                    <div id="collapseDelivery" class="collapse show">
                        <div class="card-body bg-light">
                            <p class="mb-2 font-weight-bold">La livraison est offerte au Canada, États-Unis et à l'internationale.</p>
                            <ol class="list-unstyled mb-0 pl-2">
                                <li class="mb-1"></i> 1- Ramassage à un point de rencontre à moins de 150km de la chatterie:  GRATUIT</li>
                                <li class="mb-1"></i> 2- Transport terrestre à un rayon de + de 150km:  à partir de 200$</li>
                                <li></i> 3- Transport aérien en cabine avec accompagnateur:  à partir de 1500$</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- 4. Droits de reproduction -->
                <div class="card border-0">
                    <div class="card-header bg-white" id="headingRights">
                        <h2 class="mb-0">
                            <div class="text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center p-2">
                                <span><i class="fas fa-venus-mars text-primary mr-2"></i> Droits de reproduction</span>
                                <i class="fas fa-chevron-down small"></i>
                            </div>
                        </h2>
                    </div>
                    <div id="collapseRights" class="collapse show">
                        <div class="card-body bg-light">
                            <?php if (!empty($cat['breeding_price_cad']) || !empty($cat['breeding_price_usd'])): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php if (!empty($cat['breeding_price_cad'])): ?>
                                        <span>Disponible à un cout additionnel de :</span>
                                        <li class="mb-2 d-flex align-items-center">
                                             
                                            <img src="https://flagcdn.com/32x24/ca.png" alt="Canada" style="height: 20px; margin-right: 10px;">
                                            <span class="font-weight-bold" style="font-size: 1.1em;"><?php echo number_format((float)$cat['breeding_price_cad'], 0, ',', ' '); ?> $ CAD</span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (!empty($cat['breeding_price_usd'])): ?>
                                        <li class="d-flex align-items-center">
                                           
                                            <img src="https://flagcdn.com/32x24/us.png" alt="USA" style="height: 20px; margin-right: 10px;">
                                            <span class="text-muted font-weight-bold"><?php echo number_format((float)$cat['breeding_price_usd'], 0, ',', ' '); ?> $ USD</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            <?php else: ?>
                                <p class="mb-0">Contactez-nous pour connaître le prix des droits de reproduction.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php endif; ?>
        </div>
    </div>


    
    <!-- Formulaire Contact Direct -->
    <div class="row mt-5" id="inquiryFormColumn">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white p-4 rounded-top">
                    <h3 class="mb-0">
                        <i class="fas fa-envelope-open-text"></i> 
                        <?php 
                        if (strtolower($cat['status']) == 'king') {
                            echo "INTÉRESSÉ PAR UNE SAILLIE ?";
                        } elseif (strtolower($cat['status']) == 'queen') {
                            echo "question à propos de cette QUEEN ?";
                        } else {
                            echo "Intéressé par ce chaton ?";
                        }
                        ?>
                    </h3>
                </div>
                <div class="card-body p-5">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="inquire">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Votre nom</label>
                                <input type="text" name="visitor_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="visitor_phone" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="visitor_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="4" required>Bonjour, je suis intéressé(e) par <?php echo htmlspecialchars($cat['name']); ?>...</textarea>
                        </div>
                        <!-- Cloudflare Turnstile -->
                        <div class="mb-3">
                            <div class="cf-turnstile" data-sitekey="<?php echo TURNSTILE_SITE_KEY; ?>"></div>
                        </div>
                        <button type="submit" class="btn btn-cat w-100 btn-lg">Envoyer ma demande</button>
                    </form>
                </div>
</section>
            </div>
        </div>
    </div>
</div>

<script>
function changeImage(src) {
    document.getElementById('mainImage').style.opacity = 0;
    setTimeout(() => {
        document.getElementById('mainImage').src = src;
        document.getElementById('mainImage').style.opacity = 1;
    }, 200);
}
</script>

<?php include 'includes/footer.php'; ?>
