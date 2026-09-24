<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProductOptionModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Install extends Controller
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $settingModel = new SettingModel();
        if ($settingModel->isInstalled()) {
            return redirect()->to('/');
        }

        return view('install/wizard');
    }

    public function process()
    {
        $settingModel = new SettingModel();
        if ($settingModel->isInstalled()) {
            return redirect()->to('/');
        }

        // Validação obrigatória dos campos do Wizard (conforme feedback de teste de aceite - Renan Santos)
        $rules = [
            'store_name'      => 'required|min_length[3]|max_length[100]',
            'store_segment'   => 'required|min_length[2]|max_length[50]',
            'whatsapp_number' => [
                'rules'  => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required'   => 'O campo WhatsApp Oficial é obrigatório para o recebimento de pedidos.',
                    'min_length' => 'O WhatsApp Oficial deve conter pelo menos 10 dígitos com DDD.',
                    'max_length' => 'O WhatsApp Oficial não pode exceder 20 caracteres.',
                ],
            ],
            'opening_time'    => 'required',
            'closing_time'    => 'required',
            'admin_name'      => 'required|min_length[3]|max_length[100]',
            'admin_email'     => 'required|valid_email',
            'admin_password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Passo 3: Executar Migrations do CodeIgniter
            $migrations = \Config\Services::migrations();
            $migrations->latest();

            // Passo 4: Salvar Usuário Administrador
            $userModel = new UserModel();
            $adminData = [
                'name'     => $this->request->getPost('admin_name'),
                'email'    => $this->request->getPost('admin_email'),
                'password' => $this->request->getPost('admin_password'),
                'role'     => 'admin',
            ];

            // Se o admin já existir com esse email, atualiza
            $existingAdmin = $userModel->where('email', $adminData['email'])->first();
            if ($existingAdmin) {
                $userModel->update($existingAdmin['id'], $adminData);
                $adminId = $existingAdmin['id'];
            } else {
                $adminId = $userModel->insert($adminData);
            }

            // Normaliza o número de WhatsApp (mantém apenas dígitos)
            $rawWhatsapp = preg_replace('/\D/', '', (string) $this->request->getPost('whatsapp_number'));
            // Adiciona código do país 55 se não tiver
            if (strlen($rawWhatsapp) <= 11 && ! str_starts_with($rawWhatsapp, '55')) {
                $cleanWhatsapp = '55' . $rawWhatsapp;
            } else {
                $cleanWhatsapp = $rawWhatsapp;
            }

            // Definir cor de destaque de acordo com o segmento
            $segment = strtolower(trim((string) $this->request->getPost('store_segment')));
            $themeColor = match (true) {
                str_contains($segment, 'cupcake') || str_contains($segment, 'doce') || str_contains($segment, 'confeitaria') => '#ec4899',
                str_contains($segment, 'frango') || str_contains($segment, 'frito') => '#ea580c',
                str_contains($segment, 'burger') || str_contains($segment, 'hamburguer') => '#d97706',
                str_contains($segment, 'pizza') => '#dc2626',
                str_contains($segment, 'caf') || str_contains($segment, 'coffee') => '#854d0e',
                default => '#2563eb',
            };

            // Passo 4: Salvar configurações na tabela settings
            $settingModel->setVal('store_name', $this->request->getPost('store_name'));
            $settingModel->setVal('store_segment', $this->request->getPost('store_segment'));
            $settingModel->setVal('whatsapp_number', $cleanWhatsapp);
            $settingModel->setVal('opening_time', $this->request->getPost('opening_time'));
            $settingModel->setVal('closing_time', $this->request->getPost('closing_time'));
            $settingModel->setVal('theme_color', $themeColor);
            $settingModel->setVal('is_installed', '1');

            // Criar itens demonstrativos se o banco estiver vazio
            $this->seedInitialNicheData($segment);

            // Iniciar sessão do administrador
            session()->set([
                'is_admin_logged' => true,
                'admin_id'        => $adminId,
                'admin_name'      => $adminData['name'],
                'admin_email'     => $adminData['email'],
            ]);

            return redirect()->to('/admin')->with('success', 'Instalação concluída com sucesso! Bem-vindo ao painel da sua loja ' . esc($this->request->getPost('store_name')) . '.');

        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Falha durante a instalação: ' . $e->getMessage());
        }
    }

    /**
     * Popula categorias e produtos iniciais de acordo com o nicho escolhido
     */
    private function seedInitialNicheData(string $segment): void
    {
        $categoryModel = new CategoryModel();
        if ($categoryModel->countAllResults() > 0) {
            return;
        }

        $productModel = new ProductModel();
        $optionModel  = new ProductOptionModel();

        if (str_contains($segment, 'cupcake') || str_contains($segment, 'confeitaria') || str_contains($segment, 'doce')) {
            // Nicho: Cupcakes & Confeitaria
            $cat1 = $categoryModel->insert(['name' => 'Cupcakes Tradicionais', 'description' => 'Nossos clássicos mais amados', 'display_order' => 1, 'active' => 1]);
            $cat2 = $categoryModel->insert(['name' => 'Cupcakes Especiais & Gourmet', 'description' => 'Receitas exclusivas com recheios nobres', 'display_order' => 2, 'active' => 1]);
            $cat3 = $categoryModel->insert(['name' => 'Bebidas & Cafés', 'description' => 'Acompanhamentos perfeitos', 'display_order' => 3, 'active' => 1]);

            $p1 = $productModel->insert([
                'category_id' => $cat1,
                'name'        => 'Cupcake de Chocolate Belga',
                'description' => 'Massa fofinha de cacau 70% com recheio cremoso e cobertura de brigadeiro gourmet.',
                'price'       => 12.00,
                'image_url'   => 'https://images.unsplash.com/photo-1576618148400-f54bed99fcfd?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);
            $optionModel->insert(['product_id' => $p1, 'name' => 'Granulado Belga Extra', 'price' => 2.50, 'active' => 1]);
            $optionModel->insert(['product_id' => $p1, 'name' => 'Caixa para Presente Individual', 'price' => 4.00, 'active' => 1]);

            $p2 = $productModel->insert([
                'category_id' => $cat1,
                'name'        => 'Cupcake Red Velvet',
                'description' => 'Autêntica massa aveludada vermelha com cobertura suave de frosting de cream cheese.',
                'price'       => 13.50,
                'image_url'   => 'https://images.unsplash.com/photo-1614707267537-b85aaf00c4b7?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);
            $optionModel->insert(['product_id' => $p2, 'name' => 'Geleia de Frutas Vermelhas Extra', 'price' => 3.00, 'active' => 1]);

            $p3 = $productModel->insert([
                'category_id' => $cat2,
                'name'        => 'Cupcake Ninho com Nutella',
                'description' => 'Massa branca de baunilha, recheio generoso de Nutella pura e cobertura de brigadeiro de Ninho.',
                'price'       => 15.00,
                'image_url'   => 'https://images.unsplash.com/photo-1587668178277-295251f900ce?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);

            $productModel->insert([
                'category_id' => $cat3,
                'name'        => 'Cappuccino Cremoso Artesanal',
                'description' => 'Café espresso, leite vaporizado, cacau em pó e um toque sutil de canela.',
                'price'       => 9.50,
                'image_url'   => 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);
        } else {
            // Nicho Genérico / Lanches / Frango Frito
            $cat1 = $categoryModel->insert(['name' => 'Combos & Pratos Principais', 'description' => 'As melhores opções da casa', 'display_order' => 1, 'active' => 1]);
            $cat2 = $categoryModel->insert(['name' => 'Bebidas', 'description' => 'Refrigerantes e sucos naturais', 'display_order' => 2, 'active' => 1]);

            $p1 = $productModel->insert([
                'category_id' => $cat1,
                'name'        => 'Combo Crocante da Casa',
                'description' => 'Porção generosa e crocante acompanhada de molho artesanal especial e batatas.',
                'price'       => 34.90,
                'image_url'   => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);
            $optionModel->insert(['product_id' => $p1, 'name' => 'Molho Extra (Barbecue Especial)', 'price' => 3.50, 'active' => 1]);
            $optionModel->insert(['product_id' => $p1, 'name' => 'Bacon Crocante Extra', 'price' => 5.00, 'active' => 1]);

            $productModel->insert([
                'category_id' => $cat2,
                'name'        => 'Refrigerante Lata 350ml',
                'description' => 'Coca-Cola, Guaraná Antarctica ou Sprite bem gelado.',
                'price'       => 6.00,
                'image_url'   => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=600&q=80',
                'active'      => 1,
            ]);
        }
    }
}
