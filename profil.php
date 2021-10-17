<?php 
require_once('include/init.php');
// sécurité :
// si on n'est pas connecté, on est redirigé sur la page erreur.php

if(!membreConnecte())
{
    header("Location:" . URL . "erreur.php");
}
require_once('include/header.php');
?>

<h1 class="text-center m-4">Bonjour <?= $_SESSION['membre']['prenom'] ?></h1>



<?php 
require_once('include/footer.php');