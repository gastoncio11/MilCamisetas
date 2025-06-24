<?php
namespace App\Models;

use CodeIgniter\Model;

class Detalle_venta_model extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle';
    protected $allowedFields = [
        'id_venta',
        'producto_id',
        'talle',
        'cantidad',
        'precio_unitario'
    ];
}
