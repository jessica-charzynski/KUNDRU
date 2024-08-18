<?php
require_once('../models/Cart.php');
require_once('../vendor/autoload.php');
require_once('../controllers/OrderController.php');

class PaymentController {
    private $cart;
    private $orderController;
    private $stripe;
    private $conn;

    /**
     * Constructor to initialize the PaymentController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        // Initialize the cart, order controller, and Stripe client
        $this->cart = new Cart($conn);
        $this->orderController = new OrderController($conn);
        $this->stripe = new \Stripe\StripeClient('sk_test_51PjmO9Jbpg1LH6H1Z47ZLccbynpcorsnMaHO1UOZoBQdbqddCPBq4ViRkBCvzozZz4k9rJT8OxSO2fFc4eHuUjLi00jpmPZysH');
        $this->conn = $conn;
    }

    // This method handles the payment process.
    public function handlePayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve the selected payment method from the POST request
            $paymentMethod = $_POST['paymentMethod'] ?? '';

            if ($paymentMethod === 'stripe') {
                // Handle Stripe payment
                $this->handleStripePayment();
            } else {
                echo 'Andere Zahlungsmethoden sind derzeit nicht verfügbar.';
            }
        }
    }

    // This method handles Stripe payment processing.
    private function handleStripePayment() {
        // Retrieve the Stripe payment method ID from the POST request
        $paymentMethodId = $_POST['stripePaymentMethodId'] ?? '';

        if ($paymentMethodId) {
            // Calculate the total order amount
            $amount = $this->calculateOrderAmount(); 

            try {
                // Create a new PaymentIntent
                $paymentIntent = $this->stripe->paymentIntents->create([
                    'amount' => $amount,
                    'currency' => 'eur',
                    'payment_method' => $paymentMethodId,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => 'http://localhost/KUNDRU/views/thankyou.php',
                ]);

                // Check the status of the payment
                if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
                    header('Location: ' . $paymentIntent->next_action->redirect_to_url->url);
                    exit();
                }

                // Payment was successful
                $orderId = $this->orderController->createOrderWithItems($_SESSION['user_id'], $amount / 100, $paymentIntent->id, $_SESSION['cart']);
                unset($_SESSION['cart']); // Clear the cart

                // Redirect to the thank you page
                header('Location: /KUNDRU/views/thankyou.php?order_id=' . $orderId);
                exit();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // Handle errors during payment processing
                echo 'Fehler bei der Zahlungsabwicklung: ' . $e->getMessage();
            }
        } else {
            // Handle missing payment method ID
            echo 'PaymentMethod ID fehlt';
        }
    }

    /**
     * This method calculates the total order amount.
     *
     * @return int The total amount in cents.
     */
    private function calculateOrderAmount() {
        // Calculate the total amount
        $totalSum = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $totalSum += $item['artwork']['PriceOfArtprint'] * $item['quantity'];
            }
        }
        return $totalSum * 100; // Return amount in cents
    }
}

// Initialize the Stripe library and the PaymentController
$conn = new PDO('mysql:host=localhost;dbname=kundru', 'root', ''); 
$paymentController = new PaymentController($conn);
$paymentController->handlePayment();
?>

<script src="https://js.stripe.com/v3/"></script>
<script src="/KUNDRU/assets/js/payment.js"></script>