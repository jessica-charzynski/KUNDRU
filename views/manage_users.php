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

$users = $adminDashboardController->getUsers();
?>

<html>
<body>
    <header class="text-center mb-5 admin-dashboard-header">
        <h1>Benutzer verwalten</h1>
    </header>

    <div class="admin-dashboard-container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="admin-dashboard-card mb-4">
                    <div class="mb-3">
                        <a href="/KUNDRU/views/admin_dashboard.php" class="btn btn-secondary mb-3">
                            <i class="fas fa-arrow-left"></i> Zurück zum Dashboard
                        </a>
                    </div>
                    <div class="admin-dashboard-card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Rolle</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['UserID'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($user['FirstName'] . ' ' . $user['LastName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($user['Role'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <!-- Optional actions (e.g., delete user) -->
                                            <a href="delete_user.php?id=<?php echo htmlspecialchars($user['UserID'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bist du dir sicher, dass du diesen Benutzer löschen möchtest?');">Löschen</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('footer.php'); ?>