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

    // IMPORTANT: obligatoire pour insert/update
    protected $allowedFields = [
        'reference',
        'departement_id',
        'manager_id',
        'intitule_poste',
        'nb_postes',
        'type_contrat',
        'motif',
        'niveau_experience',
        'salaire_propose',
        'date_prise_poste',
        'missions',
        'competences',
        'urgence',
        'statut',
    ];

    public function getBesoins($status = null, $managerId = null)
    {
        $builder = $this->db->table($this->table . ' as b');

        $builder->select([
            'b.id',
            'b.reference',
            'd.nom AS departement',
            'b.intitule_poste',
            'b.nb_postes',
            'b.statut',
            'b.urgence',
            'b.created_at',
            'u.nom AS manager_nom',
        ]);

        $builder->join('departements d', 'd.id = b.departement_id', 'left');
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
            'u.nom AS manager_nom',
            'b.intitule_poste',
            'b.nb_postes',
            'b.type_contrat',
            'b.motif',
            'b.niveau_experience',
            'b.salaire_propose',
            'b.date_prise_poste',
            'b.missions',
            'b.competences',
            'b.urgence',
            'b.statut',
            'b.created_at',
        ]);

        $builder->join('departements d', 'd.id = b.departement_id', 'left');
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
        return $this->getBesoins('envoye_RH', $managerId);
    }

}