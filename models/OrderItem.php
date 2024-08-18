<?php

class OrderItem {
    private $conn;

    /**
     * Constructor to initialize the OrderItem class with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Creates a new order item in the database.
     *
     * @param int $orderId The ID of the order to which the item belongs.
     * @param int $artworkId The ID of the artwork being added to the order.
     * @param int $quantity The quantity of the artwork in the order.
     * @param float $price The price of the artwork in the order.
     * 
     * @return bool True on success, false on failure.
     */
    public function createOrderItem($orderId, $artworkId, $quantity, $price) {
        $query = "INSERT INTO order_items (order_id, artwork_id, quantity, price) VALUES (:order_id, :artwork_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters to the query
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':artwork_id', $artworkId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);

        // Execute the query and handle success or failure
        if($stmt->execute()) {
            echo "Bestellposition erfolgreich erstellt.\n";
            return true;
        } else {
            echo "Fehler beim Erstellen der Bestellposition: " . implode(", ", $stmt->errorInfo()) . "\n";
            return false;
        }
    }
}
?>