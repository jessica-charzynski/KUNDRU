<?php
require_once('../models/Order.php');
require_once('../models/OrderItem.php');

class OrderController {
    private $conn;
    private $orderModel;
    private $orderItemModel;

    /**
     * Constructor to initialize the OrderController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        // Initialize the database connection and models for orders and order items
        $this->conn = $conn;
        $this->orderModel = new Order($conn);
        $this->orderItemModel = new OrderItem($conn);
    }

    /**
     * Creates an order with associated order items.
     *
     * @param int $userId The ID of the user placing the order.
     * @param float $totalAmount The total amount for the order.
     * @param string $stripeChargeId The Stripe charge ID for the payment.
     * @param array $cartItems The array of cart items to include in the order.
     * @return int The ID of the newly created order.
     * @throws Exception if there is an error creating the order or its items.
     */
    public function createOrderWithItems($userId, $totalAmount, $stripeChargeId, $cartItems) {
        try {
            // Begin a transaction
            $this->conn->beginTransaction();

            // Create a new order
            $orderId = $this->orderModel->createOrder($userId, $totalAmount, $stripeChargeId);

            // Debugging: Show the received orderId
            // echo "Erhaltene orderId: $orderId<br>";

            // Iterate over each cart item and create an order item
            foreach ($cartItems as $item) {
                echo "Einfügen von OrderItem: orderId=$orderId, artworkId=" . $item['artwork']['ArtworkID'] . ", quantity=" . $item['quantity'] . ", price=" . $item['artwork']['PriceOfArtprint'] . "<br>";
                $this->orderItemModel->createOrderItem($orderId, $item['artwork']['ArtworkID'], $item['quantity'], $item['artwork']['PriceOfArtprint']);
            }

            // Commit the transaction
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>