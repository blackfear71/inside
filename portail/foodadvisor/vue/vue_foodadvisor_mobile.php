<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Les enfants ! À table !";
      $style_head      = "styleFA.css";
      $script_head     = "scriptFA.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Menus -->
      <aside>
  			<?php include('../../includes/common/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'foodadvisor';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons d'action */
          /********************/
          // Actualiser
          echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page" class="lien_green">Actualiser</a>';

          // Proposer un choix
          if ($actions["saisir_choix"] == true)
            echo '<a id="saisiePropositions" title="Proposer où manger" class="lien_green">Proposer où manger</div></a>';

          // Faire bande à part
          if ($actions["solo"] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doSolo">';
              echo '<input type="submit" name="solo" value="Faire bande à part" class="lien_red" />';
            echo '</form>';
          }

          // Lancer la détermination
          if ($actions["determiner"] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doDeterminer">';
              echo '<input type="submit" name="determiner" value="Lancer la détermination" class="lien_red" />';
            echo '</form>';
          }

          /****************/
          /* Bande à part */
          /****************/
          if (isset($solos) AND !empty($solos))
          {
            echo '<div id="titre_propositions_solo" class="titre_section">';
              echo '<img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />';
              echo '<div class="texte_titre_section">Ils font bande à part</div>';
              echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
            echo '</div>';

            echo '<div id="afficher_propositions_solo" class="zone_propositions_solo_sans_vote" style="display: none;">';
              foreach ($solos as $solo)
              {
                echo '<div class="zone_solo_sans_vote">';
                  // Avatar
                  $avatarFormatted = formatAvatar($solo->getAvatar(), $solo->getPseudo(), 2, "avatar");

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_solo_sans_vote" />';

                  // Pseudo
                  echo '<div class="pseudo_solo_sans_vote">' . formatString($solo->getPseudo(), 30) . '</div>';

                  // Annulation bande à part
                  if ($isSolo == true AND $actions["choix"] == true AND $solo->getIdentifiant() == $_SESSION['user']['identifiant'])
                  {
                    echo '<form method="post" action="foodadvisor.php?action=doSupprimerSolo" class="form_delete_solo">';
                      echo '<input type="submit" name="delete_solo" value="" title="Ne plus faire bande à part" class="bouton_delete_solo" />';
                    echo '</form>';
                  }
                echo '</div>';
              }
            echo '</div>';
          }

          /************/
          /* Non voté */
          /************/
          if (isset($sansPropositions) AND !empty($sansPropositions))
          {
            echo '<div id="titre_propositions_sans_vote" class="titre_section">';
              echo '<img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />';
              echo '<div class="texte_titre_section">Ils n\'ont pas voté</div>';
              echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
            echo '</div>';

            echo '<div id="afficher_propositions_sans_vote" class="zone_propositions_solo_sans_vote" style="display: none;">';
              foreach ($sansPropositions as $userSansProposition)
              {
                echo '<div class="zone_solo_sans_vote">';
                  // Avatar
                  $avatarFormatted = formatAvatar($userSansProposition->getAvatar(), $userSansProposition->getPseudo(), 2, "avatar");

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_solo_sans_vote" />';

                  // Pseudo
                  echo '<div class="pseudo_solo_sans_vote">' . formatString($userSansProposition->getPseudo(), 30) . '</div>';
                echo '</div>';
              }
            echo '</div>';
          }

          /************************/
          /* Propositions du jour */
          /************************/
          echo '<div id="titre_propositions_users" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les propositions du jour</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';







          echo '<div id="afficher_propositions_users" class="zone_propositions_users">';
            if (!empty($propositions))
            {
              foreach ($propositions as $proposition)
              {
                if ($proposition->getDetermined() == "Y" AND $proposition == $propositions[0])
                  echo '<div class="zone_proposition_determined">';
                elseif ($proposition->getDetermined() == "Y" AND $proposition != $propositions[0])
                  echo '<div class="zone_proposition_determined">';
                elseif ($proposition->getClassement() == 1 AND $proposition == $propositions[0])
                  echo '<div class="zone_proposition_top">';
                elseif ($proposition->getClassement() == 1 AND $proposition != $propositions[0])
                  echo '<div class="zone_proposition_top">';
                elseif ($proposition == $propositions[0])
                  echo '<div class="zone_proposition">';
                else
                  echo '<div class="zone_proposition">';

                // Image
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_proposition" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition" />';

                // Nom restaurant
                echo '<div class="nom_proposition">' . formatString($proposition->getName(), 20) . '</div>';

                // Réserveur




                echo '</div>';
              }
            }






            //  echo '<div class="empty">L\'affichage des propositions n\'est pas encore disponible sur cette version.</div>';




            else
              echo '<div class="empty">Pas encore de propositions pour aujourd\'hui</div>';
          echo '</div>';

          //echo '<div id="afficher_propositions_users" class="zone_propositions_users">rouge</div>';

          /*************/
          /* Mes choix */
          /*************/
          if (isset($mesChoix) AND !empty($mesChoix) AND $isSolo != true)
          {
            echo '<div id="titre_propositions_mes_choix" class="titre_section">';
              echo '<img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />';
              echo '<div class="texte_titre_section">Mes choix</div>';
              echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
            echo '</div>';




            echo '<div id="afficher_propositions_mes_choix" class="zone_propositions_mes_choix">';
              if (!empty($propositions))
                echo '<div class="empty">L\'affichage des choix n\'est pas encore disponible sur cette version.</div>';
              else
                echo '<div class="empty">Pas de choix encore saisis pour aujourd\'hui</div>';
            echo '</div>';

            //echo '<div id="afficher_propositions_mes_choix" class="zone_propositions_mes_choix">jaune</div>';
          }






        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
