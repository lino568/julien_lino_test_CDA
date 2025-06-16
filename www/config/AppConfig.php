<?php

namespace Config;

class AppConfig
{
    private static array $config = [];

    /**
     * Initialise la configuration de l'application.
     * Appelez cette méthode juste après Dotenv::load() dans index.php
     */
    public static function initialize(): void
    {
        // Remplissez le tableau de configuration directement à partir de $_ENV ou getenv()
        // (en utilisant getenv comme fallback si $_ENV n'est pas fiable)
        self::$config = [
            'database' => [
                'host'     => $_ENV['DATABASE_HOST'] ?? getenv('DATABASE_HOST') ?? 'localhost',
                'name'     => $_ENV['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?? 'default_db',
                'username' => $_ENV['DATABASE_USERNAME'] ?? getenv('DATABASE_USERNAME') ?? 'root',
                'password' => $_ENV['DATABASE_PASSWORD'] ?? getenv('DATABASE_PASSWORD') ?? '',
                'port'     => $_ENV['DATABASE_PORT'] ?? getenv('DATABASE_PORT') ?? 3306, // Ajoutez le port depuis .env si vous l'avez
            ],
            // Ajoutez d'autres paramètres d'application depuis .env si nécessaire
            'app_timezone' => $_ENV['APP_TIMEZONE'] ?? getenv('APP_TIMEZONE') ?? 'Europe/Paris',
        ];
    }

    /**
     * Récupère une valeur de configuration.
     *
     * @param string $key La clé de configuration (par exemple, 'database.host')
     * @param mixed $default La valeur par défaut si la clé n'est pas trouvée
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $value = self::$config;

        foreach ($parts as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
            } else {
                return $default;
            }
        }
        return $value;
    }
}