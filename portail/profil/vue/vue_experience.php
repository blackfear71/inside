<?php
  // Classement expérience
  echo '<div class="titre_section"><img src="../../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Expérience</div></div>';

  $lvl = null;

  foreach ($listeUsers as $keyUser => $user)
  {
    if ($user->getLevel() != $lvl)
    {
      echo '<div class="zone_avatars_niveaux">';
        echo '<div class="titre_classement_niveaux">Niveau <span class="number_exp">' . $user->getLevel() . '</span></div>';

      $lvl = $user->getLevel();
    }

    echo '<div class="zone_user_niveaux">';
      // Avatar
      $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_niveau" />';

      // Pseudo
      echo '<div class="pseudo_niveau">' . formatString($user->getPseudo(), 15) . '</div>';
    echo '</div>';

    if (!isset($listeUsers[$keyUser + 1]) OR $user->getLevel() != $listeUsers[$keyUser + 1]->getLevel())
      echo '</div>';
  }
?>
