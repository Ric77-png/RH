<?php
namespace App\Controllers\Manager;

use App\Models\DepartementModel;
use CodeIgniter\RESTful\ResourceController;

class DepartementController extends ResourceController
{
    protected $departementModel;

    public function __construct()
    {
        $this->departementModel = new DepartementModel();
    }

    public function index()
    {
        $departements = $this->departementModel
            ->select('id, nom')
            ->orderBy('nom', 'ASC')
            ->findAll();

        return $this->respond([
            'data' => $departements
        ]);
    }
}