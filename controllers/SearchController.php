<?php
require_once('../models/Artwork.php');

class SearchController {
    private $artworkModel;

    /**
     * Constructor to initialize the SearchController with a database connection.
     *
     * @param PDO $conn The PDO database connection object.
     */
    public function __construct($conn) {
        // Initialize the Artwork model with the provided database connection
        $this->artworkModel = new Artwork($conn);
    }

    /**
     * Searches for artworks based on the provided query.
     *
     * @param string $query The search query string.
     * @return array The array of search results, including artworks that match the query.
     */
    public function search($query) {
        // Perform the search operation through the Artwork model
        return $this->artworkModel->searchArtworks($query);
    }
}
?>