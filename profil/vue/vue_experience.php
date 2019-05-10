<?php
  // Classement expérience
  echo '<div class="titre_section"><img src="../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" />Expérience</div>';

  $lvl = 0;

  foreach ($experienceUsers as $keyExp => $expUser)
  {
    if ($expUser['niveau'] != $lvl)
    {
      echo '<div class="titre_classement_niveaux">Niveau ' . $expUser['niveau'] . '</div>';
      echo '<div class="zone_avatars_niveaux">';

      $lvl = $expUser['niveau'];
    }

    echo '<div class="zone_user_niveaux">';
      if (!empty($expUser['avatar']))
        echo '<img src="../includes/images/profil/avatars/' . $expUser['avatar'] . '" alt="avatar" title="' . $expUser['pseudo'] . '" class="avatar_niveau" />';
      else
        echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $expUser['pseudo'] . '" class="avatar_niveau" />';

      echo '<div class="pseudo_niveau">' . $expUser['pseudo'] . '</div>';
    echo '</div>';

    if (!isset($experienceUsers[$keyExp + 1]) OR $expUser['niveau'] != $experienceUsers[$keyExp + 1]['niveau'])
      echo '</div>';
  }
?>
