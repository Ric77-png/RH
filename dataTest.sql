USE entreprise;

-- ------------------------------------------------------------
-- Manager et département de test
-- ------------------------------------------------------------

-- Le département est créé sans manager afin de pouvoir respecter
-- la dépendance circulaire entre departements et utilisateurs.
START TRANSACTION;

INSERT INTO departements (nom)
VALUES ('Informatique');

SET @departement_id = LAST_INSERT_ID();

INSERT INTO utilisateurs (
  nom,
  prenom,
  email,
  mot_de_passe,
  role,
  departement_id,
  telephone,
  poste,
  date_embauche,
  actif
)
VALUES (
  'Dupont',
  'Marie',
  'marie.dupont@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2yC3Kx0Q9p6Jq1G6',
  'manager',
  @departement_id,
  NULL,
  'Responsable informatique',
  NULL,
  1
);

SET @manager_id = LAST_INSERT_ID();

UPDATE departements
SET manager_id = @manager_id
WHERE id = @departement_id;

INSERT INTO besoins (
  reference,
  departement_id,
  manager_id,
  intitule_poste,
  nb_postes,
  type_contrat,
  motif,
  niveau_experience,
  salaire_propose,
  date_prise_poste,
  missions,
  competences,
  urgence,
  statut,
  created_at
)
VALUES (
  'BES-2026-001',
  @departement_id,
  @manager_id,
  'Développeur web',
  1,
  'CDI',
  'nouveau_projet',
  'Intermédiaire',
  42000.00,
  '2026-11-02',
  'Développer et maintenir les applications web de l’entreprise.',
  'PHP, CodeIgniter 4, Vue.js, SQL',
  'normal',
  'envoye_rh',
  '2026-11-01 10:00:00'
);

COMMIT;
