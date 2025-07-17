<?php include 'views/layout/header.php'; ?>

<div class="container mt-4">
    <h2>Cadastrar Novo Produto</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço (R$)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <h5>Variações</h5>
        <div id="variations-container">
            <div class="row variation-group mb-2">
                <div class="col-md-4">
                    <input type="text" name="variation_name[]" class="form-control" placeholder="Nome (ex: Tamanho)">
                </div>
                <div class="col-md-4">
                    <input type="text" name="variation_value[]" class="form-control" placeholder="Valor (ex: M)">
                </div>
                <div class="col-md-3">
                    <input type="number" name="variation_quantity[]" class="form-control" placeholder="Quantidade">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary btn-sm" onclick="addVariation()">+ Adicionar Variação</button>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Salvar Produto</button>
            <a href="index.php?controller=product&action=list" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
function addVariation() {
    const container = document.getElementById('variations-container');
    const group = document.createElement('div');
    group.className = 'row variation-group mb-2';
    group.innerHTML = `
        <div class="col-md-4">
            <input type="text" name="variation_name[]" class="form-control" placeholder="Nome (ex: Cor)">
        </div>
        <div class="col-md-4">
            <input type="text" name="variation_value[]" class="form-control" placeholder="Valor (ex: Azul)">
        </div>
        <div class="col-md-3">
            <input type="number" name="variation_quantity[]" class="form-control" placeholder="Quantidade">
        </div>
    `;
    container.appendChild(group);
}
</script>

<?php include 'views/layout/footer.php'; ?>
