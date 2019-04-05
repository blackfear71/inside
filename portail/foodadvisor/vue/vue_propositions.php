<?php
  /****************************/
  /*** Propositions du jour ***/
  /****************************/
  echo '<div class="titre_section">';
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

  if (!empty($propositions) OR !empty($solos))
  {
    echo '<div class="zone_propositions">';
      // Bande à part
      if (!empty($solos))
      {
        echo '<div class="zone_proposition_top" id="zone_solo">';
          echo '<div class="titre_solo">Bande à part</div>';

          foreach ($solos as $solo)
          {
            echo '<div class="zone_solo">';
              // Avatar
              if (!empty($solo->getAvatar()))
                echo '<img src="../../includes/images/profil/avatars/' . $solo->getAvatar() . '" alt="avatar" title="' . $solo->getPseudo() . '" class="avatar_solo" />';
              else
                echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $solo->getPseudo() . '" class="avatar_solo" />';

              // Pseudo
              echo '<div class="pseudo_solo">' . $solo->getPseudo() . '</div>';
            echo '</div>';
          }
        echo '</div>';
      }

      // Propositions
      foreach ($propositions as $proposition)
      {
        if ($proposition->getClassement() == 1)
        {
          if ($proposition->getDetermined() == "Y")
          {
            if ($proposition == $propositions[0])
              echo '<div class="zone_proposition_determined" id="zone_first">';
            else
              echo '<div class="zone_proposition_determined">';
          }
          else
          {
            if ($proposition == $propositions[0])
              echo '<div class="zone_proposition_top" id="zone_first">';
            else
              echo '<div class="zone_proposition_top">';
          }
            // Lien détails
            if ($proposition->getDetermined() == "Y")
              echo '<a onclick="afficherMasquer(\'zone_details_determined_' . $proposition->getId_restaurant() . '\');" class="lien_details_determined" title="Plus de détails"><span class="lien_plus">+</span></a>';
            else
              echo '<a onclick="afficherMasquer(\'zone_details_determined_' . $proposition->getId_restaurant() . '\');" class="lien_details_top" title="Plus de détails"><span class="lien_plus">+</span></a>';

            // Image + lien
            echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_proposition_top">';
              if (!empty($proposition->getPicture()))
                echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_proposition_top" />';
              else
                echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition_top" />';
            echo '</a>';

            if ($proposition->getDetermined() == "Y")
            {
              // Nom du restaurant
              echo '<div class="nom_proposition_determined">' . $proposition->getName() . '</div>';

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
                        echo '<div class="jour_non_fa">' . $semaine_short[$i] . '</div>';
                    }

                    $i++;
                  }
                echo '</div>';

                // Lieu
                echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $proposition->getLocation() . '</span>';

                // Nombre de participants
                if ($proposition->getNb_participants() == 1)
                  echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participant</span>';
                else
                  echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participants</span>';
              echo '</div>';

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
            else
            {
              // Nom du restaurant
              echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

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
                      echo '<div class="jour_non_fa" style="background-color: white;">' . $semaine_short[$i] . '</div>';
                  }

                  $i++;
                }
              echo '</div>';

              // Lieu
              echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $proposition->getLocation() . '</span>';

              // Nombre de participants
              if ($proposition->getNb_participants() == 1)
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participant</span>';
              else
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participants</span>';
            }
          echo '</div>';
        }
        else
        {
          echo '<div class="zone_proposition">';
            // Image + lien
            echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_mon_choix">';
              if (!empty($proposition->getPicture()))
                echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
              else
                echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
            echo '</a>';

            // Nom du restaurant
            echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

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
                    echo '<div class="jour_non_fa">' . $semaine_short[$i] . '</div>';
                }

                $i++;
              }
            echo '</div>';

            // Lieu
            echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $proposition->getLocation() . '</span>';

            // Nombre de participants
            if ($proposition->getNb_participants() == 1)
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participant</span>';
            else
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participants</span>';
          echo '</div>';
        }
      }
    echo '</div>';
  }
  else
    echo '<div class="empty">Pas encore de propositions pour aujourd\'hui !</div>';
?>
