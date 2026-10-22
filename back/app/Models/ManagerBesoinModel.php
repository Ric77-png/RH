<?php

namespace App\Models;

use CodeIgniter\Model;

class ManagerBesoinModel extends Model
{
    protected $table = 'besoins';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    protected $allowedFields = [
        'reference',
        'departement_id',
        'poste_id',
        'manager_id',
        'intitule_poste',
        'nb_postes',
        'type_contrat',
        'motif',
        'niveau_experience',
        'date_prise_poste',
        'missions',
        'competences',
        'urgence',
        'statut',
    ];

    public function getPostes(): array
    {
        return $this->db->table('poste')
            ->select([
                'id',
                'nom',
                'salaire_min',
                'salaire_max',
            ])
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getBesoins($status = null, $managerId = null)
    {
        $builder = $this->db->table($this->table . ' as b');

        $builder->select([
            'b.id',
            'b.reference',
            'd.nom AS departement',
            'p.nom AS poste',
            'p.salaire_min',
            'p.salaire_max',
            'b.intitule_poste',
            'b.nb_postes',
            'b.statut',
            'b.urgence',
            'b.created_at',
            'u.nom AS manager_nom',
        ]);

        $builder->join('departements d', 'd.id = b.departement_id', 'left');
        $builder->join('poste p', 'p.id = b.poste_id', 'left');
        $builder->join('utilisateurs u', 'u.id = b.manager_id', 'left');

        if ($status !== null) {
            $builder->where('b.statut', $status);
        }

        if ($managerId !== null) {
            $builder->where('b.manager_id', $managerId);
        }

        return $builder
            ->orderBy('b.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getBesoinsDetails($reference)
    {
        $builder = $this->db->table($this->table . ' as b');

        $builder->select([
            'b.id',
            'b.reference',
            'd.nom AS departement',
            'p.nom AS poste',
            'p.salaire_min',
            'p.salaire_max',
            'u.nom AS manager_nom',
            'b.intitule_poste',
            'b.nb_postes',
            'b.type_contrat',
            'b.motif',
            'b.niveau_experience',
            'b.date_prise_poste',
            'b.missions',
            'b.competences',
            'b.urgence',
            'b.statut',
            'b.created_at',
        ]);

        $builder->join('departements d', 'd.id = b.departement_id', 'left');
        $builder->join('poste p', 'p.id = b.poste_id', 'left');
        $builder->join('utilisateurs u', 'u.id = b.manager_id', 'left');
        $builder->where('b.reference', $reference);

        return $builder->get()->getRowArray();
    }

    public function getAllBesoins()
    {
        return $this->findAll();
    }

    public function getBesoinsParStatut(string $statut, ?int $managerId = null): array
    {
        return $this->getBesoins($statut, $managerId);
    }

    public function getBrouillons(?int $managerId = null): array
    {
        return $this->getBesoins('brouillon', $managerId);
    }

    public function getEnvoyesRH(?int $managerId = null): array
    {
        return $this->getBesoins('envoye_rh', $managerId);
    }
}