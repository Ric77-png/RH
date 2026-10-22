<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table = 'budget_mouvements';
    protected $table2= 'exercices';
    protected $table3= 'besoins';
    protected $table4= 'besoin_validations';
   
    protected $primaryKey = 'id';
    protected $allowedFields = ['exercice_id ','departement_id','sens','categorie ','montant','libelle','statut','virement_ref','besoin_id','cree_par','date_mouvement','created_at','updated_at'];

    protected $validationRules = [
        'exercice_id' => 'required|integer',
        'departement_id' => 'required|integer',
        'sens' => 'required|in_list[entree,sortie]',
        'categorie' => 'required',
        'montant' => 'required|numeric',
        'libelle' => 'required',
        'statut' => 'required|in_list[engage,realise,annule]',
        'virement_ref' => 'permit_empty|string',
        'besoin_id' => 'permit_empty|integer',
        'cree_par' => 'required|integer',
        'date_mouvement' => 'required|valid_date',
    ];

//ty le solde du budget pour un département donné et un exercice donné
public function soldeDepartement(int $departementId, int $exerciceId): array
{
    return $this->select("
            COALESCE(SUM(CASE WHEN sens = 'entree' THEN montant END), 0) AS total_entrees,
            COALESCE(SUM(CASE WHEN sens = 'sortie' THEN montant END), 0) AS total_sorties,
            COALESCE(SUM(CASE WHEN sens = 'entree' THEN montant ELSE -montant END), 0) AS solde
        ", false)
        ->where('departement_id', $departementId)
        ->where('exercice_id', $exerciceId)
        ->where('statut !=', 'annule')
        ->first();
}

//Ty solde de chaque département et exercice
public function historiqueDepartement(): array
{
    return $this->db->table('v_budget_departement')
        ->orderBy('annee', 'DESC')
        ->orderBy('departement', 'ASC')
        ->get()
        ->getResultArray();
}

//Ty le budget general de l'entreprise pour l'exercice en cours
public function getBudgetGeneral(): array
{
    $exercicesOuverts = $this->db->table($this->table2)
        ->select('annee')
        ->where('statut', 'ouvert')
        ->orderBy('annee', 'DESC')
        ->get()
        ->getResultArray();

    if ($exercicesOuverts === []) {
        return [];
    }

    return $this->db->table('v_budget_global')
        ->whereIn('annee', array_column($exercicesOuverts, 'annee'))
        ->get()
        ->getRowArray() ?? [];
}

public function getDecision(): array
{
    return $this->db->table($this->table4)
        ->select('decision')
        ->distinct()
        ->orderBy('decision', 'ASC')
        ->get()
        ->getResultArray();
}

public function enregistrerValidation($id_utilisateur,$id_besoin,$decision,$commentaire)
{
    $this->db->table($this->table4)->insert([
        'besoin_id' => $id_besoin,
        'utilisateur_id' => $id_utilisateur,
        'decision' => $decision,
        'etape' => 'finance',
        'commentaire' => $commentaire,
        'date_action ' => date('Y-m-d H:i:s')
    ]);
}



}