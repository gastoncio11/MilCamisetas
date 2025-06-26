<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductoTalle_model extends Model
{
    protected $table = 'producto_talle';
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id', 'talle', 'stock'];
    
    // Validaciones para evitar problemas futuros
    protected $validationRules = [
        'producto_id' => 'required|integer',
        'talle' => 'required|in_list[XS,S,M,L,XL,XXL,8,10,12,14]',
        'stock' => 'required|integer|greater_than_equal_to[0]'
    ];

    public function getStockPorProducto($producto_id)
    {
        return $this->where('producto_id', $producto_id)
                    ->orderBy('talle', 'ASC')
                    ->findAll();
    }

    public function actualizarStock($producto_id, $talle, $nuevo_stock)
    {
        // Validar datos de entrada
        if (!is_numeric($producto_id) || !in_array($talle, ['XS','S','M','L','XL','XXL','8','10','12','14']) || $nuevo_stock < 0) {
            throw new \InvalidArgumentException('Datos inválidos para actualizar stock');
        }

        $existing = $this->where('producto_id', $producto_id)
                         ->where('talle', $talle)
                         ->first();
        
        if ($existing) {
            $result = $this->update($existing['id'], ['stock' => (int)$nuevo_stock]);
        } else {
            $result = $this->insert([
                'producto_id' => (int)$producto_id,
                'talle' => $talle,
                'stock' => (int)$nuevo_stock
            ]);
        }
        
        // Actualizar stock total después de cada actualización
        if ($result) {
            $this->actualizarStockTotal($producto_id);
        }
        
        return $result;
    }

    public function restarStock($producto_id, $talle, $cantidad)
    {
        // Validar que hay suficiente stock antes de restar
        $stockActual = $this->where('producto_id', $producto_id)
                           ->where('talle', $talle)
                           ->first();
        
        if (!$stockActual || $stockActual['stock'] < $cantidad) {
            throw new \RuntimeException("Stock insuficiente para producto $producto_id, talle $talle");
        }
        
        $result = $this->where('producto_id', $producto_id)
                       ->where('talle', $talle)
                       ->set('stock', 'stock - ' . (int)$cantidad, false)
                       ->update();
        
        // Actualizar stock total después de restar
        if ($result) {
            $this->actualizarStockTotal($producto_id);
        }
        
        return $result;
    }

    public function actualizarStockTotal($producto_id)
    {
        $db = \Config\Database::connect();
        
        // Calcular stock total
        $builder = $db->table('producto_talle');
        $builder->selectSum('stock');
        $builder->where('producto_id', $producto_id);
        $result = $builder->get()->getRow();

        $stockTotal = (int)($result->stock ?? 0);

        // Actualizar tabla productos
        $productoModel = new \App\Models\Producto_model();
        $updateResult = $productoModel->update($producto_id, ['stock_total' => $stockTotal]);
        
        if (!$updateResult) {
            log_message('error', "No se pudo actualizar stock_total para producto $producto_id");
        }
        
        return $stockTotal;
    }

    // Método para verificar integridad de datos
    public function verificarIntegridad()
    {
        $db = \Config\Database::connect();
        
        // Buscar registros problemáticos
        $problemas = [];
        
        // 1. Registros con ID = 0
        $query1 = $db->query("SELECT COUNT(*) as count FROM producto_talle WHERE id = 0");
        $result1 = $query1->getRow();
        if ($result1->count > 0) {
            $problemas[] = "Hay {$result1->count} registros con ID = 0";
        }
        
        // 2. Registros con talle vacío
        $query2 = $db->query("SELECT COUNT(*) as count FROM producto_talle WHERE talle = '' OR talle IS NULL");
        $result2 = $query2->getRow();
        if ($result2->count > 0) {
            $problemas[] = "Hay {$result2->count} registros con talle vacío";
        }
        
        // 3. Registros duplicados
        $query3 = $db->query("
            SELECT producto_id, talle, COUNT(*) as count 
            FROM producto_talle 
            GROUP BY producto_id, talle 
            HAVING COUNT(*) > 1
        ");
        $duplicados = $query3->getResult();
        if (!empty($duplicados)) {
            $problemas[] = "Hay " . count($duplicados) . " combinaciones producto-talle duplicadas";
        }
        
        return $problemas;
    }

    // Método para limpiar duplicados
    public function limpiarDuplicados()
    {
        $db = \Config\Database::connect();
        
        // Eliminar duplicados manteniendo el registro con mayor stock
        $query = "
            DELETE p1 FROM producto_talle p1
            INNER JOIN producto_talle p2 
            WHERE p1.id < p2.id 
            AND p1.producto_id = p2.producto_id 
            AND p1.talle = p2.talle
        ";
        
        return $db->query($query);
    }
}
