<?php
include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    require_once('../config/config.php');
    require_once('../controllers/UserDashboardController.php');

    $userDashboardController = new UserDashboardController($conn);
    $address = $userDashboardController->getAddress($_SESSION['user_id']);
} else {
    header('Location: /KUNDRU/views/login.php');
    exit();
}
?>

<html>
<body>
    <header class="text-center mb-5 user-dashboard-header">
        <h1>Adresse aktualisieren</h1>
    </header>
    <div class="container address-form-container">
        <?php if ($address): ?>
            <div class="address-info">
                <h4>Aktuelle Adresse</h4>
                <p><strong>Straße:</strong> <?php echo htmlspecialchars($address['Street'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Stadt:</strong> <?php echo htmlspecialchars($address['City'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Postleitzahl:</strong> <?php echo htmlspecialchars($address['PostalCode'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Land:</strong> <?php echo htmlspecialchars($address['Country'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Es wurde noch keine Adresse gespeichert. Bitte füge deine Adresse hinzu.
            </div>
        <?php endif; ?>

        <form action="/KUNDRU/controllers/UserDashboardController.php" method="post">
            <div class="mb-3">
                <label for="street" class="form-label">Straße:</label>
                <input type="text" id="street" name="street" class="form-control" value="<?php echo htmlspecialchars($address['Street'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Stadt:</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($address['City'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="postalCode" class="form-label">Postleitzahl:</label>
                <input type="text" id="postalCode" name="postalCode" class="form-control" value="<?php echo htmlspecialchars($address['PostalCode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Land:</label>
                <input type="text" id="country" name="country" class="form-control" value="<?php echo htmlspecialchars($address['Country'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="text-center">
                <a href="/KUNDRU/views/user_dashboard.php" class="btn btn-secondary me-2">Zurück</a>
                <button type="submit" name="action" value="address" class="btn btn-success">Speichern</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php include('footer.php'); ?>