<?php

class OrderModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createOrder($data) {
        try {
            $this->db->beginTransaction();

            // Inserir pedido
            $stmt = $this->db->prepare("
                INSERT INTO orders (customer_name, customer_email, customer_cep, customer_address, subtotal, shipping, discount, total, coupon_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_cep'],
                $data['customer_address'],
                $data['subtotal'],
                $data['shipping'],
                $data['discount'],
                $data['total'],
                $data['coupon_id']
            ]);

            $orderId = $this->db->lastInsertId();

            // Inserir itens
            foreach ($data['items'] as $item) {
                $stmt = $this->db->prepare("
                    INSERT INTO order_items (order_id, product_id, variation_id, quantity, unit_price)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['variation_id'],
                    $item['quantity'],
                    $item['unit_price']
                ]);

                // Atualizar estoque
                $stmtStock = $this->db->prepare("
                    UPDATE stock SET quantity = quantity - ? WHERE product_id = ? AND variation_id = ?
                ");
                $stmtStock->execute([
                    $item['quantity'],
                    $item['product_id'],
                    $item['variation_id']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getOrder($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
