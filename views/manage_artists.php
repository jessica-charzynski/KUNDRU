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
    if (isset($_POST['add_artist'])) {
        $adminDashboardController->addArtist($_POST['first_name'], $_POST['last_name']);
    } elseif (isset($_POST['update_artist'])) {
        $adminDashboardController->updateArtist($_POST['id'], $_POST['first_name'], $_POST['last_name']);
    } elseif (isset($_POST['delete_artist'])) {
        $adminDashboardController->deleteArtist($_POST['id']);
    }
}

$artists = $adminDashboardController->getArtists();
?>

<html>
<body>
    <header class="text-center mb-5 admin-dashboard-header">
        <h1>Artists verwalten</h1>
    </header>

    <div class="admin-dashboard-container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <a href="/KUNDRU/views/admin_dashboard.php" class="btn btn-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Zurück zum Dashboard
                    </a>
                </div>
                <div class="admin-dashboard-card mb-4">
                    <div class="admin-dashboard-card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vorname</th>
                                    <th>Nachname</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($artists as $artist): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($artist['ArtistID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($artist['FirstName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($artist['LastName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteArtistModal" data-id="<?php echo htmlspecialchars($artist['ArtistID'], ENT_QUOTES, 'UTF-8'); ?>" data-first_name="<?php echo htmlspecialchars($artist['FirstName'], ENT_QUOTES, 'UTF-8'); ?>" data-last_name="<?php echo htmlspecialchars($artist['LastName'], ENT_QUOTES, 'UTF-8'); ?>">Löschen</button>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#updateArtistModal" data-id="<?php echo htmlspecialchars($artist['ArtistID'], ENT_QUOTES, 'UTF-8'); ?>" data-first_name="<?php echo htmlspecialchars($artist['FirstName'], ENT_QUOTES, 'UTF-8'); ?>" data-last_name="<?php echo htmlspecialchars($artist['LastName'], ENT_QUOTES, 'UTF-8'); ?>">Bearbeiten</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArtistModal">Neuen Artist hinzufügen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for new artist -->
    <div class="modal fade" id="addArtistModal" tabindex="-1" aria-labelledby="addArtistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addArtistModalLabel">Neuen Artist hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage_artists.php" method="post">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Vorname</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Nachname</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" required>
                        </div>
                        <button type="submit" name="add_artist" class="btn btn-primary">Hinzufügen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit modal for artists -->
    <div class="modal fade" id="updateArtistModal" tabindex="-1" aria-labelledby="updateArtistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateArtistModalLabel">Artist bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage_artists.php" method="post">
                        <input type="hidden" id="update_id" name="id">
                        <div class="mb-3">
                            <label for="update_first_name" class="form-label">Vorname</label>
                            <input type="text" id="update_first_name" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_last_name" class="form-label">Nachname</label>
                            <input type="text" id="update_last_name" name="last_name" class="form-control" required>
                        </div>
                        <button type="submit" name="update_artist" class="btn btn-primary">Aktualisieren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete modal for artists -->
    <div class="modal fade" id="deleteArtistModal" tabindex="-1" aria-labelledby="deleteArtistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteArtistModalLabel">Artist löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bist du sicher, dass du diesen Artist löschen möchtest?</p>
                    <form id="deleteArtistForm" action="manage_artists.php" method="post">
                        <input type="hidden" id="delete_artist_id" name="id">
                        <button type="submit" name="delete_artist" class="btn btn-danger">Löschen</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateArtistModal = document.getElementById('updateArtistModal');
            updateArtistModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var artistId = button.getAttribute('data-id');
                var artistFirstName = button.getAttribute('data-first_name');
                var artistLastName = button.getAttribute('data-last_name');

                document.getElementById('update_id').value = artistId;
                document.getElementById('update_first_name').value = artistFirstName;
                document.getElementById('update_last_name').value = artistLastName;
            });

            var deleteArtistModal = document.getElementById('deleteArtistModal');
            deleteArtistModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var artistId = button.getAttribute('data-id');
                var artistFirstName = button.getAttribute('data-first_name');
                var artistLastName = button.getAttribute('data-last_name');

                var form = document.getElementById('deleteArtistForm');
                document.getElementById('delete_artist_id').value = artistId;
            });
        });
    </script>
</body>
</html>

<?php include('footer.php'); ?>