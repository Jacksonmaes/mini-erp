<?php include 'views/layout/header.php'; ?>

<div class="container mt-4">
    <h2>Produtos</h2>
    <a href="index.php?controller=product&action=create" class="btn btn-success mb-3">Novo Produto</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
                <td><?= nl2br(htmlspecialchars($p['description'])) ?></td>
                <td>
                    <a href="index.php?controller=product&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layout/footer.php'; ?>
