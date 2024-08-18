<?php
require_once('../config/config.php');
require_once('../controllers/ProductController.php');
require_once('../controllers/CartController.php');

// Create instances of the controllers
$productController = new ProductController($conn);
$cartController = new CartController($conn);

// Get the product ID from the URL
$productId = isset($_GET['id']) ? $_GET['id'] : 0;

// Load the product data
$product = $productController->viewProduct($productId);

// Check if the product exists
if (!$product) {
    die("Produkt nicht gefunden.");
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $cartController->addToCart($productId, $quantity);
    header("Location: cart.php");
    exit;
}

// Include the header file
include('header.php');
?>

<html>
<body>
    <!-- Product View -->
    <section class="product-section">
        <div class="container">
                <div class="row">
                <!-- Left Side: Image and Back Button -->
                <div class="col-md-6 d-flex justify-content-center align-items-start position-relative">
                    <a href="javascript:history.back()" class="back-button">
                        <i class="fa fa-arrow-left"></i> Zurück
                    </a>
                    <img src="<?php echo $product['ImagePath']; ?>" class="img-fluid product-image" alt="<?php echo $product['Title']; ?>">
                </div>
                <!-- Right Side: Information -->
                <div class="col-md-6">
                    <h2><?php echo $product['Title']; ?></h2>
                    <p class="product-artist"><?php echo $product['FirstName'] . ' ' . $product['LastName']; ?></p>
                    <p><?php echo $product['Description']; ?></p>
                    <p><strong>Preis:</strong> <?php echo number_format($product['PriceOfArtprint'], 2); ?> €</p>
                    <form method="post">
                        <div class="input-group product-quantity-control">
                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1">
                            <div class="input-group-append">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">In den Warenkorb</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </section>
    <!-- About Our Art Prints -->
    <section class="about-art-prints">
        <div class="container">
            <hr class="divider">
            <h2>Über unsere Kunstdrucke</h2>
            <p>Unsere Kunstdrucke sind in einem klassischen A4-Format oder haben bei quadratischen Motiven die Maße 21x21 cm. Jeder Druck wird mit höchster Präzision und Liebe zum Detail in Handarbeit gefertigt. Wir legen großen Wert auf Nachhaltigkeit und verwenden umweltfreundliche Materialien sowohl für die Druckproduktion als auch für die Verpackung. Unsere Drucke werden sicher und zuverlässig verpackt, damit sie in einwandfreiem Zustand bei dir ankommen. Entdecke die Vielfalt unserer Kollektion und finde das perfekte Kunstwerk für dein Zuhause.</p>
        </div>
    </section>
    <!-- Discover More Products -->
    <section class="discover-products">
        <div class="container">
            <h2>Weitere Artikel entdecken</h2>
            <div class="row">
                <?php
                // Get the current product ID
                $currentProductID = isset($product['ID']) ? $product['ID'] : 0;
                
                // Get three random products excluding the current product
                $randomProducts = $productController->getRandomProducts(3, $currentProductID);
                foreach ($randomProducts as $product) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="product.php?id=<?php echo urlencode($product['ArtworkID']); ?>" class="card-link">
                                <img src="<?php echo htmlspecialchars($product['ImagePath'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['Title'], ENT_QUOTES, 'UTF-8'); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['Title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p class="card-text"><?php echo number_format($product['PriceOfArtprint'], 2); ?> €</p>
                                <a href="product.php?id=<?php echo urlencode($product['ArtworkID']); ?>" class="btn btn-primary">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</body>
</html>

<?php include('footer.php'); ?>