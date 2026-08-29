<?php
require_once '../includes/config.php';
session_start();

$error = '';
$success = '';

// Vérifier qu'on arrive bien depuis le login (2FA en attente)
if (empty($_SESSION['2fa_pending']) || empty($_SESSION['2fa_code'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = trim($_POST['code'] ?? '');
    $stored_code  = $_SESSION['2fa_code'];
    $expiry       = $_SESSION['2fa_expiry'];

    if (empty($entered_code)) {
        $error = "Veuillez saisir le code reçu.";
    } elseif (date('Y-m-d H:i:s') > $expiry) {
        // Code expiré
        session_destroy();
        header('Location: login.php?error=expired');
        exit;
    } elseif ($entered_code !== $stored_code) {
        $error = "Code incorrect. Vérifiez votre Telegram ou email.";
    } else {
        // ✅ Code correct → Authentification complète
        unset($_SESSION['2fa_pending'], $_SESSION['2fa_code'], $_SESSION['2fa_expiry']);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id']        = $_SESSION['2fa_user_id'];
        $_SESSION['admin_username']  = $_SESSION['2fa_username'];
        unset($_SESSION['2fa_user_id'], $_SESSION['2fa_username']);

        header('Location: dashboard.php');
        exit;
    }
}

$username = htmlspecialchars($_SESSION['2fa_username'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification 2FA - Nyx European Maine Coon</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 420px;
        }

        .icon-shield {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        h4 {
            color: #2d3748;
            font-weight: 600;
        }

        .info-text {
            color: #718096;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .code-input {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem;
            width: 100%;
            transition: border-color 0.3s;
            color: #2d3748;
        }

        .code-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .badges {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .badge-channel {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-telegram {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-email {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .timer {
            text-align: center;
            color: #e53e3e;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: #718096;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .back-link a:hover {
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="icon-shield">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <h4 class="text-center mb-1">Vérification en 2 étapes</h4>
        <p class="info-text">
            Bonjour <strong><?php echo $username; ?></strong>,<br>
            un code à 6 chiffres vient d'être envoyé via :
        </p>

        <div class="badges">
            <span class="badge-channel badge-telegram">
                <i class="fab fa-telegram"></i> Telegram
            </span>
            <span class="badge-channel badge-email">
                <i class="fas fa-envelope"></i> Email
            </span>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 mb-3" style="border-radius:10px;">
                <i class="fas fa-exclamation-circle me-1"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <input 
                    type="text" 
                    class="code-input" 
                    id="code"
                    name="code" 
                    maxlength="6" 
                    placeholder="••••••"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    autofocus
                    required
                >
            </div>
            <button type="submit" class="btn btn-verify">
                <i class="fas fa-check-circle me-2"></i> Confirmer l'accès
            </button>
        </form>

        <div class="timer" id="timer">
            <i class="fas fa-clock me-1"></i> Code valide pendant <span id="countdown">5:00</span>
        </div>

        <div class="back-link">
            <a href="login.php">
                <i class="fas fa-arrow-left me-1"></i> Recommencer la connexion
            </a>
        </div>
    </div>

    <script>
        // Auto-format: ne laisser que les chiffres
        document.getElementById('code').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Compte à rebours 5 minutes
        let seconds = 300;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(interval);
                countdownEl.textContent = '0:00';
                countdownEl.parentElement.textContent = '⚠️ Code expiré – veuillez recommencer.';
            } else {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                countdownEl.textContent = `${m}:${s.toString().padStart(2, '0')}`;
            }
        }, 1000);
    </script>
</body>
</html>
