<?php
    class Database {
        private $host = 'localhost';
        private $database = 'id18364694_db_products';
        private $user = 'id18364694_root';
        private $pass = '[\cq9\7YA]H?P!TO';

        private $pdo = null;

        function __construct() {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->pass);
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }

        public function generateDatabase() {
            $this->pdo->exec("
                SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
                START TRANSACTION;
                SET time_zone = '+00:00';
            
                CREATE TABLE IF NOT EXISTS `products` (
                    `id` int(11) NOT NULL,
                    `sku` text NOT NULL,
                    `name` text NOT NULL,
                    `price` int(11) NOT NULL,
                    `type` enum('dvd','book','furniture') NOT NULL,
                    `size` int(11) NOT NULL,
                    `weight` int(11) NOT NULL,
                    `height` int(11) NOT NULL,
                    `width` int(11) NOT NULL,
                    `length` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                ALTER TABLE `products`
                    ADD PRIMARY KEY (`id`);
                
                ALTER TABLE `products`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
                COMMIT;");

            $return = $this->populateDatabase();

            return $return;
        }

        public function populateDatabase() {
            $products = [
                ['sku' => 'JVC20123', 'name' => 'Acme DISC 1', 'price' => 5, 'type' => 'dvd', 'size' => 128, 'weight' => 0, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'JVC20124', 'name' => 'Acme DISC 2', 'price' => 7, 'type' => 'dvd', 'size' => 256, 'weight' => 0, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'JVC20125', 'name' => 'Acme DISC 3', 'price' => 9, 'type' => 'dvd', 'size' => 512, 'weight' => 0, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'JVC20126', 'name' => 'Acme DISC 4', 'price' => 12, 'type' => 'dvd', 'size' => 1024, 'weight' => 0, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'GGWP0007', 'name' => 'War and Peace 1', 'price' => 51, 'type' => 'book', 'size' => 0, 'weight' => 1, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'GGWP0008', 'name' => 'War and Peace 2', 'price' => 52, 'type' => 'book', 'size' => 0, 'weight' => 1, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'GGWP0009', 'name' => 'War and Peace 3', 'price' => 53, 'type' => 'book', 'size' => 0, 'weight' => 1, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'GGWP0010', 'name' => 'War and Peace 4', 'price' => 54, 'type' => 'book', 'size' => 0, 'weight' => 1, 'height' => 0, 'width' => 0, 'length' => 0],
                ['sku' => 'TR120555', 'name' => 'Chair 1', 'price' => 40, 'type' => 'furniture', 'size' => 0, 'weight' => 0, 'height' => 24, 'width' => 45, 'length' => 12],
                ['sku' => 'TR120556', 'name' => 'Chair 2', 'price' => 42, 'type' => 'furniture', 'size' => 0, 'weight' => 0, 'height' => 24, 'width' => 45, 'length' => 12],
                ['sku' => 'TR120557', 'name' => 'Chair 3', 'price' => 44, 'type' => 'furniture', 'size' => 0, 'weight' => 0, 'height' => 24, 'width' => 45, 'length' => 12],
                ['sku' => 'TR120558', 'name' => 'Chair 4', 'price' => 46, 'type' => 'furniture', 'size' => 0, 'weight' => 0, 'height' => 24, 'width' => 45, 'length' => 12],
            ];

            $return = $this->insertProducts($products);

            return $return;
        }

        public function getProduct($id) {
            if ($id && is_int($id)) {
                $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=:id");
                $stmt->execute(['id' => $id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                return $product;
            } else {
                return false;
            }
        }

        public function getProducts() {
            $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $products;
        }

        public function deleteProduct($id) {
            if ($id && is_numeric($id)) {
                $stmt = $this->pdo->prepare("DELETE FROM products WHERE id=:id");
                $check = $stmt->execute(['id' => intval($id)]);

                return $check;
            }

            return false;
        }

        public function deleteProducts($ids) {
            foreach($ids as $id) {
                $check = $this->deleteProduct($id);

                if (!$check) {
                    return false;
                }
            }

            return true;
        }

        public function checkFields($product) {
            if ($product['sku'] == '' || $product['name'] == '' || $product['price'] == '' || 
                ($product['type'] == 'dvd' && $product['size'] == '') ||
                ($product['type'] == 'book' && $product['weight'] == '') ||
                ($product['type'] == 'furniture' && ($product['height'] == '' || $product['width'] == '' || $product['length'] == ''))) {
                    return [
                        'status' => 'error',
                        'message' => 'Please, submit required data'           
                    ];
            }

            if (!is_numeric($product['price']) || 
                ($product['type'] == 'dvd' && !is_numeric($product['size'])) || 
                ($product['type'] == 'book' && !is_numeric($product['weight'])) || 
                ($product['type'] == 'furniture' && (!is_numeric($product['height']) || !is_numeric($product['width']) || !is_numeric($product['length'])))) {
                    return [
                        'status' => 'error',
                        'message' => 'Please, provide the data of indicated type'           
                    ];
            }

            return [
                'status' => 'success',
                'message' => 'The product was added to Database'           
            ];
        }

        public function insertProduct($product) {
            $stmt= $this->pdo->prepare("INSERT INTO products (sku, name, price, type, size, weight, height, width, length) VALUES (:sku, :name, :price, :type, :size, :weight, :height, :width, :length)");
            $return = $stmt->execute([
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => (is_numeric($product['price']) ? intval($product['price']) : 0),
                'type' => $product['type'],
                'size' => ($product['type'] == "dvd" ? intval($product['size']) : 0),
                'weight' => ($product['type'] == "book" ? intval($product['weight']) : 0),
                'height' => ($product['type'] == "furniture" ? intval($product['height']) : 0),
                'width' => ($product['type'] == "furniture" ? intval($product['width']) : 0),
                'length' => ($product['type'] == "furniture" ? intval($product['length']) : 0),
            ]);

            return $return;
        }

        public function insertProducts($products) {
            foreach($products as $product) {
                $return = $this->insertProduct($product);

                if ($return) {
                    continue;
                } else {
                    return false;
                }
            }
        }
    }