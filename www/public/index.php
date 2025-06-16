<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Log;
use Config\Router;
use Dotenv\Dotenv;
use Config\AppConfig;

$dotenvPath = dirname(__DIR__);

try {
    $dotenv = Dotenv::createImmutable($dotenvPath);
    $dotenv->load();
    
    AppConfig::initialize();
} catch (\Throwable $e) {
    
    Log::getLogger()->critical('Erreur fatale lors du chargement de l\'environnement : ' . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    http_response_code(500);
    die("Une erreur interne du serveur est survenue. Veuillez réessayer plus tard.");
}

date_default_timezone_set(AppConfig::get('app_timezone'));

$router = new Router();
$router->dispatch();

