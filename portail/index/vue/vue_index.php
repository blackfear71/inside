<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = '';
      $style_head      = 'styleIndex.css';
      $script_head     = 'scriptIndex.js';
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Contenu -->
    <section class="section_index">
      <!-- Messages d'alerte -->
      <?php include('includes/common/alerts.php'); ?>

      <article class="article_index">
        <?php
          echo '<div class="zone_index">';
            echo '<div class="zone_index_left">';
              // Logo Inside
              if ($error_inscription == true OR $error_password == true)
                echo '<div id="logo" class="zone_logo_index" style="display: none;">';
              else
                echo '<div id="logo" class="zone_logo_index">';
                echo '<img src="includes/icons/common/inside_index.png" alt="inside_index" class="logo_index" />';
              echo '</div>';

              // Texte inscription
              if ($error_inscription == true)
                echo '<div id="texteInscription" class="zone_texte_index">';
              else
                echo '<div id="texteInscription" class="zone_texte_index" style="display: none;">';
                echo '<div class="texte_index">';
                  echo 'Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo ainsi qu\'un mot de passe.
                  Celui-ci sera directement crypté afin de garantir la sécurité de l\'accès. Une demande sera envoyée à l\'administrateur qui validera
                  votre inscription dans les plus brefs délais.';
                echo '</div>';
              echo '</div>';

              // Texte réinitialisation mot de passe
              if ($error_password == true)
                echo '<div id="textePassword" class="zone_texte_index">';
              else
                echo '<div id="textePassword" class="zone_texte_index" style="display: none;">';
                echo '<div class="texte_index">';
                  echo 'Si vous avez perdu votre mot de passe, vous pouvez effectuer une demande de réinitialisation du mot de passe à l\'administrateur via le formulaire ci-dessous.
                  L\'administrateur est suceptible de vous contacter directement afin de vérifier votre demande. Il vous suffit de renseigner votre identifiant afin que celui-ci
                  puisse procéder à la création d\'un nouveau mot de passe qu\'il vous communiquera par la suite.';
                echo '</div>';
              echo '</div>';

              // Logos catégories
              $icons = array("movie_house",
                             "food_advisor",
                             "cooking_box",
                             "expense_center",
                             "collector",
                             "calendars",
                             //event_manager,
                             "petits_pedestres",
                             "missions"
                            );

              echo '<div class="zone_logos">';
                foreach ($icons as $icon)
                {
                  echo '<span class="zone_logo_categories">';
                    echo '<img src="includes/icons/common/' . $icon . '.png" alt="' . $icon . '_grey" class="logo_categories" />';
                  echo '</span>';
                }
              echo '</div>';
            echo '</div>';

            echo '<div class="zone_index_right">';
              // Formulaires
              echo '<div class="zone_form_index">';
                // Connexion
                if ($error_inscription == true OR $error_password == true)
                  echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index" style="display: none;">';
                else
                  echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index">';
                  echo '<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" id="focus_identifiant" required />';
                  echo '<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                  echo '<input type="submit" name="connect" value="CONNEXION" class="bouton_index" />';
                echo '</form>';

                // Inscription
                if ($error_inscription == true)
                  echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index">';
                else
                  echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index" style="display: none;">';
                  echo '<input type="text" name="trigramme" value="' . $_SESSION['index']['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_2" required />';
                  echo '<input type="text" name="pseudo" value="' . $_SESSION['index']['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne_index" required />';
                  echo '<input type="password" name="password" value="' . $_SESSION['index']['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                  echo '<input type="password" name="confirm_password" value="' . $_SESSION['index']['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne_index" required />';
                  echo '<input type="submit" name="ask_inscription" value="SOUMETTRE" class="bouton_index" />';
                echo '</form>';

                // Réinitialisation mot de passe
                if ($error_password == true)
                  echo '<form method="post" action="index.php?action=doDemanderMdp" id="formPassword" class="form_index">';
                else
                  echo '<form method="post" action="index.php?action=doDemanderMdp" id="formPassword" class="form_index" style="display: none;">';
                  echo '<input type="text" name="login" value="' . $_SESSION['index']['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_3" required />';
                  echo '<input type="submit" name="ask_password" value="SOUMETTRE" class="bouton_index" />';
                echo '</form>';
              echo '</div>';

              // Boutons
              echo '<div class="zone_boutons_index">';
                // Lien connexion
                if ($error_inscription == true OR $error_password == true)
                  echo '<a id="afficherConnexion" class="lien_index">Se connecter</a>';
                else
                  echo '<a id="afficherConnexion" class="lien_index" style="display: none;">Se connecter</a>';

                // Lien inscription
                if ($error_inscription == true)
                  echo '<a id="afficherInscription" class="lien_index" style="display: none;">S\'inscrire</a>';
                else
                  echo '<a id="afficherInscription" class="lien_index">S\'inscrire</a>';

                // Lien mot de passe perdu
                if ($error_password == true)
                  echo '<a id="afficherPassword" class="lien_index" style="display: none;">Mot de passe oublié ?</a>';
                else
                  echo '<a id="afficherPassword" class="lien_index">Mot de passe oublié ?</a>';
              echo '</div>';
            echo '</div>';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
