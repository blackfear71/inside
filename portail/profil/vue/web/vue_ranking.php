<?php
  // Notes explicatives succès
  $lvl = 0;

  echo '<div class="zone_succes_profil" style="display: none;">';
    foreach ($listeSuccess as $keySuccess => $success)
    {
      if ($success->getLevel() != $lvl)
      {
        // Formatage du titre du niveau
        echo formatLevelTitle($success->getLevel());
        $lvl = $success->getLevel();

        // Définit une zone pour appliquer la Masonry
        echo '<div class="zone_niveau_succes">';
      }

      if ($success->getDefined() == 'Y')
      {
        if ($success->getValue_user() >= $success->getLimit_success())
          echo '<a id="agrandir_succes_' . $success->getId() . '" class="agrandirSucces classement_liste yellow">';
        else
          echo '<div class="classement_liste">';
          // Logo succès
          echo '<div class="zone_logo_classement">';
            if ($success->getValue_user() >= $success->getLimit_success())
              echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_classement_unlocked" />';
            else
              echo '<img src="../../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_classement_locked" />';
          echo '</div>';

          // Titre succès
          echo '<div class="titre_classement">' . $success->getTitle() . '</div>';

          // Médailles
          if (!empty($success->getClassement()))
          {
            if ($success->getValue_user() >= $success->getLimit_success())
              echo '<div class="yellow_strong">';
            else
              echo '<div class="grey_strong">';

              $previousRank = 0;

              foreach ($success->getClassement() as $keyRank => $classement)
              {
                if ($classement->getRank() != $previousRank)
                {
                  // Médailles
                  switch ($classement->getRank())
                  {
                    case 1:
                      echo '<img src="../../includes/icons/common/medals/or.png" alt="or" class="medal_rank" />';
                      break;

                    case 2:
                      echo '<img src="../../includes/icons/common/medals/argent.png" alt="argent" class="medal_rank" />';
                      break;

                    case 3:
                      echo '<img src="../../includes/icons/common/medals/bronze.png" alt="bronze" class="medal_rank" />';
                      break;

                    default:
                      break;
                  }

                  $previousRank = $classement->getRank();

                  echo '<div class="zone_medals">';
                }

                // Avatar
                $avatarFormatted = formatAvatar($classement->getAvatar(), $classement->getPseudo(), 2, 'avatar');

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_classement" />';

                if (!isset($success->getClassement()[$keyRank + 1]) OR $classement->getRank() != $success->getClassement()[$keyRank + 1]->getRank())
                  echo '</div>';
              }
            echo '</div>';
          }

        if ($success->getValue_user() >= $success->getLimit_success())
          echo '</a>';
        else
          echo '</div>';
      }
      else
      {
        echo '<div class="classement_liste">';
          // Logo succès
          echo '<div class="zone_logo_classement">';
            echo '<img src="../../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_classement_locked" />';
          echo '</div>';

          // Titre succès
          echo '<div class="titre_classement">Succès non défini</div>';
        echo '</div>';
      }

      // Termine la zone Masonry du niveau
      if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
        echo '</div>';
    }
  echo '</div>';
?>
