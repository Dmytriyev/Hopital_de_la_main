<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserCredentialGenerator;
use App\Tests\DatabaseWebTestCase;
use Doctrine\ORM\Tools\SchemaValidator;

/**
 * Test d'INTÉGRATION : on vérifie que plusieurs briques fonctionnent ENSEMBLE
 * (conteneur Symfony + Doctrine + base de données), pas une classe isolée.
 */
final class DoctrineEtServicesTest extends DatabaseWebTestCase
{
    public function testLeMappingDesEntitesEstValide(): void
    {
        $erreurs = (new SchemaValidator($this->em))->validateMapping();

        self::assertSame([], $erreurs, 'Erreurs de mapping Doctrine : '.print_r($erreurs, true));
    }

    public function testLeServiceDeGenerationEstDisponibleDansLeConteneur(): void
    {
        self::assertInstanceOf(
            UserCredentialGenerator::class,
            static::getContainer()->get(UserCredentialGenerator::class)
        );
    }

    public function testUnUtilisateurEstEnregistrePuisRetrouveEnBase(): void
    {
        $user = $this->createUser(['ROLE_MEDECIN'], 'dr.house@test.fr');
        $this->em->clear(); // on vide la mémoire de Doctrine pour forcer une vraie lecture SQL

        /** @var UserRepository $repository */
        $repository = static::getContainer()->get(UserRepository::class);
        $relu = $repository->findOneBy(['email' => 'dr.house@test.fr']);

        self::assertInstanceOf(User::class, $relu);
        self::assertSame($user->getId(), $relu->getId());
        self::assertContains('ROLE_MEDECIN', $relu->getRoles());
    }
}
