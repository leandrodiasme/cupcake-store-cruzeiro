<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador Dinâmico White-Label | Sistema de Pedidos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ec4899;
            --primary-hover: #db2777;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fdf2f8 0%, #f1f5f9 50%, #eff6ff 100%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .wizard-container {
            width: 100%;
            max-width: 680px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 0 0 1px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .wizard-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 32px 32px 24px;
            text-align: center;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            color: #fbcfe8;
        }

        .wizard-header h1 {
            font-size: 1.65rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .wizard-header p {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        /* Stepper */
        .stepper-bar {
            display: flex;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 24px;
            justify-content: space-between;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            opacity: 0.6;
            transition: all 0.2s ease;
        }

        .step-item.active {
            color: var(--primary);
            opacity: 1;
        }

        .step-item.completed {
            color: var(--success);
            opacity: 1;
        }

        .step-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--border);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .step-item.active .step-circle {
            background: var(--primary);
            color: white;
        }

        .step-item.completed .step-circle {
            background: var(--success);
            color: white;
        }

        /* Form Body */
        .wizard-body {
            padding: 32px;
        }

        .step-pane {
            display: none;
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        .step-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pane-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .pane-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 24px;
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
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="time"],
        select {
            width: 100%;
            padding: 12px 14px;
            font-size: 0.95rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
        }

        /* Quick Chips */
        .chips-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
        }

        .chip-btn {
            background: #f1f5f9;
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            color: #475569;
            transition: all 0.2s ease;
        }

        .chip-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #fdf2f8;
        }

        .chip-btn.selected {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 600;
        }

        /* Checklist Step 3 */
        .checklist-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            padding: 8px 0;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .check-item:last-child {
            border-bottom: none;
        }

        .check-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* Footer buttons */
        .wizard-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 12px 24px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-prev {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-prev:hover {
            background: #e2e8f0;
        }

        .btn-next {
            background: var(--primary);
            color: white;
            margin-left: auto;
        }

        .btn-next:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .btn-submit {
            background: var(--success);
            color: white;
            margin-left: auto;
        }

        .btn-submit:hover {
            background: #059669;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .alert-box {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .stepper-bar {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>

<div class="wizard-container">
    <div class="wizard-header">
        <span class="badge-pill">⚙️ White-Label Setup</span>
        <h1>Instalador Dinâmico</h1>
        <p>Configure a sua loja de pedidos em menos de 2 minutos</p>
    </div>

    <div class="stepper-bar">
        <div class="step-item active" id="stepIndicator1">
            <div class="step-circle">1</div>
            <span>Dados da Loja</span>
        </div>
        <div class="step-item" id="stepIndicator2">
            <div class="step-circle">2</div>
            <span>Administrador</span>
        </div>
        <div class="step-item" id="stepIndicator3">
            <div class="step-circle">3</div>
            <span>Banco & Migrations</span>
        </div>
        <div class="step-item" id="stepIndicator4">
            <div class="step-circle">4</div>
            <span>Conclusão</span>
        </div>
    </div>

    <div class="wizard-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-box">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-box">
                <ul style="padding-left: 18px;">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('install/process') ?>" method="post" id="wizardForm">
            <?= csrf_field() ?>

            <!-- PASSO 1: DADOS DA LOJA -->
            <div class="step-pane active" id="step1">
                <h2 class="pane-title">Passo 1: Identidade da Loja</h2>
                <p class="pane-desc">Defina o nome da sua marca, nicho de atuação e WhatsApp comercial para receber os pedidos.</p>

                <div class="form-group">
                    <label for="store_name">Nome da Loja *</label>
                    <input type="text" name="store_name" id="store_name" required placeholder="Ex: Doce Sonho Cupcakes, Frito Araçatuba" value="<?= old('store_name', 'Doce Encanto Cupcakes') ?>">
                </div>

                <div class="form-group">
                    <label>Segmento / Nicho *</label>
                    <div class="chips-container">
                        <button type="button" class="chip-btn selected" data-segment="Cupcakes & Confeitaria" onclick="selectSegment(this)">🧁 Cupcakes & Confeitaria</button>
                        <button type="button" class="chip-btn" data-segment="Frango Frito & Lanches" onclick="selectSegment(this)">🍗 Frango Frito & Lanches</button>
                        <button type="button" class="chip-btn" data-segment="Hamburgueria Artesanal" onclick="selectSegment(this)">🍔 Hamburgueria Artesanal</button>
                        <button type="button" class="chip-btn" data-segment="Pizzaria Delivery" onclick="selectSegment(this)">🍕 Pizzaria Delivery</button>
                    </div>
                    <input type="text" name="store_segment" id="store_segment" required placeholder="Ou digite outro nicho..." value="<?= old('store_segment', 'Cupcakes & Confeitaria') ?>">
                </div>

                <div class="form-group">
                    <label for="whatsapp_number">Número do WhatsApp Oficial * (com DDD)</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" required placeholder="(18) 99999-9999" value="<?= old('whatsapp_number', '(18) 99765-4321') ?>">
                    <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; display: block;">É para este número que os clientes enviarão o comprovante do pedido já formatado.</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="opening_time">Horário de Abertura *</label>
                        <input type="time" name="opening_time" id="opening_time" required value="<?= old('opening_time', '09:00') ?>">
                    </div>
                    <div class="form-group">
                        <label for="closing_time">Horário de Fechamento *</label>
                        <input type="time" name="closing_time" id="closing_time" required value="<?= old('closing_time', '22:00') ?>">
                    </div>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-next" onclick="goToStep(2)">Continuar para Administrador →</button>
                </div>
            </div>

            <!-- PASSO 2: USUÁRIO ADMINISTRADOR -->
            <div class="step-pane" id="step2">
                <h2 class="pane-title">Passo 2: Primeiro Administrador</h2>
                <p class="pane-desc">Crie as credenciais de acesso ao Painel de Controle e Métricas.</p>

                <div class="form-group">
                    <label for="admin_name">Nome Completo *</label>
                    <input type="text" name="admin_name" id="admin_name" required placeholder="Ex: Leandro Silva" value="<?= old('admin_name', 'Administrador da Loja') ?>">
                </div>

                <div class="form-group">
                    <label for="admin_email">E-mail de Acesso *</label>
                    <input type="email" name="admin_email" id="admin_email" required placeholder="admin@sualoja.com" value="<?= old('admin_email', 'admin@cupcakestore.com') ?>">
                </div>

                <div class="form-group">
                    <label for="admin_password">Senha de Acesso *</label>
                    <input type="password" name="admin_password" id="admin_password" required placeholder="Mínimo de 6 caracteres" value="<?= old('admin_password', 'admin123') ?>">
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-prev" onclick="goToStep(1)">← Voltar</button>
                    <button type="button" class="btn btn-next" onclick="goToStep(3)">Revisar Banco & Migrations →</button>
                </div>
            </div>

            <!-- PASSO 3: MIGRATIONS & BANCO -->
            <div class="step-pane" id="step3">
                <h2 class="pane-title">Passo 3: Banco de Dados & Migrations</h2>
                <p class="pane-desc">O CodeIgniter 4 executará as migrações automáticas para estruturar o banco local SQLite.</p>

                <div class="checklist-box">
                    <div class="check-item">
                        <span class="check-icon">✓</span>
                        <span><strong>Ambiente:</strong> SQLite3 Local (Compatível com MySQL/MariaDB VPS)</span>
                    </div>
                    <div class="check-item">
                        <span class="check-icon">✓</span>
                        <span><strong>Tabelas:</strong> settings, users, categories, products, product_options, product_clicks, orders</span>
                    </div>
                    <div class="check-item">
                        <span class="check-icon">✓</span>
                        <span><strong>Métricas:</strong> Rastreamento de cliques em produtos habilitado</span>
                    </div>
                    <div class="check-item">
                        <span class="check-icon">✓</span>
                        <span><strong>White-Label:</strong> Cores e identidade visual dinâmicas configuradas</span>
                    </div>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-prev" onclick="goToStep(2)">← Voltar</button>
                    <button type="button" class="btn btn-next" onclick="goToStep(4)">Ir para Conclusão →</button>
                </div>
            </div>

            <!-- PASSO 4: CONCLUSÃO -->
            <div class="step-pane" id="step4">
                <h2 class="pane-title">Passo 4: Pronto para Inicializar!</h2>
                <p class="pane-desc">Ao clicar no botão abaixo, as tabelas serão criadas, suas credenciais salvas e você será autenticado no Painel Administrativo.</p>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                    <h3 style="color: #166534; font-size: 1rem; margin-bottom: 8px;">🎉 Tudo Pronto!</h3>
                    <p style="color: #15803d; font-size: 0.88rem; line-height: 1.5;">
                        Sua loja será configurada com dados demonstrativos do segmento escolhido para que você possa testar os pedidos e o checkout imediatamente via WhatsApp.
                    </p>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-prev" onclick="goToStep(3)">← Voltar</button>
                    <button type="submit" class="btn btn-submit" id="submitBtn">🚀 Executar Migrations & Concluir</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    // Máscara Vanilla JS para Telefone WhatsApp: (99) 99999-9999 ou (99) 9999-9999
    const phoneInput = document.getElementById('whatsapp_number');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length > 6) {
            value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
        } else if (value.length > 2) {
            value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
        }
        e.target.value = value;
    });

    // Seleção de Nicho Rápido
    function selectSegment(btn) {
        document.querySelectorAll('.chip-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('store_segment').value = btn.getAttribute('data-segment');
        
        // Ajusta nome sugestivo
        const nameInput = document.getElementById('store_name');
        if (btn.getAttribute('data-segment').includes('Cupcake')) {
            nameInput.value = 'Doce Encanto Cupcakes';
        } else if (btn.getAttribute('data-segment').includes('Frango')) {
            nameInput.value = 'Frito Crocante Araçatuba';
        } else if (btn.getAttribute('data-segment').includes('Hamburguer')) {
            nameInput.value = 'Burger House Gourmet';
        } else if (btn.getAttribute('data-segment').includes('Pizza')) {
            nameInput.value = 'Bella Forneria Pizzas';
        }
    }

    // Navegação em passos
    function goToStep(stepNumber) {
        // Validação simples antes de avançar do passo 1
        if (stepNumber === 2) {
            const name = document.getElementById('store_name').value.trim();
            const segment = document.getElementById('store_segment').value.trim();
            const phone = document.getElementById('whatsapp_number').value.trim();
            if (!name || !segment || phone.length < 10) {
                alert('Por favor, preencha todos os campos obrigatórios da loja antes de prosseguir.');
                return;
            }
        }

        // Validação simples antes de avançar do passo 2
        if (stepNumber === 3) {
            const adminName = document.getElementById('admin_name').value.trim();
            const adminEmail = document.getElementById('admin_email').value.trim();
            const adminPass = document.getElementById('admin_password').value;
            if (!adminName || !adminEmail || adminPass.length < 6) {
                alert('Por favor, preencha nome, e-mail válido e uma senha de no mínimo 6 caracteres.');
                return;
            }
        }

        // Atualiza panes
        document.querySelectorAll('.step-pane').forEach(p => p.classList.remove('active'));
        const currentPane = document.getElementById('step' + stepNumber);
        if (currentPane) currentPane.classList.add('active');

        // Atualiza indicadores
        for (let i = 1; i <= 4; i++) {
            const ind = document.getElementById('stepIndicator' + i);
            ind.classList.remove('active', 'completed');
            if (i < stepNumber) {
                ind.classList.add('completed');
            } else if (i === stepNumber) {
                ind.classList.add('active');
            }
        }
    }

    // Feedback visual ao submeter
    document.getElementById('wizardForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '⏳ Configurando Banco & Instalando...';
    });
</script>

</body>
</html>
