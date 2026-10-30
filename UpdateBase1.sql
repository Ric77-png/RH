ALTER TABLE utilisateurs MODIFY COLUMN role ENUM('candidat','employe','manager','rh','finance','direction') NOT NULL;

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '$2y$10$m/9Zdz3IYJdsXPBPzernCOgOuIDUU1.RNCYyPxTrcq/7rpuUznUN2', 'manager');