<?php require_once 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nyx European Maine Coon</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fuzzy+Bubbles:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e67e22;
            --accent-color: #3498db;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Fuzzy Bubbles', cursive;
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: white;
            padding-top: 1rem;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand span {
            color: var(--secondary-color);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid var(--secondary-color);
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .top-bar {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        Nyx<span>Admin</span>
    </a>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos(basename($_SERVER['PHP_SELF']), 'cats') !== false ? 'active' : ''; ?>" href="cats.php">
                <i class="fas fa-cat"></i> Mes Chats
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos(basename($_SERVER['PHP_SELF']), 'litters') !== false ? 'active' : ''; ?>" href="litters.php">
                <i class="fas fa-baby-carriage"></i> Portées
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'requests.php' ? 'active' : ''; ?>" href="requests.php">
                <i class="fas fa-envelope-open-text"></i> Demandes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos(basename($_SERVER['PHP_SELF']), 'vip_requests') !== false ? 'active' : ''; ?>" href="vip_requests.php">
                <i class="fas fa-gem"></i> Demandes d'adoption
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact_messages.php' ? 'active' : ''; ?>" href="contact_messages.php">
                <i class="fas fa-inbox"></i> Messages Contact
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos(basename($_SERVER['PHP_SELF']), 'users') !== false ? 'active' : ''; ?>" href="users.php">
                <i class="fas fa-users-cog"></i> Administrateurs
            </a>
        </li>
        <li class="nav-item mt-5">
            <a class="nav-link text-danger" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </li>
    </ul>
</nav>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Content Wrapper -->
<div class="main-content" id="mainContent">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="d-flex align-items-center">
            <button class="btn btn-primary d-md-none me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0 fs-5 fs-md-4">
                <?php 
                $pageName = basename($_SERVER['PHP_SELF'], '.php');
                if($pageName == 'dashboard') echo 'Tableau de bord';
                elseif($pageName == 'cats') echo 'Gestion des Chats';
                elseif($pageName == 'users') echo 'Gestion des Admins';
                else echo ucfirst($pageName);
                ?>
            </h4>
        </div>
        <div class="d-flex align-items-center">
            <span class="me-3 d-none d-md-inline">Bonjour, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
            <a href="../" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-external-link-alt"></i> <span class="d-none d-sm-inline">Voir le site</span></a>
        </div>
    </div>

    <!-- Content Container start -->
    <div class="container-fluid p-0">
