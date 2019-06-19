<?php
  /****************************/
  /*** Propositions du jour ***/
  /****************************/
  echo '<div class="zone_propositions_right">';
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="logo_titre_section" />';

      echo 'Les propositions du jour';

      echo '<div class="zone_actions">';
        // Faire bande à part
        if ($actions["solo"] == true)
        {
          echo '<form method="post" action="foodadvisor.php?action=doSolo" class="form_action">';
            echo '<input type="submit" name="solo" value="Faire bande à part" class="bouton_determination" />';
          echo '</form>';
        }

        // Lancer la détermination
        if ($actions["determiner"] == true)
        {
          echo '<form method="post" action="foodadvisor.php?action=doDeterminer" class="form_action">';
            echo '<input type="submit" name="determiner" value="Lancer la détermination" class="bouton_determination" />';
          echo '</form>';
        }

        echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page"><img src="../../includes/icons/foodadvisor/refresh.png" alt="" class="image_refresh" /></a>';
      echo '</div>';
    echo '</div>';

    if (!empty($propositions))
    {
      echo '<div class="zone_propositions">';
        // Propositions
        foreach ($propositions as $proposition)
        {
          if ($proposition->getDetermined() == "Y" AND $proposition == $propositions[0])
            echo '<div class="zone_proposition_determined" id="zone_first">';
          elseif ($proposition->getDetermined() == "Y" AND $proposition != $propositions[0])
            echo '<div class="zone_proposition_determined">';
          elseif ($proposition->getClassement() == 1 AND $proposition == $propositions[0])
            echo '<div class="zone_proposition_top" id="zone_first">';
          elseif ($proposition->getClassement() == 1 AND $proposition != $propositions[0])
            echo '<div class="zone_proposition_top">';
          elseif ($proposition == $propositions[0])
            echo '<div class="zone_proposition" id="zone_first">';
          else
            echo '<div class="zone_proposition">';

            // Lien détails
            if ($proposition->getDetermined() == "Y")
              echo '<a id="afficher_details_' . $proposition->getId_restaurant() . '" class="lien_details_determined afficherDetails" title="Plus de détails"><span class="lien_plus">+</span></a>';
            else
              echo '<a id="afficher_details_' . $proposition->getId_restaurant() . '" class="lien_details_top afficherDetails" title="Plus de détails"><span class="lien_plus">+</span></a>';

            // Image + lien
            if ($proposition->getDetermined() == "Y" OR $proposition->getClassement() == 1)
            {
              echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_proposition_top">';
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_proposition_top" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition_top" />';
              echo '</a>';
            }
            else
            {
              echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_mon_choix">';
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
              echo '</a>';
            }

            // Nom du restaurant
            if ($proposition->getDetermined() == "Y")
              echo '<div class="nom_proposition_determined">' . $proposition->getName() . '</div>';
            else
              echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

            if ($proposition->getDetermined() == "Y")
              echo '<div class="zone_icones_mon_choix">';

              // Jours d'ouverture
              echo '<div class="zone_ouverture_mes_choix">';
                $explodedOpened = explode(";", $proposition->getOpened());
                $semaine_short  = array("Lu", "Ma", "Me", "Je", "Ve");
                $i              = 0;

                foreach ($explodedOpened as $opened)
                {
                  if (!empty($opened))
                  {
                    if ($opened == "Y")
                      echo '<div class="jour_oui_fa">' . $semaine_short[$i] . '</div>';
                    else
                    {
                      if ($proposition->getClassement() == 1)
                        echo '<div class="jour_non_fa" style="background-color: white;">' . $semaine_short[$i] . '</div>';
                      else
                        echo '<div class="jour_non_fa">' . $semaine_short[$i] . '</div>';
                    }
                  }

                  $i++;
                }

              if ($proposition->getDetermined() == "Y")
                echo '</div>';

              // Lieu
              echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $proposition->getLocation() . '</span>';

              // Nombre de participants
              if ($proposition->getNb_participants() == 1)
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participant</span>';
              else
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participants</span>';
            echo '</div>';

            // Réserveur
            if ($proposition->getDetermined() == "Y")
            {
              echo '<div class="caller">';
                echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                // Avatar
                if (!empty($proposition->getAvatar()))
                  echo '<img src="../../includes/images/profil/avatars/' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';
                else
                  echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';

                // Numéro de téléphone
                if (!empty($proposition->getPhone()))
                  echo formatPhoneNumber($proposition->getPhone());
              echo '</div>';
            }

            // Bouton réservation / annulation
            if ($actions["reserver"] == true)
            {
              // On vérifie si on participe à chacun des restaurants pour pouvoir réserver
              $participe = false;

              foreach ($proposition->getDetails() as $detailsUser)
              {
                if ($_SESSION['user']['identifiant'] == $detailsUser['identifiant'])
                {
                  $participe = true;
                  break;
                }
              }

              if ($participe == true)
              {
                echo '<div class="zone_reservation">';
                  echo '<form method="post" action="foodadvisor.php?action=doReserver">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $proposition->getId_restaurant() . '" />';
                    echo '<input type="submit" name="reserve" value="J\'ai réservé !" class="bouton_reserver"/>';
                  echo '</form>';
                echo '</div>';
              }
            }

            if ($proposition->getDetermined() == "Y" AND ($proposition->getReserved() == "Y" OR $actions["annuler_reserver"] == true))
            {
              echo '<div class="zone_reservation">';
                if ($proposition->getReserved() == "Y")
                  echo '<div class="reserved">Réservé !</div>';

                if ($actions["annuler_reserver"] == true)
                {
                  echo '<form method="post" action="foodadvisor.php?action=doAnnulerReserver">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $proposition->getId_restaurant() . '" />';
                    echo '<input type="submit" name="unreserve" value="Annuler la réservation" class="bouton_reserver" style="margin-top: 10px;"/>';
                  echo '</form>';
                }
              echo '</div>';
            }
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas encore de propositions pour aujourd\'hui !</div>';
  echo '</div>';
?>
