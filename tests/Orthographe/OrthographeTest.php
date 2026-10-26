<?php

namespace App\Tests\Orthographe;

use PHPUnit\Framework\TestCase;

/**
 * Test d'ORTHOGRAPHE du texte visible par l'utilisateur.
 *
 * Difficulté : un gabarit Twig mélange texte affiché et code. On retire donc d'abord
 * le code (commentaires, balises {% %}, attributs HTML, noms techniques) pour ne
 * vérifier que les mots qu'un humain lira à l'écran.
 */
final class OrthographeTest extends TestCase
{
    /** @return array<string, string> */
    private static function fautes(): array
    {
        return require __DIR__.'/fautes_connues.php';
    }

    /** Garde uniquement le texte lisible d'un gabarit Twig. */
    private static function texteVisibleTwig(string $contenu): string
    {
        $contenu = preg_replace('/\{#.*?#\}/s', ' ', $contenu);          // commentaires Twig
        $contenu = preg_replace('/<(script|style)\b.*?<\/\1>/si', ' ', $contenu); // JS et CSS
        $contenu = preg_replace('/\s[\w:-]+="[^"]*"/', ' ', $contenu);    // attributs HTML (id, class, onclick...)
        $contenu = preg_replace('/\{%.*?%\}/s', ' ', $contenu);          // logique Twig
        $contenu = preg_replace("/'[^']*\\.twig'/", ' ', $contenu);       // chemins de gabarits inclus
        $contenu = preg_replace('/\{\{(.*?)\}\}/s', ' $1 ', $contenu);   // garde les textes entre {{ }}
        $contenu = preg_replace('/\b[a-z_]+\.[\w.]+/i', ' ', $contenu);  // variables : user.nom, app.user...
        $contenu = preg_replace('/<[^>]*>/', ' ', $contenu);             // balises HTML

        return $contenu;
    }

    /** Garde uniquement les libellés, aides et messages d'un formulaire PHP. */
    private static function texteVisibleFormulaire(string $contenu): string
    {
        preg_match_all("/(?:'(?:label|help|placeholder)'\s*=>\s*|message:\s*)'((?:[^'\\\\]|\\\\.)*)'/", $contenu, $libelles);
        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'\s*=>\s*'ROLE_/", $contenu, $choixDeRoles);

        return implode("\n", array_merge($libelles[1], $choixDeRoles[1]));
    }

    public function testLeTexteAfficheNeContientPasDeFautesConnues(): void
    {
        $racine = dirname(__DIR__, 2);
        $erreurs = [];

        $fichiers = array_merge(
            glob($racine.'/templates/*.twig'),
            glob($racine.'/templates/*/*.twig'),
            glob($racine.'/src/Form/*.php'),
        );

        foreach ($fichiers as $fichier) {
            $contenu = file_get_contents($fichier);
            $texte = str_ends_with($fichier, '.php')
                ? self::texteVisibleFormulaire($contenu)
                : self::texteVisibleTwig($contenu);

            foreach (self::fautes() as $faute => $correction) {
                // \b ne gère pas les accents : on définit nous-mêmes les "frontières de mot"
                if (preg_match('/(?<![\p{L}\d_])'.preg_quote($faute, '/').'(?![\p{L}\d_])/u', $texte)) {
                    $erreurs[] = sprintf('%s : "%s" devrait s\'écrire "%s"', str_replace($racine.'/', '', $fichier), $faute, $correction);
                }
            }
        }

        self::assertSame([], $erreurs, "Fautes d'orthographe trouvées :\n".implode("\n", $erreurs));
    }

    public function testLesFichiersSontEnUtf8Valide(): void
    {
        $racine = dirname(__DIR__, 2);
        $invalides = [];

        foreach (array_merge(glob($racine.'/templates/*/*.twig'), glob($racine.'/templates/*.twig'), glob($racine.'/src/*/*.php')) as $fichier) {
            if (!mb_check_encoding(file_get_contents($fichier), 'UTF-8')) {
                $invalides[] = str_replace($racine.'/', '', $fichier);
            }
        }

        self::assertSame([], $invalides, 'Fichiers mal encodés (les accents s\'afficheront mal) : '.implode(', ', $invalides));
    }
}
