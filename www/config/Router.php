<?php

namespace Config;

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

// Assurez-vous d'avoir une classe Log fonctionnelle pour le débogage et l'erreur handling
use Config\Log;

class Router
{
    private Dispatcher $dispatcher;
    // Supprime la propriété $pathToController car nous ne voulons plus de préfixe automatique
    // private string $pathToController = 'App\\Controllers\\';

    public function __construct()
    {
        $this->dispatcher = simpleDispatcher(require __DIR__ . '/../routes/web.php');
    }

    public function dispatch(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Calcul du chemin de base du script pour les sous-dossiers
        // Ceci est crucial si votre projet est dans un sous-répertoire (ex: /mon_projet/public)
        // et que vous voulez que vos routes soient définies comme /connexion et non /mon_projet/connexion.
        // $_SERVER['SCRIPT_NAME'] contient le chemin complet du script exécuté (ex: /mon_projet/public/index.php)
        $scriptName = $_SERVER['SCRIPT_NAME'];
        // dirname() donne le répertoire parent (ex: /mon_projet/public)
        $basePath = rtrim(dirname($scriptName), '/'); // retire le slash final si DocumentRoot est la racine

        // Nettoyage de l'URI : retire les paramètres de requête et décode l'URL
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        // Retire le basePath de l'URI si elle commence par ce chemin
        // Cela transforme "/mon_projet/public/connexion" en "/connexion" si $basePath est "/mon_projet/public"
        if (!empty($basePath) && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        // Assure que l'URI commence toujours par un slash, et supprime les slashes de fin
        // ainsi que les doubles slashes (ex: //route -> /route)
        $uri = '/' . trim($uri, '/');
        $uri = preg_replace('#/+#', '/', $uri); // Gère les multiples slashes

        // --- Fin du nettoyage et de l'ajustement d'URI ---

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                Log::getLogger()->warning('404 Not Found pour URI: ' . $uri . ' Méthode: ' . $httpMethod);
                // Vous pouvez rendre une vue d'erreur ou un JSON
                echo json_encode(['success' => false, 'message' => '404 - Ressource non trouvée.']);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                Log::getLogger()->warning('405 Method Not Allowed pour URI: ' . $uri . ' Méthode tentée: ' . $httpMethod . ' Méthodes autorisées: ' . implode(', ', $allowedMethods));
                // Vous pouvez rendre une vue d'erreur ou un JSON
                echo json_encode(['success' => false, 'message' => '405 - Méthode non autorisée pour cette ressource.']);
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $controllerClass = $handler[0];
                $method = $handler[1];
                $filter = $handler[2] ?? null;

                // Applique le filtre si présent et est un callable
                // Assurez-vous que votre filtre est un callable valide (ex: un tableau [Classe, 'méthode'] ou une closure)
                if ($filter && is_callable($filter)) {
                    // Les filtres peuvent potentiellement arrêter l'exécution ou rediriger
                    // Considérez si le filtre doit retourner une valeur pour continuer ou non.
                    call_user_func($filter);
                }

                // Validation robuste de l'existence de la classe
                if (!class_exists($controllerClass)) {
                    http_response_code(500);
                    Log::getLogger()->error('Erreur interne: Controller ' . $controllerClass . ' introuvable pour la route: ' . $uri);
                    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur: Contrôleur introuvable.']);
                    break; // Utiliser break ici au lieu de return pour terminer le switch et potentiellement d'autres traitements
                }

                $controller = new $controllerClass();

                // Validation robuste de l'existence de la méthode
                if (!method_exists($controller, $method)) {
                    http_response_code(500);
                    Log::getLogger()->error('Erreur interne: Méthode ' . $method . ' introuvable dans le contrôleur ' . $controllerClass . ' pour la route: ' . $uri);
                    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur: Méthode de contrôleur introuvable.']);
                    break; // Utiliser break ici au lieu de return
                }

                // Appel de la méthode du contrôleur avec les variables
                // try-catch pour attraper les erreurs dans le contrôleur
                try {
                    call_user_func_array([$controller, $method], $vars);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    Log::getLogger()->error('Erreur lors de l\'exécution du contrôleur ' . $controllerClass . '::' . $method . ': ' . $e->getMessage(), ['exception' => $e]);
                    echo json_encode(['success' => false, 'message' => 'Une erreur inattendue est survenue.']);
                }
                break;
        }
    }
}