<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table = 'besoins';
    protected $primaryKey = 'id';
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getAllBesoins()
    {
        return $this->findAll();
    }

}