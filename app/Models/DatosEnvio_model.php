<?php
namespace App\Models;

use CodeIgniter\Model;

class DatosEnvio_model extends Model
{
    protected $table = 'datos_envio';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_venta',
        'localidad',
        'telefono',
        'numero_tarjeta',
        'cvv'
    ];
    
    protected $useTimestamps = false;
}