<?php

namespace App\Tests\Unit\Security;

use App\Security\AppCustomAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Ici on utilise des "doublures" (stubs) : de faux objets qui remplacent
 * le vrai générateur d'URL. Ainsi on teste l'authentificateur SEUL.
 */
final class AppCustomAuthenticatorTest extends TestCase
{
    private function creerAuthenticator(): AppCustomAuthenticator
    {
        $urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturnCallback(
            static fn (string $route): string => match ($route) {
                'app_home' => '/home',
                'app_login' => '/',
                default => '/'.$route,
            }
        );

        return new AppCustomAuthenticator($urlGenerator);
    }

    private function creerRequeteAvecSession(): Request
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        return $request;
    }

    public function testApresConnexionOnEstRedirigeVersLAccueil(): void
    {
        $response = $this->creerAuthenticator()->onAuthenticationSuccess(
            $this->creerRequeteAvecSession(),
            $this->createStub(TokenInterface::class),
            'main'
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/home', $response->getTargetUrl());
    }

    public function testApresConnexionOnRetourneSurLaPageDemandeeAvant(): void
    {
        $request = $this->creerRequeteAvecSession();
        // Symfony mémorise la page demandée avant la redirection vers le login
        $request->getSession()->set('_security.main.target_path', '/consultation');

        $response = $this->creerAuthenticator()->onAuthenticationSuccess(
            $request,
            $this->createStub(TokenInterface::class),
            'main'
        );

        self::assertSame('/consultation', $response->getTargetUrl());
    }
}
