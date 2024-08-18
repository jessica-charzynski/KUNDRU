<?php include('header.php'); ?>

<html>
<body>
    <!-- About Header Section -->
    <header class="header text-center py-5">
        <h1>Über das Projekt</h1>
    </header>
    <!-- Main Content Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h3>Allgemeines</h3>
                <p>KUNDRU ist ein Online-Shop für Kunstdrucke, der als Abschlussprojekt im Modul "Internetserver-Programmierung" im Sommersemester 2024 an der Berliner Hochschule für Technik entstanden ist.</p>
                <h3>Funktionsumfang</h3>
                <ul>
                    <li><strong>Shop:</strong> Nutzer können Kunstwerke in verschiedenen Kategorien erkunden. Jedes Produkt wird auch in einer detaillierten Produktansicht präsentiert. Zudem bietet der Shop Filter- und Sortieroptionen sowie eine Suchfunktion, um gezielt nach Kunstwerken oder Künstlern zu suchen.</li>
                    <li><strong>Warenkorb & Checkout:</strong> Der Shop ermöglicht es, Kunstdrucke in den Warenkorb zu legen und den Kauf sicher über Stripe abzuschließen.</li>
                    <li><strong>Registrierung und Login:</strong> Nutzer können sich registrieren und einloggen, um auf personalisierte Funktionen zuzugreifen.</li>
                    <li><strong>User Dashboard:</strong> Nutzer können ihre Adresse und ihr Passwort aktualisieren, Bestellungen einsehen und sich ausloggen.</li>
                    <li><strong>Admin Dashboard:</strong> Administratoren können Künstler und Produkte hinzufügen, ändern und löschen sowie Benutzerkonten verwalten.</li>
                    <li><strong>Blog:</strong> Der Blog bietet in Form von Blogartikeln Einblicke in die Welt der Künstler.</li>
                </ul>
                <h3>Technologie-Stack</h3>
                <div class="tech-icons mb-3">
                    <i class="fab fa-php fa-3x mx-2"></i>
                    <i class="fas fa-database fa-3x mx-2"></i>
                    <i class="fab fa-bootstrap fa-3x mx-2"></i>
                    <i class="fas fa-archive fa-3x mx-2"></i>
                    <i class="fab fa-js fa-3x mx-2"></i>
                    <i class="fab fa-css3-alt fa-3x mx-2"></i>
                </div>
                <ul>
                    <li><strong>PHP:</strong> Das Projekt verwendet PHP für die serverseitige Logik und Datenverarbeitung.</li>
                    <li><strong>MySQL-Datenbank:</strong> Die Datenbank wird in phpMyAdmin verwaltet und verwendet MySQL.</li>
                    <li><strong>Bootstrap:</strong> Das Framework wird für das Frontend-Design und die responsiven Layouts genutzt.</li>
                    <li><strong>Composer:</strong> Wird verwendet um die Stripe-Bibliothek zu verwalten.</li>
                    <li><strong>jQuery:</strong> Wird eingesetzt, um dynamische Such-, Filter- und Sortierfunktionen zu ermöglichen.</li>
                    <li><strong>Eigene CSS- und JavaScript-Dateien:</strong> Werden für spezifische Anpassungen eingesetzt.</li>
                </ul>

                <h3>Projektstruktur</h3>
                <p>Das Projekt ist nach dem Model-View-Controller (MVC) Architekturprinzip strukturiert, um eine klare Trennung von Anwendungslogik, Daten und Präsentation zu gewährleisten.</p>
                <p>Externe Dateien sind in der Tabelle unterstrichen.</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Verzeichnis</th>
                                <th>Inhalte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>KUNDRU</td>
                                <td>
                                    <ul>
                                        <li><span class="external">composer.json und composer.lock</span> - Verwaltung von PHP-Abhängigkeiten</li>
                                        <li>index.php - Einstiegspunkt der Anwendung</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > assets</td>
                                <td>
                                    <ul>
                                        <li><span class="external">bootstrap/</span> - Bootstrap CSS- und JavaScript-Dateien für das Layout</li>
                                        <li>css/stylesheet.css - Benutzerdefiniertes Stylesheet</li>
                                        <li><span class="external">fonts/Poppins/</span> - Schriftarten</li>
                                        <li>images/ - Bilddateien</li>
                                        <li><span class="external">jquery/</span> - jQuery-Bibliothek</li>
                                        <li>js/ - Eigene JavaScript-Dateien</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > config</td>
                                <td>
                                    <ul>
                                        <li>config.php - Konfigurationsdatei</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > controllers</td>
                                <td>
                                    <ul>
                                        <li>AdminDashboardController.php</li>
                                        <li>AuthenticateController.php</li>
                                        <li>CartController.php</li>
                                        <li>CheckoutController.php</li>
                                        <li>HomeController.php</li>
                                        <li>OrderController.php</li>
                                        <li>PaymentController.php</li>
                                        <li>ProductController.php</li>
                                        <li>RegisterController.php</li>
                                        <li>SearchController.php</li>
                                        <li>ShopController.php</li>
                                        <li>UserDashboardController.php</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > database</td>
                                <td>
                                    <ul>
                                        <li>kundru.sql - Datenbank des Projektes</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > models</td>
                                <td>
                                    <ul>
                                        <li>Artwork.php</li>
                                        <li>Cart.php</li>
                                        <li>Order.php</li>
                                        <li>OrderItem.php</li>
                                        <li>User.php</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > vendor</td>
                                <td>
                                    <ul>
                                        <li><span class="external">autoload.php</span> - Automatische Klassenladung</li>
                                        <li><span class="external">composer/</span> - Composer spezifische Dateien</li>
                                        <li><span class="external">stripe/</span> - Stripe-Bibliothek für die Zahlungsintegration</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>KUNDRU > views</td>
                                <td>
                                    <ul>
                                        <li>about.php</li>
                                        <li>address_form.php</li>
                                        <li>admin_dashboard.php</li>
                                        <li>blog.php</li>
                                        <li>cart.php</li>
                                        <li>checkout.php</li>
                                        <li>footer.php</li>
                                        <li>header.php</li>
                                        <li>home.php</li>
                                        <li>login.php</li>
                                        <li>manage_artists.php</li>
                                        <li>manage_products.php</li>
                                        <li>manage_users.php</li>
                                        <li>product.php</li>
                                        <li>register.php</li>
                                        <li>search.php</li>
                                        <li>shop.php</li>
                                        <li>thankyou.php</li>
                                        <li>user_dashboard.php</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('footer.php'); ?>