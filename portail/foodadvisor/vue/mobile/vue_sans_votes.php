<?php
  if (isset($sansPropositions) AND !empty($sansPropositions))
  {
    // Titre
    echo '<div id="titre_propositions_sans_vote" class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Ils n\'ont pas voté</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
    echo '</div>';

    // Affichage des utilisateurs n'ayant pas voté
    echo '<div id="afficher_propositions_sans_vote" class="zone_propositions_solo_sans_vote" style="display: none;">';
      foreach ($sansPropositions as $userSansProposition)
      {
        echo '<div class="zone_solo_sans_vote">';
          // Avatar
          $avatarFormatted = formatAvatar($userSansProposition->getAvatar(), $userSansProposition->getPseudo(), 2, 'avatar');

          echo '<div class="zone_avatar_solo_sans_vote">';
            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_solo_sans_vote" />';
          echo '</div>';

          // Pseudo
          echo '<div class="pseudo_solo_sans_vote">' . formatString($userSansProposition->getPseudo(), 30) . '</div>';
        echo '</div>';
      }
    echo '</div>';
  }
?>
