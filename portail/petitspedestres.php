<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	include('../includes/init_session.php');

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
  <title>Inside CGI - PP</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
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

        <form method="post" action="petitspedestres/ajout_parcours.php" class="form_saisie_rapide">
					<input type="text" name="nom_parcours" value="<?php echo $_SESSION['nom_parcours'];?>" placeholder="Nom du parcours" maxlength="255" class="name_saisie_rapide" required />
            <input type="text" name="distance" value="<?php echo $_SESSION['distance'];?>" placeholder="Distance (km)" maxlength="10"  class="date_saisie_rapide" />
					<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />
				</form>

        <?php
        	echo '<table class="table_movie_house">';
          	echo '<tr>';
              echo '<td class="init_table_dates" style="width: 120px;">Nom du parcours</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Distance</td>';
              echo '<td class="init_table_dates" style="width: 120px;">Lieu</td>';
            echo '</tr>';

            $reponse = $bdd->query('SELECT * FROM petits_pedestres_parcours ORDER BY id ASC');

            while($donnees = $reponse->fetch())
            {
                $parcours = new Parcours($donnees);

                echo '<tr>';
                  echo '<td class="table_users">';
                    echo '<div>';
                      echo $parcours->getNom();
                    echo '</div>';
                  echo '</td>';

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
