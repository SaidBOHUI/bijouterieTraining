<?php
// on a besoin du fichier init.php
// mais pas du header / footer car il n'y a pas de visuel

require_once('include/init.php');

// cette page sert uniquement à detruire la session
// donc déconnexion
session_destroy();


// on redirige l'utilisateur après la destruction de la session sur une autre page
header("Location:" . URL . "connexion.php"); exit;

// fonction prédéfinie header() qui permet la redirection
// syntaxe entre les parenthèses :  "Location : fichier.php "
// on termine par le exit; qui permet de sortir du fichier sans lire la suite du code s'il y en a 





