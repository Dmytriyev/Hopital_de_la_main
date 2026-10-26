<?php

namespace App\Service;

use App\Entity\User;

/**
 * Centralise la génération des identifiants d'un utilisateur.
 *
 * Avant, cette logique était copiée dans RegistrationController et dans
 * UsersFixture. La regrouper ici permet de la tester une seule fois
 * (voir tests/Unit/Service/UserCredentialGeneratorTest.php).
 */
class UserCredentialGenerator
{
    public const MATRICULE_LENGTH = 6;

    /**
     * Mot de passe initial : ! + date de naissance (ddmmYYYY) + 3 premières lettres du nom.
     * Exemple : né le 15/03/1985, nom "Martin" => "!15031985Mar".
     */
    public function generatePlainPassword(User $user): string
    {
        if (null === $user->getDateNaissance() || null === $user->getNom() || '' === $user->getNom()) {
            throw new \InvalidArgumentException('La date de naissance et le nom sont obligatoires pour générer le mot de passe.');
        }

        $nom = $user->getNom();

        return '!'
            . $user->getDateNaissance()->format('dmY')
            . mb_strtoupper(mb_substr($nom, 0, 1))
            . mb_strtolower(mb_substr($nom, 1, 2));
    }

    /**
     * Matricule : l'ID en tête, complété par des chiffres aléatoires jusqu'à 6 chiffres.
     * Exemple : id=7 => "7" + "00342" => 700342.
     */
    public function generateMatricule(int $userId): int
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('L\'identifiant doit être un entier positif.');
        }

        $idStr = (string) $userId;
        $remaining = max(0, self::MATRICULE_LENGTH - strlen($idStr));
        $random = $remaining > 0
            ? str_pad((string) random_int(0, (int) str_repeat('9', $remaining)), $remaining, '0', STR_PAD_LEFT)
            : '';

        return (int) ($idStr . $random);
    }
}
