<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Painel') ?> | <?= esc($storeName ?? 'Painel') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: <?= esc($themeColor ?? '#ec4899') ?>;
            --primary-hover: #be185d;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card: #ffffff;
            --radius: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: white;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px 24px;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
            color: white;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .nav-menu {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-item.active {
            background: var(--primary);
            color: white;
        }

        .sidebar-footer {
            padding-top: 16px;
            border-top: 1px solid #1e293b;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ef4444;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* Main Content */
        .content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 100vh;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h2 {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .page-body {
            padding: 32px;
            max-width: 1200px;
            width: 100%;
        }

        /* Flash Messages */
        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
            <div class="brand-icon">🧁</div>
            <div>
                <div class="brand-title"><?= esc($storeName ?? 'Painel') ?></div>
                <div class="brand-sub">Administração</div>
            </div>
        </a>

        <nav class="nav-menu">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= uri_string() === 'admin' || uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                📊 Dashboard & Métricas
            </a>
            <a href="<?= base_url('admin/products') ?>" class="nav-item <?= str_contains(uri_string(), 'products') ? 'active' : '' ?>">
                📦 Produtos & Adicionais
            </a>
            <a href="<?= base_url('admin/categories') ?>" class="nav-item <?= str_contains(uri_string(), 'categories') ? 'active' : '' ?>">
                🏷️ Categorias
            </a>
            <a href="<?= base_url('admin/settings') ?>" class="nav-item <?= str_contains(uri_string(), 'settings') ? 'active' : '' ?>">
                ⚙️ Configurações da Loja
            </a>
            <a href="<?= base_url('/') ?>" target="_blank" class="nav-item">
                🌐 Ver Loja Pública ↗
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= base_url('admin/logout') ?>" class="btn-logout">
                🚪 Sair do Painel
            </a>
        </div>
    </aside>

    <div class="content-wrapper">
        <header class="topbar">
            <h2><?= esc($title ?? 'Painel') ?></h2>
            <div class="admin-user">
                <span>Olá, <?= esc(session()->get('admin_name') ?? 'Admin') ?></span>
                <div class="admin-avatar">👤</div>
            </div>
        </header>

        <main class="page-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
