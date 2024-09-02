<?php

require_once '../classes/User.php';
use classes\User;

/**
 * Vérifie si tous les champs requis du formulaire sont remplis.
 *
 * Cette fonction vérifie que les champs requis (nom d’utilisateur, adresse email,
 * mot de passe et confirmation du mot de passe) ne sont pas vides. Si un ou plusieurs
 * champs sont vides, une exception est lancée avec un message spécifique.
 * Si tous les champs sont remplis, les valeurs nettoyées (espaces en début et fin supprimés)
 * sont retournées dans un tableau associatif.
 *
 * @param string $username Nom d’utilisateur à vérifier.
 * @param string $email Adresse email à vérifier.
 * @param string $password Mot de passe à vérifier.
 * @param string $confirm_password Confirmation du mot de passe à vérifier.
 * @return array Un tableau associatif contenant les valeurs nettoyées des champs du formulaire :
 *               - 'username' : Nom d’utilisateur nettoyé.
 *               - 'email' : Adresse email nettoyée.
 *               - 'password' : Mot de passe nettoyé.
 *               - 'confirm_password' : Confirmation du mot de passe nettoyée.
 * @throws Exception Si l’un des champs requis est vide, une exception est lancée avec
 *                   un message d’erreur correspondant :
 *                   - "Veuillez indiquer un nom d’utilisateur" si le nom d’utilisateur est vide.
 *                   - "Veuillez indiquer une adresse email" si l’adresse email est vide.
 *                   - "Veuillez indiquer un mot de passe" si le mot de passe est vide.
 *                   - "Veuillez confirmer votre mot de passe" si la confirmation du mot de passe est vide.
 */
function checkFormCompletion(string $username, string $email, string $password, string $confirm_password): array
{
    if (empty($username)) {
        throw new Exception("Veuillez indiquer un nom d'utilisateur");
    } elseif (empty($email)) {
        throw new Exception("Veuillez indiquer une adresse email");
    } elseif (empty($password)) {
        throw new Exception("Veuillez indiquer un mot de passe");
    } elseif (empty($confirm_password)) {
        throw new Exception("Veuillez confirmez votre mot de passe");
    } else {
        return [
            'username' => trim($username),
            'email' => trim($email),
            'password' => trim($password),
            'confirm_password' => trim($confirm_password)
        ];
    }
}

/**
 * Cette fonction vérifie si le nom d’utilisateur et/ou l’email existent déjà en base de données.
 *
 * @param string $username Le nom d’utilisateur à vérifier.
 * @param string $email L’email a vérifié.
 * @param mysqli $connection La connexion à la base de données.
 * @return void
 * @throws Exception Si le nom d’utilisateur ou l’email existent déjà.
 */

function usernameOrEmailExist(string $username, string $email, PDO $connection) : void
{
    if(user::exist($username, $email,$connection)){
        throw new Exception('ce nom d\'utilisateur ou cet email sont déjà utilisés');
    }
}
/**
 * Valide un nom d’utilisateur selon des critères de longueur.
 *
 * Cette fonction vérifie que le nom d’utilisateur respecte les critères de longueur minimale et maximale.
 * Elle sécurise également le nom d’utilisateur contre les attaques XSS en utilisant `htmlspecialchars’.
 *
 * @param string $username Le nom d’utilisateur à valider.
 * @return string Le nom d’utilisateur sécurisé s’il respecte les critères de longueur.
 * @throws Exception Si le nom d’utilisateur ne respecte pas les critères de longueur.
 */

function validateUsername(string $username): string

{

    if (strlen($username) < MINCHAR_USERNAME) {
        throw new Exception('votre nom d\'utilisateur doit être d\'une longueur minimum de ' . MINCHAR_USERNAME . ' caractères');
    } elseif (strlen($username) > MAXCHAR_USERNAME) {
        throw new Exception('votre nom d\'utilisateur doit être d\'une longueur maximum de ' . MAXCHAR_USERNAME . ' caractères');
    } else {
        return htmlspecialchars($username, ENT_QUOTES);
    }
}

/**
 * Valide une adresse email et sécurise la chaîne de caractères.
 *
 * Cette fonction vérifie que l’adresse email est dans un format valide selon les règles de la fonction
 * `filter_var` avec le filtre 'FILTER_VALIDATE_EMAIL'. Elle sécurise également l’adresse email contre
 * les attaques de type XSS en utilisant 'htmlspecialchars'.
 *
 * @param string $email L’adresse email à valider.
 * @return string L’adresse email sécurisée si elle est valide.
 * @throws Exception Si l’adresse email n’est pas dans un format valide.
 */

function validateEmail(string $email): string
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return htmlspecialchars($email, ENT_QUOTES);
    } else {
        throw new Exception('Le mail doit être dans un format valide');

    }
}

/**
 * Valide un mot de passe selon plusieurs critères de sécurité.
 *
 * Cette fonction vérifie que le mot de passe et la confirmation du mot de passe sont identiques,
 * et qu'ils respectent les critères de longueur et de complexité suivants :
 * - Au moins 8 caractères
 * - Au maximum 255 caractères
 * - Contient au moins une lettre minuscule
 * - Contient au moins une lettre majuscule
 * - Contient au moins un chiffre
 * - Contient au moins un caractère spécial parmi " !@#$%^&*".
 *
 * @param string $password Le mot de passe à valider.
 * @param string $confirm_password La confirmation du mot de passe.
 * @return string Le mot de passe validé s’il respecte tous les critères.
 * @throws Exception Si l’un des critères de validation n’est pas respecté.
 */
function validatePassword(string $password, string $confirm_password): string
{
    if ($password != $confirm_password) {
        throw new Exception('Les mots de passe ne correspondent pas');
    } elseif (strlen($password) < 8) {
        throw new Exception('La longueur du mot de passe doit être d\'au moins 8 caractères');
    } elseif (strlen($password) > 255) {
        throw new Exception('La longueur du mot de passe ne peut pas dépasser les 255 caractères');

    } elseif (!preg_match('/[a-z]/', $password)) {
        throw new Exception('le mot de passe doit au moins contenir une lettre minuscule');

    } elseif (!preg_match('/[A-Z]/', $password)) {
        throw new Exception('le mot de passe doit au moins contenir une lettre majuscule');

    } elseif (!preg_match('/[0-9]/', $password)) {
        throw new Exception('le mot de passe doit au moins contenir un chiffre;');
    } elseif (!preg_match('/[!@#$%^&*]/', $password)) {
        throw new Exception('le mot de passe doit contenir au moins l\'un des caractères spéciaux suivant : "!@#$%^&*"');
    } else {
        return $password;
    }
}