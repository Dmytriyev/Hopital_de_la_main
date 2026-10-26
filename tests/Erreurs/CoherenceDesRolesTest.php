<?php

namespace App\Tests\Erreurs;

use PHPUnit\Framework\TestCase;

/**
 * Une faute de frappe dans un nom de rôle ne provoque AUCUNE erreur visible :
 * is_granted('ROLE_INFIRMIER') renvoie simplement "faux", et le bouton disparaît.
 * C'est exactement le bug qui venait d'être corrigé dans templates/observation/index.html.twig.
 * Ce test liste tous les rôles écrits dans le code et refuse ceux qui sont inconnus.
 */
final class CoherenceDesRolesTest extends TestCase
{
    /**
     * Rôles officiels. Note : "INFERMIER" est mal orthographié (on écrit "infirmier"),
     * mais il est enregistré tel quel en base de données : le renommer demande une migration.
     */
    private const ROLES_CONNUS = [
        'ROLE_USER', 'ROLE_ADMIN', 'ROLE_MEDECIN', 'ROLE_SECRETAIRE',
        'ROLE_INFERMIER', 'ROLE_DIRECTION', 'ROLE_ALLOWED_TO_SWITCH',
    ];

    public function testTousLesRolesUtilisesExistent(): void
    {
        $racine = dirname(__DIR__, 2);
        $inconnus = [];

        $fichiers = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($racine.'/src'));
        $fichiers = array_merge(
            iterator_to_array($fichiers),
            iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($racine.'/templates'))),
            [new \SplFileInfo($racine.'/config/packages/security.yaml')],
        );

        foreach ($fichiers as $fichier) {
            if (!$fichier->isFile() || !preg_match('/\.(php|twig|yaml)$/', $fichier->getFilename())) {
                continue;
            }
            foreach (file($fichier->getPathname()) as $numero => $ligne) {
                preg_match_all('/\bROLE_[A-Z_]+\b/', $ligne, $trouves);
                foreach ($trouves[0] as $role) {
                    if (!in_array($role, self::ROLES_CONNUS, true)) {
                        $inconnus[] = sprintf('%s:%d  %s', str_replace($racine.'/', '', $fichier->getPathname()), $numero + 1, $role);
                    }
                }
            }
        }

        self::assertSame([], $inconnus, "Rôles inconnus (faute de frappe ?) :\n".implode("\n", $inconnus));
    }
}
