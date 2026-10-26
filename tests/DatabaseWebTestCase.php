<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Classe de base des tests qui ont besoin du site complet et d'une base de données.
 *
 * Avant chaque test : la base SQLite de test est vidée puis recréée à partir des entités.
 * Chaque test part donc d'un état propre et connu : c'est ce qui rend les tests reproductibles.
 */
abstract class DatabaseWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     * Crée et enregistre un utilisateur avec les rôles demandés.
     *
     * @param list<string> $roles
     */
    protected function createUser(array $roles, ?string $email = null, string $plainPassword = 'MotDePasse!123'): User
    {
        $user = new User();
        $user->setEmail($email ?? strtolower(implode('-', $roles)).'-'.uniqid().'@test.fr');
        $user->setRoles($roles);
        $user->setNom('Martin');
        $user->setPrenom('Alice');
        $user->setDateNaissance(new \DateTime('1985-03-15'));
        $user->setAdresse('1 rue du Test, 75001 Paris');
        $user->setCodePostale(75001);
        $user->setMatricule(123456);
        $user->setCreatedAt(new \DateTimeImmutable());

        $hasher = static::getContainer()->get('security.user_password_hasher');
        $user->setPassword($hasher->hashPassword($user, $plainPassword));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /** Crée un utilisateur avec ces rôles et le connecte dans le navigateur de test. */
    protected function loginAs(array $roles): User
    {
        $user = $this->createUser($roles);
        $this->client->loginUser($user);

        return $user;
    }
}
