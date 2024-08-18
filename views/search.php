<?php
require_once('../config/config.php');
require_once('../controllers/SearchController.php');

// Creating an instance of SearchController with the database connection
$controller = new SearchController($conn);

// Get search query from the request
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Perform the search
$results = $controller->search($query);

// Including header.php for the page layout
include('header.php');
?>

<html>
<body>
    <div class="container mt-5">
        <h2>Suchergebnisse für: <?php echo htmlspecialchars($query); ?></h2>
        <?php if (empty($results)): ?>
            <p>Keine Ergebnisse gefunden.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($results as $artwork): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <a href="product.php?id=<?php echo $artwork['ArtworkID']; ?>">
                                <img src="<?php echo $artwork['ImagePath']; ?>" class="card-img-top" alt="<?php echo $artwork['Title']; ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $artwork['Title']; ?></h5>
                                <p class="card-text"><em><?php echo $artwork['FirstName'] . ' ' . $artwork['LastName']; ?></em></p>
                                <p class="card-text"><strong><?php echo number_format($artwork['PriceOfArtprint'], 2); ?> €</strong></p>
                                <a href="product.php?id=<?php echo $artwork['ArtworkID']; ?>" class="btn btn-primary">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php include('footer.php'); ?>