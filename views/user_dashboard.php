<?php
include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Read error messages from the URL
$errorMessages = [];
$successMessages = [];

if (isset($_GET['error'])) {
    $errorType = $_GET['error'];
    switch ($errorType) {
        case 'password':
            $errorMessages['password'] = 'Das aktuelle Passwort ist falsch.';
            break;
        case 'update':
            $errorMessages['update'] = 'Das Profil konnte nicht aktualisiert werden.';
            break;
        case 'delete':
            $errorMessages['delete'] = 'Das Konto konnte nicht gelöscht werden.';
            break;
        case 'incorrect_password':
            $errorMessages['incorrect_password'] = 'Die Aktualisierung war wegen falscher Eingabe nicht erfolgreich. Bitte probiere es nochmal.';
            break;
        case 'short_password':
            $errorMessages['short_password'] = 'Das neue Passwort muss mindestens 8 Zeichen lang sein.';
            break;
        default:
            $errorMessages['general'] = 'Ein unbekannter Fehler ist aufgetreten.';
            break;
    }
}

if (isset($_GET['success']) && $_GET['success'] === 'password_update') {
    $successMessages['password_update'] = 'Ihr Passwort wurde erfolgreich geändert.';
}

if (isset($_SESSION['user_id'])) {
    require_once('../config/config.php');
    require_once('../controllers/UserDashboardController.php');

    $userDashboardController = new UserDashboardController($conn);
    $user = $userDashboardController->getUserDetails($_SESSION['user_id']);
    $address = $userDashboardController->getAddress($_SESSION['user_id']);
    $orders = $userDashboardController->getUserOrders($_SESSION['user_id']);

    $firstName = $user['FirstName'];
    $lastName = $user['LastName'];
} else {
    header('Location: /KUNDRU/views/login.php');
    exit();
}
?>

