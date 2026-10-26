<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Service\UserCredentialGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UserCredentialGeneratorTest extends TestCase
{
    private UserCredentialGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new UserCredentialGenerator();
    }

    /**
     * Un "DataProvider" fournit plusieurs jeux de données au même test :
     * on écrit le test une fois, PHPUnit l'exécute pour chaque ligne.
     */
    public static function fournirNomsEtMotsDePasse(): iterable
    {
        yield 'nom simple' => ['Martin', '1985-03-15', '!15031985Mar'];
        yield 'nom en majuscules' => ['DUBOIS', '1990-12-01', '!01121990Dub'];
        yield 'nom accentué' => ['élodie', '2000-01-09', '!09012000Élo'];
        yield 'nom court' => ['Li', '1975-07-04', '!04071975Li'];
    }

    #[DataProvider('fournirNomsEtMotsDePasse')]
    public function testLeMotDePasseSuitLeFormatAttendu(string $nom, string $date, string $attendu): void
    {
        $user = (new User())->setNom($nom)->setDateNaissance(new \DateTime($date));

        self::assertSame($attendu, $this->generator->generatePlainPassword($user));
    }

    public function testSansDateDeNaissanceUneExceptionEstLevee(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->generator->generatePlainPassword((new User())->setNom('Martin'));
    }

    public function testLeMatriculeFaitSixChiffresEtCommenceParLId(): void
    {
        // Le matricule contient une partie aléatoire : on répète le test pour être sûr.
        for ($i = 0; $i < 50; ++$i) {
            $matricule = (string) $this->generator->generateMatricule(42);

            self::assertSame(6, strlen($matricule));
            self::assertStringStartsWith('42', $matricule);
        }
    }

    public function testUnIdTresGrandEstConserveTelQuel(): void
    {
        self::assertSame(1234567, $this->generator->generateMatricule(1234567));
    }

    public function testUnIdInvalideEstRefuse(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->generator->generateMatricule(0);
    }
}
