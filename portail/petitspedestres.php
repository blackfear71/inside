<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	//include('../includes/init_session.php');

  // Classe Parcours
  include('../includes/classes/parcours.php');

  // Connexion à la bdd
  include('../includes/appel_bdd.php');

  if (!isset($_SESSION['erreur_distance']) OR $_SESSION['erreur_distance'] == false)
  {
      $_SESSION['nom_parcours'] = "";
      $_SESSION['distance'] = "";
  }
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<title>Inside - PP</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>
    <header>
			<?php include('../includes/onglets.php') ; ?>
		</header>

    <section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

      <article class="article_portail">

				<!-- Bandeau catégorie -->
				<img src="../includes/images/petits_pedestres_band.png" alt="petits_pedestres_band" class="bandeau_categorie" />

				<?php
					// Saisie
					echo '<form method="post" action="petitspedestres/ajout_parcours.php" class="form_saisie_rapide">';
						echo '<input type="text" name="nom_parcours" value="' . $_SESSION['nom_parcours'] . '" placeholder="Nom du parcours" maxlength="255" class="name_saisie_rapide" required />';
						echo '<input type="text" name="distance" value="' . $_SESSION['distance'] . '" placeholder="Distance (km)" maxlength="10" class="date_saisie_rapide" />';
						echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
					echo '</form>';

					// Tableau des parcours
        	echo '<table class="table_movie_house">';
          	echo '<tr>';
              echo '<td class="init_table_dates" style="width: 120px;">Nom du parcours</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Distance</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Lieu</td>';
            echo '</tr>';

            $reponse = $bdd->query('SELECT * FROM petits_pedestres_parcours ORDER BY id ASC');

            while($donnees = $reponse->fetch())
            {
							// Instanciation d'un objet Parcours à partir des données remontées de la bdd
                $parcours = Parcours::withData($donnees);

                echo '<tr>';
                  echo '<td class="table_users">';
                    echo '<div>';
                      echo '<a href="petitspedestres/controleur/parcours.php?id=' . $parcours->getId() . '&action=consulter">'. $parcours->getNom() . '</a>';
                    echo '</div>';
                  echo '</td>';

									/*
									Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
									Réponse : Parcours.
									C'est nul ? Oui, c'est nul.
									*/

                  echo '<td class="table_users">';
                    echo '<div>';
                      echo $parcours->getDistance() . ' km';
                    echo '</div>';
                  echo '</td>';

                  echo '<td class="table_users">';
                    echo '<div>';
                      echo $parcours->getLieu();
                    echo '</div>';
                  echo '</td>';
                echo '</tr>';
            }

            $reponse->closeCursor();
          echo '</table>';
        ?>

      	</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

    </body>
</html>
