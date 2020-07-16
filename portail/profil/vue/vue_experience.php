<?php
  // Classement expérience
  echo '<div class="titre_section"><img src="../../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Expérience</div></div>';

  $lvl = null;

  foreach ($experienceUsers as $keyExp => $expUser)
  {
    if ($expUser['niveau'] != $lvl)
    {
      echo '<div class="zone_avatars_niveaux">';
        echo '<div class="titre_classement_niveaux">Niveau <span class="number_exp">' . $expUser['niveau'] . '</span></div>';

      $lvl = $expUser['niveau'];
    }

    echo '<div class="zone_user_niveaux">';
      // Avatar
      $avatarFormatted = formatAvatar($expUser['avatar'], $expUser['pseudo'], 2, 'avatar');

      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_niveau" />';

      // Pseudo
      echo '<div class="pseudo_niveau">' . formatString($expUser['pseudo'], 15) . '</div>';
    echo '</div>';

    if (!isset($experienceUsers[$keyExp + 1]) OR $expUser['niveau'] != $experienceUsers[$keyExp + 1]['niveau'])
      echo '</div>';
  }
?>
