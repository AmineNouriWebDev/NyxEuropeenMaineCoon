<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Récupération des portées actives avec les parents du king et de la queen
$sql = "SELECT l.*, 
        f.name as father_name, f.id as father_id,
        m.name as mother_name, m.id as mother_id,
        f_father.id as father_father_id, f_father.name as father_father_name,
        f_mother.id as father_mother_id, f_mother.name as father_mother_name,
        m_father.id as mother_father_id, m_father.name as mother_father_name,
        m_mother.id as mother_mother_id, m_mother.name as mother_mother_name
        FROM upcoming_litters l
        LEFT JOIN chats f ON l.father_id = f.id
        LEFT JOIN chats m ON l.mother_id = m.id
        LEFT JOIN chats f_father ON f.father_id = f_father.id
        LEFT JOIN chats f_mother ON f.mother_id = f_mother.id
        LEFT JOIN chats m_father ON m.father_id = m_father.id
        LEFT JOIN chats m_mother ON m.mother_id = m_mother.id
        WHERE l.is_active = 1
        ORDER BY l.created_at DESC";
$litters = $pdo->query($sql)->fetchAll();

// Récupérer toutes les couleurs pour le mapping code -> nom
$colors_map = [];
$colors_query = $pdo->query("SELECT code, name_fr FROM colors");
while ($color = $colors_query->fetch()) {
    $colors_map[$color['code']] = $color['name_fr'];
}

// Helper pour image principale
function get_main_image($pdo, $cat_id) {
    if (!$cat_id) return 'default.jpg';
    $stmt = $pdo->prepare("SELECT image_path FROM cat_images WHERE cat_id = ? ORDER BY sort_order LIMIT 1");
    $stmt->execute([$cat_id]);
    return $stmt->fetchColumn() ?: 'default.jpg';
}
?>

<!-- Hero Section with Gradient -->
<div class="litter-hero">
    <div class="container">
        <div class="hero-content text-center">
            <span class="hero-badge">
                <i class="fas fa-heart"></i> Nos Futurs Mariages
            </span>
            <h1 class="hero-title">
                Portées <span class="gradient-text">À Venir</span>
            </h1>
            <p class="hero-subtitle">
                Découvrez nos futurs mariages d'exception et réservez votre chaton de rêve
            </p>
        </div>
    </div>
</div>

