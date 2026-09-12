<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'customer_name',
        'customer_phone',
        'cep',
        'street',
        'number',
        'neighborhood',
        'city',
        'complement',
        'payment_method',
        'change_for',
        'total_amount',
        'order_items_json',
        'status',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Retorna o total de pedidos registrados
     */
    public function getTotalOrders(): int
    {
        return (int) $this->countAllResults();
    }

    /**
     * Retorna pedidos recentes para a listagem
     */
    public function getRecentOrders(int $limit = 10): array
    {
        return $this->orderBy('id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
