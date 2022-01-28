<?php
    header('Content-Type: application/json; charset=utf-8');
    
    require "db.php";
    
    $db = new Database();
    $products = $db->getProducts();
    
    echo json_encode($products);
    exit;