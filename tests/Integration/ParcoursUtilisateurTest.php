<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Tests\DatabaseWebTestCase;

/**
 * Tests FONCTIONNELS (une forme d'intégration) : un faux navigateur
 * parcourt le site comme le ferait un vrai utilisateur.
 */
final class ParcoursUtilisateurTest extends DatabaseWebTestCase
{
    public function testLaPageDeConnexionSAffiche(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('input[name="email"]');
        self::assertSelectorExists('input[name="password"]');
    }

    public function testUnUtilisateurConnecteVoitLAccueil(): void
    {
        $this->loginAs(['ROLE_MEDECIN']);

        $this->client->request('GET', '/home');

        self::assertResponseIsSuccessful();
    }

    public function testUnMedecinVoitLaListeDesConsultations(): void
    {
        $this->loginAs(['ROLE_MEDECIN']);

        $this->client->request('GET', '/consultation');

        self::assertResponseIsSuccessful();
    }

    public function testUnAdminCreeUnUtilisateurViaLInscription(): void
    {
        $this->loginAs(['ROLE_ADMIN']);
        $crawler = $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="registration_form"]')->form();
        $form['registration_form[email]'] = 'nouveau.medecin@test.fr';
        $form['registration_form[nom]'] = 'Durand';
        $form['registration_form[prenom]'] = 'Paul';
        $form['registration_form[adresse]'] = '3 rue de Paris';
        $form['registration_form[codePostale]'] = '75002';
        $form['registration_form[telephone]'] = '0601020304';
        $form['registration_form[post]'] = 'Chirurgien';
        $form['registration_form[dateNaissance]'] = '1980-05-20';
        // Chaque rôle est une case à cocher distincte : on coche celle de valeur ROLE_MEDECIN
        foreach ($form->get('registration_form[roles]') as $caseACocher) {
            if (in_array('ROLE_MEDECIN', $caseACocher->availableOptionValues(), true)) {
                $caseACocher->tick();
            }
        }
        $form['registration_form[agreeTerms]']->tick();
        $this->client->submit($form);

        self::assertResponseRedirects('/home');

        $cree = $this->em->getRepository(User::class)->findOneBy(['email' => 'nouveau.medecin@test.fr']);
        self::assertNotNull($cree, 'L\'utilisateur aurait dû être enregistré.');
        self::assertSame(6, strlen((string) $cree->getMatricule()));

        // Le mot de passe généré doit permettre de se connecter
        $hasher = static::getContainer()->get('security.user_password_hasher');
        self::assertTrue($hasher->isPasswordValid($cree, '!20051980Dur'));
    }
}
