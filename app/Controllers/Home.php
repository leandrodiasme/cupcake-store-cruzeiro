<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $settingModel = new SettingModel();
        $settings     = $settingModel->getAllKeyValue();

        // Valores padrão caso não definidos
        $storeName      = $settings['store_name'] ?? 'Cupcake Store White-Label';
        $storeSegment   = $settings['store_segment'] ?? 'Confeitaria & Cupcakes';
        $whatsappNumber = $settings['whatsapp_number'] ?? '5518999999999';
        $openingTime    = $settings['opening_time'] ?? '09:00';
        $closingTime    = $settings['closing_time'] ?? '22:00';
        $themeColor     = $settings['theme_color'] ?? '#ec4899';

        // Verificação de Horário Comercial
        $isOpen = $this->isStoreOpen($openingTime, $closingTime);

        // Carregar categorias ativas com seus produtos
        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $categories = $categoryModel->getActiveCategories();
        $catalog = [];

        foreach ($categories as $cat) {
            $products = $productModel->select('products.*')
                                     ->where('products.category_id', $cat['id'])
                                     ->where('products.active', 1)
                                     ->findAll();

            // Carregar opções/adicionais para cada produto
            foreach ($products as &$prod) {
                $optModel = new \App\Models\ProductOptionModel();
                $prod['options'] = $optModel->where('product_id', $prod['id'])
                                            ->where('active', 1)
                                            ->findAll();
            }
            unset($prod);

            if (! empty($products)) {
                $cat['products'] = $products;
                $catalog[] = $cat;
            }
        }

        $data = [
            'storeName'      => $storeName,
            'storeSegment'   => $storeSegment,
            'whatsappNumber' => $whatsappNumber,
            'openingTime'    => $openingTime,
            'closingTime'    => $closingTime,
            'themeColor'     => $themeColor,
            'isOpen'         => $isOpen,
            'catalog'        => $catalog,
        ];

        return view('home/index', $data);
    }

    /**
     * Valida se o horário atual está dentro da faixa de funcionamento da loja.
     * Suporta horários do mesmo dia (ex: 09:00 às 22:00) e noturnos (ex: 18:00 às 02:00).
     */
    public function isStoreOpen(string $opening, string $closing, ?string $currentTime = null): bool
    {
        $now = $currentTime ? strtotime($currentTime) : time();
        $currentH = date('H:i', $now);

        if ($opening <= $closing) {
            // Mesmo dia (ex: 09:00 até 22:00)
            return ($currentH >= $opening && $currentH <= $closing);
        } else {
            // Vira a noite (ex: 18:00 até 02:00)
            return ($currentH >= $opening || $currentH <= $closing);
        }
    }
}
