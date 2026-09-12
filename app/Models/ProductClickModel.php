<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductClickModel extends Model
{
    protected $table            = 'product_clicks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['product_id', 'ip_address', 'user_agent', 'clicked_at'];

    protected $useTimestamps = false;

    /**
     * Registra um clique em um produto
     */
    public function trackClick(int $productId, ?string $ip = null, ?string $userAgent = null): bool
    {
        return (bool) $this->insert([
            'product_id' => $productId,
            'ip_address' => substr($ip ?? '', 0, 45),
            'user_agent' => substr($userAgent ?? '', 0, 255),
            'clicked_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Retorna os produtos mais populares agrupados por total de cliques
     */
    public function getMostPopularProducts(int $limit = 5): array
    {
        return $this->select('products.id, products.name, categories.name as category_name, COUNT(product_clicks.id) as total_clicks')
                    ->join('products', 'products.id = product_clicks.product_id')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->groupBy('product_clicks.product_id')
                    ->orderBy('total_clicks', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Retorna o total geral de cliques computados
     */
    public function getTotalClicks(): int
    {
        return (int) $this->countAllResults();
    }
}
