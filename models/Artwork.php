<?php

class Artwork {
    private $conn;

    /**
     * Constructor to initialize the Artwork model with a database connection
     *
     * @param PDO $conn The PDO database connection object
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves all artworks from the database with optional filters and sorting
     *
     * @param string $category The category to filter by
     * @param string $sort The sort order
     * @return array Associative array containing all artworks
     */
    public function getAllArtworks($category = '', $sort = '') {
        $sql = "SELECT Artwork.*, Artist.FirstName, Artist.LastName 
                FROM Artwork 
                INNER JOIN Artist ON Artwork.ArtistID = Artist.ArtistID";

        // Apply category filter if selected
        if (!empty($category)) {
            $sql .= " WHERE Category = :category";
        }

        // Apply sorting if selected
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY PriceOfArtprint ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY PriceOfArtprint DESC";
                break;
            default:
                $sql .= " ORDER BY ArtworkID ASC";
                break;
        }

        $stmt = $this->conn->prepare($sql);

        // Bind category parameter if selected
        if (!empty($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a single artwork by its ID
     *
     * @param int $artworkID The ID of the artwork
     * @return array Associative array containing the artwork details
     */
    public function getArtworkByID($artworkID) {
        $sql = "SELECT Artwork.*, Artist.FirstName, Artist.LastName 
                FROM Artwork 
                INNER JOIN Artist ON Artwork.ArtistID = Artist.ArtistID
                WHERE Artwork.ArtworkID = :artworkID";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':artworkID', $artworkID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a specified number of random artworks excluding a given artwork ID
     *
     * @param int $limit The number of random artworks to retrieve
     * @param int $excludeID The ID of the artwork to exclude from the results
     * @return array Associative array containing random artworks
     */
    public function getRandomArtworks($limit, $excludeID) {
        $sql = "SELECT * FROM Artwork WHERE ArtworkID != :excludeID ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':excludeID', $excludeID, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Searches artworks by Title or Artist name
     *
     * @param string $query The search query
     * @return array Associative array containing search results
     */
    public function searchArtworks($query) {
        $sql = "SELECT Artwork.*, Artist.FirstName, Artist.LastName 
                FROM Artwork 
                INNER JOIN Artist ON Artwork.ArtistID = Artist.ArtistID
                WHERE Artwork.Title LIKE :query OR CONCAT(Artist.FirstName, ' ', Artist.LastName) LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>