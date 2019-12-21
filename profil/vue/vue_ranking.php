<?php
  // Notes explicatives succès
  $lvl = 0;

  echo '<div class="zone_succes_profil" style="display: none;">';
    foreach ($listeSuccess as $keySuccess => $success)
    {
      if ($success->getLevel() != $lvl)
      {
        echo formatTitleLvl($success->getLevel());
        $lvl = $success->getLevel();

        // Définit une zone pour appliquer la Masonry
        echo '<div class="zone_niveau_succes">';
      }

      if ($success->getDefined() == "Y")
      {
        if ($success->getValue_user() >= $success->getLimit_success())
          echo '<div class="classement_liste yellow">';
        else
          echo '<div class="classement_liste">';
          // Logo succès
          if ($success->getValue_user() >= $success->getLimit_success())
            echo '<img src="../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_classement_unlocked" />';
          else
            echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_classement_locked" />';

          // Titre succès
          echo '<div class="titre_classement">' . $success->getTitle() . '</div>';

          // Médailles
          if (!empty($classementUsers))
          {
            if ($success->getValue_user() >= $success->getLimit_success())
              echo '<div class="yellow_strong">';
            else
              echo '<div class="grey_strong">';

              foreach ($classementUsers as $classement)
              {
                if ($classement['id'] == $success->getId())
                {
                  $rank = 0;

                  foreach ($classement['podium'] as $keyRank => $podium)
                  {
                    if ($podium['rank'] != $rank)
                    {
                      // Médailles
                      switch ($podium['rank'])
                      {
                        case 1:
                          echo '<img src="../includes/icons/common/medals/or.png" alt="or" class="medal_rank" />';
                          break;

                        case 2:
                          echo '<img src="../includes/icons/common/medals/argent.png" alt="argent" class="medal_rank" />';
                          break;

                        case 3:
                          echo '<img src="../includes/icons/common/medals/bronze.png" alt="bronze" class="medal_rank" />';
                          break;

                        default:
                          break;
                      }

                      $rank = $podium['rank'];

                      echo '<div class="zone_medals">';
                    }

                    // Avatar
                    $avatarFormatted = formatAvatar($podium['avatar'], $podium['pseudo'], 1, "avatar");

                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_classement" />';

                    if (!isset($classement['podium'][$keyRank + 1]) OR $podium['rank'] != $classement['podium'][$keyRank + 1]['rank'])
                      echo '</div>';
                  }

                  break;
                }
              }
            echo '</div>';
          }
        echo '</div>';
      }
      else
      {
        echo '<div class="classement_liste">';
          // Logo succès
          echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_classement_locked" />';

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
