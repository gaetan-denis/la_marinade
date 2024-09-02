<?php
require_once '../config/config.php';
require_once '../utils/validation.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

use classes\Database;
use classes\User;
$errorsMessages=[];
$connection = new Database();
$connection = $connection->connectToDatabase();

//Vérifie que les champs sont bien complétés et renvoi une erreur si ce n’est pas le cas

try {
    $array = checkFormCompletion($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);

    $username = $array['username'];
    $email = $array['email'];
    $password = $array['password'];
    $confirm_password = $array['confirm_password'];

} catch (Exception $e) {
    $errorsMessages[]= 'Erreur : ' . $e->getMessage();
}


// Vérifie que le nom d’utilisateur ou l’email ne soit pas déjà existant en base de donnée.

try {
    usernameOrEmailExist($username,$email,$connection);
}catch (Exception $e){
    $errorsMessages[]= 'Erreur : ' . $e->getMessage();
}
// Vérifie que le nom d’utilisateur soit compris entre 0 et 50 caractères et renvoi une erreur si ce n’est pas le cas

try {
    validateUsername($username);
} catch (Exception $e) {
    $errorsMessages[]= 'Erreur : ' . $e->getMessage();
}

// Vérifie si le format du mail est correct et renvoi une erreur si ce n'est pas le cas.

try {
    validateEmail($email);
} catch (Exception $e) {
    $errorsMessages[]= 'Erreur : ' . $e->getMessage();
    exit;
}

// Vérifie que les deux mots de passes correspondent et que ceux-ci contiennent les caractères demandés

try {
    validatePassword($password, $confirm_password);
} catch (Exception $e) {
    $errorsMessages[]= 'Erreur : ' . $e->getMessage();
    exit;
}

if(!empty($errorsMessages)){
    foreach($errorsMessages as $errorMessage){
        echo $errorMessage.'<br>';
    }
    exit;
}


try {
    $user = new User($username, $email, $password, $connection);
    $user->create();
    header('Location: ../public/index.php');
    echo 'Vous êtes inscrit avec succès';
    exit;
} catch (Exception $e) {
    echo 'Une erreur est survenue' . $e->getMessage();
    exit;
}
