ALTER TABLE besoins DROP COLUMN IF EXISTS salaire_propose;

CREATE TABLE IF NOT EXISTS poste (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    salaire_min DECIMAL(10,2) NOT NULL,
    salaire_max DECIMAL(10,2) NOT NULL
);

INSERT INTO poste (nom, salaire_min, salaire_max)
SELECT 'Développeur', 3000.00, 5000.00
WHERE NOT EXISTS (SELECT 1 FROM poste WHERE nom = 'Développeur');

INSERT INTO poste (nom, salaire_min, salaire_max)
SELECT 'Designer', 2500.00, 4000.00
WHERE NOT EXISTS (SELECT 1 FROM poste WHERE nom = 'Designer');

INSERT INTO poste (nom, salaire_min, salaire_max)
SELECT 'Chef de projet', 4000.00, 6000.00
WHERE NOT EXISTS (SELECT 1 FROM poste WHERE nom = 'Chef de projet');

ALTER TABLE besoins
    ADD COLUMN IF NOT EXISTS poste_id INT UNSIGNED;

SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE besoins ADD CONSTRAINT fk_besoins_poste FOREIGN KEY (poste_id) REFERENCES poste(id)',
        'SELECT 1'
    )
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'besoins'
      AND CONSTRAINT_NAME = 'fk_besoins_poste'
);

PREPARE ajouter_fk FROM @sql;
EXECUTE ajouter_fk;
DEALLOCATE PREPARE ajouter_fk;

UPDATE besoins b
JOIN poste p ON p.nom = 'Développeur'
SET b.poste_id = p.id
WHERE b.poste_id IS NULL;

ALTER TABLE besoins MODIFY COLUMN poste_id INT UNSIGNED NOT NULL;