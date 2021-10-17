<?php

// statut = 1 : CLIENT
// statut = 2 : ADMIN


// ---- FONCTION MEMBRE CONNECTÉ

function membreConnecte()
{
    // si le tableau 'membre' dans $_SESSION n'est pas défini
    if(!isset($_SESSION['membre']))
    {
        return false; // on retourne false
    }
    else
    {
        return true; // sinon s'il est défini on retourne true.
    }
}




// ---- FONCTION ADMIN CONNECTÉ

// la différence entre un client et un admin, c'est le statut 
// sauf qu'un admin c'est aussi un membreConnecte
function adminConnecte()
{

    // un admin est un membreConnecte() il doit également avoir le tableau 'membre' dans la $_SESSION mais avec une précision, son statut doit être égal à 2
    if(membreConnecte() && $_SESSION['membre']['statut'] == 2)
    {
        return true;
    }
    else
    {
        return false;
    }
}