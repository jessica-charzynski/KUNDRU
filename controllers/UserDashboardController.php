<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../models/User.php');
require_once(__DIR__ . '/../models/Order.php');
require_once(__DIR__ . '/../models/OrderItem.php');

class UserDashboardController {
    private $conn;
    private $userModel;
    private $orderModel;
    private $orderItemModel;

    /**
     * Constructor to initialize UserDashboardController with the necessary models
     *
     * @param PDO $conn The PDO database connection object
     */
    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new User($conn);
        $this->orderModel = new Order($conn);
        $this->orderItemModel = new OrderItem($conn);
    }

    /**
     * Retrieves user details by user ID
     *
     * @param int $userId The ID of the user
     * @return array Associative array containing user details
     */
    public function getUserDetails($userId) {
        return $this->userModel->getUserById($userId);
    }

    /**
     * Retrieves the address for a specific user
     *
     * @param int $userId The ID of the user
     * @return array Associative array containing user address
     */
    public function getAddress($userId) {
        return $this->userModel->getAddressByUserId($userId);
    }

    /**
     * Updates the user's profile information
     *
     * @param int $userId The ID of the user
     * @param array $data Associative array containing user profile data to update
     * @return bool True on success, false on failure
     */
    public function updateProfile($userId, $data) {
        return $this->userModel->updateUser($userId, $data);
    }

    /**
     * Deletes a user account
     *
     * @param int $userId The ID of the user
     * @return bool True on success, false on failure
     */
    public function deleteAccount($userId) {
        return $this->userModel->deleteUser($userId);
    }

    /**
     * Adds or updates a user's address
     *
     * @param int $userId The ID of the user
     * @param array $data Associative array containing address data
     * @return bool True on success, false on failure
     */
    public function addOrUpdateAddress($userId, $data) {
        return $this->userModel->addOrUpdateAddress($userId, $data);
    }

    /**
     * Verifies the current password of a user
     *
     * @param int $userId The ID of the user
     * @param string $currentPassword The current password to verify
     * @return bool True if the password is correct, false otherwise
     */
    public function verifyCurrentPassword($userId, $currentPassword) {
        $stmt = $this->conn->prepare("SELECT Password FROM user WHERE UserID = ?");
        $stmt->execute([$userId]);
        $hashedPassword = $stmt->fetchColumn();

        return password_verify($currentPassword, $hashedPassword);
    }

    /**
     * Updates the user's password
     *
     * @param int $userId The ID of the user
     * @param string $currentPassword The current password
     * @param string $newPassword The new password
     * @return string Status of the password update operation
     */
    public function updatePassword($userId, $currentPassword, $newPassword) {
        if (!$this->verifyCurrentPassword($userId, $currentPassword)) {
            return 'incorrect_password'; // Current password is incorrect
        }

        if (strlen($newPassword) < 8) {
            return 'short_password'; // New password is too short
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE user SET Password = ? WHERE UserID = ?");
        return $stmt->execute([$hashedPassword, $userId]) ? 'success' : 'update_failed';
    }

    /**
     * Retrieves all orders for a specific user
     *
     * @param int $userId The ID of the user
     * @return array Associative array containing all user orders
     */
    public function getUserOrders($userId) {
        $query = "
            SELECT o.id AS OrderID, o.created_at AS OrderDate, o.payment_status AS Status, o.total_amount AS TotalAmount, 
                   oi.artwork_id, oi.quantity, oi.price, a.Title AS ArtworkTitle
            FROM `order` o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN artwork a ON oi.artwork_id = a.ArtworkID
            WHERE o.UserID = :userId
            ORDER BY o.created_at DESC
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['userId' => $userId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   

    /**
     * Retrieves details of a specific order
     *
     * @param int $orderId The ID of the order
     * @return string JSON-encoded order details
     */
    public function getOrderDetails($orderId) {
        // Get the order data
        $stmt = $this->conn->prepare("SELECT * FROM `order` WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$order) {
            return json_encode(['error' => 'Order not found']);
        }
    
        // Get the order items
        $stmt = $this->conn->prepare("SELECT oi.*, a.Title AS ArtworkTitle 
                                      FROM order_items oi 
                                      LEFT JOIN artwork a ON oi.artwork_id = a.ArtworkID 
                                      WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Adjust order status
        $status = $order['payment_status'] === 'completed' ? 'bezahlt' : 'ausstehend';
    
        // Add Euro sign to total amount
        $totalAmount = number_format($order['total_amount'], 2, ',', '.') . ' €';
        
        foreach ($items as &$item) {
            $item['price'] = number_format($item['price'], 2, ',', '.') . ' €';
        }
        
        return json_encode([
            'orderID' => $order['id'],
            'orderDate' => $order['created_at'],
            'status' => $status,
            'totalAmount' => $totalAmount,
            'items' => $items
        ]);
    }    
    
}

// Handling GET request for order details
if (isset($_GET['action']) && $_GET['action'] === 'get_order_details') {
    $orderId = $_GET['order_id'];
    $controller = new UserDashboardController($conn); 
    echo $controller->getOrderDetails($orderId);
    exit();
}

// Handling POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /KUNDRU/views/login.php');
        exit();
    }

    $action = $_POST['action'];
    $userId = $_SESSION['user_id'];
    $controller = new UserDashboardController($conn);

    switch ($action) {
        case 'logout':
            session_destroy();
            header('Location: /KUNDRU/views/login.php');
            exit();

        case 'delete':
            $success = $controller->deleteAccount($userId);
            if ($success) {
                session_destroy();
                header('Location: /KUNDRU/views/login.php');
                exit();
            } else {
                header('Location: /KUNDRU/views/user_dashboard.php?error=delete');
                exit();
            }

        case 'update_password':
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $result = $controller->updatePassword($userId, $currentPassword, $newPassword);

            if ($result === 'success') {
                header('Location: /KUNDRU/views/user_dashboard.php?status=success');
            } elseif ($result === 'incorrect_password') {
                header('Location: /KUNDRU/views/user_dashboard.php?error=incorrect_password');
            } elseif ($result === 'short_password') {
                header('Location: /KUNDRU/views/user_dashboard.php?error=short_password');
            } else {
                header('Location: /KUNDRU/views/user_dashboard.php?error=update_failed');
            }
            exit();

        case 'address':
            $addressData = [
                'street' => $_POST['street'],
                'city' => $_POST['city'],
                'postalCode' => $_POST['postalCode'],
                'country' => $_POST['country']
            ];
            $controller->addOrUpdateAddress($userId, $addressData);
            header('Location: /KUNDRU/views/user_dashboard.php');
            exit();
    }
}
?>