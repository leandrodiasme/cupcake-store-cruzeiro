<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($storeName) ?> | Pedidos Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: <?= esc($themeColor) ?>;
            --primary-light: <?= esc($themeColor) ?>1a;
            --primary-hover: #be185d;
            --text: #1e293b;
            --text-muted: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --radius: 14px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            padding-bottom: 90px;
        }

        /* Header White-Label */
        .navbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 16px 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .navbar-content {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .brand-info h1 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
        }

        .brand-info span {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .status-badge.open {
            background: #dcfce7;
            color: #15803d;
        }

        .status-badge.closed {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Banner Aviso Loja Fechada */
        .closed-alert-banner {
            background: #fef2f2;
            border-bottom: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 24px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Hero / Container Principal */
        .main-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 16px;
        }

        .hero-banner {
            background: linear-gradient(135deg, var(--card) 0%, var(--primary-light) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .hero-text h2 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--text);
        }

        .hero-text p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .hours-badge {
            background: white;
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Categorias Sticky Nav */
        .categories-nav {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 12px;
            margin-bottom: 24px;
            scrollbar-width: none;
        }

        .categories-nav::-webkit-scrollbar {
            display: none;
        }

        .category-tab {
            padding: 8px 18px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 9999px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .category-tab:hover, .category-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Seção de Produtos */
        .category-section {
            margin-bottom: 40px;
        }

        .category-title {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 4px;
            color: var(--text);
        }

        .category-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 18px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
        }

        .product-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .product-content {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text);
        }

        .product-desc {
            font-size: 0.82rem;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 14px;
            flex-grow: 1;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .product-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text);
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-add:hover {
            opacity: 0.9;
            transform: scale(1.03);
        }

        /* Floating Cart Bar */
        .cart-floating-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--primary);
            color: white;
            padding: 14px 24px;
            border-radius: 9999px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            z-index: 50;
            font-weight: 700;
            border: none;
            transition: transform 0.2s ease;
        }

        .cart-floating-btn:hover {
            transform: scale(1.05);
        }

        .cart-count {
            background: white;
            color: var(--primary);
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
        }

        /* Modals & Drawers */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-card {
            background: white;
            width: 100%;
            max-width: 520px;
            border-radius: var(--radius);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            animation: slideUp 0.25s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 1.15rem;
            font-weight: 800;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--text-muted);
            line-height: 1;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Cart Items List */
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-title {
            font-weight: 700;
            font-size: 0.92rem;
        }

        .cart-item-opts {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .cart-qty-ctrl {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 4px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: white;
            border-radius: 6px;
            font-weight: 800;
            cursor: pointer;
            color: var(--text);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .qty-val {
            font-weight: 700;
            font-size: 0.9rem;
            min-width: 20px;
            text-align: center;
        }

        .btn-remove {
            color: var(--danger);
            background: none;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 4px;
        }

        /* Checkout Form */
        .form-group {
            margin-bottom: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #334155;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            font-size: 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: #f8fafc;
            color: var(--text);
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .payment-pill-group {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            margin-top: 6px;
        }

        .payment-pill {
            border: 1.5px solid var(--border);
            padding: 10px 8px;
            border-radius: 8px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .payment-pill.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Feedback Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #1e293b;
            color: white;
            padding: 12px 20px;
            border-radius: 9999px;
            font-size: 0.88rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 200;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .toast-undo {
            color: #38bdf8;
            background: none;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .hero-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR WHITE-LABEL -->
    <header class="navbar">
        <div class="navbar-content">
            <a href="<?= base_url() ?>" class="brand">
                <div class="brand-logo">🧁</div>
                <div class="brand-info">
                    <h1><?= esc($storeName) ?></h1>
                    <span><?= esc($storeSegment) ?></span>
                </div>
            </a>

            <div>
                <?php if ($isOpen): ?>
                    <span class="status-badge open">
                        <span class="status-dot"></span>
                        Loja Aberta
                    </span>
                <?php else: ?>
                    <span class="status-badge closed">
                        <span class="status-dot"></span>
                        Loja Fechada
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ALERTA SE LOJA FECHADA -->
    <?php if (! $isOpen): ?>
        <div class="closed-alert-banner">
            <span>⚠️ Atenção: Nosso expediente é das <?= esc($openingTime) ?> às <?= esc($closingTime) ?>. No momento a loja está fechada e o checkout está bloqueado.</span>
        </div>
    <?php endif; ?>

    <main class="main-container">
        <!-- HERO CARD -->
        <section class="hero-banner">
            <div class="hero-text">
                <h2>Seja bem-vindo(a)! 👋</h2>
                <p>Faça seu pedido online de forma rápida e confirme direto pelo WhatsApp oficial.</p>
            </div>
            <div class="hours-badge">
                ⏰ Horário: <strong><?= esc($openingTime) ?> às <?= esc($closingTime) ?></strong>
            </div>
        </section>

        <!-- CATEGORIAS -->
        <?php if (! empty($catalog)): ?>
            <div class="categories-nav">
                <?php foreach ($catalog as $i => $cat): ?>
                    <a href="#cat-<?= $cat['id'] ?>" class="category-tab <?= $i === 0 ? 'active' : '' ?>">
                        <?= esc($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- PRODUTOS POR CATEGORIA -->
            <?php foreach ($catalog as $cat): ?>
                <section class="category-section" id="cat-<?= $cat['id'] ?>">
                    <h3 class="category-title"><?= esc($cat['name']) ?></h3>
                    <?php if (! empty($cat['description'])): ?>
                        <p class="category-desc"><?= esc($cat['description']) ?></p>
                    <?php endif; ?>

                    <div class="products-grid">
                        <?php foreach ($cat['products'] as $prod): ?>
                            <div class="product-card" onclick="openProductModal(<?= htmlspecialchars(json_encode($prod), ENT_QUOTES, 'UTF-8') ?>)">
                                <img src="<?= esc($prod['image_url'] ?? 'https://images.unsplash.com/photo-1576618148400-f54bed99fcfd?auto=format&fit=crop&w=600&q=80') ?>" alt="<?= esc($prod['name']) ?>" class="product-img" loading="lazy">
                                <div class="product-content">
                                    <h4 class="product-name"><?= esc($prod['name']) ?></h4>
                                    <p class="product-desc"><?= esc($prod['description']) ?></p>
                                    <div class="product-footer">
                                        <span class="product-price">R$ <?= number_format($prod['price'], 2, ',', '.') ?></span>
                                        <button type="button" class="btn-add">
                                            <?= ! empty($prod['options']) ? 'Personalizar' : '+ Adicionar' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: var(--radius); border: 1px solid var(--border);">
                <h3>Nenhum produto disponível no momento</h3>
                <p style="color: var(--text-muted); margin-top: 8px;">Acesse o painel administrativo para cadastrar produtos e categorias.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- BOTAO FLUTUANTE DO CARRINHO -->
    <button class="cart-floating-btn" id="cartFloatingBtn" onclick="openCartModal()" style="display: none;">
        <div class="cart-count" id="cartCountBadge">0</div>
        <span>Ver Sacola</span>
        <span id="cartSubtotalBadge">R$ 0,00</span>
    </button>

    <!-- MODAL DE DETALHES / OPÇÕES DO PRODUTO -->
    <div class="modal-overlay" id="productModalOverlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalProdTitle">Detalhes do Produto</h3>
                <button type="button" class="modal-close" onclick="closeProductModal()">×</button>
            </div>
            <div class="modal-body">
                <img id="modalProdImg" src="" style="width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 14px; display: none;">
                <p id="modalProdDesc" style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 16px;"></p>
                <div style="font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 20px;" id="modalProdPrice">R$ 0,00</div>

                <!-- Adicionais -->
                <div id="modalProdOptionsContainer" style="display: none; margin-bottom: 20px;">
                    <label style="font-size: 0.9rem; font-weight: 700; margin-bottom: 10px; display: block;">Adicionais & Opcionais:</label>
                    <div id="modalProdOptionsList" style="display: flex; flex-direction: column; gap: 8px;"></div>
                </div>

                <!-- Quantidade -->
                <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border);">
                    <span style="font-weight: 700; font-size: 0.9rem;">Quantidade:</span>
                    <div class="cart-qty-ctrl">
                        <button type="button" class="qty-btn" onclick="adjustModalQty(-1)">-</button>
                        <span class="qty-val" id="modalProdQty">1</span>
                        <button type="button" class="qty-btn" onclick="adjustModalQty(1)">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Subtotal</span>
                    <strong style="font-size: 1.15rem; color: var(--text);" id="modalProdTotal">R$ 0,00</strong>
                </div>
                <button type="button" class="btn-add" style="padding: 12px 24px; font-size: 0.95rem;" onclick="confirmAddToCart()">Adicionar ao Pedido</button>
            </div>
        </div>
    </div>

    <!-- MODAL DE CARRINHO / CHECKOUT -->
    <div class="modal-overlay" id="cartModalOverlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Seu Pedido</h3>
                <button type="button" class="modal-close" onclick="closeCartModal()">×</button>
            </div>
            <div class="modal-body">
                <!-- Itens da Sacola -->
                <div id="cartItemsList"></div>

                <!-- Formulário de Entrega -->
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 14px;">📍 Dados de Entrega</h4>

                    <div class="form-group">
                        <label for="checkout_name">Seu Nome *</label>
                        <input type="text" id="checkout_name" placeholder="Ex: Maria Oliveira" required>
                    </div>

                    <div class="form-group">
                        <label for="checkout_phone">WhatsApp de Contato *</label>
                        <input type="text" id="checkout_phone" placeholder="(99) 99999-9999" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkout_cep">CEP * (Busca Automática)</label>
                            <input type="text" id="checkout_cep" placeholder="99999-999" maxlength="9">
                            <small id="cepLoading" style="color: var(--primary); font-size: 0.75rem; display: none;">Buscando endereço...</small>
                        </div>
                        <div class="form-group">
                            <label for="checkout_number">Número *</label>
                            <input type="text" id="checkout_number" placeholder="Ex: 123">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkout_street">Rua / Logradouro *</label>
                        <input type="text" id="checkout_street" placeholder="Rua das Flores">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="checkout_neighborhood">Bairro *</label>
                            <input type="text" id="checkout_neighborhood" placeholder="Centro">
                        </div>
                        <div class="form-group">
                            <label for="checkout_city">Cidade *</label>
                            <input type="text" id="checkout_city" placeholder="Sua Cidade">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkout_complement">Complemento (opcional)</label>
                        <input type="text" id="checkout_complement" placeholder="Apto 42, Bloco B">
                    </div>

                    <!-- Forma de Pagamento -->
                    <h4 style="font-size: 1rem; font-weight: 700; margin-top: 20px; margin-bottom: 10px;">💳 Pagamento</h4>
                    <div class="payment-pill-group">
                        <div class="payment-pill selected" onclick="selectPayment('PIX', this)">📱 PIX</div>
                        <div class="payment-pill" onclick="selectPayment('Cartão', this)">💳 Cartão</div>
                        <div class="payment-pill" onclick="selectPayment('Dinheiro', this)">💵 Dinheiro</div>
                    </div>

                    <div class="form-group" id="changeGroup" style="display: none; margin-top: 14px;">
                        <label for="checkout_change">Precisa de troco para quanto?</label>
                        <input type="number" id="checkout_change" placeholder="Ex: 50,00" step="0.50">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Total do Pedido</span>
                    <strong style="font-size: 1.25rem; color: var(--text);" id="cartModalTotal">R$ 0,00</strong>
                </div>

                <?php if ($isOpen): ?>
                    <button type="button" class="btn-add" id="btnSubmitOrder" style="padding: 12px 20px;" onclick="submitOrder()">
                        Finalizar no WhatsApp ➔
                    </button>
                <?php else: ?>
                    <button type="button" class="btn-add" style="padding: 12px 20px; background: #94a3b8; cursor: not-allowed;" disabled title="Loja Fechada no momento">
                        ⛔ Loja Fechada
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TOAST DE REVERSIBILIDADE / IHC -->
    <div class="toast" id="feedbackToast">
        <span id="toastMessage">Item removido</span>
        <button type="button" class="toast-undo" id="toastUndoBtn" onclick="undoLastAction()">Desfazer</button>
    </div>

<script>
    // ESTADO DA APLICAÇÃO
    const isStoreOpen = <?= $isOpen ? 'true' : 'false' ?>;
    let cart = JSON.parse(localStorage.getItem('cupcake_store_cart') || '[]');
    let currentModalProduct = null;
    let currentModalQty = 1;
    let selectedPaymentMethod = 'PIX';
    let lastDeletedItem = null;

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        renderCartUI();
        initMasks();
        initViaCep();
    });

    // MÁSCARAS VANILLA JS (IHC)
    function initMasks() {
        const phoneInput = document.getElementById('checkout_phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 11) v = v.slice(0, 11);
                if (v.length > 6) {
                    v = `(${v.slice(0, 2)}) ${v.slice(2, 7)}-${v.slice(7)}`;
                } else if (v.length > 2) {
                    v = `(${v.slice(0, 2)}) ${v.slice(2)}`;
                }
                e.target.value = v;
            });
        }

        const cepInput = document.getElementById('checkout_cep');
        if (cepInput) {
            cepInput.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 8) v = v.slice(0, 8);
                if (v.length > 5) {
                    v = `${v.slice(0, 5)}-${v.slice(5)}`;
                }
                e.target.value = v;
            });
        }
    }

    // INTEGRAÇÃO VIACEP
    function initViaCep() {
        const cepInput = document.getElementById('checkout_cep');
        const loadingIndicator = document.getElementById('cepLoading');

        cepInput.addEventListener('blur', async () => {
            const rawCep = cepInput.value.replace(/\D/g, '');
            if (rawCep.length === 8) {
                loadingIndicator.style.display = 'block';
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${rawCep}/json/`);
                    const data = await response.json();
                    if (!data.erro) {
                        document.getElementById('checkout_street').value = data.logradouro || '';
                        document.getElementById('checkout_neighborhood').value = data.bairro || '';
                        document.getElementById('checkout_city').value = data.localidade || '';
                        document.getElementById('checkout_number').focus();
                    } else {
                        showToast('CEP não encontrado. Digite o endereço manualmente.', false);
                    }
                } catch (err) {
                    console.error('Erro ViaCEP:', err);
                } finally {
                    loadingIndicator.style.display = 'none';
                }
            }
        });
    }

    // RASTREAMENTO DE CLIQUES & ABERTURA DE PRODUTO
    function openProductModal(prod) {
        currentModalProduct = prod;
        currentModalQty = 1;

        // Disparo assíncrono de rastreamento de métricas
        fetch('<?= base_url('api/track-click') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: prod.id })
        }).catch(err => console.log('Click track err:', err));

        document.getElementById('modalProdTitle').innerText = prod.name;
        document.getElementById('modalProdDesc').innerText = prod.description || '';
        document.getElementById('modalProdPrice').innerText = 'R$ ' + parseFloat(prod.price).toFixed(2).replace('.', ',');

        const imgEl = document.getElementById('modalProdImg');
        if (prod.image_url) {
            imgEl.src = prod.image_url;
            imgEl.style.display = 'block';
        } else {
            imgEl.style.display = 'none';
        }

        // Renderizar Adicionais
        const optsContainer = document.getElementById('modalProdOptionsContainer');
        const optsList = document.getElementById('modalProdOptionsList');
        optsList.innerHTML = '';

        if (prod.options && prod.options.length > 0) {
            optsContainer.style.display = 'block';
            prod.options.forEach(opt => {
                const optItem = document.createElement('label');
                optItem.style.display = 'flex';
                optItem.style.alignItems = 'center';
                optItem.style.justifyContent = 'space-between';
                optItem.style.padding = '8px 12px';
                optItem.style.background = '#f8fafc';
                optItem.style.border = '1px solid var(--border)';
                optItem.style.borderRadius = '8px';
                optItem.style.cursor = 'pointer';

                optItem.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="prod-opt-chk" data-name="${opt.name}" data-price="${opt.price}" onchange="calcModalTotal()">
                        <span>${opt.name}</span>
                    </div>
                    <strong>+ R$ ${parseFloat(opt.price).toFixed(2).replace('.', ',')}</strong>
                `;
                optsList.appendChild(optItem);
            });
        } else {
            optsContainer.style.display = 'none';
        }

        document.getElementById('modalProdQty').innerText = '1';
        calcModalTotal();
        document.getElementById('productModalOverlay').classList.add('active');
    }

    function closeProductModal() {
        document.getElementById('productModalOverlay').classList.remove('active');
    }

    function adjustModalQty(delta) {
        currentModalQty = Math.max(1, currentModalQty + delta);
        document.getElementById('modalProdQty').innerText = currentModalQty;
        calcModalTotal();
    }

    function calcModalTotal() {
        if (!currentModalProduct) return;
        let base = parseFloat(currentModalProduct.price);
        const chks = document.querySelectorAll('.prod-opt-chk:checked');
        chks.forEach(c => base += parseFloat(c.getAttribute('data-price')));
        const total = base * currentModalQty;
        document.getElementById('modalProdTotal').innerText = 'R$ ' + total.toFixed(2).replace('.', ',');
    }

    function confirmAddToCart() {
        if (!currentModalProduct) return;
        const chks = document.querySelectorAll('.prod-opt-chk:checked');
        const selectedOptions = [];
        let unitPrice = parseFloat(currentModalProduct.price);

        chks.forEach(c => {
            const optPrice = parseFloat(c.getAttribute('data-price'));
            selectedOptions.push({ name: c.getAttribute('data-name'), price: optPrice });
            unitPrice += optPrice;
        });

        // Identificador único considerando opções
        const itemKey = currentModalProduct.id + '_' + selectedOptions.map(o => o.name).sort().join('_');

        const existing = cart.find(i => i.key === itemKey);
        if (existing) {
            existing.quantity += currentModalQty;
        } else {
            cart.push({
                key: itemKey,
                product_id: currentModalProduct.id,
                name: currentModalProduct.name,
                price: unitPrice,
                base_price: parseFloat(currentModalProduct.price),
                quantity: currentModalQty,
                options: selectedOptions
            });
        }

        saveCart();
        closeProductModal();
        showToast(`"${currentModalProduct.name}" adicionado à sacola!`, false);
    }

    // CARRINHO E CONTROLE DE QUANTIDADE (IHC: PREVENÇÃO DE ERROS E REVERSIBILIDADE)
    function saveCart() {
        localStorage.setItem('cupcake_store_cart', JSON.stringify(cart));
        renderCartUI();
    }

    function renderCartUI() {
        const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
        const totalAmount = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

        const floatBtn = document.getElementById('cartFloatingBtn');
        const countBadge = document.getElementById('cartCountBadge');
        const subtotalBadge = document.getElementById('cartSubtotalBadge');
        const modalTotal = document.getElementById('cartModalTotal');

        if (totalItems > 0) {
            floatBtn.style.display = 'flex';
            countBadge.innerText = totalItems;
            subtotalBadge.innerText = 'R$ ' + totalAmount.toFixed(2).replace('.', ',');
        } else {
            floatBtn.style.display = 'none';
        }

        modalTotal.innerText = 'R$ ' + totalAmount.toFixed(2).replace('.', ',');

        // Renderizar lista no modal
        const listContainer = document.getElementById('cartItemsList');
        if (cart.length === 0) {
            listContainer.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 20px;">Sua sacola está vazia.</p>';
            return;
        }

        listContainer.innerHTML = '';
        cart.forEach((item, index) => {
            const itemEl = document.createElement('div');
            itemEl.className = 'cart-item';

            const optsText = item.options && item.options.length > 0 
                ? item.options.map(o => o.name).join(', ') 
                : '';

            itemEl.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.name}</div>
                    ${optsText ? `<div class="cart-item-opts">+ ${optsText}</div>` : ''}
                    <div style="font-weight: 700; color: var(--primary); font-size: 0.88rem; margin-top: 4px;">
                        R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}
                    </div>
                </div>
                <div class="cart-qty-ctrl">
                    <button type="button" class="qty-btn" onclick="updateCartQty(${index}, -1)">-</button>
                    <span class="qty-val">${item.quantity}</span>
                    <button type="button" class="qty-btn" onclick="updateCartQty(${index}, 1)">+</button>
                </div>
                <button type="button" class="btn-remove" onclick="removeCartItem(${index})" title="Remover item">🗑️</button>
            `;
            listContainer.appendChild(itemEl);
        });
    }

    function updateCartQty(index, delta) {
        if (cart[index]) {
            cart[index].quantity += delta;
            if (cart[index].quantity <= 0) {
                removeCartItem(index);
                return;
            }
            saveCart();
        }
    }

    function removeCartItem(index) {
        lastDeletedItem = { item: cart[index], index: index };
        cart.splice(index, 1);
        saveCart();
        showToast('Item removido da sacola', true);
    }

    function undoLastAction() {
        if (lastDeletedItem) {
            cart.splice(lastDeletedItem.index, 0, lastDeletedItem.item);
            saveCart();
            lastDeletedItem = null;
            document.getElementById('feedbackToast').classList.remove('show');
        }
    }

    function showToast(msg, allowUndo = false) {
        const toast = document.getElementById('feedbackToast');
        document.getElementById('toastMessage').innerText = msg;
        document.getElementById('toastUndoBtn').style.display = allowUndo ? 'inline' : 'none';
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    function openCartModal() {
        renderCartUI();
        document.getElementById('cartModalOverlay').classList.add('active');
    }

    function closeCartModal() {
        document.getElementById('cartModalOverlay').classList.remove('active');
    }

    function selectPayment(method, btn) {
        selectedPaymentMethod = method;
        document.querySelectorAll('.payment-pill').forEach(p => p.classList.remove('selected'));
        btn.classList.add('selected');

        const changeGroup = document.getElementById('changeGroup');
        if (method === 'Dinheiro') {
            changeGroup.style.display = 'block';
        } else {
            changeGroup.style.display = 'none';
        }
    }

    // FINALIZAR PEDIDO (VALIDAÇÃO E WHATSAPP)
    async function submitOrder() {
        if (!isStoreOpen) {
            alert('A loja está fechada no momento. Por favor, volte durante nosso horário de atendimento.');
            return;
        }

        if (cart.length === 0) {
            alert('Sua sacola está vazia!');
            return;
        }

        const name = document.getElementById('checkout_name').value.trim();
        const phone = document.getElementById('checkout_phone').value.trim();
        const cep = document.getElementById('checkout_cep').value.trim();
        const street = document.getElementById('checkout_street').value.trim();
        const number = document.getElementById('checkout_number').value.trim();
        const neighborhood = document.getElementById('checkout_neighborhood').value.trim();
        const city = document.getElementById('checkout_city').value.trim();
        const complement = document.getElementById('checkout_complement').value.trim();
        const changeFor = document.getElementById('checkout_change').value;

        if (!name || phone.length < 10 || !street || !number) {
            alert('Por favor, preencha Nome, Telefone, Rua e Número para a entrega.');
            return;
        }

        const totalAmount = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

        if (selectedPaymentMethod === 'Dinheiro' && changeFor && parseFloat(changeFor) < totalAmount) {
            alert('O valor informado para o troco não pode ser menor que o total do pedido.');
            return;
        }

        const btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.innerText = 'Enviando Pedido...';

        try {
            const payload = {
                customer_name: name,
                customer_phone: phone,
                cep: cep,
                street: street,
                number: number,
                neighborhood: neighborhood,
                city: city,
                complement: complement,
                payment_method: selectedPaymentMethod,
                change_for: changeFor ? parseFloat(changeFor) : null,
                items: cart,
                total_amount: totalAmount
            };

            const response = await fetch('<?= base_url('api/create-order') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.status === 'success' && data.whatsapp_url) {
                // Limpa carrinho após pedido concluído
                cart = [];
                saveCart();
                closeCartModal();

                // Redireciona diretamente para o WhatsApp oficial da loja com o texto pré-carregado
                window.location.href = data.whatsapp_url;
            } else {
                alert(data.message || 'Ocorreu um erro ao processar seu pedido.');
                btn.disabled = false;
                btn.innerText = 'Finalizar no WhatsApp ➔';
            }
        } catch (err) {
            console.error('Erro ao enviar pedido:', err);
            alert('Erro de conexão com o servidor.');
            btn.disabled = false;
            btn.innerText = 'Finalizar no WhatsApp ➔';
        }
    }
</script>

</body>
</html>
