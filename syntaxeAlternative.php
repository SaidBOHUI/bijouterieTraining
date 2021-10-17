                <ul class="navbar-nav mr-auto">

                    <!-- LA SYNTAXE ALTERNATIVE sert à combiner du PHP et du HTML 
                         elle permet d'éviter d'utiliser les accolades et surtout d'afficher le HTML par des echo car on emploie la balise PHP comme les balise HTML : ouvrante et fermante

                         syntaxe ouvrante : < ? php if(....) : ?>

                         les : sont comme : l'accolade ouvrante

                         syntaxe fermante : < ? php endif ;?>

                         on reprend le nom et on y accole devant le terme end
                         if => endif
                         for => endfor
                         while => endwhile
                         switch => endswitch
                         foreach => endforeach
                         ET ne pas oublier le point virgule

                         entre les 2 balises on met du html "normalement"
                    
                     -->
                    <?php if(!membreConnecte()) : ?>

                    <li class="nav-item active">
                        <a href="<?= URL ?>connexion.php">connexion</a>
                    </li>
                    
                    <?php else : ?>

                    <li class="nav-item">
                        <a href="<?= URL ?>deconnexion.php">déconnexion</a>
                    </li>

                    <?php endif; ?>

                </ul>