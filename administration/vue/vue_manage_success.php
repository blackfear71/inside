<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - Succès</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Succès";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect     = true;
          $modify_success = true;
					$back_admin     = true;

					include('../includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
      <?php
        include('../includes/alerts.php');
      ?>

			<article>
        <?php
          // Ajout succès
          echo '<form method="post" action="manage_success.php?action=doAjouter" class="form_saisie_succes" enctype="multipart/form-data" runat="server">';
            echo '<table class="table_saisie_succes">';
              echo '<tr>';
                // Titre
                echo '<td class="td_saisie_succes_tit">';
                  echo '<input type="text" name="title" placeholder="Titre" value="' . $_SESSION['title_success'] . '" class="saisie_succes_tit" required />';
                echo '</td>';

                // Référence
                echo '<td class="td_saisie_succes_ref">';
                  echo '<input type="text" name="reference" placeholder="Référence" value="' . $_SESSION['reference_success'] . '" maxlength="255" class="saisie_succes_ref" required />';
                echo '</td>';

                // Niveau
                echo '<td class="td_saisie_succes_niv">';
                  echo '<input type="text" name="level" placeholder="Niveau" value="' . $_SESSION['level'] . '" maxlength="4" class="saisie_succes_niv" required />';
                echo '</td>';

                // Ordonnancement
                echo '<td class="td_saisie_succes_ord">';
                  echo '<input type="text" name="order_success" placeholder="Ordonnancement" value="' . $_SESSION['order_success'] . '" maxlength="3" class="saisie_succes_ord" required />';
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
                  echo '<input type="text" name="description" placeholder="Description" value="' . $_SESSION['description_success'] . '" class="saisie_description" required />';
                echo '</td>';

                // Condition
                echo '<td colspan="2" class="td_saisie_succes_cond">';
                  echo '<input type="text" name="limit_success" placeholder="Condition" value="' . $_SESSION['limit_success'] . '" maxlength="3" class="saisie_limit" required />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Explications
                echo '<td colspan="100%" class="td_saisie_succes_expl">';
                  echo '<input type="text" name="explanation" placeholder="Explications" value="' . $_SESSION['explanation_success'] . '" class="saisie_explaination" required />';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          echo '</form>';

          // Affichage des succès
          $lvl = 0;

          echo '<div class="zone_succes_admin">';
            foreach ($listeSuccess as $success)
            {
              if ($success->getLevel() != $lvl)
              {
                echo formatTitleLvl($success->getLevel());
                $lvl = $success->getLevel();
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
                  echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" alt="success" class="logo_succes" />';

                  // Titre succès
                  echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

                  // Description succès
                  echo '<div class="description_succes">' . $success->getDescription() . '</div>';

                  // Explications succès
                  echo '<div class="explications_succes">' . formatExplanation($success->getExplanation(), $success->getLimit_success()) . '</div>';
                echo '</div>';
              echo '</div>';
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
