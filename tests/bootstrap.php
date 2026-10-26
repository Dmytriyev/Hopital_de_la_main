<?php

// Fichier exécuté par PHPUnit avant tous les tests (voir phpunit.dist.xml).
// Il charge l'autoload de Composer puis les variables d'environnement (.env, .env.test).

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG'] ?? false) {
    umask(0000);
}
