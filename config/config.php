<?php

// Database host
define("HOST", "localhost");

// Database name
define("DBNAME", "KUNDRU");

// Database user
define("USER", "root");

// Database password
define("PASS", "");

/**
 * Establishes a connection to the MySQL database using PDO.
 * 
 * This script attempts to connect to a MySQL database using PDO (PHP Data Objects)
 * with the provided configuration. If the connection fails, an exception is caught 
 * and an error message is displayed.
 * 
 * @throws PDOException if the connection fails
 */
try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=".HOST.";dbname=".DBNAME, USER, PASS);
    
    // Set the PDO error mode to exception to handle errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Display error message and exit if connection fails
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>