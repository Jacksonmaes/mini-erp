<?php include 'views/layout/header.php'; ?>

<div class="container mt-4">
    <h2>Editar Produto</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Nome do Produto</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Preço</label>
            <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="index.php?controller=product&action=list" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
