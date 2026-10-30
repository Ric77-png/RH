<?php

namespace App\Controllers\Rh;

use App\Models\CandidatureModel;
use CodeIgniter\RESTful\ResourceController;

class CandidatureController extends ResourceController
{
    public function index()
    {
        return $this->respond([
            'data' => (new CandidatureModel())->getNonTraitees(),
        ]);
    }
}