<section class="litter-section">
    <div class="container">
        <?php if (empty($litters)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-heart-broken"></i>
                </div>
                <h3>Aucune portée annoncée pour le moment</h3>
                <p>Revenez bientôt pour découvrir nos futurs mariages d'exception !</p>
            </div>
        <?php else: ?>
            <?php foreach ($litters as $index => $litter): ?>
                <?php 
                $father_img = get_main_image($pdo, $litter['father_id']);
                $mother_img = get_main_image($pdo, $litter['mother_id']);
                $father_img_url = asset_url('img/' . $father_img);
                $mother_img_url = asset_url('img/' . $mother_img);
                ?>
                
                <div class="modern-litter-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <!-- Card Header -->
                    <div class="litter-card-header">
                        <div class="season-badge">
                            <i class="fas fa-calendar-star"></i>
                            <?php echo htmlspecialchars($litter['season_text']); ?>
                        </div>
                        <h2 class="litter-title">
                            <span class="cat-name mother-name"><?php echo htmlspecialchars($litter['mother_name']); ?></span>
                            <span class="heart-divider">
                                <i class="fas fa-heart"></i>
                            </span>
                            <span class="cat-name father-name"><?php echo htmlspecialchars($litter['father_name']); ?></span>
                        </h2>
                    </div>

                    <!-- Card Body -->
                    <div class="litter-card-body">
                        <div class="row align-items-stretch">
                            <!-- King Image -->
                            <div class="col-lg-5">
                                <div class="cat-showcase king-showcase">
                                    <div class="cat-image-wrapper" data-img="<?php echo $father_img_url; ?>" onclick="openImageModal(this.dataset.img)">
                                        <img src="<?php echo $father_img_url; ?>" alt="<?php echo htmlspecialchars($litter['father_name']); ?>" class="cat-main-image">
                                        <div class="image-overlay">
                                            <div class="overlay-content">
                                                <i class="fas fa-search-plus"></i>
                                                <span>Voir en grand</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cat-info-panel">
                                        <div class="cat-badge king-badge">
                                            <i class="fas fa-crown"></i> King
                                        </div>
                                        <h4 class="cat-profile-name"><?php echo htmlspecialchars($litter['father_name']); ?></h4>
                                        <div class="cat-actions">
                                            <a href="chat_details.php?id=<?php echo $litter['father_id']; ?>" class="btn-profile">
                                                <i class="fas fa-info-circle"></i> Voir le profil
                                            </a>
                                            <?php if (!empty($litter['father_father_id']) || !empty($litter['father_mother_id'])): ?>
                                            <div class="parent-buttons">
                                                <?php if (!empty($litter['father_father_id'])): ?>
                                                <a href="chat_details.php?id=<?php echo $litter['father_father_id']; ?>" class="btn-parent" title="Papa de <?php echo htmlspecialchars($litter['father_name']); ?>">
                                                    <i class="fas fa-male"></i> Papa
                                                </a>
                                                <?php endif; ?>
                                                <?php if (!empty($litter['father_mother_id'])): ?>
                                                <a href="chat_details.php?id=<?php echo $litter['father_mother_id']; ?>" class="btn-parent" title="Maman de <?php echo htmlspecialchars($litter['father_name']); ?>">
                                                    <i class="fas fa-female"></i> Maman
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Center Info -->
                            <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                <div class="center-divider">
                                    <div class="divider-icon">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="divider-line"></div>
                                </div>
                            </div>

                            <!-- Queen Image -->
                            <div class="col-lg-5">
                                <div class="cat-showcase queen-showcase">
                                    <div class="cat-image-wrapper" data-img="<?php echo $mother_img_url; ?>" onclick="openImageModal(this.dataset.img)">
                                        <img src="<?php echo $mother_img_url; ?>" alt="<?php echo htmlspecialchars($litter['mother_name']); ?>" class="cat-main-image">
                                        <div class="image-overlay">
                                            <div class="overlay-content">
                                                <i class="fas fa-search-plus"></i>
                                                <span>Voir en grand</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cat-info-panel">
                                        <div class="cat-badge queen-badge">
                                            <i class="fas fa-heart"></i> Queen
                                        </div>
                                        <h4 class="cat-profile-name"><?php echo htmlspecialchars($litter['mother_name']); ?></h4>
                                        <div class="cat-actions">
                                            <a href="chat_details.php?id=<?php echo $litter['mother_id']; ?>" class="btn-profile">
                                                <i class="fas fa-info-circle"></i> Voir le profil
                                            </a>
                                            <?php if (!empty($litter['mother_father_id']) || !empty($litter['mother_mother_id'])): ?>
                                            <div class="parent-buttons">
                                                <?php if (!empty($litter['mother_father_id'])): ?>
                                                <a href="chat_details.php?id=<?php echo $litter['mother_father_id']; ?>" class="btn-parent" title="Papa de <?php echo htmlspecialchars($litter['mother_name']); ?>">
                                                    <i class="fas fa-male"></i> Papa
                                                </a>
                                                <?php endif; ?>
                                                <?php if (!empty($litter['mother_mother_id'])): ?>
                                                <a href="chat_details.php?id=<?php echo $litter['mother_mother_id']; ?>" class="btn-parent" title="Maman de <?php echo htmlspecialchars($litter['mother_name']); ?>">
                                                    <i class="fas fa-female"></i> Maman
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description & Colors -->
                        <div class="litter-details">
                            <?php if (!empty($litter['description'])): ?>
                            <div class="litter-description">
                                <div class="description-icon">
                                    <i class="fas fa-comment-dots"></i>
                                </div>
                                <div class="description-text">
                                    <?php echo $litter['description']; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($litter['expected_colors'])): ?>
                            <div class="expected-colors-section">
                                <h5 class="colors-title">
                                    <i class="fas fa-palette"></i> Couleurs Probables
                                </h5>
                                <div class="colors-content">
                                    <?php 
                                    $color_codes = explode(', ', $litter['expected_colors']);
                                    foreach ($color_codes as $code):
                                        $code = trim($code);
                                        if (!empty($code) && isset($colors_map[$code])):
                                    ?>
                                        <span class="color-badge">
                                            <span class="color-code"><?php echo htmlspecialchars($code); ?></span>
                                            <span class="color-name"><?php echo htmlspecialchars($colors_map[$code]); ?></span>
                                        </span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- CTA Button -->
                        <div class="litter-cta">
                            <button onclick="openWaitingListModal(<?php echo $litter['id']; ?>, '<?php echo htmlspecialchars($litter['season_text']); ?>', '<?php echo htmlspecialchars($litter['father_name']); ?>', '<?php echo htmlspecialchars($litter['mother_name']); ?>')" class="btn-waiting-list">
                                <span class="btn-icon"><i class="fas fa-clipboard-list"></i></span>
                                <span class="btn-text">Rejoindre la Liste d'Attente</span>
                                <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Liste d'Attente -->
