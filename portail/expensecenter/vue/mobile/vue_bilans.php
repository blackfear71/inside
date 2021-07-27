<?php
  // Titre
  echo '<div id="titre_depenses_bilan" class="titre_section">';
    echo '<img src="../../includes/icons/expensecenter/total_grey.png" alt="total_grey" class="logo_titre_section" />';
    echo '<div class="texte_titre_section">Bilan</div>';
    echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
  echo '</div>';

  // Bilan
  echo '<div id="afficher_depenses_bilan" class="zone_bilan_users">';
    foreach ($listeUsers as $user)
    {
      if ($user->getTeam() == $_SESSION['user']['equipe'])
      {
        // Détermination classe à appliquer
        if ($user->getExpenses() <= -6)
          $classBilan = 'rouge';
        elseif ($user->getExpenses() <= -3 AND $user->getExpenses() > -6)
          $classBilan = 'orange';
        elseif ($user->getExpenses() < -0.01 AND $user->getExpenses() > -3)
          $classBilan = 'jaune';
        elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() < 5)
          $classBilan = 'vert';
        elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() >= 5)
          $classBilan = 'vert_fonce';
        else
          $classBilan = 'gris';

        // Bilan
        echo '<div class="zone_bilan_user bilan_' . $classBilan . '">';
          // Avatar
          $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

          echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bilan" />';

          // Pseudo
          echo '<div class="pseudo_bilan">' . formatString($user->getPseudo(), 15) . '</div>';

          // Total
          if ($user->getExpenses() > -0.01 AND $user->getExpenses() < 0.01)
            echo '<div class="total_bilan total_' . $classBilan . '">' . formatAmountForDisplay('') . '</div>';
          else
            echo '<div class="total_bilan total_' . $classBilan . '">' . formatAmountForDisplay($user->getExpenses()) . '</div>';
        echo '</div>';
      }
    }
  echo '</div>';
?>
