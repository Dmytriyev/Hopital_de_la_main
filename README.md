README
Cahier des Charges - Application Hôpital de la Main

## 1. Présentation du Projet

* **Nom du projet :** Hôpital de la Main* 
**Type :** Site-Application interne pour un hôpital
* **Objectif :** Permettre aux médecins et infirmiers de gérer les patients, les opérations, leurs traitements, et leurs informations médicales de manière centralisée.
* **Utilisateur cible :** Médecins, Infirmiers, Secrétaires de l'hôpital.
* **Accès :** Interne uniquement, aucune connexion externe autorisée.

### 1.1 Demands client
**Les objectifs de l'application :** 
* L'application Hôpital de la Main doit être un outil numérique pour centraliser la gestion et le soin des patients ayant des problèmes avec leurs mains. Son objectif est d'avoir accès à l'information du patient et son emplacement actuel pour un médecin ou une infirmière, ainsi que de réduire la quantité de papier et d'éviter toute perte de documents. 
* Le corps du projet repose sur la simplicité d'usage et la mise en relation du patient avec l'ensemble de l'équipe de l'hôpital pour améliorer l'efficacité des soins et rendre les visites plus agréables.

**Les cibles du projet :** 
* Le projet de l'Hôpital de la Main s'adresse à plusieurs types d'utilisateurs. Tout d'abord, aux médecins spécialistes pour faciliter le suivi de leurs patients. Aux infirmières, qui peuvent voir la prescription du médecin et ajouter des notes tout au long des soins. En cas d'opération, c'est l'anesthésiste qui prescrit une anesthésie personnalisée. Enfin, à l'équipe administrative. Un rôle d'administrateur global doit compléter l'organisation pour surveiller la plateforme dans son ensemble.

**Description de l'application :**
* L'application est pour une utilisation interne uniquement. L'accès au site est possible uniquement après la connexion.
* La création de compte par utilisateur est impossible. Le compte est créé uniquement par un administrateur.
* Chaque profil doit avoir différents accès aux différentes informations. Certaines actions ne sont disponibles que pour certains profils.
* L'administrateur de la plate-forme dispose d'un espace de supervision pour gérer tous les utilisateurs, leurs rôles et les créations de comptes.
* À chaque nouveau patient créé par l'administration, un dossier avec son numéro personnel doit être attribué. Chaque membre de l'équipe a sa propre immatriculation.
* L'application centralise plusieurs actions : admission de patients et création de dossier, donner un rendez-vous, attribution d'un médecin de suivi, prescription de traitement et ajout d'images de scanner.
* La gestion des opérations : attribution d'anesthésiologie, une chambre pour patients et un bloc opératoire avec l'heure précise, le traitement donné pendant l'opération, la durée et par qui l'opération a été réalisée. L'administration et l'organisation de la sortie du patient.
* La partie administrative doit inclure : la possibilité d'ajouter un médecin et tous les membres de l'équipe, attribuer un patient à ce médecin, ajouter des informations par membre de l'équipe, suppression et modifications des informations uniquement par le médecin. Chaque membre de l'équipe doit avoir des autorisations différentes pour accéder aux différentes données du dossier patient.

**La charte graphique :**
* L'univers médical impose ses propres règles : les couleurs doivent être calmes et claires sans perturber la visibilité. Couleur blanche - général, couleur bleue - pour les actions neutres, couleur verte - pour valider les actions, couleur rouge - danger ou suppression. 
* Le style du site doit être intuitif et très compréhensible. Il doit permettre un accès direct aux actions les plus urgentes ou utiles. 
* L'application doit être rapide et fonctionnelle. Utilisable sur ordinateur comme sur une tablette.
* Le logo de l'application doit être simple et dans l'esprit médical.
* Chaque page de l'application doit respecter des structures homogènes avec deux barres de navigation avec des boutons fixes à gauche et une barre de navigation générale en haut.  

**MVP Minimum Viable Product :**
* Ce projet doit être conçu en plusieurs étapes. Première étape : possibilité de créer un utilisateur avec son numéro de dossier. L'attribuer à un médecin spécialiste. Avoir des rôles et des accès différents pour un médecin, une infirmière et l'administration.
* Deuxième étape : possibilité d'ajouter des images et des documents au dossier patient. Amélioration de l'aspect visuel global du site. En cas d'opération, attribution d'un bloc opératoire, avec la possibilité d'ajouter l'information supplémentaire comme: le traitement donné pendant l'opération, attribution d'anesthésiologue pour ce patient, possibilité noter toutes les membres d'équipe qui a participé à l'opération. Attribuer une chambre de repos, suivi des soins, et service postopératoire (comme la nourriture ou les besoins personnalisés).

