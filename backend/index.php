<?php
    header('Content-Type: application/json; charset=utf-8');
    
    require "db.php";
    
    $db = new Database();
    $db->generateDatabase();