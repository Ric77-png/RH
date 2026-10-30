<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class CandidatureModel extends Model
{
    protected $table = 'candidatures';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'annonce_id',
        'candidat_id',
        'cv_path',
        'lettre_path',
        'message',
        'consentement',
        'statut',
    ];

    /**
     * Retourne les candidatures encore en attente de traitement par le RH.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getNonTraitees(): array
    {
        return $this->select([
            'candidatures.id',
            'candidatures.annonce_id',
            'candidatures.statut',
            'candidatures.cv_path',
            'candidatures.lettre_path',
            'candidatures.date_candidature',
            'candidats.nom',
            'candidats.prenom',
            'candidats.email',
            'candidats.telephone',
            'annonces.titre',
        ])
            ->join('candidats', 'candidats.id = candidatures.candidat_id')
            ->join('annonces', 'annonces.id = candidatures.annonce_id')
            ->where('candidatures.statut', 'nouvelle')
            ->orderBy('candidatures.date_candidature', 'DESC')
            ->findAll();
    }

    /**
     * Enregistre le candidat et sa candidature dans une seule transaction.
     *
     * @param array<string, mixed> $candidateData
     */
    public function enregistrerCandidature(int $annonceId, array $candidateData): int
    {
        $db = $this->db;
        $db->transBegin();

        try {
            $candidateModel = new Model();
            $candidateModel->setTable('candidats');
            $candidateModel->setAllowedFields(['nom', 'prenom', 'email', 'telephone']);

            $candidate = $db->table('candidats')
                ->where('email', $candidateData['email'])
                ->get()
                ->getRowArray();

            if ($candidate) {
                $candidateId = (int) $candidate['id'];
                $candidateModel->update($candidateId, [
                    'nom' => $candidateData['nom'],
                    'prenom' => $candidateData['prenom'],
                    'telephone' => $candidateData['telephone'],
                ]);
            } else {
                $candidateId = (int) $candidateModel->insert([
                    'nom' => $candidateData['nom'],
                    'prenom' => $candidateData['prenom'],
                    'email' => $candidateData['email'],
                    'telephone' => $candidateData['telephone'],
                ], true);
            }

            if ($candidateId < 1) {
                throw new RuntimeException('Impossible d’enregistrer le candidat.');
            }

            $existing = $db->table($this->table)
                ->where('annonce_id', $annonceId)
                ->where('candidat_id', $candidateId)
                ->countAllResults();

            if ($existing > 0) {
                throw new RuntimeException('Une candidature existe déjà pour cette annonce avec cet e-mail.');
            }

            $candidatureId = (int) $this->insert([
                'annonce_id' => $annonceId,
                'candidat_id' => $candidateId,
                'cv_path' => $candidateData['cv_path'],
                'lettre_path' => $candidateData['lettre_path'],
                'consentement' => 1,
                'statut' => 'nouvelle',
            ], true);

            if ($candidatureId < 1 || !$db->transStatus()) {
                throw new RuntimeException('Impossible d’enregistrer la candidature.');
            }

            $db->transCommit();
            return $candidatureId;
        } catch (\Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }
    }
}
