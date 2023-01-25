<?php
  if (isset($solos) AND !empty($solos))
  {
    // Titre
    echo '<div id="titre_propositions_solo" class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/solo_grey.png" alt="solo_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Ils font bande à part</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
    echo '</div>';

    // Affichage des utilisateurs faisant bande à part
    echo '<div id="afficher_propositions_solo" class="zone_propositions_solo_sans_vote" style="display: none;">';
      foreach ($solos as $solo)
      {
        echo '<div class="zone_solo_sans_vote">';
          // Avatar
          $avatarFormatted = formatAvatar($solo->getAvatar(), $solo->getPseudo(), 2, 'avatar');

          echo '<div class="zone_avatar_solo_sans_vote">';
            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_solo_sans_vote" />';
          echo '</div>';

          // Pseudo
          echo '<div class="pseudo_solo_sans_vote">' . formatString($solo->getPseudo(), 30) . '</div>';

          // Annulation bande à part
          if ($isSolo == true AND $actions['choix'] == true AND $solo->getIdentifiant() == $_SESSION['user']['identifiant'])
          {
            echo '<form method="post" action="foodadvisor.php?action=doSupprimerSolo" class="form_delete_solo">';
              echo '<input type="submit" name="delete_solo" value="" title="Ne plus faire bande à part" class="bouton_delete_solo" />';
            echo '</form>';
          }
        echo '</div>';
      }
    echo '</div>';
  }
?>
