<?php
namespace App\Models;

use CodeIgniter\Model;

class Venta_model extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    protected $allowedFields = [
        'id_usuario',
        'fecha',
        'total'
    ];

    protected $useTimestamps = false; 
}
