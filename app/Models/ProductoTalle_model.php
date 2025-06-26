<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductoTalle_model extends Model
{
    protected $table = 'producto_talle';
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id', 'talle', 'stock'];

    public function getStockPorProducto($producto_id)
    {
        return $this->where('producto_id', $producto_id)
                    ->where('talle !=', '') // Excluir talles vacíos
                    ->where('talle IS NOT', null) // Excluir talles nulos
                    ->orderBy('talle', 'ASC')
                    ->findAll();
    }

    public function actualizarStock($producto_id, $talle, $nuevo_stock)
    {
        // Log para debug
        log_message('info', "🔧 actualizarStock - Producto: {$producto_id}, Talle: '{$talle}', Stock: {$nuevo_stock}");
        
        // Validar que el talle no esté vacío
        if (empty($talle) || $talle === null) {
            throw new \InvalidArgumentException('El talle no puede estar vacío');
        }

        // Buscar registro existente con condiciones específicas
        $existing = $this->where('producto_id', $producto_id)
                         ->where('talle', $talle)
                         ->first();
        
        if ($existing) {
            // Actualizar SOLO este registro específico por ID
            $result = $this->update($existing['id'], ['stock' => $nuevo_stock]);
            log_message('info', "✏️ Actualizando registro ID {$existing['id']} con stock {$nuevo_stock}");
        } else {
            // Insertar nuevo registro
            $result = $this->insert([
                'producto_id' => $producto_id,
                'talle' => $talle,
                'stock' => $nuevo_stock
            ]);
            log_message('info', "➕ Insertando nuevo registro para talle {$talle} con stock {$nuevo_stock}");
        }
        
        // Actualizar stock total después de cada actualización
        if ($result) {
            $this->actualizarStockTotal($producto_id);
        }
        
        return $result;
    }

    public function restarStock($producto_id, $talle, $cantidad)
    {
        log_message('info', "➖ restarStock - Producto: {$producto_id}, Talle: '{$talle}', Cantidad: {$cantidad}");
        
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
        $builder = $db->table('producto_talle');
        $builder->selectSum('stock');
        $builder->where('producto_id', $producto_id);
        $builder->where('talle !=', ''); // Excluir talles vacíos
        $builder->where('talle IS NOT', null); // Excluir talles nulos
        $result = $builder->get()->getRow();

        $stockTotal = $result->stock ?? 0;
        log_message('info', "📊 Stock total calculado para producto {$producto_id}: {$stockTotal}");

        // Actualizar tabla productos
        $productoModel = new \App\Models\Producto_model();
        $productoModel->update($producto_id, ['stock_total' => $stockTotal]);
        
        return $stockTotal;
    }

    // Método para limpiar registros problemáticos de un producto específico
    public function limpiarTallesProblematicos($producto_id)
    {
        // Eliminar registros con talles vacíos o nulos
        return $this->where('producto_id', $producto_id)
                    ->groupStart()
                        ->where('talle', '')
                        ->orWhere('talle IS', null)
                    ->groupEnd()
                    ->delete();
    }

    // Método para crear talles Kids para un producto
    public function crearTallesKids($producto_id)
    {
        $talles = ['8', '10', '12', '14'];
        
        foreach ($talles as $talle) {
            // Solo insertar si no existe
            $existing = $this->where('producto_id', $producto_id)
                             ->where('talle', $talle)
                             ->first();
            
            if (!$existing) {
                $this->insert([
                    'producto_id' => $producto_id,
                    'talle' => $talle,
                    'stock' => 0
                ]);
            }
        }
    }

    // NUEVO MÉTODO PARA ACTUALIZACIÓN INDIVIDUAL MÁS SEGURA
    public function actualizarStockIndividual($producto_id, $talle, $nuevo_stock)
    {
        log_message('info', "🎯 actualizarStockIndividual - Producto: {$producto_id}, Talle: '{$talle}', Stock: {$nuevo_stock}");
        
        // Usar query directo para máximo control
        $db = \Config\Database::connect();
        
        // Primero verificar si existe
        $existing = $db->query("SELECT id FROM producto_talle WHERE producto_id = ? AND talle = ?", [$producto_id, $talle])->getRow();
        
        if ($existing) {
            // Actualizar registro específico
            $result = $db->query("UPDATE producto_talle SET stock = ? WHERE id = ?", [$nuevo_stock, $existing->id]);
            log_message('info', "🔄 Actualizando registro ID {$existing->id} - Filas afectadas: " . $db->affectedRows());
        } else {
            // Insertar nuevo registro
            $result = $db->query("INSERT INTO producto_talle (producto_id, talle, stock) VALUES (?, ?, ?)", [$producto_id, $talle, $nuevo_stock]);
            log_message('info', "➕ Nuevo registro insertado - ID: " . $db->insertID());
        }
        
        return $result;
    }
}
