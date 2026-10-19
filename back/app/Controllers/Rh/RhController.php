<?php

namespace App\Controllers\Rh;

use App\Models\BesoinModel;
use CodeIgniter\RESTful\ResourceController;

class RhController extends ResourceController
{
    protected $besoinModel;

    public function __construct()
    {
        $this->besoinModel = new BesoinModel();
    }

    public function index()
    {
        $statut = 'envoye_rh';
        $besoins = $this->besoinModel->getBesoins($statut);

        return $this->respond([
            'data' => $besoins,
        ]);
    }

    public function getDetails($reference)
    {
        $besoin = $this->besoinModel->getBesoinsDetails($reference);

        if (!$besoin) {
            return $this->failNotFound('Besoin not found');
        }

        return $this->respond([
            'data' => $besoin,
        ]);
    }
}