<?php include 'views/layout/header.php'; ?>

<div class="container mt-4">
    <h2>Novo Produto</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Nome do Produto</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Preço</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="index.php?controller=product&action=list" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
