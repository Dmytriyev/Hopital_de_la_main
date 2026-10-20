<?php

namespace App\DataFixtures;
use App\Entity\DossierPatient;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * DependentFixtureInterface::getDependencies() indique à Doctrine Fixtures
 * Bundle que UsersFixture doit être chargée AVANT pour la références vers les médecins/secrétaires
 */
class DossierFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UsersFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $dossiersData = [
            [
                'sexe'               => 'Homme',
                'nom'                => 'Lambert',
                'prenom'             => 'Lucas',
                'dateNaissance'      => '12/05/1978',
                'adresse'            => "10 rue de Vaugirard, 75006 Paris",
                'codePostale'        => 75006,
                'telephone'          => '0612340001',
                'secuDepartement'    => '75',
                'secuCommune'        => '101',
                'secuOrdre'          => 12,
                'nomConfiance'       => 'Lambert',
                'prenomConfiance'    => 'Anne',
                'telephoneConfiance' => '0612340002',
                'details'            => 'Antecedents, hypertension arterielle. Suivi regulier tous les 3 mois.',
                'medecin'            => UsersFixture::REF_MEDECIN_LEFEBVRE,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_LECLERC,
                'createdAt'          => '10/01/2026 09:15:00',
                'updatedAt'          => '15/02/2026 11:00:00',
            ],
            [
                'sexe'               => 'Femme',
                'nom'                => 'Faure',
                'prenom'             => 'Chloé',
                'dateNaissance'      => '03/09/1985',
                'adresse'            => "48 rue de Charonne, 75011 Paris",
                'codePostale'        => 75011,
                'telephone'          => '0612340003',
                'secuDepartement'    => '75',
                'secuCommune'        => '111',
                'secuOrdre'          => 27,
                'nomConfiance'       => 'Faure',
                'prenomConfiance'    => 'Michel',
                'telephoneConfiance' => '0612340004',
                'details'            => 'Allergie a la penicilline. Aucun traitement en cours.',
                'medecin'            => UsersFixture::REF_MEDECIN_ROUSSEAU,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_MORIN,
                'createdAt'          => '22/01/2026 10:30:00',
                'updatedAt'          => '02/03/2026 14:45:00',
            ],
            [
                'sexe'               => 'Homme',
                'nom'                => 'Blanchard',
                'prenom'             => 'Georges',
                'dateNaissance'      => '28/02/1958',
                'adresse'            => "5 avenue Parmentier, 75011 Paris",
                'codePostale'        => 75011,
                'telephone'          => '0612340005',
                'secuDepartement'    => '75',
                'secuCommune'        => '111',
                'secuOrdre'          => 45,
                'nomConfiance'       => 'Blanchard',
                'prenomConfiance'    => 'Josiane',
                'telephoneConfiance' => '0612340006',
                'details'            => 'Porteur d un pacemaker depuis 2019. Controle cardiologique annuel.',
                'medecin'            => UsersFixture::REF_MEDECIN_MOREAU,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_MERCIER,
                'createdAt'          => '05/02/2026 08:00:00',
                'updatedAt'          => '20/03/2026 09:30:00',
            ],
            [
                'sexe'               => 'Femme',
                'nom'                => 'Robin',
                'prenom'             => 'Nadia',
                'dateNaissance'      => '17/11/1990',
                'adresse'            => "63 boulevard Voltaire, 75011 Paris",
                'codePostale'        => 75011,
                'telephone'          => '0612340007',
                'secuDepartement'    => '75',
                'secuCommune'        => '111',
                'secuOrdre'          => 8,
                'nomConfiance'       => 'Robin',
                'prenomConfiance'    => 'Karim',
                'telephoneConfiance' => '0612340008',
                'details'            => 'Intervention chirurgicale programmee. Anesthesie generale prevue.',
                'medecin'            => UsersFixture::REF_MEDECIN_BERNARD,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_LECLERC,
                'createdAt'          => '11/03/2026 13:20:00',
                'updatedAt'          => '01/04/2026 16:10:00',
            ],
            [
                'sexe'               => 'Homme',
                'nom'                => 'Chevalier',
                'prenom'             => 'Bernard',
                'dateNaissance'      => '09/07/1949',
                'adresse'            => "21 rue Oberkampf, 75011 Paris",
                'codePostale'        => 75011,
                'telephone'          => '0612340009',
                'secuDepartement'    => '75',
                'secuCommune'        => '111',
                'secuOrdre'          => 63,
                'nomConfiance'       => 'Chevalier',
                'prenomConfiance'    => 'Suzanne',
                'telephoneConfiance' => '0612340010',
                'details'            => 'Suivi pre-operatoire. Bilan sanguin complet demande avant l intervention.',
                'medecin'            => UsersFixture::REF_MEDECIN_PETIT,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_MORIN,
                'createdAt'          => '28/04/2026 07:45:00',
                'updatedAt'          => '10/05/2026 12:00:00',
            ],
            [
                // Dossier d'un enfant, suivi par le médecin pédiatre (Dr Roche).
                // La personne de confiance est logiquement l'un de ses parents.
                'sexe'               => 'Femme',
                'nom'                => 'Lambert',
                'prenom'             => 'Léa',
                'dateNaissance'      => '04/04/2019',
                'adresse'            => "10 rue de Vaugirard, 75006 Paris",
                'codePostale'        => 75006,
                'telephone'          => '0612340001',
                'secuDepartement'    => '75',
                'secuCommune'        => '106',
                'secuOrdre'          => 4,
                'nomConfiance'       => 'Lambert',
                'prenomConfiance'    => 'Lucas',
                'telephoneConfiance' => '0612340001',
                'details'            => 'Suivi pediatrique de routine. Vaccinations a jour.',
                'medecin'            => UsersFixture::REF_MEDECIN_PEDIATRE,
                'secretaire'         => UsersFixture::REF_SECRETAIRE_MERCIER,
                'createdAt'          => '18/05/2026 15:00:00',
                'updatedAt'          => '30/06/2026 09:00:00',
            ],
        ];

        /** @var DossierPatient[] $dossiers */
        $dossiers = [];
        foreach ($dossiersData as $data) {
            $dossiers[] = $this->creerDossier($data, $manager);
        }
        $manager->flush();
        // DossierPatientController::new() : année (4 chiffres) + id.
        // Ex: année 2026 + id 7 => numéro de dossier 20260007... ici 20267
        foreach ($dossiers as $dossier) {
            $numeroDossier = (int) ((new \DateTimeImmutable('now'))->format('Y') . $dossier->getId());
            $dossier->setNumeroDossier($numeroDossier);
        }

        $manager->flush();
    }

    private function creerDossier(array $data, ObjectManager $manager): DossierPatient
    {
        $dateNaissance = \DateTime::createFromFormat('d/m/Y', $data['dateNaissance']);

        $dossier = new DossierPatient();
        $dossier->setSexe($data['sexe']);
        $dossier->setNom($data['nom']);
        $dossier->setPrenom($data['prenom']);
        $dossier->setDateNaissance($dateNaissance);
        $dossier->setAdresse($data['adresse']);
        $dossier->setCodePostale($data['codePostale']);
        $dossier->setTelephone($data['telephone']);
        $dossier->setNumeroSecu($this->genererNumeroSecu(
            $data['sexe'],
            $dateNaissance,
            $data['secuDepartement'],
            $data['secuCommune'],
            $data['secuOrdre'],
        ));
        $dossier->setNomConfiance($data['nomConfiance']);
        $dossier->setPrenomConfiance($data['prenomConfiance']);
        $dossier->setTelephoneConfiance($data['telephoneConfiance']);
        $dossier->setDetails($data['details']);
        // Valeur temporaire non nulle (colonne NOT NULL) : recalculée après
        $dossier->setNumeroDossier(0);
        $dossier->setCreatedAt(\DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $data['createdAt']));
        $dossier->setUpdatedAt(\DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $data['updatedAt']));

        /** @var User $medecin */
        $medecin = $this->getReference($data['medecin'], User::class);
        $dossier->setNomMedecinTraitant($medecin);
        // Synchronise aussi la liste "médecins associés au dossier"
        $dossier->addMesMedecin($medecin);

        /** @var User $secretaire */
        $secretaire = $this->getReference($data['secretaire'], User::class);
        $dossier->setSecretaire($secretaire);
        $dossier->setNouveauPatient($secretaire);

        $manager->persist($dossier);

        return $dossier;
    }

    /**
     * Calcule un numéro de sécurité sociale
     * Structure : [1 sexe][2 année][2 mois][2 département][3 commune][3 ordre][2 clé]
     * Clé de contrôle = 97.
     */
    private function genererNumeroSecu(
        string $sexe,
        \DateTime $dateNaissance,
        string $departement,
        string $commune,
        int $ordre,
    ): string {
        $sexeChiffre = $sexe === 'Femme' ? '2' : '1';
        $annee = $dateNaissance->format('y');
        $mois = $dateNaissance->format('m');
        $ordreFormate = str_pad((string) $ordre, 3, '0', STR_PAD_LEFT);

        $treizeChiffres = $sexeChiffre . $annee . $mois . $departement . $commune . $ordreFormate;
        $cle = 97 - ((int) $treizeChiffres % 97);

        return $treizeChiffres . str_pad((string) $cle, 2, '0', STR_PAD_LEFT);
    }
}
