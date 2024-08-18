<?php
require_once('../models/Artwork.php');

class ShopController {
    private $artworkModel;

    /**
     * Constructor to initialize ShopController with Artwork model
     *
     * @param PDO $conn The PDO database connection object
     */
    public function __construct($conn) {
        // Initialize the Artwork model with the provided database connection
        $this->artworkModel = new Artwork($conn);
    }

    /**
     * Retrieves all artworks using the Artwork model with optional filtering and sorting
     *
     * @param string $category The category to filter by
     * @param string $sort The sort order
     * @return array Associative array containing all artworks
     */
    public function getAllArtworks($category = '', $sort = '') {
        // Fetch artworks with optional filtering and sorting
        return $this->artworkModel->getAllArtworks($category, $sort);
    }

    /**
     * Resets all filters and retrieves all artworks without any filter or sort
     *
     * @return array Associative array containing all artworks
     */
    public function resetFilters() {
        // Retrieve all artworks with no filters or sorting
        return $this->getAllArtworks();
    }

    /**
     * Retrieves specific artworks based on their IDs
     *
     * @param array $ids An array of artwork IDs to retrieve
     * @return array Associative array containing the artworks that match the given IDs
     */
    public function getArtworksByIds(array $ids) {
        // Ensure the IDs are integers to prevent SQL injection
        $ids = array_map('intval', $ids);
        // Convert the array of IDs to a comma-separated string
        $idsString = implode(',', $ids);
        
        // SQL query to select artworks with the specified IDs
        $sql = "SELECT * FROM artworks WHERE ArtworkID IN ($idsString)";
        
        // Execute the query and fetch the results
        $result = $this->artworkModel->getConnection()->query($sql);
        
        // Fetch all results as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>