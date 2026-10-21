<?php

namespace App\Controllers\Finance;

use App\Models\BudgetModel;
use App\Models\BesoinModel;

use CodeIgniter\RESTful\ResourceController;

class FinanceController extends ResourceController
{
    protected $budgetModel;
    protected $besoinModel;

    public function __construct()
    {
        $this->budgetModel = new BudgetModel();
        $this->besoinModel = new BesoinModel();
    }

    public function soldeDepartement($departementId, $exerciceId)
    {
        $solde = $this->budgetModel->soldeDepartement((int)$departementId, (int)$exerciceId);

        return $this->respond([
            'data' => $solde,
        ]);
    }

    public function historiqueDepartement()
    {
        $historique = $this->budgetModel->historiqueDepartement();

        return $this->respond([
            'data' => $historique,
        ]);
    }

    public function getBudgetGeneral()
    {
        $budgetGeneral = $this->budgetModel->getBudgetGeneral();

        return $this->respond([
            'data' => $budgetGeneral,
        ]);
    }

    //ty le notification du besoins avec statut =envoye_finance
//d vita le validation hoe na 'budget_adequat' na 'budget_inadequat' zay vao apidirina ao @ table besoin_validations
    //Ty mo notification no nataoko anarany f ty mamôka an izay besoins ao @finance
    public function getNotificationBesoin(){
        $status='envoye_finance';
        $notifications = $this->besoinModel->getBesoins($status);

        return $this->respond([
            'data' => $notifications,
        ]);
    }

    //Ty raha t ahazo an ilay détail an ilay besoin
    public function getDetailsBesoin($reference)
    {
        $besoin = $this->besoinModel->getBesoinsDetails($reference);

        if (!$besoin) {
            return $this->failNotFound('Besoin not found');
        }

        return $this->respond([
            'data' => $besoin,
        ]);
    }

    //Ty ilay decision misy ao @besoins validations
    public function getDecision()
    {
        $decisions = $this->budgetModel->getDecision();

        return $this->respond([
            'data' => $decisions,
        ]);
    }

    //ty le manao enregistrement an izay besoin valié ao @finance
    public function enregistrerValidation($id_utilisateur,$id_besoin,$decision,$commentaire)
    {
        $this->budgetModel->enregistrerValidation($id_utilisateur,$id_besoin,$decision,$commentaire);

        return $this->respond([
            'message' => 'Validation enregistrée avec succès',
        ]);
    }
}