<?php
require_once('../config/config.php');
include('header.php');

// Load error messages and form data
$errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : '';
$formData = isset($_SESSION['login_form_data']) ? $_SESSION['login_form_data'] : [];
session_unset(); 
?>

<html>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Login</h2>
                <form id="loginForm" method="post" action="/KUNDRU/controllers/AuthenticateController.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail-Adresse</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($formData['email'] ?? '', ENT_QUOTES); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo htmlspecialchars($errors, ENT_QUOTES); ?>
                        </div>
                    <?php endif; ?>
                </form>
                <div class="text-center mt-3">
                    <p>Noch keinen Account? <a href="register.php">Registrieren</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="/KUNDRU/assets/js/validation.js"></script>
    <script src="/KUNDRU/assets/js/displayErrors.js"></script>
</body>
</html>

<?php include('footer.php'); ?>