<?php 
require_once('include/init.php');
// tout le code PHP propre à la page est à mettre entre le init.php et header.php

// si je suis connecté, je n'ai pas l'autorisation d'être sur cette page
// je suis redirigé par exemple sur la page erreur.php
if(membreConnecte())
{
    header("Location:" . URL . "erreur.php");
}

//echo '<pre>'; print_r(get_class_methods($pdoObject)); echo '</pre>';

if($_POST) // si j'ai cliqué sur le bouton donc que j'ai POSTé
{
   
    $pdoStatement = $pdoObject->prepare("SELECT * FROM membre WHERE email = :email");
    $pdoStatement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $pdoStatement->execute();

    $membreArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);

    //1e condition pour l'inscription : vérifier l'email
    if(!empty($membreArray))
    // si le tableau $membreArray n'est pas vide donc l'email existe dans la table membre
    {
        //2e condition : vérification du mot
        // on va comparer le mdp du formulaire avec celui hashé dans la table membre qui correspond à l'email checké
        
        if(password_verify($_POST['mdp'], $membreArray['mdp'])) // si c'est true 
        // la fonction prédéfinie password_verify() permet de comparer 2 valeurs dont l'une des deux est hashée
        // elle retourne un boolean : true / false
        {
            // pour se connecter il faut rajouter un tableau 'membre' dans lequel on va boucler les informations de l'utilisateur dans la table membre

            foreach($membreArray as $key => $value)
            {
                $_SESSION['membre'][$key] = $value;
            }

            // la redirection en fonction du statut
            // client (statut 1 ) redirection sur la page profil.php
            // admin (statut 2) redirection sur la page admin/admin.php
            
            // si le statut = 1 (client)
            if($_SESSION['membre']['statut'] == 1)
            {
                header('Location:' . URL . 'profil.php' );
            }
            else // sinon le statut = 2 (admin)
            {
                header('Location:' . URL . 'admin/admin.php' );
            }
            
        }
        else // else de la 2e condition : le mot de passe est incorrect
        {
            $erreur .= "<div class='col-md-6 offset-md-3 alert alert-danger text-center'> 
                            le mot de passe est incorrect.
                        </div>";
        }

    }
    else // else de la 1e condition : le tableau $membreArray est vide donc l'email n'existe pas dans la table membre
    {
        $erreur .= "<div class='col-md-6 offset-md-3 alert alert-danger text-center'> 
                        Email " . $_POST['email'] . " inexistant.
                    </div>";
    }


} // FIN DU POST





require_once('include/header.php');
?>

    <h1 class="text-center m-3">Connexion</h1>

    <?= $erreur ?>
    <?= $notification ?>


    <form method="post" class='col-md-6 mx-auto'>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Saisir votre email">
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Saisir votre mot de passe">
        </div>


        <button type="submit" class="col-md-12 btn btn-dark">Connexion</button>

    </form>



<?php 
require_once('include/footer.php');