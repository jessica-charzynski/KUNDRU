<?php
require_once('../config/config.php');
require_once('../controllers/CartController.php');

// Creating an instance of CartController with the database connection
$controller = new CartController($conn);

// Handling quantity updates
if (isset($_GET['add'])) {
    $controller->addToCart($_GET['add']);
} elseif (isset($_GET['remove'])) {
    $controller->removeFromCart($_GET['remove']);
} elseif (isset($_GET['decrease'])) {
    $controller->addToCart($_GET['decrease'], -1);
}

// Loading cart items using the controller
$cartItems = $controller->viewCart();

// Calculating the total sum
$totalSum = 0;
foreach ($cartItems as $item) {
    $totalSum += $item['artwork']['PriceOfArtprint'] * $item['quantity'];
}

// Including header.php for the page layout
include('header.php');
?>

<!-- Header Section -->
<header class="header">
    <h1>Warenkorb</h1>
</header>

<!-- Cart Items Section -->
<section class="cart-section">
    <div class="container cart-container">
        <?php if (empty($cartItems)): ?>
            <p class="empty-cart-message">Dein Warenkorb ist leer.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th>Vorschau</th>
                            <th>Titel</th>
                            <th>Künstler</th>
                            <th>Preis</th>
                            <th>Menge</th>
                            <th>Gesamt</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <?php
                            // Remove item if quantity is 0
                            if ($item['quantity'] == 0) {
                                $controller->removeFromCart($item['artwork']['ArtworkID']);
                                continue;
                            }
                            ?>
                            <tr>
                                <td data-label="Vorschau"><img src="<?php echo $item['artwork']['ImagePath']; ?>" class="img-fluid" alt="<?php echo $item['artwork']['Title']; ?>"></td>
                                <td data-label="Titel"><?php echo $item['artwork']['Title']; ?></td>
                                <td data-label="Künstler"><?php echo $item['artwork']['FirstName'] . ' ' . $item['artwork']['LastName']; ?></td>
                                <td data-label="Preis"><?php echo number_format($item['artwork']['PriceOfArtprint'], 2); ?> €</td>
                                <td data-label="Menge">
                                    <div class="input-group quantity-control">
                                        <a href="cart.php?decrease=<?php echo $item['artwork']['ArtworkID']; ?>" class="btn btn-outline-secondary">-</a>
                                        <input type="text" class="form-control text-center" value="<?php echo $item['quantity']; ?>" readonly>
                                        <a href="cart.php?add=<?php echo $item['artwork']['ArtworkID']; ?>" class="btn btn-outline-secondary">+</a>
                                    </div>
                                </td>
                                <td data-label="Gesamt"><?php echo number_format($item['artwork']['PriceOfArtprint'] * $item['quantity'], 2); ?> €</td>
                                <td data-label="Aktionen">
                                    <a href="cart.php?remove=<?php echo $item['artwork']['ArtworkID']; ?>" class="btn btn-danger">Entfernen</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-center total-row totalsum">
                                Gesamtsumme: <?php echo number_format($totalSum, 2); ?> €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Action Buttons Section -->
            <div class="cart-buttons text-center mt-4">
                <a href="/KUNDRU/views/shop.php" class="btn btn-secondary mx-2">Weiter einkaufen</a>
                <?php if (User::isLoggedIn()): ?>
                    <a href="/KUNDRU/views/checkout.php" class="btn btn-success mx-2">Zur Kasse</a>
                <?php else: ?>
                    <a href="/KUNDRU/views/login.php" class="btn btn-success mx-2">Zur Kasse</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
</body>
</html>

<?php include('footer.php'); ?>