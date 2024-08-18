<?php
include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /KUNDRU/views/login.php');
    exit();
}

require_once('../config/config.php');
require_once('../controllers/AdminDashboardController.php');

$adminDashboardController = new AdminDashboardController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $title = $_POST['title'];
        $artistId = $_POST['artist_id'];
        $price = $_POST['price'];
        $description = $_POST['description'] ?? null;
        $category = $_POST['category'] ?? null;
        $imagePath = null;

        if (!empty($_FILES['image']['name'])) {
            $imagePath = basename($_FILES['image']['name']);
            // Set the correct path prefix
            $imagePath = '/KUNDRU/assets/images/' . $imagePath;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . basename($_FILES['image']['name']));
        }

        $adminDashboardController->addProduct($title, $artistId, $price, $imagePath, $description, $category);
    } elseif (isset($_POST['update_product'])) {
        $productId = $_POST['product_id'];
        $title = $_POST['title'];
        $artistId = $_POST['artist_id'];
        $price = $_POST['price'];
        $description = $_POST['description'] ?? null;
        $category = $_POST['category'] ?? null;
        $imagePath = $_POST['existing_image'];

        if (!empty($_FILES['image']['name'])) {
            $imagePath = basename($_FILES['image']['name']);
            // Set the correct path prefix
            $imagePath = '/KUNDRU/assets/images/' . $imagePath;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . basename($_FILES['image']['name']));
        }

        $adminDashboardController->updateProduct($productId, $title, $artistId, $price, $imagePath, $description, $category);
    } elseif (isset($_POST['delete_product'])) {
        $productId = $_POST['product_id'];
        $adminDashboardController->deleteProduct($productId);
    }
}

$products = $adminDashboardController->getProducts();
$artists = $adminDashboardController->getArtists();
?>

<html>
<body>
    <header class="text-center mb-5 admin-dashboard-header">
        <h1>Produkte verwalten</h1>
    </header>

    <div class="admin-dashboard-container my-5">
        <!-- Back to the dashboard button -->
        <a href="/KUNDRU/views/admin_dashboard.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Zurück zum Dashboard
        </a>

        <div class="admin-dashboard-card mb-4">
            <div class="card-header">
                <h2 class="admin-dashboard-card-title">Produktliste</h2>
            </div>
            <div class="admin-dashboard-card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titel</th>
                            <th>Künstler</th>
                            <th>Preis</th>
                            <th>Bild</th>
                            <th>Beschreibung</th>
                            <th>Kategorie</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['ArtworkID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($product['Title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php 
                                    $artist = array_filter($artists, function($a) use ($product) { return $a['ArtistID'] == $product['ArtistID']; });
                                    echo htmlspecialchars(current($artist)['FirstName'] . ' ' . current($artist)['LastName'], ENT_QUOTES, 'UTF-8'); 
                                ?></td>
                                <td><?php echo htmlspecialchars($product['PriceOfArtprint'], ENT_QUOTES, 'UTF-8'); ?> €</td>
                                <td>
                                    <?php if (!empty($product['ImagePath'])): ?>
                                        <a href="<?php echo htmlspecialchars($product['ImagePath'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                            <img src="<?php echo htmlspecialchars($product['ImagePath'], ENT_QUOTES, 'UTF-8'); ?>" width="100" alt="<?php echo htmlspecialchars($product['Title'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </a>
                                    <?php else: ?>
                                        Kein Bild verfügbar
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['Description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($product['Category'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <form action="manage_products.php" method="post" style="display: inline;">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['ArtworkID'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm action-buttons" onclick="return confirm('Bist du dir sicher, dass du dieses Produkt löschen möchtest?');">Löschen</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-sm action-buttons" data-bs-toggle="modal" data-bs-target="#updateProductModal"
                                        data-id="<?php echo htmlspecialchars($product['ArtworkID'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-title="<?php echo htmlspecialchars($product['Title'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-artist="<?php echo htmlspecialchars($product['ArtistID'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo htmlspecialchars($product['PriceOfArtprint'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($product['Description'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-category="<?php echo htmlspecialchars($product['Category'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-image="<?php echo htmlspecialchars($product['ImagePath'], ENT_QUOTES, 'UTF-8'); ?>">
                                        Bearbeiten
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Neues Produkt hinzufügen</button>
            </div>
        </div>
    </div>
    <!-- Modal for new product -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Neues Produkt hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage_products.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titel</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="artist_id" class="form-label">Künstler</label>
                            <select id="artist_id" name="artist_id" class="form-select" required>
                                <?php foreach ($artists as $artist): ?>
                                    <option value="<?php echo htmlspecialchars($artist['ArtistID'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($artist['FirstName'] . ' ' . $artist['LastName'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Preis (€)</label>
                            <input type="text" id="price" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Beschreibung</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategorie</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="Painting">Painting</option>
                                <option value="Typography">Typography</option>
                                <option value="Photography">Photography</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Bild</label>
                            <input type="file" id="image" name="image" class="form-control" required>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-primary">Hinzufügen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit modal for product -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProductModalLabel">Produkt bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage_products.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="mb-3">
                            <label for="update_title" class="form-label">Titel</label>
                            <input type="text" id="update_title" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_artist_id" class="form-label">Künstler</label>
                            <select id="update_artist_id" name="artist_id" class="form-select" required>
                                <?php foreach ($artists as $artist): ?>
                                    <option value="<?php echo htmlspecialchars($artist['ArtistID'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($artist['FirstName'] . ' ' . $artist['LastName'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="update_price" class="form-label">Preis</label>
                            <input type="text" id="update_price" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_description" class="form-label">Beschreibung</label>
                            <textarea id="update_description" name="description" class="form-control textarea-large"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="update_category" class="form-label">Kategorie</label>
                            <select id="update_category" name="category" class="form-select" required>
                                <option value="Painting">Painting</option>
                                <option value="Typography">Typography</option>
                                <option value="Photography">Photography</option>
                            </select>
                        </div>
                        <div class="mb-3">
                        <label for="update_image" class="form-label">Bild</label>
                        <div id="current_image_wrapper" class="mb-3">
                        </div>
                        <input type="file" id="update_image" name="image" class="form-control">
                    </div>
                        <button type="submit" name="update_product" class="btn btn-primary">Aktualisieren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var updateProductModal = document.getElementById('updateProductModal');
        updateProductModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var productId = button.getAttribute('data-id');
            var title = button.getAttribute('data-title');
            var artistId = button.getAttribute('data-artist');
            var price = button.getAttribute('data-price');
            var description = button.getAttribute('data-description');
            var category = button.getAttribute('data-category');
            var image = button.getAttribute('data-image');

            document.getElementById('product_id').value = productId;
            document.getElementById('update_title').value = title;
            document.getElementById('update_artist_id').value = artistId;
            document.getElementById('update_price').value = price;
            document.getElementById('update_description').value = description;
            document.getElementById('update_category').value = category;;

            var currentImageWrapper = document.getElementById('current_image_wrapper');
            if (image) {
                currentImageWrapper.innerHTML = `
                    <a href="${image}" target="_blank">
                        <img src="${image}" width="100" alt="${title}">
                    </a>
                    <p>${image.split('/').pop()}</p>
                `;
            } else {
                currentImageWrapper.innerHTML = `<p>Kein Bild verfügbar</p>`;
            }
        });
    });
</script>

</body>
</html>

<?php include('footer.php'); ?>