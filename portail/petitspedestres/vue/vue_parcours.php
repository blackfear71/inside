<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="stylePP.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/header.php');
        include('../../includes/onglets.php');
      ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect       = true;
					$profil_user      = true;
					$ajouter_parcours = true;
					$modify_parcours  = true;
					$back             = true;
					$ideas            = true;
					$reports          = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
				<div class="PP-parcours">
          <div class="PP-titre">
            <?php echo $parcours->getNom(); ?>
          </div>

          <p>
            Distance : <?php echo $parcours->getDistance() . ' km'; ?><br/>
            Lieu : <?php echo $parcours->getLieu(); ?>

            <?php
              if ($parcours->isImageSet())
              {
                echo '<br/><img src="' . $parcours->getImage() .'" alt="' . $parcours->getNom() . ' classe="PP-image" /><br/>';
              }
            ?>
          </p>
        </div>
      </article>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
