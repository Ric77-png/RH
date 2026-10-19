<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinModel extends Model
{
    protected $table = 'besoins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom','reference','departement_id','manager_id','intitule_poste','nb_postes','type_contrat','motif','niveau_experience','salaire_propose','date_prise_poste','missions','competences','urgence','statut','created_at','updated_at'];

    protected $validationRules = [
        'nom' => 'required',
        'reference' => 'required',
        'departement_id' => 'required|integer',
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

    
    public function getBesoins($statut){
        return $this->where('statut', $statut)->findAll();
    }
}