<div id="waitingModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h4 class="modal-title">
                <i class="fas fa-pencil-alt"></i> Liste d'Attente
            </h4>
            <button onclick="closeWaitingModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <p class="modal-subtitle">
                Inscription pour la portée : <strong id="modalLitterName"></strong>
            </p>
            <p class="modal-parents" style="text-align: center; margin-bottom: 20px; color: #4a5568; display: none;">
                Parents : <strong id="modalParents" style="color: #667eea;"></strong>
            </p>
            
            <form id="waitingForm">
                <input type="hidden" name="litter_id" id="litterIdInput">
                
                <div class="form-group">
                    <label>Votre Nom</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" required placeholder="email@exemple.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Téléphone</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" placeholder="(555) 555-5555">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Vos Préférences / Message</label>
                    <div class="input-wrapper">
                        <i class="fas fa-comment"></i>
                        <textarea name="message" rows="3" placeholder="Préférence de sexe, couleur, caractère..."></textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <span>Confirmer l'inscription</span>
                    <i class="fas fa-check"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <button class="image-modal-close" onclick="closeImageModal()">
        <i class="fas fa-times"></i>
    </button>
    <img class="image-modal-content" id="modalImage" alt="Image agrandie">
</div>

<style>
/* ========== Hero Section ========== */
.litter-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 100px 0 80px;
    position: relative;
    overflow: hidden;
}

.litter-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    font-family: 'Vijaya', serif;
}

.gradient-text {
    background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    max-width: 600px;
    margin: 0 auto;
}

/* ========== Main Section ========== */
.litter-section {
    padding: 60px 0;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
}

/* ========== Modern Litter Card ========== */
.modern-litter-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 60px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-litter-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
}

/* Card Header */
.litter-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 30px;
    text-align: center;
    position: relative;
}

.season-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.litter-title {
    font-size: 2.5rem;
    color: white;
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
}

.cat-name {
    font-family: 'Vijaya', serif;
}

.heart-divider {
    color: #ff6b9d;
    font-size: 1.5rem;
    animation: heartbeat 1.5s infinite;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Card Body */
.litter-card-body {
    padding: 40px 30px;
}

/* Cat Showcase */
.cat-showcase {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.cat-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transition: all 0.4s ease;
}

.cat-image-wrapper:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
}

.cat-main-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.4s ease;
}

.cat-image-wrapper:hover .cat-main-image {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cat-image-wrapper:hover .image-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
}

.overlay-content i {
    font-size: 3rem;
    margin-bottom: 10px;
    display: block;
}

