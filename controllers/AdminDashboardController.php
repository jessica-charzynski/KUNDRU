<?php

class AdminDashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to fetch user details by user ID
    public function getUserDetails($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE UserID = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to fetch all artists
    public function getArtists() {
        $stmt = $this->db->prepare("SELECT * FROM artist");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch all products (artworks)
    public function getProducts() {
        $stmt = $this->db->prepare("SELECT * FROM artwork");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch all users
    public function getUsers() {
        $stmt = $this->db->prepare("SELECT * FROM user");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to add a new artist
    public function addArtist($firstName, $lastName) {
        $stmt = $this->db->prepare("INSERT INTO artist (FirstName, LastName) VALUES (:firstName, :lastName)");
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    // Method to delete an artist by artist ID
    public function deleteArtist($artistId) {
        $stmt = $this->db->prepare("DELETE FROM artist WHERE ArtistID = :artistId");
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Method to update artist details
    public function updateArtist($artistId, $firstName, $lastName) {
        $stmt = $this->db->prepare("UPDATE artist SET FirstName = :firstName, LastName = :lastName WHERE ArtistID = :artistId");
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Method to add a new product (artwork)
    public function addProduct($title, $artistId, $price, $imagePath, $description = null, $category = null) {
        $stmt = $this->db->prepare("INSERT INTO artwork (Title, ArtistID, PriceOfArtprint, ImagePath, Description, Category) VALUES (:title, :artistId, :price, :imagePath, :description, :category)");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    // Method to delete a product (artwork) by product ID
    public function deleteProduct($productId) {
        $stmt = $this->db->prepare("DELETE FROM artwork WHERE ArtworkID = :productId");
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Method to update product (artwork) details
    public function updateProduct($productId, $title, $artistId, $price, $imagePath, $description = null, $category = null) {
        $stmt = $this->db->prepare("UPDATE artwork SET Title = :title, ArtistID = :artistId, PriceOfArtprint = :price, ImagePath = :imagePath, Description = :description, Category = :category WHERE ArtworkID = :productId");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>