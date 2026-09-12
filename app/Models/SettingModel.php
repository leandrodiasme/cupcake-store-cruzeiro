<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'key';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['key', 'value', 'updated_at'];
    protected $useTimestamps    = false;

    /**
     * Obter o valor de uma configuração
     */
    public function getVal(string $key, $default = null)
    {
        try {
            $row = $this->find($key);
            return $row ? $row['value'] : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Definir ou atualizar o valor de uma configuração
     */
    public function setVal(string $key, $value): bool
    {
        $data = [
            'key'        => $key,
            'value'      => is_array($value) ? json_encode($value) : (string) $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return (bool) $this->save($data);
    }

    /**
     * Retorna todas as configurações como um array associativo chave => valor
     */
    public function getAllKeyValue(): array
    {
        try {
            $rows = $this->findAll();
            $result = [];
            foreach ($rows as $row) {
                $result[$row['key']] = $row['value'];
            }
            return $result;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Verifica se o sistema foi instalado
     */
    public function isInstalled(): bool
    {
        try {
            $db = \Config\Database::connect();
            if (! $db->tableExists('settings')) {
                return false;
            }
            $val = $this->getVal('is_installed');
            return $val === '1' || $val === 'true';
        } catch (\Throwable $e) {
            return false;
        }
    }
}
