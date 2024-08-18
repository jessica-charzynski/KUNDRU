<?php
require_once('../config/config.php');
session_start(); 

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
session_unset();

include('header.php');
?>

<html>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Registrieren</h2>
                <form id="registerForm" method="post" action="/KUNDRU/controllers/RegisterController.php">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Vorname</label>
                        <input type="text" class="form-control <?php echo isset($errors['firstName']) ? 'is-invalid' : ''; ?>" id="firstName" name="firstName" value="<?php echo htmlspecialchars($formData['firstName'] ?? '', ENT_QUOTES); ?>" required>
                        <?php if (isset($errors['firstName'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['firstName'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Nachname</label>
                        <input type="text" class="form-control <?php echo isset($errors['lastName']) ? 'is-invalid' : ''; ?>" id="lastName" name="lastName" value="<?php echo htmlspecialchars($formData['lastName'] ?? '', ENT_QUOTES); ?>" required>
                        <?php if (isset($errors['lastName'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['lastName'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail-Adresse</label>
                        <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($formData['email'] ?? '', ENT_QUOTES); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['email'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefonnummer</label>
                        <input type="text" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" id="phone" name="phone" value="<?php echo htmlspecialchars($formData['phone'] ?? '', ENT_QUOTES); ?>" required>
                        <?php if (isset($errors['phone'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['phone'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['password'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrieren</button>
                    <!-- Container für Fehlermeldungen -->
                    <div class="error-messages mt-3"></div>
                </form>
                <div class="text-center mt-3">
                    <p>Bereits einen Account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="/KUNDRU/assets/js/validation.js"></script>
</body>
</html>

<?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Willkommen bei KUNDRU 🎉</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deine Registrierung war erfolgreich.
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-primary">Weiter zum Login</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    </script>
<?php endif; ?>

<?php include('footer.php'); ?>