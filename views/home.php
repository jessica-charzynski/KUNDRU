<?php include('header.php'); ?>

<html>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Kunstdrucke, die dein Zuhause einzigartig machen.</h1>
            <p>Tauche ein in eine Welt voller kreativer Kunstdrucke. Hier findest du einzigartige Werke, die perfekt zu deinem individuellen Stil passen und dein Zuhause in eine Galerie verwandeln.</p>
            <a href="/KUNDRU/views/shop.php" class="btn">Zum Shop</a>
        </div>
    </section>
    <!-- Intro Section -->
    <section class="intro mt-5">
        <div class="container text-center">
            <h2>Entdecke unsere Kollektionen</h2>
            <p class="lead">Stöbere durch unsere vielfältigen Kategorien und finde den idealen Kunstdruck für deine Wände.</p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card category-card">
                        <a href="/KUNDRU/views/shop.php?category=Painting&sort=ArtworkID" class="card-link">
                            <img src="assets/images/somei-yoshino.jpg" class="card-img-top rounded" alt="Malerei & Zeichnungen">
                            <div class="card-body">
                                <h5 class="card-title">Malerei & Zeichnungen</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <a href="/KUNDRU/views/shop.php?category=Typography&sort=ArtworkID" class="card-link">
                            <img src="assets/images/inhale-exhale.png" class="card-img-top rounded" alt="Typografie">
                            <div class="card-body">
                                <h5 class="card-title">Typografie</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <a href="/KUNDRU/views/shop.php?category=Photography&sort=ArtworkID" class="card-link">
                            <img src="assets/images/wildblumen.jpg" class="card-img-top rounded" alt="Fotografie">
                            <div class="card-body">
                                <h5 class="card-title">Fotografie</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Teaser Section -->
    <section class="blog-teaser">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4">Lass dich inspirieren</h2>
                    <p class="mb-4">Entdecke die Geschichten hinter den Kunstwerken.<br>Tauche ein in die Welt unser Künstler*innen.</p>
                    <a href="/KUNDRU/views/blog.php" class="btn btn-dark btn-lg">MEHR ENTDECKEN</a>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/blog_teaser.png" class="img-fluid rounded blog-teaser-image" alt="Blog Teaser">
                </div>
            </div>
        </div>
    </section>
</body>
</html>

<?php include('footer.php'); ?>