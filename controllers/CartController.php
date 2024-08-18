<?php

require_once('../models/Cart.php');
require_once('../models/Artwork.php');

class CartController {
    private $cart;
    private $artworkModel;

    /**
     * Constructor to initialize the CartController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        // Initialize the cart and artwork model
        $this->cart = new Cart();
        $this->artworkModel = new Artwork($conn);
    }

    /**
     * Adds an artwork to the cart.
     *
     * @param int $artworkID The ID of the artwork to add.
     * @param int $quantity The quantity of the artwork to add. Default is 1.
     */
    public function addToCart($artworkID, $quantity = 1) {
        // Fetch artwork details by ID
        $artwork = $this->artworkModel->getArtworkByID($artworkID);
        if ($artwork) {
            // Add artwork to the cart with specified quantity
            $this->cart->addItem($artwork, $quantity);
        }
    }

    /**
     * Removes an artwork from the cart.
     *
     * @param int $artworkID The ID of the artwork to remove.
     */
    public function removeFromCart($artworkID) {
        // Remove artwork from the cart by ID
        $this->cart->removeItem($artworkID);
    }

    /**
     * Retrieves all items currently in the cart.
     *
     * @return array The array of cart items.
     */
    public function viewCart() {
        // Get all items in the cart
        return $this->cart->getItems();
    }
}

?>