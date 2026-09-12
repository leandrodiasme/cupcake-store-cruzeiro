<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'description', 'display_order', 'active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
    ];

    /**
     * Retorna todas as categorias ativas ordenadas
     */
    public function getActiveCategories(): array
    {
        return $this->where('active', 1)
                    ->orderBy('display_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