<html>
<body>
    <!-- User Account Section -->
    <header class="text-center mb-5 user-dashboard-header">
        <h1>Mein Benutzerkonto</h1>
        <p class="lead">Hallo <?php echo htmlspecialchars($firstName . ' ' . $lastName, ENT_QUOTES, 'UTF-8'); ?></p>
    </header>

    <div class="user-dashboard-container my-5">    
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Error messages container -->
                <div class="error-messages"></div>
                <!-- General settings -->
                <div class="user-dashboard-card mb-4">
                    <div class="card-header">
                        <h2 class="user-dashboard-card-title">Allgemeine Einstellungen</h2>
                    </div>
                    <div class="user-dashboard-card-body text-center">
                        <!-- Form for logging out -->
                        <form action="/KUNDRU/controllers/UserDashboardController.php" method="post" class="d-inline">
                            <button type="submit" name="action" value="logout" class="btn btn-primary user-dashboard-btn me-2">Ausloggen</button>
                        </form>
                        <!-- Delete account form -->
                        <form id="delete-form" action="/KUNDRU/controllers/UserDashboardController.php" method="post" style="display: none;">
                            <input type="hidden" name="action" value="delete">
                        </form>

                        <button type="button" class="btn btn-danger user-dashboard-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">Konto löschen</button>
                    </div>
                </div>
                <div class="user-dashboard-divider"></div>
                <!-- Update password -->
                <div class="user-dashboard-card mb-4">
                    <div class="card-header">
                        <h2 class="user-dashboard-card-title">Passwort aktualisieren</h2>
                    </div>
                    <div class="user-dashboard-card-body">
                        <form id="updatePasswordForm" action="/KUNDRU/controllers/UserDashboardController.php" method="post">
                            <div class="mb-3">
                                <label for="current_password" class="form-label text-center d-block">Aktuelles Passwort eingeben:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control user-dashboard-input">
                                <div id="current_passwordFeedback" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label text-center d-block">Neues Passwort eingeben:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control user-dashboard-input">
                                <div id="new_passwordFeedback" class="invalid-feedback"></div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="action" value="update_password" class="btn btn-success user-dashboard-btn">Speichern</button>
                            </div>
                            <div id="formFeedback" class="alert alert-danger mt-3" role="alert" style="display: none;"></div>
                        </form>
                    </div>
                </div>
                <div class="user-dashboard-divider"></div>
                <!-- Update address -->
                <div class="user-dashboard-card mb-4">
                    <div class="card-header">
                        <h2 class="user-dashboard-card-title">Adresse aktualisieren</h2>
                    </div>
                    <div class="user-dashboard-card-body text-center">
                        <a href="/KUNDRU/views/address_form.php" class="btn btn-secondary user-dashboard-btn">Weiter</a>
                    </div>
                </div>
                <div class="user-dashboard-divider"></div>
                <!-- My orders -->
                <div class="user-dashboard-card mb-4">
                    <div class="card-header">
                        <h2 class="user-dashboard-card-title">Meine Bestellungen</h2>
                    </div>
                    <div class="user-dashboard-card-body">
                        <?php if (empty($orders)): ?>
                            <p style="text-align: center;">Noch keine Bestellungen vorhanden</p>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Bestellnummer</th>
                                        <th>Datum</th>
                                        <th>Status</th>
                                        <th>Gesamtbetrag</th>
                                        <th>Aktionen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($orders as $order) {
                                        $formattedTotalAmount = number_format($order['TotalAmount'], 2, ',', '.') . ' €';
                                        if (!in_array($order['OrderID'], $displayedOrders)) {
                                            $displayedOrders[] = $order['OrderID'];
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['OrderID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['OrderDate'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['Status'] === 'completed' ? 'bezahlt' : 'ausstehend', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($formattedTotalAmount, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" data-order-id="<?php echo htmlspecialchars($order['OrderID'], ENT_QUOTES, 'UTF-8'); ?>">Details</button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal for account -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konto löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bist du dir sicher, dass du dein Konto löschen möchtest?<br>Diese Aktion kann nicht rückgängig gemacht werden.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Konto löschen</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for successful password change -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Änderung erfolgreich</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ihr Passwort wurde erfolgreich geändert.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for incorrect password change -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Fehler bei der Passwortänderung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                Die Aktualisierung war aufgrund einer falschen Eingabe nicht erfolgreich. Bitte versuche es noch einmal.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal for order details -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="orderDetailsModalLabel">Bestelldetails</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success') : ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            <?php endif; ?>
            
            <?php if (isset($errorMessages['incorrect_password'])): ?>
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            <?php endif; ?>

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                document.getElementById('delete-form').submit();
            });

            var orderDetailsModal = document.getElementById('orderDetailsModal');
            orderDetailsModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var orderId = button.getAttribute('data-order-id');
                
                // Fetch order details via AJAX
                fetch(`/KUNDRU/controllers/UserDashboardController.php?action=get_order_details&order_id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('orderDetailsContent').innerHTML = `<p>Fehler beim Laden der Bestelldetails: ${data.error}</p>`;
                            return;
                        }

                        var content = `
                            <h5>Bestellnummer: ${data.orderID}</h5>
                            <p><strong>Datum:</strong> ${data.orderDate}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                            <p><strong>Gesamtbetrag:</strong> ${data.totalAmount}</p>
                            <h5>Bestellpositionen:</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titel</th>
                                        <th>Anzahl</th>
                                        <th>Preis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.items.map(item => `
                                        <tr>
                                            <td>${item.ArtworkTitle || 'Kein Titel verfügbar'}</td>
                                            <td>${item.quantity}</td>
                                            <td>${item.price}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                        document.getElementById('orderDetailsContent').innerHTML = content;
                    })
                    .catch(error => {
                        console.error('Fehler beim Laden der Bestelldetails:', error);
                        document.getElementById('orderDetailsContent').innerHTML = '<p>Fehler beim Laden der Bestelldetails.</p>';
                    });
            });
        });    
    </script>
    <script src="/KUNDRU/assets/js/displayErrors.js"></script>
    <script src="/KUNDRU/assets/js/validation.js"></script>
</body>
</html>

<?php include('footer.php'); ?>