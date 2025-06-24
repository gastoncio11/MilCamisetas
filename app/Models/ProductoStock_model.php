<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductoStock_model extends Model
{
    protected $table = 'producto_stock';
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id', 'talle', 'stock'];

    public function getStockPorProducto($producto_id)
    {
        return $this->where('producto_id', $producto_id)
                    ->orderBy('talle', 'ASC')
                    ->findAll();
    }

    public function actualizarStock($producto_id, $talle, $nuevo_stock)
    {
        $existing = $this->where('producto_id', $producto_id)
                         ->where('talle', $talle)
                         ->first();
        
        if ($existing) {
            return $this->update($existing['id'], ['stock' => $nuevo_stock]);
        } else {
            return $this->insert([
                'producto_id' => $producto_id,
                'talle' => $talle,
                'stock' => $nuevo_stock
            ]);
        }
    }
}