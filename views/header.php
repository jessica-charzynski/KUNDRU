<?php
require_once(__DIR__ . '/../models/Cart.php');
require_once(__DIR__ . '/../models/User.php');

// Start session, if not yet started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create a cart instance and calculate the total number of items
$cart = new Cart();
$totalItems = array_sum(array_column($cart->getItems(), 'quantity')); 
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KUNDRU</title>
    
    <!-- Bootstrap CSS -->
    <link type="text/css" href="/KUNDRU/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Own Stylesheet -->
    <link type="text/css" href="/KUNDRU/assets/css/stylesheet.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/vnd.microsoft.icon" href="/KUNDRU/assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- Logo -->
    <a class="navbar-brand" href="/KUNDRU/index.php">
        <img src="/KUNDRU/assets/images/logo.png" alt="Logo">
    </a>
    <!-- Navbar Toggler Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar Collapse Content -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <!-- Navbar Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/KUNDRU/views/shop.php">Shop</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/KUNDRU/views/blog.php">Blog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/KUNDRU/views/about.php">Über das Projekt</a>
            </li>
        </ul>
    </div>
    <!-- Icons -->
    <ul class="navbar-nav ml-auto navbar-right d-flex flex-row" id="navbarIcons">
         <!-- User Dashboard -->
         <li class="nav-item">
            <?php if (User::isLoggedIn()): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a class="nav-link icon" href="/KUNDRU/views/admin_dashboard.php">
                        <i class="fas fa-user-cog"></i>
                    </a>
                <?php else: ?>
                    <a class="nav-link icon" href="/KUNDRU/views/user_dashboard.php">
                        <i class="fas fa-user"></i>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a class="nav-link icon" href="/KUNDRU/views/login.php">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>
        </li>
         <!-- Shopping Cart -->
        <li class="nav-item position-relative">
            <a class="nav-link icon" href="/KUNDRU/views/cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?php echo ($totalItems > 0) ? "($totalItems)" : ""; ?></span>
            </a>
        </li>
    </ul>
</nav>
    <!-- Bootstrap JS -->
    <script src="/KUNDRU/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/f315a95388.js" crossorigin="anonymous"></script>
</body>
</html>