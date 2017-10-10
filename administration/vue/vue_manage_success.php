<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

		<title>Inside - Succès</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../includes/images/success_band.png" alt="success_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
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

			<article class="article_portail">
        <?php
          // Ajout succès
          echo '<form method="post" action="manage_success.php?action=doAjouter" class="form_saisie_succes" enctype="multipart/form-data" runat="server">';
            // Titre
            echo '<input type="text" name="title" placeholder="Titre" value="' . $_SESSION['title_success'] . '" class="saisie_title" required />';

            // Référence
            echo '<input type="text" name="reference" placeholder="Référence" value="' . $_SESSION['reference_success'] . '" maxlength="255" class="saisie_reference" required />';

            // Niveau
            echo '<input type="text" name="level" placeholder="Niveau" value="' . $_SESSION['level'] . '" maxlength="4" class="saisie_niveau" required />';

            // Ordonnancement
            echo '<input type="text" name="order_success" placeholder="Ordonnancement" value="' . $_SESSION['order_success'] . '" maxlength="3" class="saisie_order" required />';

            // Bouton parcourir
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';
            echo '<div class="zone_parcourir_succes">';
              echo '<div class="label_parcourir">Parcourir</div>';
              echo '<input type="file" accept=".png" name="success" class="bouton_parcourir_succes" required />';
            echo '</div>';

            // Bouton envoi
            echo '<input type="submit" name="send" value="" class="send_succes" />';

            // Description
            echo '<input type="text" name="description" placeholder="Description" value="' . $_SESSION['description_success'] . '" class="saisie_description" required />';

            // Condition
            echo '<input type="text" name="limit_success" placeholder="Condition" value="' . $_SESSION['limit_success'] . '" maxlength="3" class="saisie_limit" required />';
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
