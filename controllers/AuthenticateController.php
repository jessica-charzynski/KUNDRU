<?php
require_once('../config/config.php');

class AuthenticateController {
    public function login() {
        // Start a new session or resume the existing session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Retrieve email and password from POST request
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
    
        // Check if email or password is empty
        if (empty($email) || empty($password)) {
            $_SESSION['login_errors'] = 'Bitte geben Sie sowohl eine E-Mail-Adresse als auch ein Passwort ein.';
            header('Location: /KUNDRU/views/login.php');
            exit();
        }
    
        // Use the global database connection
        global $conn;
    
        // Prepare SQL statement to fetch user by email
        $stmt = $conn->prepare("SELECT UserID, Password, role FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        // Fetch the user as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verify the password
        if (isset($user['Password']) && password_verify($password, $user['Password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);
    
            // Store user ID in session
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['role'] = $user['role'];
    
            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header('Location: /KUNDRU/views/admin_dashboard.php');
            } else {
                header('Location: /KUNDRU/views/user_dashboard.php');
            }

            exit();
        } else {
            // Set login error message in session
            $_SESSION['login_errors'] = 'Entweder war die E-Mail-Adresse oder das Passwort nicht korrekt.';
            header('Location: /KUNDRU/views/login.php');
            exit();
        }
    }    
}

// Create a new instance of AuthenticateController and call the login method
$controller = new AuthenticateController();
$controller->login();
?>