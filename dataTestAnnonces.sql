USE entreprise;

-- ============================================================
-- Correctif : complète le besoin BES-2026-002 déjà inséré
-- (validations, annonce, budget) avec les utilisateurs existants.
-- Peut être relancé sans erreur.
-- ============================================================
START TRANSACTION;

-- Le besoin existe déjà : on le retrouve et on force son statut
SET @besoin_id = (SELECT id FROM besoins WHERE reference = 'BES-2026-002');
UPDATE besoins SET statut = 'annonce_publiee' WHERE id = @besoin_id;

SET @departement_id = (SELECT departement_id FROM besoins WHERE id = @besoin_id);
SET @manager_id     = (SELECT manager_id     FROM besoins WHERE id = @besoin_id);
SET @poste_id       = (SELECT poste_id       FROM besoins WHERE id = @besoin_id);

-- Pour chaque rôle : 1) utilisateur du bon rôle s'il existe,
-- 2) sinon le manager du besoin (utilisateur déjà présent)
SET @rh_id        = COALESCE((SELECT id FROM utilisateurs WHERE role = 'rh'        ORDER BY id LIMIT 1), @manager_id);
SET @finance_id   = COALESCE((SELECT id FROM utilisateurs WHERE role = 'finance'   ORDER BY id LIMIT 1), @manager_id);
SET @direction_id = COALESCE((SELECT id FROM utilisateurs WHERE role = 'direction' ORDER BY id LIMIT 1), @manager_id);

-- Nettoyage d'un éventuel essai précédent
DELETE FROM besoin_validations WHERE besoin_id = @besoin_id;
DELETE FROM annonces           WHERE besoin_id = @besoin_id;

-- Historique du circuit
INSERT INTO besoin_validations (besoin_id, etape, utilisateur_id, decision, commentaire, date_action) VALUES
(@besoin_id, 'rh',        @rh_id,        'transmis',       'Dossier complet, transmis à la finance.',         '2026-10-06 10:00:00'),
(@besoin_id, 'finance',   @finance_id,   'budget_adequat', 'Salaire compatible avec le budget Informatique.', '2026-10-07 11:30:00'),
(@besoin_id, 'direction', @direction_id, 'valide',         'Recrutement validé.',                             '2026-10-08 08:15:00');

-- Annonce publiée
INSERT INTO annonces (
  besoin_id, rh_id, titre, lieu, description, profil_recherche,
  afficher_salaire, date_limite, statut, date_publication
) VALUES (
  @besoin_id, @rh_id, 'Développeur web', 'Antananarivo',
  'Rejoignez le département Informatique pour développer et maintenir nos applications web.',
  'Bac+3 ou équivalent, 2 ans d’expérience, PHP, CodeIgniter 4, Vue.js, SQL.',
  0, '2026-11-30', 'publiee', '2026-10-09 09:00:00'
);

-- Budget (l'exercice 2026 existe déjà)
INSERT IGNORE INTO exercices (annee, statut) VALUES (2026, 'ouvert');
SET @exercice_id = (SELECT id FROM exercices WHERE annee = 2026);
SET @cout_annuel = (SELECT salaire_max * 12 FROM poste WHERE id = @poste_id);
SET @ref = UUID();

DELETE FROM budget_mouvements
WHERE exercice_id = @exercice_id
  AND libelle IN ('Budget annuel 2026', 'Allocation Informatique', 'Besoin BES-2026-002');

INSERT INTO budget_mouvements
  (exercice_id, departement_id, sens, categorie, montant, libelle, statut, virement_ref, besoin_id, cree_par, date_mouvement)
VALUES
  (@exercice_id, NULL,            'entree', 'dotation_initiale',   1000000,      'Budget annuel 2026',     'realise', NULL, NULL,       @direction_id, '2026-01-02'),
  (@exercice_id, NULL,            'sortie', 'allocation',           300000,      'Allocation Informatique', 'realise', @ref, NULL,       @direction_id, '2026-01-05'),
  (@exercice_id, @departement_id, 'entree', 'allocation',           300000,      'Allocation Informatique', 'realise', @ref, NULL,       @direction_id, '2026-01-05'),
  (@exercice_id, @departement_id, 'sortie', 'salaire_recrutement',  @cout_annuel, 'Besoin BES-2026-002',    'engage',  NULL, @besoin_id, @direction_id, '2026-10-08');

COMMIT;

-- Vérifications
SELECT reference, statut FROM besoins;
SELECT etape, decision, utilisateur_id FROM besoin_validations WHERE besoin_id = @besoin_id;
SELECT titre, statut, rh_id FROM annonces WHERE besoin_id = @besoin_id;
SELECT * FROM v_budget_departement;