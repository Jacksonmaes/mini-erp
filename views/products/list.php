<?php include 'views/layout/header.php'; ?>

<div class="container mt-4">
    <h2>Lista de Produtos</h2>

    <a href="index.php?controller=product&action=create" class="btn btn-primary mb-3">+ Novo Produto</a>

    <?php if (!empty($products)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Descrição</th>
                        <th>Variações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>R$ <?= number_format($product['price'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>
                                <?php if (!empty($product['variations'])): ?>
                                    <ul class="mb-0">
                                        <?php foreach ($product['variations'] as $variation): ?>
                                            <li>
                                                <?= $variation['variation_name'] ?>: <?= $variation['variation_value'] ?>
                                                (Estoque: <?= $variation['quantity'] ?>)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted">Sem variações</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="index.php?controller=product&action=delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhum produto cadastrado ainda.</div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
