<?php 
namespace App\Models;

use CodeIgniter\Model;

class Categoria_model extends Model
{
    protected $table            = 'categoria';
    protected $primaryKey       = 'id_categoria';
    protected $allowedFields    = ['ct_nombre', 'ct_descripcion', 'fecha', 'activacion'];
    protected $useSoftDeletes   = false;

    protected $useTimestamps    = false;
    protected $createdField     = 'fecha';
    protected $deletedField     = 'activacion';

    protected $validationRules  = [
        "ct_nombre"         => "required|min_length[3]|max_length[50]",
        "ct_descripcion"    => "required|max_length[200]"
    ];

    protected $validationMessages = [
        "ct_nombre" => [
            "required"    => 'El nombre de la categoría es obligatorio',
            "min_length"  => 'Debe tener al menos 3 caracteres',
            "max_length"  => 'Debe tener como máximo 50 caracteres'
        ],
        "ct_descripcion" => [
            "required"    => 'La descripción es obligatoria',
            "max_length"  => 'Debe tener como máximo 200 caracteres'
        ]
    ];

    public function obtenerCategorias($buscar = null)
    {
        if ($buscar) {
            return $this->like('ct_nombre', $buscar)->findAll();
        }
        return $this->findAll();
    }
}