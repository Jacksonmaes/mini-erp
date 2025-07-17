<?php include '../views/layout/header.php'; ?>
<div class="container mt-5">
    <h2>Finalizar Pedido</h2>
    <form method="POST" action="/orders/process_checkout">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="customer_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>CEP:</label>
            <input type="text" name="customer_cep" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Endereço:</label>
            <input type="text" name="customer_address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cupom (opcional):</label>
            <input type="text" name="coupon_code" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Finalizar</button>
    </form>
</div>
<?php include '../views/layout/footer.php'; ?>
