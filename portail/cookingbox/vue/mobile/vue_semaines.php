<?php
  // Titre
  echo '<div class="titre_section">';
    echo '<img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" />';
    echo '<div class="texte_titre_section">Le gâteau de la semaine</div>';
  echo '</div>';

  // Semaine en cours
  echo '<div class="zone_semaine" id="semaineCourante">';
    if (!empty($currentWeek->getIdentifiant()))
    {
      // Avatar
      $avatarFormatted = formatAvatar($currentWeek->getAvatar(), $currentWeek->getPseudo(), 2, 'avatar');   
        
      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_semaine" />'; 
        
      // Pseudo
      echo '<div class="pseudo_semaine">' . formatString(formatUnknownUser($currentWeek->getPseudo(), true, false), 30) . '</div>';  
    }
    else
    {
      echo '<div class="semaine_vide">';
        echo 'Encore personne d\'affecté...';
      echo '</div>';
    }

    // Numéro semaine
    echo '<div class="zone_numero_semaine">';
      if ($currentWeek->getCooked() == 'Y')
        echo '<div class="numero_semaine numero_semaine_realisee">' . formatWeekForDisplay(date('W')) . '</div>';
      else
        echo '<div class="numero_semaine">' . formatWeekForDisplay(date('W')) . '</div>';
    echo '</div>';
  echo '</div>';

  // Semaine suivante
  echo '<div class="zone_semaine" id="semaineSuivante">';
    if (!empty($nextWeek->getIdentifiant()))
    {
      // Avatar
      $avatarFormatted = formatAvatar($nextWeek->getAvatar(), $nextWeek->getPseudo(), 2, 'avatar');   
        
      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_semaine" />'; 
        
      // Pseudo
      echo '<div class="pseudo_semaine">' . formatString(formatUnknownUser($nextWeek->getPseudo(), true, false), 30) . '</div>';  
    }
    else
    {
      echo '<div class="semaine_vide">';
        echo 'Encore personne d\'affecté...';
      echo '</div>';
    }

    // Numéro semaine
    echo '<div class="zone_numero_semaine">';
      if ($nextWeek->getCooked() == 'Y')
        echo '<div class="numero_semaine numero_semaine_realisee">' . formatWeekForDisplay(date('W', strtotime('+ 1 week'))) . '</div>';
      else
        echo '<div class="numero_semaine">' . formatWeekForDisplay(date('W', strtotime('+ 1 week'))) . '</div>';
    echo '</div>';
  echo '</div>';
?>