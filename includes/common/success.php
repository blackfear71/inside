<?php
    // Initialisation des succès à afficher
    $succesDebloques = array();

    if (isset($_SESSION['success'])AND !empty($_SESSION['success']))
    {
        // Récupération des succès
        foreach ($_SESSION['success'] as $reference => $success)
        {
            // Récupération des succès
            if (!empty($success))
            {
                // Récupération du succès
                $donneesSucces = getSuccesDebloques($reference);

                // On ajoute la ligne au tableau
                if ($donneesSucces->getDefined() == 'Y')
                    array_push($succesDebloques, $donneesSucces);

                // Suppression du succès de la session
                unset($_SESSION['success'][$reference]);
            }
            else
            {
                // Nettoyage de la session
                unset($_SESSION['success'][$reference]);
            }
        }
    }

    // Affichage des succès débloqués
    if (!empty($succesDebloques))
    {
        echo '<div id="zoom_succes" class="fond_zoom_succes">';
            echo '<div class="zone_success_zoom">';
                // Titre
                if (count($succesDebloques) > 1)
                    echo '<div class="titre_zone_succes_zoom">Succès débloqués !</div>';
                else
                    echo '<div class="titre_zone_succes_zoom">Succès débloqué !</div>';

                // Affichage des succès
                foreach ($succesDebloques as $ligneSuccesDebloque)
                {
                    // Succès
                    echo '<div class="zone_succes_zoom">';
                        // Titre du succès
                        echo '<div class="titre_succes_zoom">' . $ligneSuccesDebloque->getTitle() . '</div>';

                        // Logo du succès
                        echo '<img src="/inside/includes/images/profil/success/' . $ligneSuccesDebloque->getReference() . '.png" alt="' . $ligneSuccesDebloque->getReference() . '" class="logo_succes_zoom" />';

                        // Description du succès
                        echo '<div class="description_succes_zoom">' . $ligneSuccesDebloque->getDescription() . '</div>';

                        // Explications du succès
                        echo '<div class="explications_succes_zoom">' . formatExplanation($ligneSuccesDebloque->getExplanation(), formatNumericForDisplay($ligneSuccesDebloque->getLimit_success()), '%limit%') . '</div>';
                    echo '</div>';
                }

                // Boutons
                echo '<div class="zone_boutons_succes_zoom">';
                    // Bouton fermeture
                    echo '<a id="closeZoomSuccess" class="bouton_succes_zoom bouton_succes_zoom_margin">Trop bien !</a>';

                    // Bouton redirection
                    echo '<a href="/inside/portail/profil/profil.php?view=success&action=goConsulter" class="bouton_succes_zoom">Voir mes succès</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';

        // Suppression des succès une fois affichés
        $succesDebloques = array();
    }
?>