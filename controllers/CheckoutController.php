<?php
require_once('../models/Order.php');
require_once('../models/OrderItem.php');
require_once('../controllers/OrderController.php');

class CheckoutController {
    private $conn;
    private $orderController;

    /**
     * Constructor to initialize the CheckoutController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        $this->conn = $conn;
        $this->orderController = new OrderController($conn);
    }

    /**
     * This method creates a new order 
     *
     * @param int $userId The ID of the user placing the order.
     * @param float $totalAmount The total amount for the order.
     * @param string $stripeChargeId The Stripe charge ID for the payment.
     * @param array $cartItems The array of cart items to include in the order.
     * @return int The ID of the newly created order.
     * @throws Exception if there is an error placing the order.
     */
    public function placeOrder($userId, $totalAmount, $stripeChargeId, $cartItems) {
        try {
            // Create a new order with items using the OrderController
            $orderId = $this->orderController->createOrderWithItems($userId, $totalAmount, $stripeChargeId, $cartItems);
            return $orderId;
        } catch (Exception $e) {
            // Handle and log exceptions
            echo "Fehler: " . $e->getMessage();
            throw $e;
        }
    }
}
?>