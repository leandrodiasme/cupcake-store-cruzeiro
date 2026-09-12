<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class Categories extends Controller
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $categoryModel = new CategoryModel();
        $settingModel  = new SettingModel();

        $categories = $categoryModel->orderBy('display_order', 'ASC')->findAll();

        return view('admin/categories/index', [
            'categories' => $categories,
            'storeName'  => $settingModel->getVal('store_name', 'Minha Loja'),
            'themeColor' => $settingModel->getVal('theme_color', '#ec4899'),
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Nome da categoria inválido.');
        }

        $categoryModel = new CategoryModel();
        $categoryModel->insert([
            'name'          => trim((string) $this->request->getPost('name')),
            'description'   => trim((string) $this->request->getPost('description')),
            'display_order' => (int) $this->request->getPost('display_order'),
            'active'        => $this->request->getPost('active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Categoria cadastrada com sucesso!');
    }

    public function update($id)
    {
        $categoryModel = new CategoryModel();
        $category      = $categoryModel->find($id);

        if (! $category) {
            return redirect()->to('/admin/categories')->with('error', 'Categoria não encontrada.');
        }

        $categoryModel->update($id, [
            'name'          => trim((string) $this->request->getPost('name')),
            'description'   => trim((string) $this->request->getPost('description')),
            'display_order' => (int) $this->request->getPost('display_order'),
            'active'        => $this->request->getPost('active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function delete($id)
    {
        $categoryModel = new CategoryModel();
        $categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Categoria excluída com sucesso.');
    }
}
