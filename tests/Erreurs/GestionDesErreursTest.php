<?php

namespace App\Tests\Erreurs;

use App\Tests\DatabaseWebTestCase;

/**
 * Tests d'ERREURS : le site doit répondre proprement quand quelque chose ne va pas
 * (page inexistante, dossier supprimé...) au lieu de planter avec une erreur 500.
 */
final class GestionDesErreursTest extends DatabaseWebTestCase
{
    public function testUnePageInexistanteRenvoie404(): void
    {
        $this->loginAs(['ROLE_MEDECIN']);

        $this->client->request('GET', '/cette-page-n-existe-pas');

        self::assertResponseStatusCodeSame(404);
    }

    public function testUnDossierPatientInexistantRenvoie404(): void
    {
        $this->loginAs(['ROLE_MEDECIN']);

        $this->client->request('GET', '/dossierPatient/999999');

        self::assertResponseStatusCodeSame(404);
    }

    public function testUneMauvaiseMethodeHttpRenvoie405(): void
    {
        $this->loginAs(['ROLE_MEDECIN']);

        // La suppression n'accepte que POST : un DELETE doit être refusé proprement
        $this->client->request('DELETE', '/consultation/1');

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * Test de NON-RÉGRESSION du bug corrigé : /user/new créait un utilisateur sans
     * mot de passe, ce qui déclenchait une erreur SQL (500). Ce test empêche le bug de revenir.
     */
    public function testLaCreationDUtilisateurNePlantePlus(): void
    {
        $this->loginAs(['ROLE_ADMIN']);

        $this->client->request('GET', '/user/new');

        self::assertResponseRedirects('/register');
    }

    /** Pages principales de chaque rôle : aucune ne doit produire d'erreur serveur. */
    public function testLesPagesPrincipalesNeProduisentPasDErreur500(): void
    {
        $this->loginAs(['ROLE_MEDECIN', 'ROLE_SECRETAIRE', 'ROLE_INFERMIER', 'ROLE_ADMIN', 'ROLE_DIRECTION']);

        foreach (['/home', '/consultation', '/consultation/new', '/dossierPatient', '/dossierPatient/new',
                  '/observation', '/observation/new', '/prescription', '/rdv', '/rdv/new', '/rdvOperatoire',
                  '/chambre', '/salleOperatoire', '/specialite', '/image', '/image/new', '/user', '/register'] as $url) {
            $this->client->request('GET', $url);
            $code = $this->client->getResponse()->getStatusCode();

            self::assertLessThan(500, $code, sprintf('Erreur serveur %d sur la page %s', $code, $url));
        }
    }
}
