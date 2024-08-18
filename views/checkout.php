<?php
include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /KUNDRU/views/login.php');
    exit();
}

require_once('../config/config.php');
require_once('../controllers/CheckoutController.php');
require_once('../controllers/CartController.php');
require_once('../controllers/OrderController.php');
require_once('../models/Order.php');
require_once('../models/OrderItem.php');
require_once('../models/User.php');

$checkoutController = new CheckoutController($conn);

// Retrieve user information
$userModel = new User($conn);
$user = $userModel->getUserById($_SESSION['user_id']);
$firstName = $user['FirstName'];
$lastName = $user['LastName'];

// Process address if the form has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateAddress') {
    $addressData = [
        'street' => $_POST['street'],
        'city' => $_POST['city'],
        'postalCode' => $_POST['postalCode'],
        'country' => $_POST['country']
    ];
    $userModel->addOrUpdateAddress($_SESSION['user_id'], $addressData);
}

// Retrieve address data, if available
$address = $userModel->getAddressByUserId($_SESSION['user_id']);

// Loading the shopping cart items
$cartController = new CartController($conn);
$cartItems = $cartController->viewCart();

// Calculation of the total amount
$totalSum = 0;
foreach ($cartItems as $item) {
    $totalSum += $item['artwork']['PriceOfArtprint'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'placeOrder') {
    try {
        $cartItemsProcessed = [];
        foreach ($_POST['cartItems'] as $item) {
            $cartItemsProcessed[] = [
                'artwork' => [
                    'ArtworkID' => intval($item['artwork']['ArtworkID']),
                    'PriceOfArtprint' => floatval($item['artwork']['PriceOfArtprint']),
                ],
                'quantity' => intval($item['quantity'])
            ];
        }

        // Process Stripe payment
        $stripeChargeId = $_POST['stripeChargeId'];

        // Create order
        $orderId = $checkoutController->placeOrder($_SESSION['user_id'], $totalSum, $stripeChargeId, $cartItemsProcessed);

        // Redirection to the Thank You page
        header('Location: /KUNDRU/views/thank_you.php?order_id=' . $orderId);
        exit();
    } catch (Exception $e) {
        echo "Fehler beim Erstellen der Bestellung: " . $e->getMessage();
    }
}
?>

<html>
<body>
    <!-- Header Section -->
    <header class="header">
        <h1>Kasse</h1>
    </header>
    <div class="container checkout-container my-5 p-4 bg-white rounded shadow">
        <div class="row g-4">
            <!-- Shopping cart summary -->
            <div class="col-md-6 col-checkout">
                <h2>Warenkorb-Zusammenfassung</h2>
                <ul class="list-group mb-4">
                    <?php foreach ($cartItems as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column flex-grow-1">
                                <h5><?php echo htmlspecialchars($item['artwork']['Title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <small><?php echo htmlspecialchars($item['artwork']['FirstName'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($item['artwork']['LastName'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                            <span class="quantity badge bg-primary rounded-pill"><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="price"><?php echo number_format($item['artwork']['PriceOfArtprint'] * $item['quantity'], 2); ?> €</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <h4 class="checkout-total">Gesamtsumme: <?php echo number_format($totalSum, 2); ?> €</h4>
                <!-- Navigationsbuttons -->
                <div class="mt-4">
                    <a href="/KUNDRU/views/cart.php" class="btn btn-secondary btn-checkout">Zurück zum Warenkorb</a>
                </div>
            </div>
            <!-- User details -->
            <div class="col-md-6 col-checkout">
                <h2>Benutzerdetails</h2>
                <p><strong>Vorname:</strong> <?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Nachname:</strong> <?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?></p>

                <form action="" method="post">
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
                    <button type="submit" name="action" value="updateAddress" class="btn btn-primary btn-checkout">Lieferadresse aktualisieren</button>
                </form>
                <!-- Payment method -->
                <h3 class="mt-4">Bezahlmethode</h3>
                <form id="payment-form" action="/KUNDRU/controllers/paymentController.php" method="POST">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="stripe" value="stripe" checked>
                        <label class="form-check-label" for="stripe">
                            Stripe
                        </label>
                    </div>
                    <!-- Stripe Elements -->
                    <div id="stripe-payment-details" class="mt-2">
                        <div id="card-element" class="form-control"></div>
                        <div id="card-errors" role="alert"></div>
                        <input type="hidden" name="action" value="placeOrder">
                        <input type="hidden" name="stripePaymentMethodId" id="stripePaymentMethodId" value="">
                        <?php foreach ($cartItems as $index => $item): ?>
                            <input type="hidden" name="cartItems[<?php echo $index; ?>][artwork][ArtworkID]" value="<?php echo htmlspecialchars($item['artwork']['ArtworkID'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="cartItems[<?php echo $index; ?>][artwork][PriceOfArtprint]" value="<?php echo htmlspecialchars($item['artwork']['PriceOfArtprint'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="cartItems[<?php echo $index; ?>][quantity]" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-success btn-checkout-last">Bestellung bestätigen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="/KUNDRU/assets/js/payment.js"></script> 
</body>
</html>

<?php include('footer.php'); ?>