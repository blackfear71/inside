<!-- Notes explicatives succès -->
<!--
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
-->

<?php
  echo '<div class="zone_succes_profil">';
    foreach ($listeSuccess as $success)
    {
      if (isset($successUser[$success->getOrder_success()]))
      {
        if ($successUser[$success->getOrder_success()] >= $success->getLimit_success())
          echo '<div class="succes_liste" style="background-color: #ffad01;">';
        else
          echo '<div class="succes_liste">';
          // Logo succès
          if ($successUser[$success->getOrder_success()] >= $success->getLimit_success())
            echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_unlocked" />';
          else
            echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

          // Description succès
          if ($successUser[$success->getOrder_success()] >= $success->getLimit_success())
            echo '<div class="description_succes">' . $success->getDescription() . '</div>';
        echo '</div>';
      }
      else
      {
        echo '<div class="succes_liste" style="background-color: #ffad01;">';
          // Logo succès
          echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes_locked" />';

          // Titre succès
          echo '<div class="titre_succes">Succès non défini</div>';
        echo '</div>';
      }
    }
  echo '</div>';
?>
