<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="stylePP.css" />
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  	<title>Inside - PP</title>
  </head>

	<body>
    <header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

    <section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$ajouter_parcours = true;
					$back = true;
					$ideas = true;
					$reports = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

      <article class="article_portail">
				<!-- Bandeau catégorie -->
				<img src="../../includes/images/petits_pedestres_band.png" alt="petits_pedestres_band" class="bandeau_categorie" />

				<?php
					// Tableau des parcours
        	echo '<table class="table_movie_house" style="margin-top: 30px;">';
          	echo '<tr>';
              echo '<td class="init_table_dates" style="width: 120px;">Nom du parcours</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Distance</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Lieu</td>';
            echo '</tr>';

            foreach ($parcours as $prcr)
            {
                echo '<tr>';
                  echo '<td class="table_users">';
                    echo '<div>';
                      echo '<a href="parcours.php?id=' . $prcr->getId() . '&action=consulter">'. $prcr->getNom() . '</a>';
                    echo '</div>';
                  echo '</td>';

									/*
									Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
									Réponse : Parcours.
									C'est nul ? Oui, c'est nul.
									*/

                  echo '<td class="table_users">';
                    echo '<div>';
                      echo $prcr->getDistance() . ' km';
                    echo '</div>';
                  echo '</td>';

                  echo '<td class="table_users">';
                    echo '<div>';
                      echo $prcr->getLieu();
                    echo '</div>';
                  echo '</td>';
                echo '</tr>';
            }

          echo '</table>';
        ?>
    	</article>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
