<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table = 'besoins';
    protected $table2 = 'besoin_validations';
    protected $table3 = 'annonces';
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

    public function getBesoins($status)
    {
        return $this->select('
            annonces.id AS annonce_id,
            besoins.reference,
            departements.nom AS departement,
            poste.nom AS poste,
            poste.salaire_min,
            poste.salaire_max,
            besoins.intitule_poste,
            besoins.nb_postes,
            besoins.type_contrat,
            besoins.motif,
            besoins.niveau_experience,
            besoins.date_prise_poste,
            besoins.missions,
            besoins.competences,
            besoins.statut,
            besoins.urgence,
            besoins.updated_at
        ')
            ->join('departements', 'departements.id = besoins.departement_id', 'left')
            ->join('poste', 'poste.id = besoins.poste_id', 'left')
            ->join('annonces', "annonces.besoin_id = besoins.id AND annonces.statut = 'publiee'", 'left')
            ->where('besoins.statut', $status)
            ->orderBy('besoins.updated_at', 'DESC')
            ->findAll();
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

    public function getAnnonce($statut)
    {
        return $this->getBesoins($statut);
    }

    public function insertAnnonce($besoinID, $rhID, $titre, $lieu, $description, $profilRechercher, $afficherSalaire, $dateLimite, $statut)
    {
        $data = [
            'besoin_id' => $besoinID,
            'rh_id' => $rhID,
            'titre' => $titre,
            'lieu' => $lieu,
            'description' => $description,
            'profil_rechercher' => $profilRechercher,
            'afficher_salaire' => $afficherSalaire,
            'date_limite' => $dateLimite,
            'statut' => $statut
        ];

        return $this->db->table($this->table3)->insert($data);
    }

     public function getAllBesoins()
    {
        return $this->findAll();
    }

}