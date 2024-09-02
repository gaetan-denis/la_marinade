<?php

namespace classes;


use MongoDB\BSON\Timestamp;
use PDO;
use PDOException;
use RuntimeException;


class Database
{
    private string $host;
    private string $name;
    private string $user;
    private string $pass;
    private string $charset;

    private PDO|null $connection = null;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->name = DB_NAME;
        $this->user = DB_USER;
        $this->pass = DB_PASSWORD;
        $this->charset = DB_CHARSET;
    }

    /**
     * Tente d’établir une connexion à la base de données via PDO.
     *
     * Cette méthode vérifie d’abord si une connexion est déjà établie. Si ce n’est pas le cas, elle
     * tente d’en établir une, en utilisant les paramètres de connexion définis dans la classe. En cas de
     * succès, elle retourne l’objet PDO représentant la connexion. Si une erreur survient lors de la
     * tentative de connexion, elle lance une exception `PDOException` avec un message d’erreur détaillé.
     *
     * @return PDO La connexion PDO à la base de données si elle est réussie.
     *
     * @throws PDOException Si une erreur survient lors de la tentative de connexion.
     */
    public function connectToDatabase(): PDO
    {
        if ($this->connection === null) {
            try {
                $dsn = "mysql:host=$this->host;dbname=$this->name;charset=$this->charset";
                $this->connection = new PDO($dsn, $this->user, $this->pass);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->connection;
            } catch (PDOException $e) {
                $date = date('Y-m-d H:i:s');
                $destination = '../logs/errors.log';
                error_log($date . " - Erreur de connexion à la base de données: " . $e->getMessage() . "\n", 3, $destination);
                throw $e;
            }
        }
        return $this->connection;
    }
}


