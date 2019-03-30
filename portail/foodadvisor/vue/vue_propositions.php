<?php
  /****************************/
  /*** Propositions du jour ***/
  /****************************/
  echo '<div class="titre_section">';
    echo 'Les propositions du jour';

    echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page"><img src="../../includes/icons/foodadvisor/refresh.png" class="image_refresh" /></a>';

    if (!empty($propositions) AND date("H") < 13)
    {
      echo '<form method="post" action="foodadvisor.php?action=doDeterminer">';
        echo '<input type="submit" name="determiner" value="Lancer la détermination" class="bouton_determination" />';
      echo '</form>';
    }
  echo '</div>';

  if (!empty($propositions))
  {
    echo '<div class="zone_propositions">';
      foreach ($propositions as $proposition)
      {
        if ($proposition->getClassement() == 1)
        {
          if ($proposition->getDetermined() == "Y")
            echo '<div class="zone_proposition_determined">';
          else
            echo '<div class="zone_proposition_top">';
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
                echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

                // Nombre de participants
                if ($proposition->getNb_participants() == 1)
                  echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
                else
                  echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
              echo '</div>';

              echo '<div class="caller">';
                echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                // Avatar
                if (!empty($proposition->getAvatar()))
                  echo '<img src="../../includes/images/profil/avatars/' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';
                else
                  echo '<img src="../../includes/icons/common/default.png' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';

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
              echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

              // Nombre de participants
              if ($proposition->getNb_participants() == 1)
                echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
              else
                echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
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
            echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

            // Nombre de participants
            if ($proposition->getNb_participants() == 1)
              echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
            else
              echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
          echo '</div>';
        }
      }
    echo '</div>';
  }
  else
    echo '<div class="empty">Pas encore de propositions pour aujourd\'hui !</div>';
?>
