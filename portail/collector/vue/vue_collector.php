<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="styleCO.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="scriptCO.js"></script>

		<title>Inside - CO</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<!-- Bandeau catégorie -->
				<img src="../../includes/images/collector_band.png" alt="collector_band" class="bandeau_categorie" />

        <?php
          // Saisie phrase culte
          echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" class="form_saisie_collector">';
            // Saisie speaker
            echo '<select name="speaker" class="saisie_speaker" required>';
              echo '<option value="" hidden>Choisissez...</option>';

              foreach ($listeUsers as $user)
              {
                if ($user->getIdentifiant() == $_SESSION['speaker'])
                  echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                else
                  echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
              }
            echo '</select>';

            // Saisie date
            echo '<input type="text" name="date_collector" value="' . $_SESSION['date_collector'] . '" placeholder="Date" maxlength="10" id="datepicker" class="saisie_date_collector" required />';

            // Bouton
            echo '<input type="submit" name="insert_collector" value="Ajouter" title="Ajouter" class="saisie_bouton" />';

            // Saisie phrase
            echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['collector'] . '</textarea>';
          echo '</form>';

          // Affichage des phrases cultes
          include('vue/table_collectors.php');

          // Pagination
          if ($nbPages > 1)
          {
            echo '<div class="zone_pagination">';
              for($i = 1; $i <= $nbPages; $i++)
              {
                if($i == $_GET['page'])
                  echo '<div class="numero_page_active">' . $i . '</div>';
                else
                {
                  echo '<div class="numero_page_inactive">';
                    echo '<a href="collector.php?action=goConsulter&page=' . $i . '" class="lien_pagination">' . $i . '</a>';
                  echo '</div>';
                }
              }
            echo '</div>';
          }
        ?>
      </article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
