<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Expense Center";
      $style_head      = "styleEC.css";
      $script_head     = "scriptEC.js";
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
        $celsius = 'expensecenter';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /**********/
          /* Années */
          /**********/
          echo '<div id="zoneSaisieAnnee" class="fond_saisie">';
            echo '<div class="div_saisie">';
              // Titre
              echo '<div class="zone_titre_saisie">';
                echo 'Voir une autre année';
              echo '</div>';

              // Saisie
              echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                  foreach ($onglets as $annee)
                  {
                    if ($annee == date('Y'))
                      echo '<a href="expensecenter.php?year=' . $annee . '&action=goConsulter" class="lien_saisie lien_courant">' . $annee . '</a>';
                    else
                      echo '<a href="expensecenter.php?year=' . $annee . '&action=goConsulter" class="lien_saisie">' . $annee . '</a>';
                  }
                echo '</div>';
              echo '</div>';

              // Bouton fermeture
              echo '<div class="zone_boutons_saisie">';
                echo '<a id="fermerSaisieAnnee" class="bouton_saisie_fermer">Fermer</a>';
              echo '</div>';
            echo '</div>';
          echo '</div>';

          /**********/
          /* Saisie */
          /**********/

          /********************/
          /* Boutons d'action */
          /********************/
          // Années
          echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red">' . $_GET['year'] . '</a>';

          // Saisie dépense
          echo '<a id="" title="Saisir une dépense" class="lien_green">Saisir une dépense</a>';

          /**********/
          /* Bilans */
          /**********/
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
                $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, "avatar");

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bilan" />';

                // Pseudo
                echo '<div class="pseudo_bilan">' . formatString($user->getPseudo(), 15) . "</div>";

                // Total
                if ($user->getExpenses() > -0.01 AND $user->getExpenses() < 0.01)
                  echo '<div class="total_bilan total_' . $classBilan . '">' . formatBilanForDisplay(abs($user->getExpenses())) . '</div>';
                else
                  echo '<div class="total_bilan total_' . $classBilan . '">' . formatBilanForDisplay($user->getExpenses()) . '</div>';
              echo '</div>';
            }
          echo '</div>';

          /************/
          /* Dépenses */
          /************/
          echo '<div id="titre_depenses_utilisateurs" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les dépenses</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          echo '<div id="afficher_depenses_utilisateurs" class="empty" style="display: none;">';
            echo 'Ici apparaîtra bientôt la liste des dépenses. Quand elle sera disponible, on pourra voir les détails d\'une dépense et la modifier. En attendant, ces fonctionnalités restent disponible sur la version classique du site.';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
