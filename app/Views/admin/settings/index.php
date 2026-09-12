<?= view('admin/layout/header', ['title' => 'Configurações White-Label', 'storeName' => $storeName, 'themeColor' => $themeColor]) ?>

<style>
    .panel-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 32px;
        max-width: 680px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    label {
        display: block;
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #334155;
    }

    input[type="text"],
    input[type="time"],
    select {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        outline: none;
        font-size: 0.95rem;
    }

    input:focus, select:focus { border-color: var(--primary); }

    .color-picker-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    input[type="color"] {
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 8px;
        cursor: pointer;
        background: none;
    }

    .btn-save {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        margin-top: 10px;
    }
</style>

<div class="panel-card">
    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 6px;">Identidade da Loja & Regras White-Label</h3>
    <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 28px;">Personalize o nome da marca, número do WhatsApp comercial, horários de atendimento e paleta de cores.</p>

    <form action="<?= base_url('admin/settings/update') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="store_name">Nome da Loja *</label>
            <input type="text" name="store_name" id="store_name" required value="<?= esc($settings['store_name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="store_segment">Segmento / Nicho *</label>
            <input type="text" name="store_segment" id="store_segment" required value="<?= esc($settings['store_segment'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="whatsapp_number">Número do WhatsApp Oficial * (com DDD)</label>
            <input type="text" name="whatsapp_number" id="whatsapp_number" required value="<?= esc($settings['whatsapp_number'] ?? '') ?>">
            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">Destinatário de todos os pedidos finalizados pelo catálogo.</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="opening_time">Horário de Abertura *</label>
                <input type="time" name="opening_time" id="opening_time" required value="<?= esc($settings['opening_time'] ?? '09:00') ?>">
            </div>

            <div class="form-group">
                <label for="closing_time">Horário de Fechamento *</label>
                <input type="time" name="closing_time" id="closing_time" required value="<?= esc($settings['closing_time'] ?? '22:00') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="theme_color">Cor Primária da Marca (White-Label)</label>
            <div class="color-picker-group">
                <input type="color" name="theme_color" id="theme_color" value="<?= esc($settings['theme_color'] ?? '#ec4899') ?>" onchange="document.getElementById('color_hex').value = this.value">
                <input type="text" id="color_hex" value="<?= esc($settings['theme_color'] ?? '#ec4899') ?>" style="width: 130px;" oninput="document.getElementById('theme_color').value = this.value">
            </div>
        </div>

        <button type="submit" class="btn-save">Salvar Configurações</button>
    </form>
</div>

<?= view('admin/layout/footer') ?>
