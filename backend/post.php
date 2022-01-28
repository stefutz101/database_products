<?php
    header('Content-Type: application/json; charset=utf-8');
    
    require "db.php";

    if (isset($_POST['product'])) {
        
        $db = new Database();

        $output = $db->checkFields($_POST['product']);

        if ($output['status'] == 'success') {
            $check = $db->insertProduct($_POST['product']);

            if ($check) {
                echo json_encode($output);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Something went wrong'           
                ]);
            }
        } else {
            echo json_encode($output);
        }
    }
    
    exit;