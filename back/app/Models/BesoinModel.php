<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table = 'besoins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['reference','departements_id','manager_id','intitule_poste','nb_postes','type_contrat','motif','niveau_experience','salaire_propose','date_prise_poste','missions','competences','urgence','statut','created_at','updated_at'];

    protected $validationRules = [
        'reference' => 'required',
        'departements_id' => 'required|integer',
        'manager_id' => 'required|integer',
        'intitule_poste' => 'required',
        'nb_postes' => 'required|integer',
        'type_contrat' => 'required',
        'motif' => 'required|in_list[manque_effectif,remplacement,nouveau_projet]',
        'niveau_experience' => 'required',
        'salaire_propose' => 'required|numeric',
        'date_prise_poste' => 'required|valid_date',
        'missions' => 'required',
        'competences' => 'required',
        'urgence' => 'required|in_list[normal,urgent]',
        'statut' => 'required|in_list[bruillon,envoye_rh,envoye_finance,envoye_direction,valide,refuse,annonce_publiee]',
    ];

    
    public function getBesoins($status){
        return $this->select('
            besoins.reference,
            departements.nom AS departements,
            besoins.intitule_poste,
            besoins.nb_postes,
            besoins.statut,
            besoins.urgence
        ')
            ->join('departements', 'departements.id = besoins.departement_id', 'left')
            ->where('besoins.statut', $status)
            ->findAll();
    }

    public function getBesoinsDetails($reference)
    {
        return $this->select('
                besoins.reference,
                departements.nom AS departements,
                utilisateurs.nom AS manager,
                besoins.intitule_poste,
                besoins.nb_postes,
                besoins.type_contrat,
                besoins.motif,
                besoins.niveau_experience,
                besoins.salaire_propose,
                besoins.date_prise_poste,
                besoins.missions,
                besoins.competences,
                besoins.urgence,
                besoins.statut,
                besoins.created_at
            ')
            ->join('departements', 'departements.id = besoins.departement_id', 'left')
            ->join('utilisateurs', 'utilisateurs.id = besoins.manager_id', 'left')
            ->where('besoins.reference', $reference)
            ->findAll();
    }
}