<?php include '../views/layout/header.php'; ?>
<div class="container mt-5">
    <h2>Carrinho</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Preço</th>
                    <th>Qtd</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subtotal = 0;
                foreach ($_SESSION['cart'] as $item):
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                ?>
                <tr>
                    <td><?= $item['product_name'] ?></td>
                    <td><?= $item['variation'] ?></td>
                    <td>R$<?= number_format($item['price'], 2, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>R$<?= number_format($itemTotal, 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Subtotal:</strong> R$<?= number_format($subtotal, 2, ',', '.') ?></p>
        <a href="/orders/checkout" class="btn btn-success">Finalizar Pedido</a>
    <?php endif; ?>
</div>
<?php include '../views/layout/footer.php'; ?>
