<?php

class Order {
    private $conn;

    /**
     * Constructor to initialize the Order class with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Creates a new order in the database.
     *
     * @param int $userId The ID of the user placing the order.
     * @param float $totalAmount The total amount of the order.
     * @param string $stripeChargeId The Stripe charge ID associated with the payment.
     * 
     * @return int The ID of the newly created order.
     * 
     * @throws Exception If there is an error during order creation.
     */
    public function createOrder($userId, $totalAmount, $stripeChargeId) {
        $sql = "INSERT INTO `order` (UserID, total_amount, stripe_charge_id)
                VALUES (:userId, :totalAmount, :stripeChargeId)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':totalAmount', $totalAmount, PDO::PARAM_STR);
            $stmt->bindParam(':stripeChargeId', $stripeChargeId, PDO::PARAM_STR);

            // Execute the query and return the last inserted ID if successful
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                throw new Exception("Fehler beim Erstellen der Bestellung.");
            }
        } catch (PDOException $e) {
            throw new Exception("Datenbankfehler: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Allgemeiner Fehler: " . $e->getMessage());
        }
    }
}
?>