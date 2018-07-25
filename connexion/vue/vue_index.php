<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
		<link rel="stylesheet" href="/inside/connexion/styleIndex.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside</title>
  </head>

	<body class="page_index">
    <section class="section_index">
      <!-- Messages d'alerte -->
      <?php
        include('includes/alerts.php');
      ?>

      <article class="article_index">
        <div class="left_index">
          <img src="includes/icons/inside_index.png" alt="inside_index" class="logo_index" />

          <div class="categories_index">
            <span class="zone_logo_categories" id="logo1">
              <img src="includes/icons/movie_house.png" alt="movie_house_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo2">
              <img src="includes/icons/expense_center.png" alt="expense_center_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo3">
              <img src="includes/icons/petits_pedestres.png" alt="petits_pedestres_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo4">
              <img src="includes/icons/calendars.png" alt="calendars_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo5">
              <img src="includes/icons/collector.png" alt="collector_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo6">
              <img src="includes/icons/missions.png" alt="missions_grey" class="logo_categories" />
            </span>
            <span class="zone_logo_categories" id="logo7">
              <img src="includes/icons/event_manager.png" alt="event_manager_grey" class="logo_categories" />
            </span>
          </div>
        </div>

        <div class="right_index">
          <div class="bandeau_index">
            <!-- Connexion -->
            <form method="post" action="index.php?action=doConnecter" class="form_index">
              <input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" required />
              <input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />
              <input type="submit" name="connect" value="CONNEXION" class="bouton_index" />
            </form>

            <div class="rift_index" style="width: 2px;"></div>
          </div>
        </div>

        <?php
          if ($error_inscription == true)
            echo '<div id="inscription" class="zone_ins_pass" style="display: block;">';
          else
            echo '<div id="inscription" class="zone_ins_pass" style="display: block;">';
        ?>
          <div class="bandeau_index" style="width: 100%;">
            <!-- Inscription -->
            <?php
              echo '<form method="post" action="index.php?action=doDemanderInscription" class="form_index">';
                echo '<a onclick="masquerIndex(\'inscription\')" class="close_index"><img src="includes/icons/close.png" alt="close" title="Fermer" class="close_img" /></a>';

                echo '<div class="avertissement_ins_pass">';
                  echo 'Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo ainsi qu\'un mot de passe.
                  Celui-ci sera directement crypté afin de garantir la sécurité de l\'accès. Une demande sera envoyée à l\'administrateur qui validera
                  votre inscription dans les plus brefs délais.';
                echo '</div>';

                echo '<input type="text" name="trigramme" value="' . $_SESSION['index']['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" required />';
                echo '<input type="text" name="pseudo" value="' . $_SESSION['index']['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne_index" required />';
                echo '<input type="password" name="password" value="' . $_SESSION['index']['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '<input type="password" name="confirm_password" value="' . $_SESSION['index']['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '<input type="submit" name="ask_inscription" value="SOUMETTRE" class="bouton_index" />';
              echo '</form>';
            ?>

            <div class="rift_index"></div>
          </div>
        </div>

        <?php
          if ($error_password == true)
            echo '<div id="password" class="zone_ins_pass" style="display: block; animation: none;">';
          else
            echo '<div id="password" class="zone_ins_pass" style="display: block;">';
        ?>

          <div class="bandeau_index" style="width: 100%;">
            <!-- Réinitialisation mot de passe -->
            <?php
              echo '<form method="post" action="index.php?action=doDemanderMdp" class="form_index">';
                echo '<a onclick="masquerIndex(\'password\')" class="close_index"><img src="includes/icons/close.png" alt="close" title="Fermer" class="close_img" /></a>';

                echo '<div class="avertissement_ins_pass">';
                  echo 'Si vous avez perdu votre mot de passe, vous pouvez effectuer une demande de réinitialisation du mot de passe à l\'administrateur via le formulaire ci-dessous.
        					L\'administrateur est suceptible de vous contacter directement afin de vérifier votre demande. Il vous suffit de renseigner votre identifiant afin que celui-ci
        					puisse procéder à la création d\'un nouveau mot de passe qu\'il vous communiquera par la suite.';
                echo '</div>';

                echo '<input type="text" name="login" value="' . $_SESSION['index']['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" required />';
    						echo '<input type="submit" name="ask_password" value="SOUMETTRE" class="bouton_index" />';
              echo '</form>';
            ?>

            <div class="rift_index"></div>
          </div>
        </div>
      </article>
    </section>

    <footer class="footer_index">
      <!-- Lien inscription -->
      <a onclick="afficherIndex('inscription', 'password');" class="link_index">
        S'inscrire
      </a>

      <!-- Lien mot de passe perdu -->
      <a onclick="afficherIndex('password', 'inscription');" class="link_index">
        Mot de passe oublié ?
      </a>

      <div class="copyright_index">© 2017 Inside</div>
    </footer>
  </body>

  <script>
    showChat = true;
  </script>
</html>
