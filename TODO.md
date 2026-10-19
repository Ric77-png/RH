# GESTION-RH — squelette CodeIgniter 4 + MySQL

Projet: application de gestion RH basée sur CodeIgniter 4 et MySQL.

Assignations (équipe de 4)

- **Personne 1 — Init repo & CI**: initialiser le dépôt distant, ajouter README, configurer CI/quality (lint, phpunit), définir environnement de dev (Docker / .env.example).
- **Personne 2 — Backend: Models & Migrations**: créer les modèles principaux (`User`, `Employee`, `Department`, `Payroll`), écrire les migrations et seeds, définir les relations.
- **Personne 3 — Controllers & Routes**: implémenter les controllers par module (`Rh`, `Manager`, `Finance`, `Direction`), ajouter routes RESTful, gérer authentification/autorisation.
- **Personne 4 — Frontend Views & Auth**: concevoir les vues (auth, layouts, tableaux de bord), intégrer formulaires, validation côté client, liaison avec controllers.

Checklist rapide

- **Créer/mettre à jour les branches**: travail par branche feature (`feature/<nom>`).
- **Revue de code**: ouvrir PR vers `main` et demander review avant merge.
- **Tests**: ajouter tests unitaires pour logique métier critique.

Notes pour push

1) Après vos modifications locales:

```powershell
git checkout -b feature/votre-nom
git add .
git commit -m "<brief>: description du travail"
git push -u origin feature/votre-nom
```

2) Ouvrir une Pull Request vers `main` sur la plateforme Git (GitHub/GitLab).

Remarque: remplacez "Personne 1..4" par les noms réels de l'équipe si vous le souhaitez.





