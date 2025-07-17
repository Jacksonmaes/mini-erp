<?php
require_once 'lib/Database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $description) {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $description]);
        return $this->db->lastInsertId();
    }

    public function update($id, $name, $price, $description) {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $description, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);

        public function getVariations($productId) {
        $stmt = $this->db->prepare("SELECT pv.*, s.quantity
            FROM product_variations pv
            LEFT JOIN stock s ON s.variation_id = pv.id
            WHERE pv.product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveVariations($productId, $variations) {
        // Remover antigas
        $stmt = $this->db->prepare("DELETE FROM product_variations WHERE product_id = ?");
        $stmt->execute([$productId]);

        foreach ($variations as $v) {
            $stmt = $this->db->prepare("INSERT INTO product_variations (product_id, variation_name, variation_value) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $v['name'], $v['value']]);
            $variationId = $this->db->lastInsertId();

            $stockStmt = $this->db->prepare("INSERT INTO stock (product_id, variation_id, quantity) VALUES (?, ?, ?)");
            $stockStmt->execute([$productId, $variationId, $v['quantity']]);
        }
    }
    
    }
}
