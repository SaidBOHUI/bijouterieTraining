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
// methodes() = fonctions()
if($_POST) // si j'ai cliqué sur le bouton donc que j'ai POSTé
{
    //echo '<pre>'; print_r($_POST); echo '</pre>';die;

    // die permet d'arrêter la lecture du code 

    // 1e étape : on vérifie si l'email existe ou non dans la table membre
    // pour rappel : un email pour un compte


    // Requête de préparation
    $pdoStatement = $pdoObject->prepare("SELECT * FROM membre WHERE email = :email");
    //1e étape : on prepare la requête 
    
    $pdoStatement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    //2e étape : on associe le marqueur à une valeur
    // marqueur => ":" (les 2 points)
    // 3 arguments :
    // 1e : le marqueur (vient de l'étape 1 : préparation)
    // 2e : sa valeur
    // 3e : le type (string / integer etc...)

    $pdoStatement->execute();
    // 3e étape : on éxecute la requête

    // une requête retourne un nouvel object de la class PDOStatement
    // il faut donc pointer sur une méthode

    // echo '<pre>'; print_r(get_class_methods($pdoStatement)); echo '</pre>';
    // [0] => execute
    // [1] => fetch
    // [2] => bindParam
    // [3] => bindColumn
    // [4] => bindValue
    // [5] => rowCount
    // [6] => fetchColumn
    // [7] => fetchAll
    // [8] => fetchObject
    // [9] => errorCode
    // [10] => errorInfo
    // [11] => setAttribute
    // [12] => getAttribute
    // [13] => columnCount
    // [14] => getColumnMeta
    // [15] => setFetchMode
    // [16] => nextRowset
    // [17] => closeCursor
    // [18] => debugDumpParams
    
    $membreArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    // fetch affiche la requête d'une seule ligne
    // fetchAll : plusieurs lignes
    

    // if(!empty($membreArray))
    // {
    //     echo "pas vide <br>";
    //     echo '<pre>'; print_r($membreArray); echo '</pre>';
    // }
    // else
    // {
    //     echo "vide";
    // }

    //1e condition pour l'inscription : vérifier l'email
    if(empty($membreArray))
    // si le tableau $membreArray est vide donc l'email n'existe pas dans la table membre
    {
        //2e condition : vérification des mdp
        if($_POST['mdp'] == $_POST['confirm_mdp'] ) // les mots sont identiques même vides
        {
            if( !empty($_POST['mdp']) ) // 2e condition bis : les mots de passe identiques mais non vides
            {
                // INSERTION DANS LA TABLE MEMBRE

                // Hashage du mot de passe

                $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                //echo $_POST['mdp'];
                // Pour une question de sécurité, comme le développeur peut avoir accès à la BDD, on va le hasher
                // la fonction password_hash() permet de créer un clé de hashage :
                // 2 arguments dans la fonction
                // 1e argument : le mot de passe que l'on souhaite hasher
                // 2e argument : algorythme de hashage, il y en a plusieurs, ici on utilise PASSWORD_DEFAULT

                // Attention : le hashage transmet en BDD une LONGUE chaîne de caractères 
                // donc soyez vigilant sur la taille du VARCHAR de mdp : mettre au max 255 

                // 1e étape : préparation de la requête d'insertion
                $pdoStatement = $pdoObject->prepare("INSERT INTO membre (email, mdp, nom, prenom, statut) VALUES (:email, :mdp, :nom, :prenom, :statut)");

                //2e étape : association des marqueurs aux valeurs du formulaire

                // on boucle toutes les données de mon formulaire d'inscription
                foreach($_POST as $key => $value)
                {
                    // confirm_mdp n'existe pas dans la table membre donc on doit l'éjecter
                    if($key != 'confirm_mdp') // Si l'indice (l'attribut 'name' de l'input) est différent de confirm_mdp
                    {
                        // on boucle tout le POST sauf confirm_mdp
                        // les variables peuvent avoir des type de valeurs différents 

                        if(gettype($value) == "string")
                        {
                            $type = PDO::PARAM_STR;
                        }
                        else
                        {
                            $type = PDO::PARAM_INT;
                        }

                        $pdoStatement->bindValue(":$key", $value, $type);

                        
            
                    } // fermeture du if confirm_mdp
                } // fermeture du foreach

                // le statut permet de définir si le compte est client ou admin
                // statut = 1 : client
                // statut = 2 : admin

                // mais pour toute inscription accessible à tous, je définie dans le code la valeur de statut
                // la client n'a pas le choix du statut

                $pdoStatement->bindValue(":statut", 1, PDO::PARAM_INT);

                // $pdoStatement->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
                // $pdoStatement->bindValue(":mdp", $_POST['mdp'], PDO::PARAM_STR);
                // $pdoStatement->bindValue(":nom", $_POST['nom'], PDO::PARAM_STR);
                // $pdoStatement->bindValue(":prenom", $_POST['prenom'], PDO::PARAM_STR);
                // $pdoStatement->bindValue(":statut", 1, PDO::PARAM_INT);


                //3e étape :

                $pdoStatement->execute();

                $notification .= "<div class='col-md-6 offset-md-3 alert alert-success text-center'> 
                                     Compte enregistré
                                </div>";
            }
            else // else de la 2e condition bis : les mots de passe identiques sont vides
            {
                $erreur .= "<div class='col-md-6 offset-md-3 alert alert-danger text-center'> 
                                Veuillez saisir un mot de passe
                            </div>";
            }

        }
        else // else de la 2e condition : les mots de passe ne sont pas identiques
        {
            $erreur .= "<div class='col-md-6 offset-md-3 alert alert-danger text-center'> 
                            les mots de passe ne sont pas identiques.
                        </div>";
        }

    }
    else // else de la 1e condition : le tableau $membreArray n'est pas vide donc l'email existe dans la table membre
    {
        $erreur .= "<div class='col-md-6 offset-md-3 alert alert-danger text-center'> 
                        Email " . $_POST['email'] . " existant.
                    </div>";
    }


} // FIN DU POST



// mdp == mdp de confirmation
// requête SQL INSERT INTO

require_once('include/header.php');
?>

    <h1 class="text-center m-3">Inscription</h1>

    <?= $erreur ?>
    <?= $notification ?>


    <form method="post" class='col-md-6 mx-auto'>
    <!-- method='post' => les données d'un formulaire sont véhiculées par la superglobale $_POST
        action='fichier.php' action permet de récupérer les données du formulaire sur un autre fichier
     -->

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Saisir votre email">

            <!--
                name="emailFormulaire"  dans le tableau $_POST on va récupérer la champ emailFormulaire et sa valeur est la donnée écrite par l'utilisateur

                for (label) / id (input) : ils sont réliés, ils permettent lorsqu'on clique sur le label, de placer le curseur dans l'input
             -->
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Saisir votre mot de passe">
        </div>

        <div class="form-group">
            <label for="confirm_mdp">Confirmation mot de passe</label>
            <input type="password" class="form-control" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmer votre mot de passe">
        </div>

        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir votre prénom">
        </div>

        <button type="submit" class="col-md-12 btn btn-dark">Enregistrer</button>
        <!--<input type="submit" value="enregistrer">-->

    </form>



<?php 
require_once('include/footer.php');