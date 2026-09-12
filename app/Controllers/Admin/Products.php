<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProductOptionModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class Products extends Controller
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();
        $settingModel  = new SettingModel();

        $products   = $productModel->getProductsWithCategory(false);
        $categories = $categoryModel->orderBy('display_order', 'ASC')->findAll();

        return view('admin/products/index', [
            'products'   => $products,
            'categories' => $categories,
            'storeName'  => $settingModel->getVal('store_name', 'Minha Loja'),
            'themeColor' => $settingModel->getVal('theme_color', '#ec4899'),
        ]);
    }

    public function store()
    {
        $rules = [
            'category_id' => 'required|integer',
            'name'        => 'required|min_length[2]|max_length[150]',
            'price'       => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Preencha os campos obrigatórios corretamente.');
        }

        $productModel = new ProductModel();
        $optionModel  = new ProductOptionModel();

        $productId = $productModel->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'price'       => (float) $this->request->getPost('price'),
            'image_url'   => trim((string) $this->request->getPost('image_url')) ?: null,
            'active'      => $this->request->getPost('active') ? 1 : 0,
        ]);

        // Processar Adicionais se informados
        $optionNames  = $this->request->getPost('option_names') ?? [];
        $optionPrices = $this->request->getPost('option_prices') ?? [];

        foreach ($optionNames as $i => $optName) {
            $name  = trim((string) $optName);
            $price = isset($optionPrices[$i]) ? (float) $optionPrices[$i] : 0.00;
            if (! empty($name)) {
                $optionModel->insert([
                    'product_id' => $productId,
                    'name'       => $name,
                    'price'      => $price,
                    'active'     => 1,
                ]);
            }
        }

        return redirect()->to('/admin/products')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function update($id)
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! $product) {
            return redirect()->to('/admin/products')->with('error', 'Produto não encontrado.');
        }

        $productModel->update($id, [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'price'       => (float) $this->request->getPost('price'),
            'image_url'   => trim((string) $this->request->getPost('image_url')) ?: null,
            'active'      => $this->request->getPost('active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produto atualizado com sucesso!');
    }

    public function delete($id)
    {
        $productModel = new ProductModel();
        $productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Produto removido com sucesso.');
    }
}
