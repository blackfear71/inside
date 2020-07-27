<?php
  /****************************/
  /*** Propositions du jour ***/
  /****************************/
  echo '<div class="zone_propositions_right">';
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="logo_titre_section" />';

      echo '<div class="texte_titre_section">';
        echo 'Les propositions du jour';

        echo '<div class="zone_actions">';
          // Lancer la détermination
          if ($actions['determiner'] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doDeterminer" class="form_action">';
              echo '<input type="submit" name="determiner" value="Lancer la détermination" class="bouton_determination" />';
            echo '</form>';
          }

          echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page"><img src="../../includes/icons/foodadvisor/refresh.png" alt="refresh" class="image_refresh" /></a>';
        echo '</div>';
      echo '</div>';
    echo '</div>';

    if (!empty($propositions))
    {
      echo '<div class="zone_propositions">';
        // Propositions
        foreach ($propositions as $proposition)
        {
          if ($proposition->getDetermined() == 'Y' AND $proposition == $propositions[0])
            echo '<div class="zone_proposition_determined">';
          elseif ($proposition->getDetermined() == 'Y' AND $proposition != $propositions[0])
            echo '<div class="zone_proposition_determined">';
          elseif ($proposition->getClassement() == 1 AND $proposition == $propositions[0])
            echo '<div class="zone_proposition_top">';
          elseif ($proposition->getClassement() == 1 AND $proposition != $propositions[0])
            echo '<div class="zone_proposition_top">';
          elseif ($proposition == $propositions[0])
            echo '<div class="zone_proposition">';
          else
            echo '<div class="zone_proposition">';

            // Lien détails
            if ($proposition->getDetermined() == 'Y')
              echo '<a id="afficher_details_' . $proposition->getId_restaurant() . '" class="lien_details_determined afficherDetails" title="Plus de détails"><span class="lien_plus">+</span></a>';
            else
              echo '<a id="afficher_details_' . $proposition->getId_restaurant() . '" class="lien_details_top afficherDetails" title="Plus de détails"><span class="lien_plus">+</span></a>';

            // Image + lien
            if ($proposition->getDetermined() == 'Y' OR $proposition->getClassement() == 1)
            {
              echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_proposition_top">';
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="' . $proposition->getPicture() . '" title="' . $proposition->getName() . '" class="image_proposition_top" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $proposition->getName() . '" class="image_proposition_top" />';
              echo '</a>';
            }
            else
            {
              echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_mon_choix">';
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="' . $proposition->getPicture() . '" title="' . $proposition->getName() . '" class="image_mon_choix" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $proposition->getName() . '" class="image_mon_choix" />';
              echo '</a>';
            }

            // Nom du restaurant
            if ($proposition->getDetermined() == 'Y')
              echo '<div class="nom_proposition_determined">' . $proposition->getName() . '</div>';
            else
              echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

            // Ouverture, lieu et participants
            echo '<div class="zone_icones_mon_choix">';
              // Jours d'ouverture
              echo '<div class="zone_ouverture_mes_choix">';
                $explodedOpened = explode(';', $proposition->getOpened());
                $semaineShort   = array('Lu', 'Ma', 'Me', 'Je', 'Ve');
                $i              = 0;

                foreach ($explodedOpened as $opened)
                {
                  if (!empty($opened))
                  {
                    if ($opened == 'Y')
                      echo '<div class="jour_oui_fa">' . $semaineShort[$i] . '</div>';
                    else
                    {
                      if ($proposition->getClassement() == 1)
                        echo '<div class="jour_non_fa_white">' . $semaineShort[$i] . '</div>';
                      else
                        echo '<div class="jour_non_fa">' . $semaineShort[$i] . '</div>';
                    }
                  }

                  $i++;
                }
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
            if ($proposition->getDetermined() == 'Y' AND (!empty($proposition->getCaller()) OR !empty($proposition->getPhone())))
            {
              echo '<div class="caller">';
                echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                // Avatar
                if (!empty($proposition->getCaller()))
                {
                  $avatarFormatted = formatAvatar($proposition->getAvatar(), $proposition->getPseudo(), 2, 'avatar');

                  echo '<div class="zone_avatar_caller">';
                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_caller" />';
                  echo '</div>';
                }

                // Numéro de téléphone
                if (!empty($proposition->getPhone()))
                {
                  echo '<div class="zone_phone_caller">';
                    echo formatPhoneNumber($proposition->getPhone());
                  echo '</div>';
                }
              echo '</div>';
            }

            // Bouton réservation / complet
            if ($actions['reserver'] == true)
            {
              // On vérifie si on participe à chacun des restaurants pour pouvoir réserver
              $participe = false;

              foreach ($proposition->getDetails() as $detailsUser)
              {
                if ($_SESSION['user']['identifiant'] == $detailsUser->getIdentifiant())
                {
                  $participe = true;
                  break;
                }
              }

              if ($participe == true)
              {
                // Zone boutons
                echo '<div class="zone_reservation">';
                  // Bouton réservation
                  echo '<form method="post" action="foodadvisor.php?action=doReserver">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $proposition->getId_restaurant() . '" />';
                    echo '<input type="submit" name="reserve" value="J\'ai réservé !" class="bouton_reserver" />';
                  echo '</form>';

                  // Bouton complet
                  if ($proposition->getDetermined() == 'Y' AND $proposition->getCaller() == $_SESSION['user']['identifiant'])
                  {
                    echo '<form id="choice_complete" method="post" action="foodadvisor.php?action=doComplet" class="margin_top_10">';
                      echo '<input type="hidden" name="id_restaurant" value="' . $proposition->getId_restaurant() . '" />';
                      echo '<input type="submit" name="complete" value="Complet..." class="bouton_reserver eventConfirm" />';
                      echo '<input type="hidden" value="Signaler ce choix comme complet ? Les votes des autres utilisateurs seront supprimés et la détermination relancée." class="eventMessage" />';
                    echo '</form>';
                  }
                echo '</div>';
              }
            }

            // Bouton annulation
            if ($proposition->getDetermined() == 'Y' AND ($proposition->getReserved() == 'Y' OR $actions['annuler_reserver'] == true))
            {
              echo '<div class="zone_reservation">';
                if ($proposition->getReserved() == 'Y')
                  echo '<div class="reserved">Réservé !</div>';

                if ($actions['annuler_reserver'] == true)
                {
                  echo '<form method="post" action="foodadvisor.php?action=doAnnulerReserver" class="margin_top_10">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $proposition->getId_restaurant() . '" />';
                    echo '<input type="submit" name="unreserve" value="Annuler la réservation" class="bouton_reserver" />';
                  echo '</form>';
                }
              echo '</div>';
            }
          echo '</div>';
        }
      echo '</div>';
    }
    else
    {
      if (date('N') > 5)
        echo '<div class="empty">Il est impossible de voter pour aujourd\'hui !</div>';
      else
      {
        if (date('H') >= 13)
          echo '<div class="empty">Il n\'est plus possible de voter pour aujourd\'hui !</div>';
        else
          echo '<div class="empty">Pas encore de propositions pour aujourd\'hui !</div>';
      }
    }
  echo '</div>';
?>
