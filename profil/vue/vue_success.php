<?php
  // Notes explicatives succès
  /*
    Les succès sont stockés dans la table "success" de la base. Ils ont tous d'abord lus afin de récupérer la liste.
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

  echo '<div class="zone_succes_profil">';
    foreach ($listeSuccess as $success)
    {
      if ($success->getLevel() != $lvl)
      {
        echo formatTitleLvl($success->getLevel());
        $lvl = $success->getLevel();
      }

      if (isset($successUser[$success->getId()]))
      {
        if ($successUser[$success->getId()] >= $success->getLimit_success())
          echo '<div class="succes_liste" style="background-color: #ffad01;">';
        else
          echo '<div class="succes_liste">';
          // Médailles (en excluant ceux qui sont uniques)
          if ($success->getLimit_success() > 1)
          {
            foreach ($classementUsers as $classement)
            {
              if ($classement['id'] == $success->getId())
              {
                if (isset($classement['podium'][0]) AND $classement['podium'][0]['identifiant'] == $_SESSION['user']['identifiant'])
                  echo '<img src="../includes/icons/medals/or.png" alt="or" class="medal" />';
                elseif (isset($classement['podium'][1]) AND $classement['podium'][1]['identifiant'] == $_SESSION['user']['identifiant'])
                  echo '<img src="../includes/icons/medals/argent.png" alt="argent" class="medal" />';
                elseif (isset($classement['podium'][2]) AND $classement['podium'][2]['identifiant'] == $_SESSION['user']['identifiant'])
                  echo '<img src="../includes/icons/medals/bronze.png" alt="bronze" class="medal" />';

                break;
              }
            }
          }

          // Logo succès
          if ($successUser[$success->getId()] >= $success->getLimit_success())
            echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_unlocked" />';
          else
            echo '<img src="../includes/icons/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

          // Description succès
          if ($successUser[$success->getId()] >= $success->getLimit_success())
            echo '<div class="description_succes">' . $success->getDescription() . '</div>';

          // Barre de progression succès
          if ($successUser[$success->getId()] < $success->getLimit_success())
            echo '<meter min="0" max="' . $success->getLimit_success() . '" value="' . $successUser[$success->getId()] . '" class="progression_succes"></meter>';
        echo '</div>';
      }
      else
      {
        echo '<div class="succes_liste">';
          // Logo succès
          echo '<img src="../includes/icons/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">Succès non défini</div>';
        echo '</div>';
      }
    }
  echo '</div>';
?>