.overlay-content span {
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Cat Info Panel */
.cat-info-panel {
    margin-top: 20px;
    text-align: center;
}

.cat-badge {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.king-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.queen-badge {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.cat-profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #2d3748;
    font-family: 'Vijaya', serif;
}

.cat-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-profile {
    display: inline-block;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.parent-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-parent {
    padding: 8px 16px;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.btn-parent:hover {
    background: rgba(102, 126, 234, 0.2);
    color: #5568d3;
    text-decoration: none;
}

/* Center Divider */
.center-divider {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    justify-content: center;
}

.divider-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 20px;
}

.divider-line {
    width: 2px;
    flex: 1;
    background: linear-gradient(180deg, #667eea 0%, transparent 100%);
}

/* Litter Details */
.litter-details {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
}

.litter-description {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px;
}

.description-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.description-text {
    flex: 1;
    color: #4a5568;
    line-height: 1.8;
}

.expected-colors-section {
    padding: 25px;
    background: white;
    border-radius: 16px;
    border: 2px solid #e9ecef;
}

.colors-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.colors-title i {
    color: #667eea;
}

.colors-content {
    color: #4a5568;
}

.colors-content ul {
    padding-left: 20px;
    column-count: 2;
    column-gap: 20px;
}

/* CTA Button */
.litter-cta {
    margin-top: 30px;
    text-align: center;
}

.btn-waiting-list {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    padding: 18px 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s ease;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-waiting-list::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-waiting-list:hover::before {
    left: 100%;
}

.btn-waiting-list:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.btn-arrow {
    transition: transform 0.3s ease;
}

.btn-waiting-list:hover .btn-arrow {
    transform: translateX(5px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.empty-state h3 {
    color: #2d3748;
    margin-bottom: 15px;
}

.empty-state p {
    color: #718096;
}

/* ========== Modals ========== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s;
}

.modal-container {
    background: white;
    border-radius: 24px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s;
}

.modal-header {
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 24px 24px 0 0;
}

.modal-title {
    color: white;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body {
    padding: 30px;
}

.modal-subtitle {
    text-align: center;
    color: #718096;
    margin-bottom: 30px;
}

.modal-subtitle strong {
    color: #667eea;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #cbd5e0;
}

.input-wrapper input,
.input-wrapper textarea {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.input-wrapper textarea {
    resize: vertical;
    padding-top: 15px;
}

.input-wrapper input:focus,
.input-wrapper textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Image Modal */
.image-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 10001;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s;
    padding: 20px;
}

.image-modal-content {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    animation: zoomIn 0.3s;
    border-radius: 12px;
}

.image-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.5rem;
}

.image-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive */
@media (max-width: 991px) {
    .center-divider {
        display: none;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .litter-title {
        font-size: 2rem;
    }
    
    .colors-content ul {
        column-count: 1;
    }
}

/* Color Badges Styling */
.colors-content {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.color-badge {
    display: inline-flex;
    align-items: center;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 5px 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.color-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #cbd5e0;
}

.color-code {
    font-weight: 800;
    color: #667eea;
    margin-right: 8px;
    padding-right: 8px;
    border-right: 1px solid #e2e8f0;
}

.color-name {
    color: #4a5568;
    font-weight: 500;
    font-size: 0.95rem;
}


@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .litter-title {
        font-size: 1.5rem;
    }
    
    .btn-waiting-list {
        padding: 15px 30px;
        font-size: 1rem;
    }
}
</style>

<script>
// Waiting List Modal
function openWaitingListModal(litterId, seasonText, fatherName, motherName) {
    document.getElementById('waitingModal').style.display = 'flex';
    document.getElementById('modalLitterName').textContent = seasonText;
    // Update parents info
    const parentsText = (fatherName && motherName) ? fatherName + ' & ' + motherName : '';
    const parentsElement = document.getElementById('modalParents');
    if(parentsElement) {
        parentsElement.textContent = parentsText;
        parentsElement.parentElement.style.display = parentsText ? 'block' : 'none';
    }
    
    document.getElementById('litterIdInput').value = litterId;
    document.body.style.overflow = 'hidden';
}

function closeWaitingModal() {
    document.getElementById('waitingModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Image Modal
function openImageModal(imageSrc) {
    if (!imageSrc) return;
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    
    // Ensure image loads
    img.onload = function() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };
    img.src = imageSrc;
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close modals on outside click
window.onclick = function(event) {
    const waitingModal = document.getElementById('waitingModal');
    const imageModal = document.getElementById('imageModal');
    
    if (event.target == waitingModal) {
        closeWaitingModal();
    }
    if (event.target == imageModal) {
        closeImageModal();
    }
}

// Close with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeWaitingModal();
        closeImageModal();
    }
});

// AJAX Form Submission
document.getElementById('waitingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    btn.disabled = true;

    const formData = new FormData(this);
    formData.append('action', 'join_waiting_list');

    fetch('ajax_litter_waitlist.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            alert('✅ Inscription réussie ! Nous vous contacterons bientôt.');
            closeWaitingModal();
            this.reset();
        } else {
            alert('❌ Erreur : ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('❌ Une erreur est survenue.');
    })
    .finally(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
