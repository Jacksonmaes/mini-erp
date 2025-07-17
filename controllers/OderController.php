<?php
require_once 'models/OrderModel.php';
require_once 'models/ProductModel.php';
require_once 'models/CouponModel.php';

class OrderController
{
    private $orderModel;
    private $productModel;
    private $couponModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->couponModel = new CouponModel();
    }

    // Exibe o carrinho
    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];

        $subtotal = 0;

        foreach ($cart as $item) {
            $product = $this->productModel->getById($item['product_id']);
            if ($product) {
                $variation = $this->productModel->getVariation($item['variation_id']);
                $product['variation'] = $variation;
                $product['quantity'] = $item['quantity'];
                $product['total'] = $product['price'] * $item['quantity'];
                $subtotal += $product['total'];
                $products[] = $product;
            }
        }

        include 'views/orders/cart.php';
    }

    // Página de checkout
    public function checkout()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header("Location: index.php?controller=order&action=cart");
            exit;
        }

        $products = [];
        $subtotal = 0;
        $discount = 0;
        $shipping = 15.00; // Frete fixo
        $coupon = null;

        foreach ($cart as $item) {
            $product = $this->productModel->getById($item['product_id']);
            if ($product) {
                $variation = $this->productModel->getVariation($item['variation_id']);
                $product['variation'] = $variation;
                $product['quantity'] = $item['quantity'];
                $product['total'] = $product['price'] * $item['quantity'];
                $subtotal += $product['total'];
                $products[] = $product;
            }
        }

        // Aplica cupom se existir na sessão
        if (!empty($_SESSION['applied_coupon'])) {
            $coupon = $this->couponModel->getByCode($_SESSION['applied_coupon']);
            if ($coupon && $coupon['is_active'] && $coupon['valid_from'] <= date('Y-m-d') && $coupon['valid_until'] >= date('Y-m-d')) {
                if ($subtotal >= $coupon['min_order_value']) {
                    $discount = $coupon['discount_type'] === 'percentage'
                        ? ($subtotal * $coupon['discount_value'] / 100)
                        : $coupon['discount_value'];
                }
            }
        }

        $total = $subtotal + $shipping - $discount;

        include 'views/orders/checkout.php';
    }

    // Finaliza o pedido
    public function finalize()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_name = $_POST['name'] ?? '';
            $customer_email = $_POST['email'] ?? '';
            $customer_cep = $_POST['cep'] ?? '';
            $customer_address = $_POST['address'] ?? '';

            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                header("Location: index.php?controller=order&action=cart");
                exit;
            }

            $subtotal = 0;
            $shipping = 15.00;
            $discount = 0;
            $coupon_id = null;

            foreach ($cart as $item) {
                $product = $this->productModel->getById($item['product_id']);
                if ($product) {
                    $subtotal += $product['price'] * $item['quantity'];
                }
            }

            if (!empty($_SESSION['applied_coupon'])) {
                $coupon = $this->couponModel->getByCode($_SESSION['applied_coupon']);
                if ($coupon && $coupon['is_active']) {
                    $coupon_id = $coupon['id'];
                    if ($subtotal >= $coupon['min_order_value']) {
                        $discount = $coupon['discount_type'] === 'percentage'
                            ? ($subtotal * $coupon['discount_value'] / 100)
                            : $coupon['discount_value'];
                    }
                }
            }

            $total = $subtotal + $shipping - $discount;

            $order_id = $this->orderModel->create([
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_cep' => $customer_cep,
                'customer_address' => $customer_address,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'coupon_id' => $coupon_id
            ]);

            if ($order_id) {
                foreach ($cart as $item) {
                    $this->orderModel->addItem($order_id, $item['product_id'], $item['variation_id'], $item['quantity']);
                    $this->productModel->decreaseStock($item['variation_id'], $item['quantity']);
                }

                unset($_SESSION['cart']);
                unset($_SESSION['applied_coupon']);
                header("Location: index.php?controller=order&action=success&id=$order_id");
                exit;
            } else {
                echo "Erro ao salvar pedido.";
            }
        }
    }

    // Exibe a tela de sucesso
    public function success()
    {
        $id = $_GET['id'] ?? null;
        include 'views/orders/success.php';
    }

    // Adiciona item ao carrinho
    public function addToCart()
    {
        $product_id = $_POST['product_id'] ?? null;
        $variation_id = $_POST['variation_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$product_id || !$variation_id) {
            echo "Produto inválido.";
            return;
        }

        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $quantity
        ];

        header("Location: index.php?controller=order&action=cart");
    }

    // Aplica cupom
    public function applyCoupon()
    {
        $code = $_POST['coupon_code'] ?? '';
        $_SESSION['applied_coupon'] = $code;
        header("Location: index.php?controller=order&action=checkout");
    }

    // Remove item do carrinho
    public function removeItem()
    {
        $index = $_GET['index'] ?? null;
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindexar
        }
        header("Location: index.php?controller=order&action=cart");
    }
}
