<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAdmin extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('savva@gmail.com');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '!01011990Dmy1'));
        $user->setSexe('Homme');
        $user->setNom('Dmytriyev');
        $user->setPrenom('Savva');
        $user->setDateNaissance(new \DateTime('01/01/1990'));
        $user->setTelephone('0612345678');
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setAdresse('12 rue de la Paix, 75001 Paris');
        $user->setCodePostale(75001);
        $user->setPost('Administrateur');

        $manager->persist($user);
        $manager->flush();

        $idStr     = (string) $user->getId();
        $remaining = max(0, 6 - strlen($idStr));
        $random    = $remaining > 0
            ? str_pad((string) random_int(0, (int) str_repeat('9', $remaining)), $remaining, '0', STR_PAD_LEFT)
            : '';
        $user->setMatricule((int) ($idStr . $random));
        $manager->flush();
    }
}
