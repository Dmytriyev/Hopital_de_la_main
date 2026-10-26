<?php

namespace App\Tests\Securite;

use App\Entity\Image;
use App\Form\ImageType;
use App\Tests\DatabaseWebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Un formulaire d'envoi de fichier est une porte d'entrée classique pour les attaques.
 * On vérifie que seuls des formats sûrs sont acceptés.
 */
final class EnvoiDeFichiersTest extends DatabaseWebTestCase
{
    private function soumettreFichier(string $nomFichier, string $contenu): bool
    {
        $chemin = sys_get_temp_dir().'/'.uniqid().'-'.$nomFichier;
        file_put_contents($chemin, $contenu);

        $form = static::getContainer()->get(FormFactoryInterface::class)
            ->create(ImageType::class, new Image(), ['csrf_protection' => false]);

        $form->submit([
            'fileName' => 'Radio main gauche',
            'imageFile' => new UploadedFile($chemin, $nomFichier, null, null, true),
        ]);

        @unlink($chemin);

        return $form->isValid();
    }

    public static function fournirFichiersDangereux(): iterable
    {
        yield 'SVG avec JavaScript (XSS)' => ['radio.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(document.cookie)</script></svg>'];
        yield 'script PHP' => ['shell.php', '<?php system($_GET["c"]); ?>'];
        yield 'page HTML' => ['page.html', '<html><script>alert(1)</script></html>'];
        yield 'PHP déguisé en image' => ['photo.jpg', '<?php echo "pirate"; ?>'];
    }

    #[DataProvider('fournirFichiersDangereux')]
    public function testLesFichiersDangereuxSontRefuses(string $nomFichier, string $contenu): void
    {
        self::assertFalse(
            $this->soumettreFichier($nomFichier, $contenu),
            sprintf('FAILLE : le fichier "%s" a été accepté.', $nomFichier)
        );
    }

    public function testUneVraieImagePngEstAcceptee(): void
    {
        // Plus petite image PNG valide (1 pixel)
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        self::assertTrue($this->soumettreFichier('radio.png', $png));
    }
}
