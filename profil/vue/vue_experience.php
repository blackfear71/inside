<?php
  // Classement expérience
  echo '<div class="titre_section"><img src="../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Expérience</div></div>';

  $lvl = 0;

  foreach ($experienceUsers as $keyExp => $expUser)
  {
    if ($expUser['niveau'] != $lvl)
    {
      echo '<div class="zone_avatars_niveaux">';
        echo '<div class="titre_classement_niveaux">Niveau <span class="number_exp">' . $expUser['niveau'] . '</span></div>';

      $lvl = $expUser['niveau'];
    }

    echo '<div class="zone_user_niveaux">';
      if (!empty($expUser['avatar']))
        echo '<img src="../includes/images/profil/avatars/' . $expUser['avatar'] . '" alt="avatar" title="' . $expUser['pseudo'] . '" class="avatar_niveau" />';
      else
        echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $expUser['pseudo'] . '" class="avatar_niveau" />';

      if (strlen($expUser['pseudo']) > 15)
        echo '<div class="pseudo_niveau">' . substr($expUser['pseudo'], 0, 15) . '...</div>';
      else
        echo '<div class="pseudo_niveau">' . $expUser['pseudo'] . '</div>';
    echo '</div>';

    if (!isset($experienceUsers[$keyExp + 1]) OR $expUser['niveau'] != $experienceUsers[$keyExp + 1]['niveau'])
      echo '</div>';
  }
?>
