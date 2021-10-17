<?php
// init.php est le premier fichier à inclure dans les pages, il contient toutes les bases du projet

// --------- CONNEXION BDD

$pdoObject = new PDO('mysql:host=localhost; dbname=bijouteriedoranco', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8') );

//pdoObject est un objet de la class PDO, il hérite de tout ce que contient la class PDO
// [0] => __construct
// [1] => prepare // prepare (4 requêtes) 3 étapes preparer associer exécuter
// [2] => beginTransaction
// [3] => commit
// [4] => rollBack
// [5] => inTransaction
// [6] => setAttribute
// [7] => exec // requête SQL (insert into update delete)
// [8] => query // requête SQL (select) $pdoObject->query("SELECT * FROM membre");
// [9] => lastInsertId
// [10] => errorCode
// [11] => errorInfo
// [12] => getAttribute
// [13] => quote
// [14] => getAvailableDrivers

// --------- CHEMINS

// Création de CONSTANTES pour simplifier les appels dans le code

define("URL", "http://localhost/bijouterieDoranco/");
// URL permet en local d'avoir l'arborescence du dossier 

define("RACINE_IMAGES", $_SERVER['DOCUMENT_ROOT'] . "/bijouterieDoranco/images/imagesUpload/");
// RACINE_IMAGES permet d'atteindre directement le dossier des images chargées dans le projet


// --------- VARIABLES
// Création de variables, vides initialement
$notification = '';
$erreur = '';



// --------- FAILLES XSS
// Sécurité des formulaires 

foreach($_POST as $key => $value)
{
    $_POST[$key] = strip_tags(trim($value));
}
// strip_tags() ==> Supprime les balises HTML et PHP d'une chaîne
// trim() ==> Supprime les espaces (ou d'autres caractères) en début et fin de chaîne


// --------- OUVERTURE SESSION

session_start();
// session_start() ne veut pas dire qu'on est connecté, 
// pour rappel : sur amazon on peut mettre dans le panier sans être connecté
// dans la session on peut avoir un tableau panier, un tableau membre (pour la connexion)

// --------- INCLUSIONS
// Pour une lecture claire, on place dans le fichier fonctions.php toutes les fonctions que l'on inclut dans init.php
require_once('fonctions.php');


//echo '<pre>'; print_r($_SESSION); echo '</pre>';
