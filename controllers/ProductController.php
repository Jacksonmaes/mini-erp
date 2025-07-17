<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function list() {
        $products = $this->model->getAll();
        include 'views/products/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';

            $productId = $this->model->create($name, $price, $description);
            header("Location: index.php?controller=product&action=edit&id=$productId");
            exit;
        }

        include 'views/products/create.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "Produto não encontrado.";
            return;
        }

        $product = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';

            $this->model->update($id, $name, $price, $description);
            header("Location: index.php?controller=product&action=list");
            exit;
        }

        include 'views/products/edit.php';
    }
}
