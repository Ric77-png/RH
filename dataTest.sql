USE entreprise;

-- ------------------------------------------------------------
-- Données minimales pour tester la liste des besoins RH
-- ------------------------------------------------------------

-- Le département est créé sans manager afin de pouvoir respecter
-- la dépendance circulaire entre departements et utilisateurs.
INSERT INTO departements (nom, manager_id)
VALUES ('Informatique', NULL);

SET @departement_id = (
    SELECT id
    FROM departements
    WHERE nom = 'Informatique'
    LIMIT 1
);

INSERT INTO utilisateurs (
    nom,
    prenom,
    email,
    mot_de_passe,
    role,
    departement_id,
    poste
)
VALUES (
    'Dupont',
    'Marie',
    'marie.dupont@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2yC3Kx0Q9p6Jq1G6',
    'manager',
    @departement_id,
    'Responsable informatique'
);

SET @manager_id = (
    SELECT id
    FROM utilisateurs
    WHERE email = 'marie.dupont@example.com'
    LIMIT 1
);

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
    statut
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
    'envoye_rh'
);