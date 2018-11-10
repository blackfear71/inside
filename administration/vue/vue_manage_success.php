<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Succès";
      $style_head   = "styleAdmin.css";
      $script_head  = "scriptAdmin.js";
      $masonry_head = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion succès";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect     = true;
          $modify_success = true;
					$back_admin     = true;

					include('../includes/common/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
      <?php
        include('../includes/common/alerts.php');
      ?>

			<article>
        <?php
          // Ajout succès
          echo '<form method="post" action="manage_success.php?action=doAjouter" class="form_saisie_succes" enctype="multipart/form-data" runat="server">';
            echo '<table class="table_saisie_succes">';
              echo '<tr>';
                // Titre
                echo '<td class="td_saisie_succes_tit">';
                  echo '<input type="text" name="title" placeholder="Titre" value="' . $_SESSION['save']['title_success'] . '" class="saisie_succes_tit" required />';
                echo '</td>';

                // Référence
                echo '<td class="td_saisie_succes_ref">';
                  echo '<input type="text" name="reference" placeholder="Référence" value="' . $_SESSION['save']['reference_success'] . '" maxlength="255" class="saisie_succes_ref" required />';
                echo '</td>';

                // Niveau
                echo '<td class="td_saisie_succes_niv">';
                  echo '<input type="text" name="level" placeholder="Niveau" value="' . $_SESSION['save']['level'] . '" maxlength="4" class="saisie_succes_niv" required />';
                echo '</td>';

                // Ordonnancement
                echo '<td class="td_saisie_succes_ord">';
                  echo '<input type="text" name="order_success" placeholder="Ordonnancement" value="' . $_SESSION['save']['order_success'] . '" maxlength="3" class="saisie_succes_ord" required />';
                echo '</td>';

                // Bouton parcourir
                echo '<td class="td_saisie_succes_par">';
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';
                  echo '<div class="zone_parcourir_succes">';
                    echo '<div class="label_parcourir">Parcourir</div>';
                    echo '<input type="file" accept=".png" name="success" class="bouton_parcourir_succes" required />';
                  echo '</div>';
                echo '</td>';

                // Bouton envoi
                echo '<td class="td_saisie_succes_env">';
                  echo '<input type="submit" name="send" value="" class="send_succes" />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Description
                echo '<td colspan="4" class="td_saisie_succes_desc">';
                  echo '<input type="text" name="description" placeholder="Description" value="' . $_SESSION['save']['description_success'] . '" class="saisie_description" required />';
                echo '</td>';

                // Condition
                echo '<td colspan="2" class="td_saisie_succes_cond">';
                  echo '<input type="text" name="limit_success" placeholder="Condition" value="' . $_SESSION['save']['limit_success'] . '" maxlength="3" class="saisie_limit" required />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Explications
                echo '<td colspan="100%" class="td_saisie_succes_expl">';
                  echo '<input type="text" name="explanation" placeholder="Explications (utiliser %limit%)" value="' . $_SESSION['save']['explanation_success'] . '" class="saisie_explaination" required />';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          echo '</form>';

          // Affichage des succès
          $lvl = 0;

          echo '<div class="zone_succes_admin">';
            foreach ($listeSuccess as $keySuccess => $success)
            {
              if ($success->getLevel() != $lvl)
              {
                echo formatTitleLvl($success->getLevel());
                $lvl = $success->getLevel();

                // Définit une zone pour appliquer la Masonry
                echo '<div class="zone_niveau_succes_admin">';
              }

              echo '<div class="ensemble_succes">';
                // Suppression succès
                echo '<form method="post" action="manage_success.php?id=' . $success->getId() . '&action=doSupprimer" class="form_suppression_succes">';
                  echo '<input type="submit" name="delete_success" value="" title="Supprimer le succès" onclick="if(!confirm(\'Supprimer le succès &quot;' . formatOnclick($success->getTitle()) . '&quot; ?\')) return false;" class="bouton_delete" />';
                echo '</form>';

                echo '<div class="succes_liste">';
                  // Ordonnancement
                  echo '<div class="ordonnancement_succes">' . $success->getOrder_success() . '</div>';

                  // Condition
                  echo '<div class="condition_succes">/ ' . $success->getLimit_success() . '</div>';

                  // Logo succès
                  echo '<img src="../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" alt="success" class="logo_succes" />';

                  // Titre succès
                  echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

                  // Description succès
                  echo '<div class="description_succes">' . $success->getDescription() . '</div>';

                  // Explications succès
                  echo '<div class="explications_succes">' . formatExplanation($success->getExplanation(), $success->getLimit_success(), '%limit%') . '</div>';
                echo '</div>';
              echo '</div>';

              if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
              {
                // Termine la zone Masonry du niveau
                echo '</div>';
              }
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
