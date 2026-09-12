<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductClickModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $settingModel    = new SettingModel();
        $clickModel      = new ProductClickModel();
        $orderModel      = new OrderModel();
        $productModel    = new ProductModel();
        $categoryModel   = new CategoryModel();

        $settings        = $settingModel->getAllKeyValue();
        $totalClicks     = $clickModel->getTotalClicks();
        $totalOrders     = $orderModel->getTotalOrders();
        $totalProducts   = $productModel->countAllResults();
        $totalCategories = $categoryModel->countAllResults();

        $popularProducts = $clickModel->getMostPopularProducts(8);
        $recentOrders    = $orderModel->getRecentOrders(6);

        // Formatação de dados para o gráfico Chart.js
        $chartLabels = [];
        $chartData   = [];
        foreach ($popularProducts as $item) {
            $chartLabels[] = $item['name'];
            $chartData[]   = (int) $item['total_clicks'];
        }

        $data = [
            'storeName'       => $settings['store_name'] ?? 'Minha Loja',
            'storeSegment'    => $settings['store_segment'] ?? 'Delivery',
            'themeColor'      => $settings['theme_color'] ?? '#ec4899',
            'totalClicks'     => $totalClicks,
            'totalOrders'     => $totalOrders,
            'totalProducts'   => $totalProducts,
            'totalCategories' => $totalCategories,
            'popularProducts' => $popularProducts,
            'recentOrders'    => $recentOrders,
            'chartLabels'     => json_encode($chartLabels, JSON_UNESCAPED_UNICODE),
            'chartData'       => json_encode($chartData),
        ];

        return view('admin/dashboard/index', $data);
    }
}
