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
					$back_admin     = true;

					include('../includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
      <?php
        include('../includes/alerts.php');
      ?>

			<article>
        <div class="avertissement_succes">
          Il est possible de modifier ici le niveau, l'ordonnancement, le titre, la description, la condition et les explications des succès. Bien contrôler l'ordonnancement par rapport au niveau pour éviter les doublons. Il n'est pas possible de modifier la référence ni l'image, il faut donc supprimer le succès via l'écran précédent. Pour les explications, insérer les caractères <i>%limit%</i> permet de les remplacer par la valeur de la conditon d'obtention du succès.
        </div>

        <?php
          // Affichage des succès
          $lvl = 0;

          echo '<form method="post" action="manage_success.php?action=doModifier" class="zone_succes_admin">';
            foreach ($listeSuccess as $success)
            {
              if ($success->getLevel() != $lvl)
              {
                echo formatTitleLvl($success->getLevel());
                $lvl = $success->getLevel();
              }

              echo '<div class="succes_liste_mod">';
                echo '<div class="succes_mod_left">';
                  // Id succès (caché)
                  echo '<input type="hidden" name="id[' . $success->getId() . ']" value="' . $success->getId() . '" />';

                  // Logo succès
                  echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" alt="success" class="logo_succes" />';

                  // Référence
                  echo '<div class="reference_succes">Ref. ' . $success->getReference() . '</div>';

                  // Niveau
                  echo '<div class="titre_succes">Niveau :</div>';
                  echo '<input type="text" value="' . $success->getLevel() . '" name="level[' . $success->getId() . ']" maxlength="4" class="saisie_modification_succes" />';

                  // Ordonnancement
                  echo '<div class="titre_succes">Ordre :</div>';
                  echo '<input type="text" value="' . $success->getOrder_success() . '" name="order_success[' . $success->getId() . ']" maxlength="3" class="saisie_modification_succes" />';
                echo '</div>';

                echo '<div class="succes_mod_right">';
                  // Titre succès
                  echo '<div class="titre_succes">Titre :</div>';
                  echo '<input type="text" value="' . $success->getTitle() . '" name="title[' . $success->getId() . ']" class="saisie_modification_succes" />';

                  // Description succès
                  echo '<div class="titre_succes">Description :</div>';
                  echo '<textarea name="description[' . $success->getId() . ']" class="textarea_modification_succes">' . $success->getDescription() . '</textarea>';

                  // Condition succès
                  echo '<div class="titre_succes">Condition :</div>';
                  echo '<input type="text" value="' . $success->getLimit_success() . '" name="limit_success[' . $success->getId() . ']" maxlength="3" class="saisie_modification_succes" />';
                echo '</div>';

                echo '<div class="succes_mod_bottom">';
                  // Explications
                  echo '<div class="titre_succes">Explications :</div>';
                  echo '<textarea name="explanation[' . $success->getId() . ']" class="textarea_modification_succes_2">' . $success->getExplanation() . '</textarea>';
                echo '</div>';
              echo '</div>';
            }

            echo '<input type="submit" value="Mettre à jour les succès" class="bouton_modification_succes" />';
          echo '</form>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
