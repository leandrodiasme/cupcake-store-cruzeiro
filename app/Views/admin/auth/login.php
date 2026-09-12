<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo | <?= esc($storeName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: <?= esc($themeColor) ?>;
            --primary-hover: #be185d;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: var(--card);
            border-radius: 16px;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            padding: 36px 32px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-icon {
            width: 52px;
            height: 52px;
            background: var(--primary);
            color: white;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 12px;
        }

        .login-header h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text);
        }

        .login-header p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            outline: none;
            font-size: 0.95rem;
        }

        input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            opacity: 0.95;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="logo-icon">🧁</div>
        <h2>Painel Administrativo</h2>
        <p><?= esc($storeName) ?></p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/login/process') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required placeholder="admin@sualoja.com" value="<?= old('email') ?>">
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-login">Entrar no Painel</button>
    </form>

    <a href="<?= base_url('/') ?>" class="back-link">← Voltar para o Catálogo</a>
</div>

</body>
</html>
