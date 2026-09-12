<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'name'        => 'required|min_length[2]|max_length[150]',
        'price'       => 'required|decimal',
    ];

    /**
     * Retorna produtos com o nome de suas categorias
     */
    public function getProductsWithCategory(bool $onlyActive = true): array
    {
        $builder = $this->select('products.*, categories.name as category_name')
                        ->join('categories', 'categories.id = products.category_id', 'left');

        if ($onlyActive) {
            $builder->where('products.active', 1)
                    ->where('categories.active', 1);
        }

        return $builder->orderBy('categories.display_order', 'ASC')
                       ->orderBy('products.name', 'ASC')
                       ->findAll();
    }

    /**
     * Retorna um produto com suas opções/adicionais
     */
    public function getProductWithOptions(int $productId)
    {
        $product = $this->find($productId);
        if (! $product) {
            return null;
        }

        $optionModel = new ProductOptionModel();
        $product['options'] = $optionModel->where('product_id', $productId)
                                          ->where('active', 1)
                                          ->findAll();

        return $product;
    }
}
