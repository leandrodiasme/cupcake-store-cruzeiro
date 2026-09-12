<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductOptionModel extends Model
{
    protected $table            = 'product_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['product_id', 'name', 'price', 'active'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'product_id' => 'required|integer',
        'name'       => 'required|min_length[2]|max_length[100]',
        'price'      => 'required|decimal',
    ];
}
