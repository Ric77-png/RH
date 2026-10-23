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
USE entreprise;

-- 1. Créer un utilisateur RH
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif) 
VALUES ('Martin', 'Sophie', 'sophie.rh@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdB5jWk4G1yXq1W6a', 'rh', 1);

-- 2. Créer un utilisateur Finance
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif) 
VALUES ('Durand', 'Paul', 'paul.finance@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdB5jWk4G1yXq1W6a', 'finance', 1);

-- 3. Créer un utilisateur Direction
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif) 
VALUES ('Leroy', 'Isabelle', 'isabelle.direction@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdB5jWk4G1yXq1W6a', 'direction', 1);

UPDATE utilisateurs 
SET mot_de_passe = '$2y$10$/uWfuKHA1sIgX0NHQkt1MOWnYgm4LHcq0UuMccVEmHYEtQPYZEsNK'
WHERE email = 'marie.dupont@example.com';

UPDATE utilisateurs 
SET mot_de_passe = '$2y$10$KpAQ87jJUzgvC6EMwH0KxeaOCA.55I76GgqQf5XW1Wgy/LRJZO6Gi'
WHERE email = 'paul.finance@example.com';

UPDATE utilisateurs 
SET mot_de_passe = '$2y$10$TkXjKrrvFQ/G3iw7xLlLLeaSSjGilmJunIywMeQyf7dl74Bl2RTWa'
WHERE email = 'isabelle.direction@example.com';