# 🏥 Hôpital de la Main

Application web **interne** de gestion des patients d'un hôpital spécialisé dans les pathologies de la main : dossiers patients, rendez-vous, opérations, prescriptions, imagerie et chambres, le tout centralisé et protégé par un système de rôles.


## 📋 Sommaire

- [Contexte et objectifs](#-contexte-et-objectifs)
- [Fonctionnalités](#-fonctionnalités)
- [Rôles et permissions](#-rôles-et-permissions)
- [Stack technique](#-stack-technique)
- [Installation](#-installation)
- [Comptes de démonstration](#-comptes-de-démonstration)
- [Structure du projet](#-structure-du-projet)
- [Sécurité](#-sécurité)
- [Charte graphique](#-charte-graphique)
- [Feuille de route (MVP)](#-feuille-de-route-mvp)
- [Gestion de projet](#-gestion-de-projet)

---

## 🎯 Contexte et objectifs

L'application doit permettre aux médecins, infirmiers et secrétaires de gérer les patients de manière centralisée afin de :

- accéder rapidement aux informations d'un patient et à son emplacement (chambre, bloc opératoire) ;
- **réduire l'usage du papier** et éviter la perte de documents ;
- faciliter la coordination entre tous les membres de l'équipe ;
- offrir une interface **simple, rapide**, utilisable sur ordinateur comme sur tablette.

L'accès est **strictement interne** : aucune inscription publique, les comptes sont créés uniquement par un administrateur.

## ✨ Fonctionnalités

| Domaine | Description |
|---|---|
| **Utilisateurs** | Création, modification et suppression des comptes par l'administrateur, matricule personnel par membre de l'équipe, gestion des rôles |
| **Dossiers patients** | Fiche médicale complète (identité, personne de confiance, numéro de dossier, médecin référent…), ajout / modification / suppression |
| **Rendez-vous** | Rendez-vous de consultation et rendez-vous opératoires |
| **Consultations** | Suivi des consultations par les médecins |
| **Prescriptions** | Traitements personnalisés prescrits par le médecin, consultables par les infirmiers |
| **Observations** | Notes de soins ajoutées par les infirmiers au dossier |
| **Imagerie / documents** | Ajout d'images (IRM, scanner) et de documents au dossier patient |
| **Chambres** | Attribution et suivi des chambres occupées |
| **Blocs opératoires** | Attribution d'un bloc avec heure de début et de fin, anesthésiste, équipe participante, traitement pendant l'opération |
| **Spécialités** | Gestion des spécialités des médecins et infirmiers |

## 👥 Rôles et permissions

| Rôle | Droits principaux |
|---|---|
| **Administrateur** | Crée, modifie et supprime les utilisateurs ; gère les rôles. **N'a aucun accès aux dossiers patients.** |
| **Secrétaire** | Crée les patients et modifie leurs informations, gère chambres, rendez-vous et rendez-vous opératoires, consulte le compte rendu postopératoire et le document de sortie. Ne peut ni prescrire ni modifier le dossier médical. |
| **Médecin** | Crée, modifie et consulte les fiches médicales de ses patients, ajoute des images, prescrit et modifie les soins, gère le post-opératoire, rédige les comptes rendus. Ne peut pas supprimer un compte patient ni modifier ses informations personnelles. |
| **Infirmier** | Ajoute des observations, consulte prescriptions et traitements. Ne peut rien modifier ni supprimer d'autre. |
| **Lecture seule** | Consultation des informations générales uniquement. |

Les permissions sont appliquées côté serveur via les attributs `#[IsGranted]` de Symfony sur chaque contrôleur.

## 🛠 Stack technique

- **Backend :** PHP ≥ 8.4, [Symfony 8.1](https://symfony.com/)
- **ORM / migrations :** Doctrine ORM 3 + Doctrine Migrations
- **Base de données :** PostgreSQL (MySQL possible)
- **Frontend :** Twig, Bootstrap 5, Font Awesome, JavaScript (Stimulus, Turbo)
- **Build assets :** Webpack Encore
- **Authentification :** Symfony Security (authenticator personnalisé)
- **Conteneurisation :** Docker Compose (base de données)

## 🚀 Installation

### Prérequis

- PHP ≥ 8.4 avec les extensions `ctype`, `iconv`, `pdo_pgsql`
- [Composer](https://getcomposer.org/)
- Node.js et npm
- PostgreSQL 16 (ou via Docker)
- [Symfony CLI](https://symfony.com/download) (optionnel, recommandé)

### Étapes

```bash
# 1. Cloner le dépôt
git clone <url-du-depot>
cd Hopital_de_la_main

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
#    Créer un fichier .env.local et y définir la connexion à la base :
#    DATABASE_URL="postgresql://USER:PASSWORD@127.0.0.1:5432/hopital?serverVersion=16&charset=utf8"

# 4. (Optionnel) Lancer PostgreSQL avec Docker
docker compose up -d database

# 5. Créer la base et appliquer les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 6. (Optionnel) Charger les données de démonstration
php bin/console doctrine:fixtures:load

# 7. Compiler les assets
npm run dev        # ou: npm run watch / npm run build (production)

# 8. Lancer le serveur
symfony server:start
# ou: php -S localhost:8000 -t public
```

L'application est alors disponible sur `http://localhost:8000`.

## 🔑 Comptes de démonstration

Les fixtures (`src/DataFixtures/`) créent un administrateur, des médecins, des infirmiers, des secrétaires, des patients, des chambres et des salles opératoires.
Consultez les fichiers de fixtures pour les identifiants de test, et **changez-les impérativement avant toute mise en production**.

## 📁 Structure du projet

```
├── assets/             # JS (Stimulus) et CSS
├── config/             # Configuration Symfony (sécurité, services, Encore…)
├── migrations/         # Migrations Doctrine
├── public/             # Point d'entrée web, images, uploads
├── src/
│   ├── Controller/     # Chambre, Consultation, DossierPatient, Image, Observation,
│   │                   # Prescription, RDV, RdvOperatoire, SalleOperatoire, Specialite, User…
│   ├── DataFixtures/   # Jeux de données de démonstration
│   ├── Entity/         # Entités Doctrine
│   ├── Form/           # Formulaires Symfony
│   ├── Repository/     # Repositories Doctrine
│   └── Security/       # Authentification
├── templates/          # Vues Twig
└── tests/              # Tests
```

## 🔒 Sécurité

- Accès à l'application **uniquement après authentification**.
- **Aucune inscription libre** : les comptes sont créés par l'administrateur.
- Contrôle d'accès par rôles sur chaque route.
- Mots de passe hachés (Symfony `auto` hasher).
- Pas de téléchargement des dossiers ; images et documents téléchargeables par les médecins uniquement.
- Déploiement prévu pour un **accès restreint au réseau de l'hôpital**.

## 🎨 Charte graphique

Interface claire et calme, pensée pour le milieu médical :

| Couleur | Usage |
|---|---|
| ⚪ Blanc | Fond général |
| 🔵 Bleu | Actions neutres |
| 🟢 Vert | Validation / normalité |
| 🟠 Orange | Attirer l'attention |
| 🔴 Rouge | Danger / suppression |

Structure homogène sur toutes les pages : barre de navigation générale en haut et barre latérale fixe à gauche. Responsive (ordinateur et tablette).

## 🗺 Feuille de route (MVP)

**Étape 1**
- [x] Création d'un utilisateur avec numéro de dossier
- [x] Attribution à un médecin spécialiste
- [x] Rôles et accès différenciés (médecin, infirmier, secrétaire, administrateur)

**Étape 2**
- [x] Ajout d'images et de documents au dossier patient
- [x] Attribution de blocs opératoires, anesthésiste et équipe
- [x] Gestion des chambres
- [ ] Suivi post-opératoire (soins, alimentation, besoins personnalisés)
- [ ] Compte rendu postopératoire et document de sortie
- [ ] Amélioration visuelle globale

> Cochez/ajustez cette liste selon l'état réel d'avancement.

## 📆 Gestion de projet

- **Durée estimée :** 2,5 mois (Préparation, Conception, Développement, Test)
- **Livraison prévue :** début juillet 2026
- **Suivi :** tableau Trello

## 📄 Licence

Projet propriétaire, usage interne uniquement. Tous droits réservés.
