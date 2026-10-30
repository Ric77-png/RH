<?php

namespace App\Models;

use CodeIgniter\Model;

class PosteModel extends Model
{
    protected $table = 'poste';
    protected $primaryKey = 'id';
    protected $allawedFields = ['nom', 'salaire_min', 'salaire_max', 'description'];

    protected $validationRules = [
        'nom' => 'required|string|max_length[255]',
        'salaire_min' => 'required|decimal',
        'salaire_max' => 'required|decimal|greater_than_equal_to[salaire_min]',
        'description' => 'permit_empty|string',
    ];
}