<?php
namespace App\Models;

use CodeIgniter\Model;

class Producto_model extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'precio', 'imagen', 'activo', 'id_categoria', 'descripcion', 'stock_total'];

    public function getProductosConCategoria()
    {
        return $this->select('productos.*, categoria.ct_nombre as categoria_nombre')
                    ->join('categoria', 'categoria.id_categoria = productos.id_categoria', 'left')
                    ->where('productos.activo', 1)
                    ->findAll();
    }
    
    public function getProductoConDetalles($id)
    {
        return $this->select('productos.*, categoria.ct_nombre as categoria_nombre')
                    ->join('categoria', 'categoria.id_categoria = productos.id_categoria', 'left')
                    ->find($id);
    }

    public function getProductosActivos()
    {
        return $this->where('activo', 1)->findAll();
    }

    // MÉTODO CORREGIDO - Sin duplicados
public function getProductosParaAdmin()
{
    // SIMPLE: Sin JOINs complicados que causen duplicados
    return $this->select('productos.*, categoria.ct_nombre as categoria_nombre')
                ->join('categoria', 'categoria.id_categoria = productos.id_categoria', 'left')
                ->orderBy('productos.activo', 'DESC')
                ->orderBy('productos.nombre', 'ASC')
                ->findAll();
}

    public function toggleActivo($id)
    {
        $producto = $this->find($id);
        if ($producto) {
            $nuevoEstado = $producto['activo'] == 1 ? 0 : 1;
            return $this->update($id, ['activo' => $nuevoEstado]);
        }
        return false;
    }
}