<?php
require_once('../config/config.php');

class RegisterController {
    public function register() {
        $errors = [];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $role = 'user'; // Default role for new users

        // Validate first name
        if (empty($firstName)) {
            $errors['firstName'] = 'Vorname ist erforderlich.';
        } elseif (!preg_match("/^[a-zA-ZäöüÄÖÜß]+$/", $firstName)) {
            $errors['firstName'] = 'Vorname darf nur Buchstaben enthalten.';
        }

        // Validate last name
        if (empty($lastName)) {
            $errors['lastName'] = 'Nachname ist erforderlich.';
        } elseif (!preg_match("/^[a-zA-ZäöüÄÖÜß]+$/", $lastName)) {
            $errors['lastName'] = 'Nachname darf nur Buchstaben enthalten.';
        }

        // Validate email address
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
        } else {
            // Check if email already exists in the database
            global $conn;
            $stmt = $conn->prepare("SELECT * FROM user WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $errors['email'] = 'Diese E-Mail-Adresse wird bereits verwendet.';
            }
        }

        // Validate phone number
        if (empty($phone) || !preg_match("/^\+?\d+$/", $phone)) {
            $errors['phone'] = 'Bitte geben Sie eine gültige Telefonnummer ein.';
        }

        // Validate password
        if (empty($password)) {
            $errors['password'] = 'Passwort ist erforderlich.';
        }

        // If there are errors, redirect back to the registration page with errors
        if (!empty($errors)) {
            // Store errors in the session and redirect back to the form
            session_start();
            $_SESSION['errors'] = $errors;
            header('Location: /KUNDRU/views/register.php');
            exit();
        }

        // Hash the password and insert the new user into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (FirstName, LastName, Email, Password, Phone, Role) VALUES (:firstName, :lastName, :email, :password, :phone, :role)");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Redirect to the registration success page
        header('Location: /KUNDRU/views/register.php?success=true');
        exit();
    }
}

// Initialize and execute the register method
$controller = new RegisterController();
$controller->register();
?>
