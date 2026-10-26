<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Test UNITAIRE : on teste une seule classe, seule, sans base de données ni Symfony.
 * C'est rapide (quelques millisecondes) et ça localise précisément un bug.
 */
final class UserTest extends TestCase
{
    public function testUnUtilisateurAToujoursLeRoleUser(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_MEDECIN']);

        self::assertContains('ROLE_USER', $user->getRoles());
        self::assertContains('ROLE_MEDECIN', $user->getRoles());
    }

    public function testLesRolesNeSontPasDupliques(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_MEDECIN']);

        self::assertCount(2, $user->getRoles());
    }

    public function testLIdentifiantDeConnexionEstLEmail(): void
    {
        $user = (new User())->setEmail('alice@hopital-main.fr');

        self::assertSame('alice@hopital-main.fr', $user->getUserIdentifier());
    }

    public function testLeHashDuMotDePasseNEstPasStockeEnSession(): void
    {
        $user = (new User())->setPassword('$2y$13$hash-tres-secret');

        $donneesSerialisees = serialize($user);

        self::assertStringNotContainsString('hash-tres-secret', $donneesSerialisees);
    }

    public function testLesCollectionsSontInitialiseesVides(): void
    {
        $user = new User();

        self::assertCount(0, $user->getMesPatients());
        self::assertCount(0, $user->getConsultations());
        self::assertCount(0, $user->getMesSpecialites());
    }
}
