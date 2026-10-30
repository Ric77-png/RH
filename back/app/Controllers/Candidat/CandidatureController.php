<?php

namespace App\Controllers\Candidat;

use App\Controllers\BaseController;
use App\Models\CandidatureModel;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class CandidatureController extends BaseController
{
    public function store(): ResponseInterface
    {
        $rules = [
            'annonce_id' => 'required|is_natural_no_zero',
            'nom' => 'required|string|max_length[100]',
            'prenom' => 'required|string|max_length[100]',
            'telephone' => 'permit_empty|string|max_length[30]',
            'email' => 'required|valid_email|max_length[150]',
            'curriculumVitae' => 'uploaded[curriculumVitae]|max_size[curriculumVitae,5120]|ext_in[curriculumVitae,pdf]|mime_in[curriculumVitae,application/pdf]',
            'lettreMotivation' => 'uploaded[lettreMotivation]|max_size[lettreMotivation,5120]|ext_in[lettreMotivation,pdf]|mime_in[lettreMotivation,application/pdf]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Veuillez corriger les informations du formulaire.',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $cv = $this->request->getFile('curriculumVitae');
        $letter = $this->request->getFile('lettreMotivation');
        $uploadDirectory = WRITEPATH . 'uploads/candidatures';

        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0750, true) && !is_dir($uploadDirectory)) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Le dossier de stockage des fichiers est indisponible.',
            ]);
        }

        $cvName = $cv->getRandomName();
        $letterName = $letter->getRandomName();

        try {
            $cv->move($uploadDirectory, $cvName);
            $letter->move($uploadDirectory, $letterName);

            $candidatureId = (new CandidatureModel())->enregistrerCandidature(
                (int) $this->request->getPost('annonce_id'),
                [
                    'nom' => trim((string) $this->request->getPost('nom')),
                    'prenom' => trim((string) $this->request->getPost('prenom')),
                    'telephone' => trim((string) $this->request->getPost('telephone')),
                    'email' => trim((string) $this->request->getPost('email')),
                    'cv_path' => 'uploads/candidatures/' . $cvName,
                    'lettre_path' => 'uploads/candidatures/' . $letterName,
                ]
            );
        } catch (Throwable $exception) {
            foreach ([$uploadDirectory . DIRECTORY_SEPARATOR . $cvName, $uploadDirectory . DIRECTORY_SEPARATOR . $letterName] as $uploadedFile) {
                if (is_file($uploadedFile)) {
                    unlink($uploadedFile);
                }
            }

            log_message('error', 'Enregistrement candidature échoué: {message}', [
                'message' => $exception->getMessage(),
            ]);
            $statusCode = str_contains($exception->getMessage(), 'existe déjà') ? 409 : 500;

            return $this->response->setStatusCode($statusCode)->setJSON([
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Votre candidature a été envoyée avec succès.',
            'data' => ['id' => $candidatureId],
        ]);
    }
}
