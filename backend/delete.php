<?php
    header('Content-Type: application/json; charset=utf-8');
    
    require "db.php";

    if (isset($_POST['ids'])) {

        $db = new Database();
        
        $check = $db->deleteProducts($_POST['ids']);

        $products = $db->getProducts();
    
        echo json_encode($products);
    }

    exit;