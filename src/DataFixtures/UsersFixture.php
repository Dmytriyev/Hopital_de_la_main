<?php

namespace App\DataFixtures;

use App\Entity\Specialite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixture extends Fixture
{
    // Références réutilisables par les autres fixtures (ex: DossierFixture)
    public const REF_MEDECIN_LEFEBVRE = 'medecin_lefebvre';
    public const REF_MEDECIN_ROUSSEAU = 'medecin_rousseau';
    public const REF_MEDECIN_MOREAU = 'medecin_moreau';
    public const REF_MEDECIN_BERNARD = 'medecin_bernard';
    public const REF_MEDECIN_PETIT = 'medecin_petit';
    public const REF_MEDECIN_DUBOIS = 'medecin_dubois';
    public const REF_MEDECIN_PEDIATRE = 'medecin_roche';

    public const REF_SECRETAIRE_LECLERC = 'secretaire_leclerc';
    public const REF_SECRETAIRE_MORIN = 'secretaire_morin';
    public const REF_SECRETAIRE_MERCIER = 'secretaire_mercier';

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $specialiteData = [
            'Chirurgie'       => '15/06/1995',
            'Anesthésiologie' => '20/09/1998',
            'Neurologie'      => '10/03/2003',
            'Pédiatrie'       => '05/11/2008',
        ];
        $specialites = [];
        foreach ($specialiteData as $nom => $dateStr) {
            $s = new Specialite();
            $s->setSpecialisation($nom);
            $s->setDateDelivrance(\DateTime::createFromFormat('d/m/Y', $dateStr));
            $manager->persist($s);
            $specialites[$nom] = $s;
        }
        $manager->flush();

        // Format : [nom, prenom, email, sexe, dateNaissance (DD/MM/YYYY), telephone, adresse, codePostale, post, roles[], specialites[]]
        $usersData = [
            [
                'nom'           => 'Lefebvre',
                'prenom'        => 'Martin',
                'email'         => 'martin.lefebvre@hopital-main.fr',  // mot de passe : !15031975Lef
                'sexe'          => 'Homme',
                'dateNaissance' => '15/03/1975',
                'telephone'     => '0611223344',
                'adresse'       => '8 rue de Rivoli, 75001 Paris',
                'codePostale'   => 75001,
                'post'          => 'Chirurgien',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Chirurgie'],
                'reference'     => self::REF_MEDECIN_LEFEBVRE,
            ],
            [
                'nom'           => 'Rousseau',
                'prenom'        => 'Sophie',
                'email'         => 'sophie.rousseau@hopital-main.fr',   // mot de passe : !22071980Rou
                'sexe'          => 'Femme',
                'dateNaissance' => '22/07/1980',
                'telephone'     => '0622334455',
                'adresse'       => '34 boulevard Haussmann, 75009 Paris',
                'codePostale'   => 75009,
                'post'          => 'Chirurgienne',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Chirurgie'],
                'reference'     => self::REF_MEDECIN_ROUSSEAU,
            ],
            [
                'nom'           => 'Moreau',
                'prenom'        => 'Pierre',
                'email'         => 'pierre.moreau@hopital-main.fr',     // mot de passe : !05111972Mor
                'sexe'          => 'Homme',
                'dateNaissance' => '05/11/1972',
                'telephone'     => '0633445566',
                'adresse'       => '17 avenue de l\'Opéra, 75001 Paris',
                'codePostale'   => 75001,
                'post'          => 'Chirurgien',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Chirurgie'],
                'reference'     => self::REF_MEDECIN_MOREAU,
            ],
            [
                'nom'           => 'Bernard',
                'prenom'        => 'Claire',
                'email'         => 'claire.bernard@hopital-main.fr',    // mot de passe : !30011983Ber
                'sexe'          => 'Femme',
                'dateNaissance' => '30/01/1983',
                'telephone'     => '0644556677',
                'adresse'       => '52 rue du Faubourg Saint-Antoine, 75012 Paris',
                'codePostale'   => 75012,
                'post'          => 'Anesthésiologiste',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Anesthésiologie'],
                'reference'     => self::REF_MEDECIN_BERNARD,
            ],
            [
                'nom'           => 'Petit',
                'prenom'        => 'Antoine',
                'email'         => 'antoine.petit@hopital-main.fr',     // mot de passe : !18091978Pet
                'sexe'          => 'Homme',
                'dateNaissance' => '18/09/1978',
                'telephone'     => '0655667788',
                'adresse'       => '3 place de la République, 75010 Paris',
                'codePostale'   => 75010,
                'post'          => 'Anesthésiologiste',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Anesthésiologie'],
                'reference'     => self::REF_MEDECIN_PETIT,
            ],
            [
                'nom'           => 'Dubois',
                'prenom'        => 'Marie',
                'email'         => 'marie.dubois@hopital-main.fr',      // mot de passe : !12041981Dub
                'sexe'          => 'Femme',
                'dateNaissance' => '12/04/1981',
                'telephone'     => '0666778899',
                'adresse'       => '29 rue de la Roquette, 75011 Paris',
                'codePostale'   => 75011,
                'post'          => 'Neurologue',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Neurologie'],
                'reference'     => self::REF_MEDECIN_DUBOIS,
            ],
            [
                'nom'           => 'Roche',
                'prenom'        => 'Julien',
                'email'         => 'julien.roche@hopital-main.fr',      // mot de passe : !14071984Roc
                'sexe'          => 'Homme',
                'dateNaissance' => '14/07/1984',
                'telephone'     => '0611998877',
                'adresse'       => '2 rue Saint-Dominique, 75007 Paris',
                'codePostale'   => 75007,
                'post'          => 'Pédiatre',
                'roles'         => ['ROLE_USER', 'ROLE_MEDECIN'],
                'specialites'   => ['Pédiatrie'],
                'reference'     => self::REF_MEDECIN_PEDIATRE,
            ],

            [
                'nom'           => 'Garnier',
                'prenom'        => 'Céline',
                'email'         => 'celine.garnier@hopital-main.fr',    // mot de passe : !08061990Gar
                'sexe'          => 'Femme',
                'dateNaissance' => '08/06/1990',
                'telephone'     => '0677889900',
                'adresse'       => '14 rue de Belleville, 75020 Paris',
                'codePostale'   => 75020,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Simon',
                'prenom'        => 'Laura',
                'email'         => 'laura.simon@hopital-main.fr',       // mot de passe : !25021993Sim
                'sexe'          => 'Femme',
                'dateNaissance' => '25/02/1993',
                'telephone'     => '0688990011',
                'adresse'       => '6 rue d\'Alésia, 75014 Paris',
                'codePostale'   => 75014,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Laurent',
                'prenom'        => 'Nathalie',
                'email'         => 'nathalie.laurent@hopital-main.fr',  // mot de passe : !11081988Lau
                'sexe'          => 'Femme',
                'dateNaissance' => '11/08/1988',
                'telephone'     => '0699001122',
                'adresse'       => '41 avenue de Clichy, 75017 Paris',
                'codePostale'   => 75017,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Fontaine',
                'prenom'        => 'Emma',
                'email'         => 'emma.fontaine@hopital-main.fr',     // mot de passe : !03121995Fon
                'sexe'          => 'Femme',
                'dateNaissance' => '03/12/1995',
                'telephone'     => '0600112233',
                'adresse'       => '78 rue Lecourbe, 75015 Paris',
                'codePostale'   => 75015,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Girard',
                'prenom'        => 'Isabelle',
                'email'         => 'isabelle.girard@hopital-main.fr',   // mot de passe : !19051987Gir
                'sexe'          => 'Femme',
                'dateNaissance' => '19/05/1987',
                'telephone'     => '0611223355',
                'adresse'       => '22 rue de la Pompe, 75016 Paris',
                'codePostale'   => 75016,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Thomas',
                'prenom'        => 'Caroline',
                'email'         => 'caroline.thomas@hopital-main.fr',   // mot de passe : !07101991Tho
                'sexe'          => 'Femme',
                'dateNaissance' => '07/10/1991',
                'telephone'     => '0622334466',
                'adresse'       => '55 boulevard de Strasbourg, 75010 Paris',
                'codePostale'   => 75010,
                'post'          => 'Infirmière',
                'roles'         => ['ROLE_USER', 'ROLE_INFERMIER'],
                'specialites'   => [],
            ],

            [
                'nom'           => 'Leclerc',
                'prenom'        => 'Valérie',
                'email'         => 'valerie.leclerc@hopital-main.fr',   // mot de passe : !14041985Lec
                'sexe'          => 'Femme',
                'dateNaissance' => '14/04/1985',
                'telephone'     => '0633445577',
                'adresse'       => '9 rue du Louvre, 75001 Paris',
                'codePostale'   => 75001,
                'post'          => 'Secrétaire médicale',
                'roles'         => ['ROLE_USER', 'ROLE_SECRETAIRE'],
                'specialites'   => [],
                'reference'     => self::REF_SECRETAIRE_LECLERC,
            ],
            [
                'nom'           => 'Morin',
                'prenom'        => 'Sandrine',
                'email'         => 'sandrine.morin@hopital-main.fr',    // mot de passe : !28111989Mor
                'sexe'          => 'Femme',
                'dateNaissance' => '28/11/1989',
                'telephone'     => '0644556688',
                'adresse'       => '37 rue de Turenne, 75003 Paris',
                'codePostale'   => 75003,
                'post'          => 'Secrétaire médicale',
                'roles'         => ['ROLE_USER', 'ROLE_SECRETAIRE'],
                'specialites'   => [],
                'reference'     => self::REF_SECRETAIRE_MORIN,
            ],
            [
                'nom'           => 'Mercier',
                'prenom'        => 'Pauline',
                'email'         => 'pauline.mercier@hopital-main.fr',   // mot de passe : !02071992Mer
                'sexe'          => 'Femme',
                'dateNaissance' => '02/07/1992',
                'telephone'     => '0655667799',
                'adresse'       => '18 rue de Bretagne, 75003 Paris',
                'codePostale'   => 75003,
                'post'          => 'Secrétaire médicale',
                'roles'         => ['ROLE_USER', 'ROLE_SECRETAIRE'],
                'specialites'   => [],
                'reference'     => self::REF_SECRETAIRE_MERCIER,
            ],

            [
                'nom'           => 'Durand',
                'prenom'        => 'François',
                'email'         => 'francois.durand@hopital-main.fr',   // mot de passe : !10021968Dur
                'sexe'          => 'Homme',
                'dateNaissance' => '10/02/1968',
                'telephone'     => '0666778800',
                'adresse'       => '5 avenue Montaigne, 75008 Paris',
                'codePostale'   => 75008,
                'post'          => 'Directeur Général',
                'roles'         => ['ROLE_USER', 'ROLE_DIRECTION'],
                'specialites'   => [],
            ],
            [
                'nom'           => 'Perrin',
                'prenom'        => 'Hélène',
                'email'         => 'helene.perrin@hopital-main.fr',     // mot de passe : !23081971Per
                'sexe'          => 'Femme',
                'dateNaissance' => '23/08/1971',
                'telephone'     => '0677889911',
                'adresse'       => '11 avenue George V, 75008 Paris',
                'codePostale'   => 75008,
                'post'          => 'Directrice Adjointe',
                'roles'         => ['ROLE_USER', 'ROLE_DIRECTION'],
                'specialites'   => [],
            ],
        ];

        foreach ($usersData as $data) {
            $this->createUser($data, $specialites, $manager);
        }
    }

    private function createUser(array $data, array $specialites, ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setSexe($data['sexe']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setDateNaissance(\DateTime::createFromFormat('d/m/Y', $data['dateNaissance']));
        $user->setTelephone($data['telephone']);
        $user->setAdresse($data['adresse']);
        $user->setCodePostale($data['codePostale']);
        $user->setPost($data['post']);
        $user->setCreatedAt(new \DateTimeImmutable('now'));

        foreach ($data['specialites'] as $nomSpecialite) {
            if (isset($specialites[$nomSpecialite])) {
                $user->addMesSpecialite($specialites[$nomSpecialite]);
            }
        }

        // Mot de passe temporaire aléatoire pour obtenir l'ID après le premier flush
        $user->setPassword($this->userPasswordHasher->hashPassword($user, bin2hex(random_bytes(16))));
        $manager->persist($user);
        $manager->flush();

        // Mot de passe : ! + dateNaissance(ddmmYYYY) + Nom(1 maj + 2 min)  ex: !15031975Lef
        $dateNaissance = $user->getDateNaissance()->format('dmY');
        $nom           = mb_strtoupper(mb_substr($user->getNom(), 0, 1))
                       . mb_strtolower(mb_substr($user->getNom(), 1, 2));
        $plainPassword = '!' . $dateNaissance . $nom;
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));


        // Matricule : ID en tête + padding aléatoire pour atteindre 6 chiffres
        $idStr     = (string) $user->getId();
        $remaining = max(0, 6 - strlen($idStr));
        $random    = $remaining > 0
            ? str_pad((string) random_int(0, (int) str_repeat('9', $remaining)), $remaining, '0', STR_PAD_LEFT)
            : '';
        $user->setMatricule((int) ($idStr . $random));
        $manager->flush();

        if (isset($data['reference'])) {
            $this->addReference($data['reference'], $user);
        }
    }
}
