<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$cat = get_cat_by_id($pdo, $id);
if (!$cat) {
    header('Location: index.php');
    exit;
}

// Récupération des images
$stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE cat_id = ? ORDER BY sort_order");
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Traitement AJAX pour Inquire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inquire') {
    header('Content-Type: application/json');
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST['phone']));
    $message = strip_tags(trim($_POST['message']));
    
    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Name and email are required.']);
        exit;
    }
    
    // Save to DB
    $stmt = $pdo->prepare("INSERT INTO inquiries (cat_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $name, $email, $phone, $message]);
    
    // Send Email
    $to = "nouri.medamine1987@gmail.com";
    $subject = "Inquiry for {$cat['name']} from $name";
    $body = "New inquiry received for {$cat['name']}.\n\nName: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message";
    $headers = "From: noreply@nyxcooncattery.com\r\nReply-To: $email";
    
    @mail($to, $subject, $body, $headers);
    
    echo json_encode(['success' => true]);
    exit;
}

include 'includes/header.php';
?>

<div style="height: 100px;"></div>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-4">
            <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php#kittens" class="text-muted">Kittens</a></li>
            <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page"><?php echo htmlspecialchars($cat['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Galerie Gauche -->
        <div class="col-lg-7 mb-5">
            <div class="position-relative overflow-hidden rounded-lg shadow-lg mb-3" style="height: 500px;">
                <img id="mainImage" src="<?php echo asset_url('img/' . ($images[0] ?? 'default.jpg')); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                <?php if ($cat['status'] !== 'available'): ?>
                    <div class="position-absolute top-0 right-0 bg-danger text-white py-2 px-4 font-weight-bold shadow" style="border-bottom-left-radius: 20px;">
                        <?php echo ucfirst($cat['status']); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="row px-1">
                <?php foreach ($images as $img): ?>
                    <div class="col-3 px-1 mb-2">
                        <img src="<?php echo asset_url('img/' . $img); ?>" class="img-fluid rounded cursor-pointer border shadow-sm thumb-img" 
                             style="height: 100px; width: 100%; object-fit: cover; opacity: 0.7; transition: 0.3s;"
                             onclick="changeImage(this.src)"
                             onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Infos Droite -->
        <div class="col-lg-5">
            <h1 class="display-4 font-weight-bold mb-2"><?php echo htmlspecialchars($cat['name']); ?></h1>
            <h3 class="text-muted h4 mb-4"><?php echo htmlspecialchars($cat['color']); ?> Maine Coon</h3>
            
            <div class="d-flex align-items-center mb-4">
                <span class="h2 text-primary font-weight-bold mb-0 mr-3">$<?php echo number_format($cat['price']); ?></span>
                <?php if($cat['status'] == 'available'): ?>
                    <span class="badge badge-success px-3 py-2 rounded-pill">Available</span>
                <?php else: ?>
                    <span class="badge badge-secondary px-3 py-2 rounded-pill"><?php echo ucfirst($cat['status']); ?></span>
                <?php endif; ?>
            </div>

            <div class="bg-light p-4 rounded-lg mb-4">
                <div class="row mb-3">
                    <div class="col-6"><span class="text-muted"><i class="fas fa-venus-mars mr-2"></i> Gender:</span></div>
                    <div class="col-6 font-weight-bold"><?php echo htmlspecialchars($cat['gender']); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-6"><span class="text-muted"><i class="fas fa-birthday-cake mr-2"></i> Age:</span></div>
                    <div class="col-6 font-weight-bold"><?php echo htmlspecialchars($cat['age_text']); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-6"><span class="text-muted"><i class="fas fa-paw mr-2"></i> Quality:</span></div>
                    <div class="col-6 font-weight-bold"><?php echo htmlspecialchars($cat['quality']); ?></div>
                </div>
                <div class="row">
                    <div class="col-6"><span class="text-muted"><i class="fas fa-weight mr-2"></i> Weight:</span></div>
                    <div class="col-6 font-weight-bold"><?php echo htmlspecialchars($cat['weight']); ?></div>
                </div>
            </div>

            <button class="btn btn-cat w-100 py-3 mb-3 shadow-lg" onclick="openInquireModal()">
                <i class="fas fa-envelope mr-2"></i> Inquire about <?php echo htmlspecialchars($cat['name']); ?>
            </button>
            <a href="https://wa.me/15142695930?text=I'm interested in <?php echo urlencode($cat['name']); ?>" target="_blank" class="btn btn-outline-success w-100 py-3 rounded-pill">
                <i class="fab fa-whatsapp mr-2"></i> Chat on WhatsApp
            </a>
        </div>
    </div>

    <!-- Description Riche (Article) -->
    <?php if (!empty($cat['description'])): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4 solid-border-bottom pb-2">About <?php echo htmlspecialchars($cat['name']); ?></h3>
            <div class="blog-content">
                <?php echo $cat['description']; // Contenu HTML riche ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Inquire -->
<div id="inquireModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-content bg-white p-5 rounded-lg shadow-lg position-relative" style="max-width: 500px; width: 90%; animation: slideIn 0.3s ease;">
        <button onclick="closeInquireModal()" class="position-absolute top-0 right-0 m-3 btn btn-link text-dark" style="font-size: 1.5rem;">&times;</button>
        
        <h3 class="font-weight-bold mb-1 text-center">Adoption Inquiry</h3>
        <p class="text-muted text-center mb-4">You are interested in <span class="text-primary font-weight-bold"><?php echo htmlspecialchars($cat['name']); ?></span></p>
        
        <form id="inquireForm">
            <input type="hidden" name="action" value="inquire">
            <div class="form-group">
                <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-4 mb-3" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control rounded-pill bg-light border-0 px-4 mb-3" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" class="form-control rounded-pill bg-light border-0 px-4 mb-3" placeholder="Phone Number">
            </div>
            <div class="form-group">
                <textarea name="message" class="form-control bg-light border-0 p-4 rounded-lg mb-4" rows="3" placeholder="Tell us a bit about your home..."></textarea>
            </div>
            <button type="submit" class="btn btn-cat w-100">Send Inquiry</button>
        </form>
    </div>
</div>

<!-- Toast Success -->
<div id="inquireToast" class="contact-toast">
    <i class="fas fa-check-circle"></i>
    <span>Request sent! We'll contact you shortly.</span>
</div>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

function openInquireModal() {
    document.getElementById('inquireModal').style.display = 'flex';
}

function closeInquireModal() {
    document.getElementById('inquireModal').style.display = 'none';
}

// Fermer modal si clic dehors
document.getElementById('inquireModal').addEventListener('click', function(e) {
    if (e.target === this) closeInquireModal();
});

// AJAX Form
document.getElementById('inquireForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    btn.disabled = true;

    fetch('', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            closeInquireModal();
            document.getElementById('inquireToast').classList.add('show');
            setTimeout(() => document.getElementById('inquireToast').classList.remove('show'), 5000);
            this.reset();
        } else {
            alert(data.message);
        }
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

// Auto-open if query param exists
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('action') === 'inquire') {
    openInquireModal();
}
</script>

<style>
@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.blog-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 15px 0; }
.blog-content { line-height: 1.8; color: #444; }
.contact-toast { position: fixed; top: 100px; left: 50%; transform: translateX(-50%) translateY(-20px); opacity: 0; transition: 0.5s; z-index: 10000; background: var(--secondary-color); color: white; padding: 15px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; gap: 10px; align-items: center; pointer-events: none; }
.contact-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>

<?php include 'includes/footer.php'; ?>
