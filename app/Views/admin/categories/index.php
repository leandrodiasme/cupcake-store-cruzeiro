<?= view('admin/layout/header', ['title' => 'Categorias do Cardápio', 'storeName' => $storeName, 'themeColor' => $themeColor]) ?>

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
        transition: opacity 0.2s;
    }

    .btn-primary:hover { opacity: 0.9; }

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
        max-width: 480px;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .form-group { margin-bottom: 16px; }

    label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #334155;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        outline: none;
        font-size: 0.9rem;
    }

    input:focus, textarea:focus { border-color: var(--primary); }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
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
</style>

<div class="action-header">
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 700;">Gerenciar Categorias</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem;">Organize as seções exibidas no cardápio online.</p>
    </div>
    <button type="button" class="btn-primary" onclick="openNewModal()">+ Nova Categoria</button>
</div>

<div class="panel-card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Ordem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><strong><?= esc($cat['display_order']) ?></strong></td>
                            <td><strong><?= esc($cat['name']) ?></strong></td>
                            <td><?= esc($cat['description'] ?? '-') ?></td>
                            <td>
                                <?php if ($cat['active']): ?>
                                    <span class="badge-active">Ativa</span>
                                <?php else: ?>
                                    <span class="badge-inactive">Inativa</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;" onclick='openEditModal(<?= json_encode($cat, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Editar</button>
                                <a href="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')" style="color: #ef4444; font-size: 0.8rem; margin-left: 8px; text-decoration: none; font-weight: 600;">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Nenhuma categoria cadastrada ainda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CRIAR / EDITAR -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal-card">
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 18px;" id="modalTitle">Nova Categoria</h3>

        <form id="categoryForm" method="post" action="<?= base_url('admin/categories/store') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="cat_name">Nome da Categoria *</label>
                <input type="text" name="name" id="cat_name" required placeholder="Ex: Cupcakes Tradicionais">
            </div>

            <div class="form-group">
                <label for="cat_desc">Descrição (opcional)</label>
                <textarea name="description" id="cat_desc" rows="2" placeholder="Breve texto exibido no catálogo"></textarea>
            </div>

            <div class="form-group">
                <label for="cat_order">Ordem de Exibição</label>
                <input type="number" name="display_order" id="cat_order" value="0">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="active" id="cat_active" value="1" checked>
                <label for="cat_active" style="margin-bottom: 0;">Categoria Ativa no Cardápio</label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-primary" id="saveBtn">Salvar Categoria</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNewModal() {
        document.getElementById('modalTitle').innerText = 'Nova Categoria';
        document.getElementById('categoryForm').action = '<?= base_url('admin/categories/store') ?>';
        document.getElementById('cat_name').value = '';
        document.getElementById('cat_desc').value = '';
        document.getElementById('cat_order').value = '0';
        document.getElementById('cat_active').checked = true;
        document.getElementById('categoryModal').classList.add('active');
    }

    function openEditModal(cat) {
        document.getElementById('modalTitle').innerText = 'Editar Categoria';
        document.getElementById('categoryForm').action = '<?= base_url('admin/categories/update/') ?>' + cat.id;
        document.getElementById('cat_name').value = cat.name;
        document.getElementById('cat_desc').value = cat.description || '';
        document.getElementById('cat_order').value = cat.display_order;
        document.getElementById('cat_active').checked = cat.active == 1;
        document.getElementById('categoryModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('categoryModal').classList.remove('active');
    }
</script>

<?= view('admin/layout/footer') ?>
