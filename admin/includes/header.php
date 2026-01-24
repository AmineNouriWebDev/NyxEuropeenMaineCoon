<?php require_once 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nyx European Maine Coon</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            font-family: 'Poppins', sans-serif;
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
            <a class="nav-link <?php echo strpos(basename($_SERVER['PHP_SELF']), 'blog') !== false ? 'active' : ''; ?>" href="blog.php">
                <i class="fas fa-newspaper"></i> Blog
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

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h4 class="mb-0">
            <?php 
            $pageName = basename($_SERVER['PHP_SELF'], '.php');
            if($pageName == 'dashboard') echo 'Tableau de bord';
            elseif($pageName == 'cats') echo 'Gestion des Chats';
            elseif($pageName == 'blog') echo 'Gestion du Blog';
            elseif($pageName == 'users') echo 'Gestion des Admins';
            else echo ucfirst($pageName);
            ?>
        </h4>
        <div class="d-flex align-items-center">
            <span class="me-3">Bonjour, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
            <a href="../" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-external-link-alt"></i> Voir le site</a>
        </div>
    </div>

    <!-- Content Container start -->
    <div class="container-fluid p-0">
