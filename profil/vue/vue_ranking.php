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
        echo '<div class="zone_niveau_succes margin_bottom">';
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
          foreach ($classementUsers as $classement)
          {
            $gold   = false;
            $silver = false;
            $bronze = false;

            if ($classement['id'] == $success->getId())
            {
              foreach ($classement['podium'] as $podium)
              {
                // Or
                if ($podium['rank'] == 1)
                {
                  echo '<div class="zone_medals">';
                    if ($gold == false)
                    {
                      echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/or.png" alt="or" class="medal_rank" /></div>';
                      $gold = true;
                    }

                    if (!empty($podium['avatar']))
                      echo '<img src="../includes/images/profil/avatars/' . $podium['avatar'] . '" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                    else
                      echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                  echo '</div>';
                }

                // Argent
                if ($podium['rank'] == 2)
                {
                  echo '<div class="zone_medals">';
                    if ($silver == false)
                    {
                      echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/argent.png" alt="argent" class="medal_rank" /></div>';
                      $silver = true;
                    }

                    if (!empty($podium['avatar']))
                      echo '<img src="../includes/images/profil/avatars/' . $podium['avatar'] . '" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                    else
                      echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                  echo '</div>';
                }

                // Bronze
                if ($podium['rank'] == 3)
                {
                  echo '<div class="zone_medals">';
                    if ($bronze == false)
                    {
                      echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/bronze.png" alt="bronze" class="medal_rank" /></div>';
                      $bronze = true;
                    }

                    if (!empty($podium['avatar']))
                      echo '<img src="../includes/images/profil/avatars/' . $podium['avatar'] . '" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                    else
                      echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $podium['pseudo'] . '" class="avatar_classement" />';
                  echo '</div>';
                }
              }

              break;
            }
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
