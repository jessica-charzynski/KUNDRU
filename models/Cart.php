<?php

class Cart {
    private $items = [];

    /**
     * Constructor to initialize the cart.
     * Starts a session if not already started and loads existing cart items from the session.
     */
    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->loadCartFromSession();
    }

    // Load existing cart items from the session if available
    private function loadCartFromSession() {
        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        }
    }

    /**
     * Adds an artwork to the cart.
     *
     * @param array $artwork The artwork details to be added.
     * @param int $quantity The quantity of the artwork to add. Default is 1.
     */
    public function addItem($artwork, $quantity = 1) {
        $artworkID = $artwork['ArtworkID'];

        // If the item already exists in the cart, update its quantity
        if (isset($this->items[$artworkID])) {
            $this->items[$artworkID]['quantity'] += $quantity;
        } else {
            // Otherwise, add the item to the cart with the specified quantity
            $this->items[$artworkID] = [
                'artwork' => $artwork,
                'quantity' => $quantity
            ];
        }

        // Save the updated cart items back to the session
        $_SESSION['cart'] = $this->items;
    }

    /**
     * Removes an artwork from the cart.
     *
     * @param int $artworkID The ID of the artwork to remove.
     */
    public function removeItem($artworkID) {
        // Remove the item from the cart if it exists
        if (isset($this->items[$artworkID])) {
            unset($this->items[$artworkID]);
            // Update the session with the modified cart
            $_SESSION['cart'] = $this->items;
        }
    }

    /**
     * Retrieves all items currently in the cart.
     *
     * @return array The array of cart items.
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Clears all items from the cart.
     */
    public function clearCart() {
        $this->items = [];
        // Update the session to clear the cart
        $_SESSION['cart'] = $this->items;
    }
}

?>