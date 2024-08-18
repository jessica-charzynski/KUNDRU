<?php

// Include the HomeController
include('controllers/HomeController.php');

// Instantiate the HomeController and call the index method
$controller = new HomeController();
$controller->index();

?>