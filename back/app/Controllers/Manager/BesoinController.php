<?php

namespace App\Controllers\Manager;

use App\Models\ManagerBesoinModel;
use App\Controllers\BesoinModel;
use CodeIgniter\RESTful\ResourceController;

class BesoinController extends ResourceController
{
    protected ManagerBesoinModel $managerbesoinModel;

    public function __construct()
    {
        $this->managerbesoinModel = new ManagerBesoinModel();
    }

    public function index()
    {
        $managerId = (int) (session()->get('user_id') ?? 1);
        $statut = $this->normalizeStatut($this->request->getGet('statut'));

        if ($statut !== null) {
            return $this->respond([
                'data' => $this->managerbesoinModel->getBesoinsParStatut($statut, $managerId),
            ]);
        }

        $besoins = $this->managerbesoinModel
            ->select([
                'besoins.id',
                'besoins.reference',
                'departements.nom AS departement',
                'poste.nom AS poste',
                'poste.salaire_min',
                'poste.salaire_max',
                'besoins.intitule_poste',
                'besoins.nb_postes',
                'besoins.statut',
                'besoins.urgence',
                // 'besoins.salaire_propose',
                'besoins.created_at'
            ])
            ->join('departements', 'departements.id = besoins.departement_id', 'left')
            ->join('poste', 'poste.id = besoins.poste_id', 'left')
            ->where('besoins.manager_id', $managerId)
            ->whereIn('besoins.statut', ['brouillon', 'envoye_rh'])
            ->orderBy('besoins.created_at', 'DESC')
            ->findAll();

        return $this->respond(['data' => $besoins]);
    }

    public function byStatut(string $statut)
    {
        $managerId = (int) (session()->get('user_id') ?? 1);
        $statutNormalise = $this->normalizeStatut($statut);

        if ($statutNormalise === null) {
            return $this->failValidationErrors(['statut' => 'Statut invalide']);
        }

        return $this->respond([
            'data' => $this->managerbesoinModel->getBesoinsParStatut($statutNormalise, $managerId),
        ]);
    }

    public function postes()
    {
        return $this->respond([
            'data' => $this->managerbesoinModel->getPostes()
        ]);
    }

    private function normalizeStatut(?string $statut): ?string
    {
        if ($statut === null || trim($statut) === '') {
            return null;
        }

        $s = strtolower(trim($statut));
        return $s === 'envoye_rh' || $s === 'brouillon' ? $s : null;
    }

    public function store()
    {
        $data = $this->request->getJSON(true);

        $data['manager_id'] = session()->get('user_id') ?? 1;

        $rules = [
            'reference' => 'required|string|max_length[50]|is_unique[besoins.reference]',
            'departement_id' => 'required|integer',
            'poste_id' => 'required|integer',
            'intitule_poste' => 'required|string|max_length[255]',
            'nb_postes' => 'required|integer',
            'type_contrat' => 'required|in_list[CDI,CDD,CE]',
            'motif' => 'required|in_list[manque_effectif,remplacement,nouveau_projet]',
            'niveau_experience' => 'required|string|max_length[50]',
            'date_prise_poste' => 'required|valid_date',
            'missions' => 'required|string',
            'competences' => 'required|string',
            'urgence' => 'required|in_list[normal,urgent]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $isDraft = filter_var($data['is_draft'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $statut = $isDraft ? 'brouillon' : 'envoye_rh';

        $payload = [
            'reference' => trim($data['reference']),
            'departement_id' => (int) $data['departement_id'],
            'poste_id' => (int) $data['poste_id'],
            'manager_id' => (int) ($data['manager_id'] ?? session()->get('user_id') ?? 1),
            'intitule_poste' => trim($data['intitule_poste']),
            'nb_postes' => (int) $data['nb_postes'],
            'type_contrat' => $data['type_contrat'],
            'motif' => $data['motif'],
            'niveau_experience' => $data['niveau_experience'],
            'date_prise_poste' => $data['date_prise_poste'],
            'missions' => $data['missions'],
            'competences' => $data['competences'],
            'urgence' => $data['urgence'],
            'statut' => $statut,
        ];

        $id = $this->managerbesoinModel->insert($payload);

        if (!$id) {
            return $this->fail('Erreur lors de la création du besoin');
        }

        return $this->respondCreated([
            'message' => $isDraft ? 'Besoin enregistré en brouillon' : 'Besoin créé et envoyé au RH',
            'data' => ['id' => $id]
        ]);
    }
}