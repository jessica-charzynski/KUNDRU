<?php
include('header.php');
?>

<html>
<body>
    <header class="header">
        <h1>Bestellbestätigung</h1>
    </header>
    <div class="container my-5 p-4 bg-white rounded shadow">
        <h2>Vielen Dank für deinen Einkauf!</h2>
        <p>Deine Bestellung wurde erfolgreich bearbeitet.</p>
        
        <?php if (isset($_GET['order_id'])): ?>
            <p>Deine Bestell-ID lautet: <strong><?php echo htmlspecialchars($_GET['order_id'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <?php endif; ?>
        
        <a href="/KUNDRU/" class="btn btn-secondary">Zurück zur Startseite</a>
        <a href="/KUNDRU/views/user_dashboard.php" class="btn btn-primary">Zu meinem Benutzerkonto</a>
    </div>
</body>
</html>

<?php include('footer.php'); ?>