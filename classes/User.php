<?php

namespace classes;

require_once '../classes/Database.php';

use classes\Database;
use PDO;

class User
{
    private string $username;
    private string $email;
    private string $password;
    private PDO $database;


    public function __construct(string $username, string $email, string $password, PDO $database)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * Crée un nouvel utilisateur dans la base de données.
     *
     * @return bool Retourne vrai si l’utilisateur a été créé avec succès, faux sinon.
     */
    public function create(): bool
    {
        try {
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password, created_at) VALUES(:username, :email, :password, NOW())";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log("Erreur pendant la création de l'utilisateur: " . $e->getMessage(), 3, '../logs/errors.log');
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur existe dans la base de données.
     *
     * Cette méthode exécute une requête SQL pour vérifier si un utilisateur
     * avec le nom d’utilisateur ou l’email fourni existe déjà dans la base de données.
     *
     * @return bool Retourne `true` si un utilisateur avec le même nom d’utilisateur
     *              ou email existe, sinon `false’.
     */
    public static function exist(string $username, $email, $database): bool
    {
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }
}