<?php
  /****************/
  /* Informations */
  /****************/
  echo '<div class="zone_infos_profil">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">' . $profil->getPseudo() . '</div>';
    echo '</div>';

    // Avatar
    echo '<div class="zone_profil_avatar">';
      $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil" />';
    echo '</div>';

    // Informations
    echo '<div class="zone_texte_infos">';
      // Niveau
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_profil" />';
        echo '<div class="texte_profil">Insider de <strong>niveau ' . $progression->getNiveau() . '</strong></div>';
      echo '</div>';

      // Expérience
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/profil/experience_grey.png" alt="experience_grey" class="logo_profil" />';
        echo '<div class="fond_experience_profil"><div class="experience_profil" style="width: ' . $progression->getPourcentage() . '%;"></div></div>';
        echo '<div class="valeur_experience_profil">' . $profil->getExperience() . ' XP</div>';
      echo '</div>';

      // Equipe
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/profil/team_grey.png" alt="team_grey" class="logo_profil" />';
        echo '<div class="texte_profil">' . $equipe->getTeam() . '</div>';
      echo '</div>';

      // Adresse mail
      if (!empty($profil->getEmail()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_profil" />';
          echo '<div class="texte_profil">' . $profil->getEmail() . '</div>';
        echo '</div>';
      }

      // Anniversaire
      if (!empty($profil->getAnniversary()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../../includes/icons/profil/anniversary_grey.png" alt="anniversary_grey" class="logo_profil" />';
          echo '<div class="texte_profil">Anniversaire le ' . formatDateForDisplay($profil->getAnniversary()) . '</div>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';

  /*****************/
  /* Contributions */
  /*****************/
  echo '<div class="zone_contributions_profil">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Mes contributions</div>';
    echo '</div>';

    // Movie House
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">MOVIE HOUSE</div>';
      echo '</div>';

      // Films ajoutés
      echo '<div class="zone_contribution">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_films_ajoutes() . '</div>';
        echo '<div class="texte_contribution">films ajoutés</div>';
      echo '</div>';

      // Commentaires
      echo '<div class="zone_contribution border_left">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_comments() . '</div>';
        echo '<div class="texte_contribution">commentaires</div>';
      echo '</div>';
    echo '</div>';

    // Food Advisor
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/food_advisor_grey.png" alt="food_advisor_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">LES ENFANTS ! À TABLE !</div>';
      echo '</div>';

      // Réservations
      echo '<div class="zone_contribution">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_reservations() . '</div>';
        echo '<div class="texte_contribution">réservations</div>';
      echo '</div>';
    echo '</div>';

    // Cooking Box
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/cooking_box_grey.png" alt="cooking_box_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">COOKING BOX</div>';
      echo '</div>';

      // Gâteaux faits
      echo '<div class="zone_contribution">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_gateaux() . '</div>';
        echo '<div class="texte_contribution">gâteaux faits</div>';
      echo '</div>';

      // Recettes saisies
      echo '<div class="zone_contribution border_left">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_recettes() . '</div>';
        echo '<div class="texte_contribution">recettes saisies</div>';
      echo '</div>';
    echo '</div>';

    // Expense Center
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">EXPENSE CENTER</div>';
      echo '</div>';

      // Solde
      echo '<div class="zone_contribution large">';
        if ($statistiques->getExpenses() > -0.01 AND $statistiques->getExpenses() < 0.01)
          echo '<div class="stat_contribution">' . formatAmountForDisplay('') . '</div>';
        else
          echo '<div class="stat_contribution">' . formatAmountForDisplay($statistiques->getExpenses()) . '</div>';
        echo '<div class="texte_contribution">solde des dépenses</div>';
      echo '</div>';
    echo '</div>';

    // Collector Room
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/collector_grey.png" alt="collector_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">COLLECTOR ROOM</div>';
      echo '</div>';

      // Phrases cultes rapportées
      echo '<div class="zone_contribution large">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_collectors() . '</div>';
        echo '<div class="texte_contribution">phrases cultes rapportées</div>';
      echo '</div>';
    echo '</div>';

    // #TheBox
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/ideas_grey.png" alt="ideas_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">#THEBOX</div>';
      echo '</div>';

      // Idées soumises
      echo '<div class="zone_contribution">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_ideas() . '</div>';
        echo '<div class="texte_contribution">idées soumises</div>';
      echo '</div>';
    echo '</div>';

    // Bugs & Evolutions
    echo '<div class="zone_contributions">';
      echo '<div class="titre_contribution">';
        echo '<img src="../../includes/icons/profil/bugs_grey.png" alt="bugs_grey" class="logo_titre_contribution" />';
        echo '<div class="texte_titre_contribution">BUGS & ÉVOLUTIONS</div>';
      echo '</div>';

      // Bugs
      echo '<div class="zone_contribution">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_bugs() . '</div>';
        echo '<div class="texte_contribution">bugs rapportés</div>';
      echo '</div>';

      // Evolutions
      echo '<div class="zone_contribution border_left">';
        echo '<div class="stat_contribution">' . $statistiques->getNb_evolutions() . '</div>';
        echo '<div class="texte_contribution">évolutions proposées</div>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
