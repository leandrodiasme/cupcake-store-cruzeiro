<?= view('admin/layout/header', ['title' => 'Produtos do Cardápio', 'storeName' => $storeName, 'themeColor' => $themeColor]) ?>

<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .panel-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
    }

    td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        color: #334155;
    }

    .prod-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        padding: 16px;
    }

    .modal-overlay.active { display: flex; }

    .modal-card {
        background: white;
        width: 100%;
        max-width: 580px;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        max-height: 90vh;
        overflow-y: auto;
    }

    .form-group { margin-bottom: 16px; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #334155;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        outline: none;
        font-size: 0.9rem;
    }

    input:focus, select:focus, textarea:focus { border-color: var(--primary); }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .opt-row {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }
</style>

<div class="action-header">
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 700;">Gerenciar Produtos</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem;">Cadastre itens, preços, fotos e opções de adicionais.</p>
    </div>
    <button type="button" class="btn-primary" onclick="openNewModal()">+ Novo Produto</button>
</div>

<div class="panel-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome do Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($products)): ?>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td>
                                <img src="<?= esc($prod['image_url'] ?? 'https://images.unsplash.com/photo-1576618148400-f54bed99fcfd?auto=format&fit=crop&w=100&q=80') ?>" class="prod-thumb" alt="">
                            </td>
                            <td>
                                <strong><?= esc($prod['name']) ?></strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($prod['description'] ?? '-') ?></div>
                            </td>
                            <td><?= esc($prod['category_name'] ?? 'Geral') ?></td>
                            <td><strong>R$ <?= number_format($prod['price'], 2, ',', '.') ?></strong></td>
                            <td>
                                <?php if ($prod['active']): ?>
                                    <span class="badge-active">Ativo</span>
                                <?php else: ?>
                                    <span class="badge-inactive">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" onclick='openEditModal(<?= json_encode($prod, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Editar</button>
                                <a href="<?= base_url('admin/products/delete/' . $prod['id']) ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')" style="color: #ef4444; font-size: 0.8rem; margin-left: 8px; text-decoration: none; font-weight: 600;">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Nenhum produto cadastrado ainda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL NOVO / EDITAR PRODUTO -->
<div class="modal-overlay" id="productModal">
    <div class="modal-card">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 18px;" id="modalTitle">Novo Produto</h3>

        <form id="productForm" method="post" action="<?= base_url('admin/products/store') ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="prod_cat">Categoria *</label>
                    <select name="category_id" id="prod_cat" required>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="prod_price">Preço (R$) *</label>
                    <input type="number" step="0.01" name="price" id="prod_price" required placeholder="0.00">
                </div>
            </div>

            <div class="form-group">
                <label for="prod_name">Nome do Produto *</label>
                <input type="text" name="name" id="prod_name" required placeholder="Ex: Cupcake de Brigadeiro">
            </div>

            <div class="form-group">
                <label for="prod_desc">Descrição Detalhada</label>
                <textarea name="description" id="prod_desc" rows="2" placeholder="Ingredientes, sabor, detalhes..."></textarea>
            </div>

            <div class="form-group">
                <label for="prod_img">URL da Imagem</label>
                <input type="text" name="image_url" id="prod_img" placeholder="https://exemplo.com/foto.jpg">
            </div>

            <!-- Adicionais (Apenas no cadastro) -->
            <div id="optionsSection">
                <label style="margin-top: 16px;">Opcionais / Adicionais (Ex: Granulado, Embalagem especial):</label>
                <div id="optionsList"></div>
                <button type="button" class="btn-secondary" style="font-size: 0.8rem; margin-top: 6px;" onclick="addOptionRow()">+ Adicionar Opção</button>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 16px;">
                <input type="checkbox" name="active" id="prod_active" value="1" checked>
                <label for="prod_active" style="margin-bottom: 0;">Produto Ativo no Catálogo</label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Salvar Produto</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNewModal() {
        document.getElementById('modalTitle').innerText = 'Novo Produto';
        document.getElementById('productForm').action = '<?= base_url('admin/products/store') ?>';
        document.getElementById('prod_name').value = '';
        document.getElementById('prod_desc').value = '';
        document.getElementById('prod_price').value = '';
        document.getElementById('prod_img').value = '';
        document.getElementById('prod_active').checked = true;
        document.getElementById('optionsSection').style.display = 'block';
        document.getElementById('optionsList').innerHTML = '';
        document.getElementById('productModal').classList.add('active');
    }

    function openEditModal(prod) {
        document.getElementById('modalTitle').innerText = 'Editar Produto';
        document.getElementById('productForm').action = '<?= base_url('admin/products/update/') ?>' + prod.id;
        document.getElementById('prod_name').value = prod.name;
        document.getElementById('prod_desc').value = prod.description || '';
        document.getElementById('prod_price').value = prod.price;
        document.getElementById('prod_img').value = prod.image_url || '';
        document.getElementById('prod_cat').value = prod.category_id;
        document.getElementById('prod_active').checked = prod.active == 1;
        document.getElementById('optionsSection').style.display = 'none';
        document.getElementById('productModal').classList.add('active');
    }

    function addOptionRow() {
        const list = document.getElementById('optionsList');
        const row = document.createElement('div');
        row.className = 'opt-row';
        row.innerHTML = `
            <input type="text" name="option_names[]" placeholder="Nome do Adicional" style="flex: 2;">
            <input type="number" step="0.01" name="option_prices[]" placeholder="Preço (0.00)" style="flex: 1;">
            <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer;">×</button>
        `;
        list.appendChild(row);
    }

    function closeModal() {
        document.getElementById('productModal').classList.remove('active');
    }
</script>

<?= view('admin/layout/footer') ?>
