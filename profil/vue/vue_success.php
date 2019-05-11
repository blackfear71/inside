<?php
  // Notes explicatives succès
  /*
    Les succès sont stockés dans la table "success" de la base. Ils sont tout d'abord lus afin de récupérer la liste.
    Ils sont composés d'une référence (non modifiable), d'un indicateur d'ordonnancement (modifiable), d'un titre (modifiable),
    d'une description (modifiable) et d'une limite (modifiable). La référence est non modifiable pour pouvoir être reliée à la
    recherche associée, l'ordonnancement permet lui de réorganiser l'ordre des succès en cas de nouvel ajout ou de modification.
    La limite définit le nombre à atteindre afin de débloquer le succès. La référence sert également à déterminer le logo du succès.
    Le logo doit donc être stocké dans le dossier des succès et avoir le nom de la référence, au format PNG.

    On appelle ensuite une fonction qui va prendre en entrée la liste complète des succès et les parcourir un à un en fonction
    de l'utilisateur. La référence permet de savoir quelles données aller chercher, c'est dans cette fonction qu'il faudra rajouter
    les éventuelles nouvelles recherches de succès.

    On remonte donc 2 tableaux, un des succès et l'autre contenant les données de l'utilisateur associé. On teste chaque succès et chaque
    limite pour déterminer si le succès est débloqué.
  */
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
          echo '<div class="succes_liste yellow">';
        else
          echo '<div class="succes_liste">';

          // Médailles (en excluant ceux qui sont uniques)
          if ($success->getLimit_success() > 1)
          {
            foreach ($classementUsers as $classement)
            {
              if ($classement['id'] == $success->getId())
              {
                foreach ($classement['podium'] as $podium)
                {
                  if ($podium['identifiant'] == $_SESSION['user']['identifiant'] AND $podium['rank'] == 1)
                  {
                    echo '<img src="../includes/icons/common/medals/or.png" alt="or" class="medal" />';
                    break;
                  }
                  elseif ($podium['identifiant'] == $_SESSION['user']['identifiant'] AND $podium['rank'] == 2)
                  {
                    echo '<img src="../includes/icons/common/medals/argent.png" alt="argent" class="medal" />';
                    break;
                  }
                  elseif ($podium['identifiant'] == $_SESSION['user']['identifiant'] AND $podium['rank'] == 3)
                  {
                    echo '<img src="../includes/icons/common/medals/bronze.png" alt="bronze" class="medal" />';
                    break;
                  }
                }

                break;
              }
            }
          }

          // Logo succès
          if ($success->getValue_user() >= $success->getLimit_success())
            echo '<img src="../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_unlocked" />';
          else
            echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

          // Description succès
          if ($success->getValue_user() >= $success->getLimit_success())
            echo '<div class="description_succes">' . $success->getDescription() . '</div>';

          // Barre de progression succès
          if ($success->getValue_user() <= 0)
            echo '<meter min="0" max="' . $success->getLimit_success() . '" value="0" class="progression_succes"></meter>';
          elseif ($success->getValue_user() < $success->getLimit_success())
            echo '<meter min="0" max="' . $success->getLimit_success() . '" value="' . $success->getValue_user() . '" class="progression_succes"></meter>';
        echo '</div>';
      }
      else
      {
        echo '<div class="succes_liste">';
          // Logo succès
          echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">Succès non défini</div>';
        echo '</div>';
      }

      // Termine la zone Masonry du niveau
      if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
        echo '</div>';
    }
  echo '</div>';
?>
