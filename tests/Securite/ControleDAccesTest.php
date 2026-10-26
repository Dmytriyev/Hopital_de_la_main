<?php

namespace App\Tests\Securite;

use App\Tests\DatabaseWebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Routing\RouterInterface;

/**
 * Tests de SÉCURITÉ : on vérifie que chaque personne ne voit QUE ce qu'elle a le droit de voir.
 * Dans un logiciel d'hôpital, une fuite de dossier patient est l'erreur la plus grave.
 */
final class ControleDAccesTest extends DatabaseWebTestCase
{
    /** Routes volontairement publiques. Toute autre route doit exiger une connexion. */
    private const ROUTES_PUBLIQUES = ['app_login', 'app_logout'];

    public function testAucunePageNEstAccessibleSansConnexion(): void
    {
        /** @var RouterInterface $router */
        $router = static::getContainer()->get(RouterInterface::class);

        $verifiees = 0;
        foreach ($router->getRouteCollection() as $nom => $route) {
            if (str_starts_with($nom, '_') || in_array($nom, self::ROUTES_PUBLIQUES, true)) {
                continue; // outils de développement (_profiler, _wdt...) et pages publiques
            }

            // On remplace les paramètres {id}, {patientId}... par 1
            $url = preg_replace('/\{[^}]+\}/', '1', $route->getPath());
            $methode = in_array('GET', $route->getMethods(), true) || [] === $route->getMethods() ? 'GET' : 'POST';

            $this->client->request($methode, $url);

            self::assertResponseRedirects(
                'http://localhost/',
                null,
                sprintf('FAILLE : la route "%s" (%s %s) est accessible sans être connecté.', $nom, $methode, $url)
            );
            ++$verifiees;
        }

        self::assertGreaterThan(50, $verifiees, 'Le test aurait dû vérifier toutes les routes de l\'application.');
    }

    /**
     * Matrice des droits : [rôles de l'utilisateur, URL, code HTTP attendu].
     * 200 = autorisé, 403 = interdit.
     */
    public static function fournirMatriceDesDroits(): iterable
    {
        yield 'infirmier interdit de consultations' => [['ROLE_INFERMIER'], '/consultation', 403];
        yield 'secrétaire interdite de consultations' => [['ROLE_SECRETAIRE'], '/consultation', 403];
        yield 'médecin autorisé aux consultations' => [['ROLE_MEDECIN'], '/consultation', 200];
        yield 'infirmier autorisé aux observations' => [['ROLE_INFERMIER'], '/observation', 200];
        yield 'secrétaire interdite d\'observations' => [['ROLE_SECRETAIRE'], '/observation', 403];
        yield 'médecin interdit de créer un compte' => [['ROLE_MEDECIN'], '/register', 403];
        yield 'direction interdite de créer un compte' => [['ROLE_DIRECTION'], '/register', 403];
        yield 'admin autorisé à créer un compte' => [['ROLE_ADMIN'], '/register', 200];
        yield 'infirmier interdit des images' => [['ROLE_INFERMIER'], '/image', 403];
        yield 'infirmier interdit de la liste du personnel' => [['ROLE_INFERMIER'], '/user', 403];
        yield 'simple utilisateur interdit des dossiers patients' => [['ROLE_USER'], '/dossierPatient', 403];
    }

    #[DataProvider('fournirMatriceDesDroits')]
    public function testChaqueRoleNAccedeQuASesPages(array $roles, string $url, int $codeAttendu): void
    {
        $this->loginAs($roles);

        $this->client->request('GET', $url);

        self::assertResponseStatusCodeSame($codeAttendu);
    }

    public function testUnMauvaisMotDePasseEstRefuse(): void
    {
        $this->createUser(['ROLE_MEDECIN'], 'medecin@test.fr', 'BonMotDePasse!1');

        $crawler = $this->client->request('GET', '/');
        $form = $crawler->filter('form')->form([
            'email' => 'medecin@test.fr',
            'password' => 'MauvaisMotDePasse',
        ]);
        $this->client->submit($form);
        $this->client->followRedirect();

        // On reste sur la page de connexion, et on n'a pas accès à l'accueil
        $this->client->request('GET', '/home');
        self::assertResponseRedirects('http://localhost/');
    }

    public function testUnBonMotDePasseDonneAccesALAccueil(): void
    {
        $this->createUser(['ROLE_MEDECIN'], 'medecin@test.fr', 'BonMotDePasse!1');

        $crawler = $this->client->request('GET', '/');
        $form = $crawler->filter('form')->form([
            'email' => 'medecin@test.fr',
            'password' => 'BonMotDePasse!1',
        ]);
        $this->client->submit($form);

        self::assertResponseRedirects('/home');
    }

    public function testLaSuppressionSansJetonCsrfEstIgnoree(): void
    {
        $this->loginAs(['ROLE_ADMIN']);
        $victime = $this->createUser(['ROLE_MEDECIN'], 'victime@test.fr');
        $id = $victime->getId();

        // Requête forgée par un site malveillant : pas de jeton CSRF valide
        $this->client->request('POST', '/user/'.$id, ['_token' => 'faux-jeton']);

        $this->em->clear();
        self::assertNotNull(
            $this->em->getRepository(\App\Entity\User::class)->find($id),
            'FAILLE CSRF : l\'utilisateur a été supprimé sans jeton valide.'
        );
    }
}
