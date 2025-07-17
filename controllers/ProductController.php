<?php
require_once 'models/ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    // Lista todos os produtos
    public function list() {
        $products = $this->model->getAll();
        include 'views/products/list.php';
    }

    // Cria um novo produto
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';

            $productId = $this->model->create($name, $price, $description);

            // Variações e estoque (opcional no cadastro inicial)
            $variations = [];
            if (!empty($_POST['variation_name'])) {
                foreach ($_POST['variation_name'] as $i => $name) {
                    $variations[] = [
                        'name' => $name,
                        'value' => $_POST['variation_value'][$i],
                        'quantity' => $_POST['variation_quantity'][$i]
                    ];
                }
                $this->model->saveVariations($productId, $variations);
            }

            header("Location: index.php?controller=product&action=edit&id=$productId");
            exit;
        }

        include 'views/products/create.php';
    }

    // Edita um produto existente
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "Produto não encontrado.";
            return;
        }

        $product = $this->model->getById($id);
        $variations = $this->model->getVariations($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';

            $this->model->update($id, $name, $price, $description);

            // Variações
            $variations = [];
            if (!empty($_POST['variation_name'])) {
                foreach ($_POST['variation_name'] as $i => $vName) {
                    $variations[] = [
                        'name' => $vName,
                        'value' => $_POST['variation_value'][$i],
                        'quantity' => $_POST['variation_quantity'][$i]
                    ];
                }
                $this->model->saveVariations($id, $variations);
            }

            header("Location: index.php?controller=product&action=list");
            exit;
        }

        include 'views/products/edit.php';
    }

    // Remove um produto (opcional)
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header("Location: index.php?controller=product&action=list");
    }
}
