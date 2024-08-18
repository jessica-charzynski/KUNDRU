<?php
include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    require_once('../config/config.php');
    require_once('../controllers/AdminDashboardController.php');

    $adminDashboardController = new AdminDashboardController($conn);
    $user = $adminDashboardController->getUserDetails($_SESSION['user_id']);

    $firstName = $user['FirstName'];
    $lastName = $user['LastName'];
} else {
    header('Location: /KUNDRU/views/login.php');
    exit();
}
?>

<html>
<body>
    <!-- Admin Dashboard Header -->
    <header class="text-center mb-5 admin-dashboard-header">
        <h1>Adminbereich</h1>
        <p class="lead">Hallo <?php echo htmlspecialchars($firstName . ' ' . $lastName, ENT_QUOTES, 'UTF-8'); ?></p>
    </header>
    <div class="admin-dashboard-container admin-overview my-5">    
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- General settings -->
                <div class="admin-dashboard-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="admin-dashboard-card-title">Allgemeine Einstellungen</h2>
                        <form action="/KUNDRU/controllers/UserDashboardController.php" method="post" class="d-inline">
                            <button type="submit" name="action" value="logout" class="btn btn-primary">Ausloggen</button>
                        </form> 
                    </div>
                </div>
                <hr class="dashboard-divider">
                <!-- Manage artists -->
                <div class="admin-dashboard-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="admin-dashboard-card-title">Künstler*innen verwalten</h2>
                        <a href="/KUNDRU/views/manage_artists.php" class="btn btn-secondary">weiter</a>
                    </div>
                </div>
                <hr class="dashboard-divider">
                <!-- Manage products -->
                <div class="admin-dashboard-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="admin-dashboard-card-title">Produkte verwalten</h2>
                        <a href="/KUNDRU/views/manage_products.php" class="btn btn-secondary">weiter</a>
                    </div>
                </div>
                <hr class="dashboard-divider">     
                <!-- Manage users -->
                <div class="admin-dashboard-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="admin-dashboard-card-title">Benutzerkonten verwalten</h2>
                        <a href="/KUNDRU/views/manage_users.php" class="btn btn-secondary">weiter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/KUNDRU/assets/js/confirmation.js"></script>
    <script src="/KUNDRU/assets/js/displayErrors.js"></script>
    <script src="/KUNDRU/assets/js/validation.js"></script>
</body>
</html>

<?php include('footer.php'); ?>