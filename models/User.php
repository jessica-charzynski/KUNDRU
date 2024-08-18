<?php
require_once(__DIR__ . '/../config/config.php');

class User {
    private $conn;

    /**
     * Constructor to initialize the User class with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves a user by their ID.
     *
     * @param int $userId The ID of the user to retrieve.
     * @return array The user data as an associative array.
     */
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE UserID = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates user details in the database.
     *
     * @param int $userId The ID of the user to update.
     * @param array $data An associative array of user details to update.
     * @return bool True on success, false on failure.
     */
    public function updateUser($userId, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $userId;
        $sql = "UPDATE user SET " . implode(", ", $fields) . " WHERE UserID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $userId The ID of the user to delete.
     * @return bool True if the user was deleted, false otherwise.
     */
    public function deleteUser($userId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user WHERE UserID = ?");
            $stmt->execute([$userId]);
    
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Fehler beim Löschen des Benutzers: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves the address of a user by their ID.
     *
     * @param int $userId The ID of the user whose address is to be retrieved.
     * @return array The address data as an associative array.
     */
    public function getAddressByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM address WHERE UserID = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Adds or updates a user's address.
     *
     * @param int $userId The ID of the user whose address is to be added or updated.
     * @param array $data An associative array of address details.
     * @return bool True on success, false on failure.
     */
    public function addOrUpdateAddress($userId, $data) {
        $existingAddress = $this->getAddressByUserId($userId);
        if ($existingAddress) {
            $stmt = $this->conn->prepare("UPDATE address SET Street = ?, City = ?, PostalCode = ?, Country = ? WHERE UserID = ?");
            return $stmt->execute([$data['street'], $data['city'], $data['postalCode'], $data['country'], $userId]);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO address (UserID, Street, City, PostalCode, Country) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$userId, $data['street'], $data['city'], $data['postalCode'], $data['country']]);
        }
    }

    /**
     * Updates a user's password.
     *
     * @param int $userId The ID of the user whose password is to be updated.
     * @param string $currentPassword The current password to verify.
     * @param string $newPassword The new password to set.
     * @return bool False if the current password is incorrect, or true if the password was successfully updated.
     */
    public function updatePassword($userId, $currentPassword, $newPassword) {
        // Check if the current password is correct
        $user = $this->getUserById($userId);
        if ($user && password_verify($currentPassword, $user['Password'])) {
             // Hash the new password and update it
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("UPDATE user SET Password = ? WHERE UserID = ?");
            return $stmt->execute([$hashedNewPassword, $userId]);
        } else {
            // If the current password is incorrect
            return false;
        }
    }

     /**
     * Checks if the user is logged in based on the session.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>