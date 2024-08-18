<?php
require_once('../models/Artwork.php');

class ProductController {
    private $artworkModel;

    /**
     * Constructor to initialize the ProductController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        $this->artworkModel = new Artwork($conn);
    }

    /**
     * Retrieves a single artwork by its ID.
     *
     * @param int $id The ID of the artwork to retrieve.
     * @return array The artwork data.
     */
    public function viewProduct($id) {
        // Get artwork by ID from the Artwork model
        return $this->artworkModel->getArtworkByID($id);
    }

    /**
     * Retrieves a specified number of random artworks excluding a given ID.
     *
     * @param int $limit The number of random artworks to retrieve.
     * @param int $excludeID The ID of the artwork to exclude from the random selection.
     * @return array The array of random artworks.
     */
    public function getRandomProducts($limit, $excludeID) {
        // Get random artworks from the Artwork model, excluding the specified ID
        return $this->artworkModel->getRandomArtworks($limit, $excludeID);
    }
}
?>