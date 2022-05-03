<?php
  /**************/
  /* Expérience */
  /**************/
  // Titre
  echo '<div id="titre_niveaux_users" class="titre_section">';
    echo '<img src="../../includes/icons/profil/experience_grey.png" alt="experience_grey" class="logo_titre_section" />';
    echo '<div class="texte_titre_section_fleche">Niveaux</div>';
    echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
  echo '</div>';

  // Affichage des niveaux des utilisateurs
  echo '<div id="afficher_niveaux_users" class="zone_niveaux_users" style="display: none;">';
    $levelUsers = null;

    foreach ($listeUsers as $keyUser => $user)
    {
      if ($user->getLevel() != $levelUsers)
      {
        $levelUsers = $user->getLevel();

        echo '<div class="zone_avatars_niveaux">';
          echo '<div class="titre_classement_niveaux">';
            echo 'Niveau <span class="number_exp">' . $levelUsers . '</span>';
          echo '</div>';
      }

      echo '<div class="zone_user_niveaux">';
        // Avatar
        $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_niveau" />';

        // Pseudo
        echo '<div class="pseudo_niveau">' . formatString($user->getPseudo(), 15) . '</div>';
      echo '</div>';

      if (!isset($listeUsers[$keyUser + 1]) OR $user->getLevel() != $listeUsers[$keyUser + 1]->getLevel())
        echo '</div>';
    }
  echo '</div>';

  /**********/
  /* Succès */
  /**********/
  $levelSuccess = 0;

  echo '<div class="zone_succes_profil">';
    foreach ($listeSuccess as $keySuccess => $success)
    {
      if ($success->getLevel() != $levelSuccess)
      {
        // Formatage du titre du niveau
        echo formatLevelTitle($success->getLevel());
        $levelSuccess = $success->getLevel();

        // Zone du niveau des succès
        echo '<div class="zone_niveau_succes">';
      }

      if ($success->getDefined() == 'Y')
      {
        if ($success->getValue_user() >= $success->getLimit_success())
          echo '<a id="agrandir_succes_' . $success->getId() . '" class="agrandirSucces succes_liste succes_liste_yellow">';
        else
          echo '<div class="succes_liste">';

        // Logo succès
        if ($success->getValue_user() >= $success->getLimit_success())
        {
          echo '<div class="succes_unlocked">';
            echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_unlocked" />';
          echo '</div>';
        }
        else
        {
          echo '<div class="succes_locked">';
            echo '<img src="../../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';
          echo '</div>';
        }

        // Zone titre succès
        if ($success->getValue_user() <= 0 OR $success->getValue_user() < $success->getLimit_success())
        {
          if ($success->getUnicity() != 'Y')
          {
            echo '<div class="zone_titre_succes zone_titre_full">';
              // Titre succès
              echo '<div class="titre_succes">' . formatString($success->getTitle(), 30) . '</div>';

              // Barre de progression succès
              if ($success->getValue_user() <= 0)
                $percentSuccess = 0;
              else
                $percentSuccess = ($success->getValue_user() * 100) / $success->getLimit_success();

              echo '<div class="fond_progression_succes">';
                echo '<div class="progression_succes" style="width: ' . $percentSuccess . '%;"></div>';
              echo '</div>';
            echo '</div>';
          }
          else
          {
            echo '<div class="zone_titre_succes zone_titre_full">';
              // Titre succès
              echo '<div class="titre_succes titre_full">' . formatString($success->getTitle(), 30) . '</div>';
            echo '</div>';
          }
        }
        else
        {
            if ($success->getUnicity() != 'Y')
            {
              echo '<div class="zone_titre_succes">';
                // Titre succès
                echo '<div class="titre_succes titre_full">' . formatString($success->getTitle(), 20) . '</div>';
              echo '</div>';
            }
            else
            {
              echo '<div class="zone_titre_succes zone_titre_full">';
                // Titre succès
                echo '<div class="titre_succes titre_full">' . formatString($success->getTitle(), 30) . '</div>';
              echo '</div>';
            }
        }

        // Médailles (en excluant ceux qui sont uniques)
        if (!empty($success->getClassement()) AND $success->getUnicity() != 'Y')
        {
          foreach ($success->getClassement() as $classement)
          {
            if ($classement->getIdentifiant() == $_SESSION['user']['identifiant'])
            {
              switch ($classement->getRank())
              {
                case 1:
                  echo '<div class="succes_unlocked">';
                    echo '<img src="../../includes/icons/common/medals/or.png" alt="or" class="medaille_succes" />';
                  echo '</div>';
                  break;

                case 2:
                  echo '<div class="succes_unlocked">';
                    echo '<img src="../../includes/icons/common/medals/argent.png" alt="argent" class="medaille_succes" />';
                  echo '</div>';
                  break;

                case 3:
                  echo '<div class="succes_unlocked">';
                    echo '<img src="../../includes/icons/common/medals/bronze.png" alt="bronze" class="medaille_succes" />';
                  echo '</div>';
                  break;

                default:
                  break;
              }

              break;
            }
          }
        }

        if ($success->getValue_user() >= $success->getLimit_success())
          echo '</a>';
        else
          echo '</div>';
      }
      else
      {
        echo '<div class="succes_liste">';
          // Logo succès
          echo '<div class="succes_locked">';
            echo '<img src="../../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';
          echo '</div>';

          // Titre succès
          echo '<div class="zone_titre_succes zone_titre_full">';
            echo '<div class="titre_succes">Succès non défini</div>';
          echo '</div>';
        echo '</div>';
      }

      // Termine la zone du niveau des succès
      if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
        echo '</div>';
    }
  echo '</div>';
?>
