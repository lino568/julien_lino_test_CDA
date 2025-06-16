<?php

namespace Config;

use PDO;
use PDOException;
use Config\Log;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;
    private string $host;
    private string $dbName;
    private string $username;
    private string $password;

    private function __construct()
    {
        $this->host = $_ENV['DATABASE_HOST'];
        $this->dbName = $_ENV['DATABASE_NAME'];
        $this->username = $_ENV['DATABASE_USERNAME'];
        $this->password = $_ENV['DATABASE_PASSWORD'];

        try {
            $dsn = "mysql:host={$this->host};port=3306;dbname={$this->dbName}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            Log::getLogger()->critical("Erreur fatale lors de la connexion à la base de données : " . $e->getMessage());
            return null;
        }
    }

    public static function getInstance(): ?PDO
    {
        if (self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}