**Le planning et périmètre livrable :**
* Le site d'hôpital doit être livré au début de juillet 2026.
* Le planning sur Trello doit aider à avoir une vision claire sur le déroulement du développement.

## 2. Objectifs Fonctionnels

* Gestion des Utilisateurs : Création, modification et suppression de comptes pour toutes membres d'equipe par administrateur acridité. 
* Gestion des Patients : Ajout, modification, suppression et visualisation des informations des patients.
* Gestion des Traitements : Attribution de traitements personnalisés aux patients, possibilité de joindre des images IRM, et des notes au cours de soins.
* Gestion des Chambres : gestion des chambres occupées par les patients, et leur suivi.
* Attribution d'un Médecin Principal : Association de chaque patient à un médecin référent et son anesthésiologue en cas besoin.
*  Gestion d'opération: attribuer aux patients le numéro du bloc opératoire lors d'opération avec l'heur début et la fin d'opération, le traitement prescrit pendant opération avec anesthésiologue ainsi que membres d'équipe qui a participé.

### 2.1. Gestion des Rôles et Permissions

* **Administrateur :**

  * Peut créer, modifier et supprimer des utilisateurs.
  * Peut gérer les rôles (permissions) des utilisateurs.
  * Il ne peut pas voir dossier de patient, il ne peut faire aucun action avec.

* **Secretaire:**

    * créer le compte patient, modifier l'information du client
    * attribuer ou modifier la chambre 
    * donner ou supprimer un rendez-vous
    * donner ou supprimer un rendez-vous operatoire
    * avoir l'accès pour  visualiser le compte rendu postopératoire et le document de sortie
    * il ne peut pas modifier ou supprimer le dossier médical ou prescrire le traitement.

* **Médecin :**

  * Peut créer, modifier et consulter les fiches médicales des patients à lui associés.
  * Peut ajouter des images IRM aux fiches médicales.
  * Peut consulter les informations des patients.
  * Peux prescrire, modifier le soin médical.
  * Attribuer ou modifier la poste operatoire.
  * Peux rediger un comte rendue.
  * Il ne peut pas supprimer le compte de client ou modifier l'information personnelle de client.

* **Infirmier :**

  * Peut ajouter des observations aux fiches médicales.
  * Peux lire la prescription médicale, le traitement.
  * Il ne peut pas modifier ou supprimer l'information de client


* **Utilisateur Lecture Seule :**

  * Peut uniquement consulter les informations générales.
  
### 2.2. Gestion des Patients

* Chaque patient a une fiche médicale comprenant les informations suivantes :

  * Nom, prénom, date de naissance, adresse, numéro de téléphone.
  * Nom, prénom, adresse, numéro de téléphone d'une personne de confiance.
  * Numéro de dossier.
  * Médecin principal.
  * Traitement personnalisé.
  * Chambre attribuée.
  * Bloc opératoire attribué.
  * Observations de l’infirmier.
  * Images IRM et doccument (téléchargeables par les médecins uniquement).

### 2.3. Gestion des Médecins

* Chaque médecin a une fiche de profil comprenant les informations suivantes :

  * Nom, prénom, date de naissance, téléphone. 
  * Profession, Spécialisation.
  * Patients attaché à lui.

### 2.4. Gestion des Infirmiers

* Chaque infirmier a une fiche de profil comprenant les informations suivantes :

  * Nom, prénom, date de naissance, téléphone
  * Spécialisation.

## 3. Sécurité

* Accès au application uniquement par authentification sécurisée.
* Aucune possibilité de créer un compte par User.
* Accès restreint uniquement au réseau de l’hôpital.
* Absence de possibilité de téléchargement de dossiers.
* Les données des patients doivent être protégées par un système de permissions.

## 4. Architecture du Système

* Frontend : JavaScript, HTML, CSS (avec Twig pour les templates).
* Backend : PHP avec Symfony.
* Base de Données : MySQL ou PostgreSQL.
* Hébergement : Serveur externe sécurisé.

## 5. Maquette et Interface

* Interface utilisateur intuitive pour une prise en main rapide, sans encombrement.
* Design adapté aux besoins médicaux: claire avec les couleurs intuitif (verte pour validation ou normalite,  orange pour attirer l'attention, rouge pour danger ou suppression, bleu pour les actions notre. style global couleur blanc) 
* Accès rapide par les boutons aux patients, traitements et chambres.
* Charte graphique incluse.

## 6. Gestion de Projet, Planning

* Développeur : Dmytriyev Savva
* Responsable du projet : Maria 
* Durée estimée : 2,5 mois
* Phases : Préparation, Conception, Développement, Test.
* Suivie de planning vie Trello disponible.
