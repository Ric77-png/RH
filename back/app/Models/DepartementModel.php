<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'manager_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
}