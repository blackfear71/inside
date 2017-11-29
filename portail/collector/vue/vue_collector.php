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
    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptCO.js"></script>

		<title>Inside - CO</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Collector Room";

        include('../../includes/header.php');
			  include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <?php
          // Saisie phrase culte
          echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" class="form_saisie_collector">';
            echo '<table class="table_saisie_collector">';
              echo '<tr>';
                // Saisie speaker
                if (!empty($_SESSION['other_speaker']))
                  echo '<td class="td_saisie_collector_user" id="td_other" style="width: 20%;">';
                else
                  echo '<td class="td_saisie_collector_user" id="td_other">';
                    echo '<select name="speaker" id="speaker" onchange="afficherOther(\'td_other\', \'speaker\', \'other_speaker\', \'other_name\');" class="saisie_speaker" required>';
                      echo '<option value="" hidden>Choisissez...</option>';

                      foreach ($listeUsers as $user)
                      {
                        if ($user->getIdentifiant() == $_SESSION['speaker'])
                          echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                        else
                          echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                      }

                      if (!empty($_SESSION['other_speaker']))
                        echo '<option value="other" selected>Autre</option>';
                      else
                        echo '<option value="other">Autre</option>';
                  echo '</select>';
                echo '</td>';

                // Saisie "Autre"
                if (!empty($_SESSION['other_speaker']))
                  echo '<td class="td_saisie_collector_name" id="other_speaker">';
                else
                  echo '<td class="td_saisie_collector_name" id="other_speaker" style="display: none;">';
                    echo '<input type="text" name="other_speaker" value="' . $_SESSION['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" />';
                echo '</td>';

                // Saisie date
                echo '<td class="td_saisie_collector_date">';
                  echo '<input type="text" name="date_collector" value="' . $_SESSION['date_collector'] . '" placeholder="Date" maxlength="10" id="datepickerSaisie" class="saisie_date_collector" required />';
                echo '</td>';

                // Bouton
                echo '<td class="td_saisie_collector_add">';
                  echo '<input type="submit" name="insert_collector" value="Ajouter" class="saisie_bouton" />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Saisie phrase
                echo '<td colspan="100%" class="td_saisie_collector">';
                  echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['collector'] . '</textarea>';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Saisie contexte
                echo '<td colspan="100%" class="td_saisie_collector_cont">';
                  echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['context'] . '</textarea>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
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
