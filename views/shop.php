<?php
require_once('../config/config.php');
require_once('../controllers/ShopController.php');
require_once('../controllers/SearchController.php');

// Creating instances of controllers with the database connection
$shopController = new ShopController($conn);
$searchController = new SearchController($conn);

// Get filter and sort options from the request
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$reset = isset($_GET['reset']) ? true : false;
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Loading artworks using the controller
if ($reset) {
    $artworks = $shopController->resetFilters();
} else if (!empty($query)) {
    $artworks = $searchController->search($query);
} else {
    $artworks = $shopController->getAllArtworks($category, $sort);
}

// Count the number of artworks
$artworksCount = count($artworks);

// Including header.php for the page layout
include('header.php');
?>

<html>
<body>
    <!-- Shop Header Section -->
    <header class="header">
        <h1>Shop</h1>
    </header>
    <!-- Filter and Sort Section -->
    <section class="filter-sort">
        <div class="container">
            <form method="get" action="shop.php">
                <div class="row align-items-end">
                    <!-- Filter by Category -->
                    <div class="dropdown">
                        <select name="category" id="category" class="form-control">
                            <option value="" <?php echo isset($_GET['category']) && $_GET['category'] == '' ? 'selected' : ''; ?>>Alle Kategorien</option>
                            <option value="Painting" <?php echo isset($_GET['category']) && $_GET['category'] == 'Painting' ? 'selected' : ''; ?>>Malerei & Zeichnungen</option>
                            <option value="Typography" <?php echo isset($_GET['category']) && $_GET['category'] == 'Typography' ? 'selected' : ''; ?>>Typografie</option>
                            <option value="Photography" <?php echo isset($_GET['category']) && $_GET['category'] == 'Photography' ? 'selected' : ''; ?>>Fotografie</option>
                        </select>
                        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5H7z"/></svg>
                    </div>
                    <!-- Sort by Options -->
                    <div class="dropdown">
                        <select name="sort" id="sort" class="form-control">
                            <option value="ArtworkID" <?php if ($sort == 'ArtworkID') echo 'selected'; ?>>Standardsortierung</option>
                            <option value="price_low" <?php if ($sort == 'price_low') echo 'selected'; ?>>Niedrigster Preis</option>
                            <option value="price_high" <?php if ($sort == 'price_high') echo 'selected'; ?>>Höchster Preis</option>
                        </select>
                        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5H7z"/></svg>
                    </div>
                    <!-- Apply and Reset Buttons -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary apply-btn">Anwenden</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-secondary reset-btn" onclick="resetFilters()">Zurücksetzen</button>
                    </div>
                    <!-- Search Icon -->
                    <div class="col-md-1 d-flex justify-content-center">
                        <a class="nav-link search-icon" href="#" data-toggle="modal" data-target="#searchModal">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Artworks Count Section -->
    <section class="artworks-count">
        <div class="container">
            <p>
                <?php 
                echo $artworksCount == 1 ? "Es wird ein Artikel angezeigt." : "Es werden " . $artworksCount . " Artikel angezeigt.";
                ?>
            </p>
        </div>
    </section>
    <!-- Artwork Cards Section -->
    <section class="artworks">
        <div class="container">
            <div class="row">
                <?php foreach ($artworks as $artwork): ?>
                    <div class="col-md-3">
                        <div class="card article-card" id="articleCard">
                            <a href="product.php?id=<?php echo $artwork['ArtworkID']; ?>">
                                <img src="<?php echo $artwork['ImagePath']; ?>" class="card-img-top" alt="<?php echo $artwork['Title']; ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork['Title']; ?></h5>
                                <p class="card-text"><em><?php echo $artwork['FirstName'] . ' ' . $artwork['LastName']; ?></em></p>
                                <p class="card-text"><strong><?php echo number_format($artwork['PriceOfArtprint'], 2); ?> €</strong></p>
                                <a href="product.php?id=<?php echo $artwork['ArtworkID']; ?>" class="btn btn-primary">Mehr erfahren</a>
                                <a href="cart.php?add=<?php echo $artwork['ArtworkID']; ?>" class="btn btn-primary">In den Warenkorb</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Suche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="get" action="shop.php">
                        <div class="form-group">
                            <label for="query">Suchbegriff</label>
                            <input type="text" class="form-control" id="query" name="query" placeholder="Suche nach Titel oder Künstler" value="<?php echo htmlspecialchars($query); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-search-btn">Suchen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Reset Filters -->
    <script>
        function resetFilters() {
            window.location.href = "shop.php?reset=true";
        }
    </script>
    <!-- External resources for the search functionality: jQuery, Popper.js, and Bootstrap JS -->
    <script src="../assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>