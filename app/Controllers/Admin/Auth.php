<?php

namespace App\Controllers\Admin;

use App\Models\SettingModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $helpers = ['form', 'url'];

    public function login()
    {
        if (session()->get('is_admin_logged')) {
            return redirect()->to('/admin/dashboard');
        }

        $settingModel = new SettingModel();
        $storeName    = $settingModel->getVal('store_name', 'Painel Administrativo');
        $themeColor   = $settingModel->getVal('theme_color', '#ec4899');

        return view('admin/auth/login', [
            'storeName'  => $storeName,
            'themeColor' => $themeColor,
        ]);
    }

    public function processLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Por favor, informe e-mail e senha.');
        }

        $userModel = new UserModel();
        $user      = $userModel->authenticate($email, $password);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Credenciais inválidas. Verifique seu e-mail e senha.');
        }

        session()->set([
            'is_admin_logged' => true,
            'admin_id'        => $user['id'],
            'admin_name'      => $user['name'],
            'admin_email'     => $user['email'],
            'admin_role'      => $user['role'],
        ]);

        return redirect()->to('/admin/dashboard')->with('success', 'Bem-vindo de volta, ' . esc($user['name']) . '!');
    }

    public function logout()
    {
        session()->remove(['is_admin_logged', 'admin_id', 'admin_name', 'admin_email', 'admin_role']);
        session()->destroy();

        return redirect()->to('/admin/login')->with('success', 'Você saiu do painel com segurança.');
    }
}
