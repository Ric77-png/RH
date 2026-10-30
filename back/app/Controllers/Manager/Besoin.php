<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Models\ManagerModel;
use App\Models\BesoinModel;

class Besoin extends BaseController
{
    public function index() {
    // {
    //     if (!session()->get('operateur_logged_in')) {
    //         return redirect()->to('/operateur');
    //     }

        $model = new BesoinModel();
        $data['besoins'] = $model->orderBy('id', 'DESC')->findAll();
        return view('operateur/operateurs', $data);
    }

    public function store()
    {
        if (!session()->get('operateur_logged_in')) {
            return redirect()->to('/operateur');
        }

        $nom = $this->request->getPost('nom');
        $code = strtoupper($this->request->getPost('code'));
        $description = $this->request->getPost('description');

        if (empty($nom) || empty($code)) {
            return redirect()->back()->with('error', 'Nom et code obligatoires');
        }

        $model = new OperateurModel();
        
        $existing = $model->where('code', $code)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Ce code existe déjà');
        }

        $model->insert([
            'nom' => $nom,
            'code' => $code,
            'description' => $description
        ]);

        return redirect()->to('/operateur/operateurs')->with('success', 'Opérateur ajouté');
    }

    public function delete($id)
    {
        if (!session()->get('operateur_logged_in')) {
            return redirect()->to('/operateur');
        }

        $model = new OperateurModel();
        $model->delete($id);
        return redirect()->to('/operateur/operateurs')->with('success', 'Opérateur supprimé');
    }

    public function toggle($id)
    {
        if (!session()->get('operateur_logged_in')) {
            return redirect()->to('/operateur');
        }

        $model = new OperateurModel();
        $operateur = $model->find($id);
        
        if ($operateur) {
            $newStatus = $operateur['actif'] ? 0 : 1;
            $model->update($id, ['actif' => $newStatus]);
        }

        return redirect()->to('/operateur/operateurs')->with('success', 'Statut modifié');
    }
}