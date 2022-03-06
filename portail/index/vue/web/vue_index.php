<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = '';
      $styleHead       = 'styleIndex.css';
      $scriptHead      = 'scriptIndex.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        include('includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section class="section_no_nav">
      <!-- Messages d'alerte -->
      <?php include('includes/common/alerts.php'); ?>

			<article>
        <?php
          // Message d'aide inscription
          echo '<div class="fond_alerte">';
            echo '<div id="aideInscription" class="zone_affichage_alerte">';
              // Titre
              echo '<div class="zone_titre_alerte">';
                echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" class="image_alerte" />';
                echo '<div class="titre_alerte">Pour votre inscription</div>';
              echo '</div>';

              // Affichage du message
              echo '<div class="texte_aide_index">';
                echo 'Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo, votre équipe ainsi qu\'un mot de passe.
                Celui-ci sera directement crypté afin de garantir la sécurité de l\'accès. Vous pouvez également suggérer la création d\'une nouvelle équipe si celle que vous
                souhaitez intégrer n\'est pas disponible. Une demande sera ensuite envoyée à l\'administrateur qui validera votre inscription dans les plus brefs délais.';
              echo '</div>';

              // Bouton
              echo '<a id="fermerAideInscription" class="bouton_alerte">Fermer</a>';
            echo '</div>';
          echo '</div>';

          // Message d'aide changement de mot de passe
          echo '<div class="fond_alerte">';
            echo '<div id="aidePassword" class="zone_affichage_alerte">';
              // Titre
              echo '<div class="zone_titre_alerte">';
                echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" class="image_alerte" />';
                echo '<div class="titre_alerte">Réinitialiser un mot de passe</div>';
              echo '</div>';

              // Affichage du message
              echo '<div class="texte_aide_index">';
                echo 'Si vous avez perdu votre mot de passe, vous pouvez effectuer une demande de réinitialisation du mot de passe à l\'administrateur via le formulaire ci-dessous.
                L\'administrateur est suceptible de vous contacter directement afin de vérifier votre demande. Il vous suffit de renseigner votre identifiant afin que celui-ci
                puisse procéder à la création d\'un nouveau mot de passe qu\'il vous communiquera par la suite.';
              echo '</div>';

              // Bouton
              echo '<a id="fermerAidePassword" class="bouton_alerte">Fermer</a>';
            echo '</div>';
          echo '</div>';

          // Formulaires
          echo '<div class="zone_index">';
            // Connexion
            if ($erreursIndex['erreurInscription'] == true OR $erreursIndex['erreurPassword'] == true)
              echo '<div id="formConnexion" class="zone_form_index" style="display: none;">';
            else
              echo '<div id="formConnexion" class="zone_form_index">';
              // Logo
              echo '<div class="zone_logo_index">';
                echo '<img src="includes/icons/common/profile.png" alt="profile" class="logo_index" />';
              echo '</div>';

              // Formulaire
              echo '<form method="post" action="index.php?action=doConnecter" class="form_index">';
                // Données utilisateur
                echo '<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" id="focus_identifiant" required />';
                echo '<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';

                // Boutons
                echo '<div class="zone_boutons_index">';
                  echo '<input type="submit" name="connect" value="CONNEXION" class="bouton_index" />';
                echo '</div>';
              echo '</form>';
            echo '</div>';

            // Inscription
            if ($erreursIndex['erreurInscription'] == true AND $erreursIndex['erreurPassword'] == false)
              echo '<div id="formInscription" class="zone_form_index">';
            else
              echo '<div id="formInscription" class="zone_form_index" style="display: none;">';
              // Logo
              echo '<div class="zone_logo_index">';
                echo '<img src="includes/icons/index/users.png" alt="users" class="logo_index" />';
              echo '</div>';

              // Formulaire
              echo '<form method="post" action="index.php?action=doDemanderInscription" class="form_index_large">';
                // Données utilisateur
                echo '<div class="zone_form_index_left">';
                  echo '<input type="text" name="trigramme" value="' . $_SESSION['save']['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_2" required />';
                  echo '<input type="text" name="pseudo" value="' . $_SESSION['save']['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne_index" required />';

                  // Choix de l'équipe
                  echo '<select name="equipe" class="select_form_index" required>';
                    echo '<option value="" hidden>Choisir une équipe</option>';

                    foreach ($listeEquipes as $equipe)
                    {
                      if ($_SESSION['save']['equipe_saisie'] == $equipe->getReference())
                        echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
                      else
                        echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
                    }

                    if ($_SESSION['save']['equipe_saisie'] == 'other')
                      echo '<option value="other" selected>Créer une équipe</option>';
                    else
                      echo '<option value="other">Créer une équipe</option>';
                  echo '</select>';

                  // Saisie "Autre"
                  if ($_SESSION['save']['equipe_saisie'] == 'other')
                    echo '<input type="text" name="autre_equipe" value="' . $_SESSION['save']['autre_equipe'] . '" placeholder="Nom de l\'équipe" id="autre_equipe" class="monoligne_index_equipe" />';
                  else
                    echo '<input type="text" name="autre_equipe" value="' . $_SESSION['save']['autre_equipe'] . '" placeholder="Nom de l\'équipe" id="autre_equipe" class="monoligne_index_equipe" style="display: none;" />';
                echo '</div>';

                // Mot de passe
                echo '<div class="zone_form_index_right">';
                  echo '<input type="password" name="password" value="' . $_SESSION['save']['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                  echo '<input type="password" name="confirm_password" value="' . $_SESSION['save']['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '</div>';

                // Boutons
                echo '<div class="zone_boutons_index">';
                  echo '<input type="submit" name="ask_inscription" value="INSCRIPTION" class="bouton_index" />';
                  echo '<a id="afficherAideInscription" class="lien_bouton_index">';
                    echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" title="Aide" class="image_bouton_index" />';
                  echo '</a>';
                echo '</div>';
              echo '</form>';
            echo '</div>';

            // Réinitialisation mot de passe
            if ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == true)
              echo '<div id="formPassword" class="zone_form_index">';
            else
              echo '<div id="formPassword" class="zone_form_index" style="display: none;">';
              // Logo
              echo '<div class="zone_logo_index">';
                echo '<img src="includes/icons/index/lock.png" alt="lock" class="logo_index margin_top_moins_20" />';
              echo '</div>';

              // Formulaire
              echo '<form method="post" action="index.php?action=doDemanderMdp" class="form_index">';
                // Données utilisateur
                echo '<input type="text" name="login" value="' . $_SESSION['save']['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_3" required />';

                // Boutons
                echo '<div class="zone_boutons_index">';
                  echo '<input type="submit" name="ask_password" value="MOT DE PASSE" class="bouton_index" />';
                  echo '<a id="afficherAidePassword" class="lien_bouton_index">';
                    echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" title="Aide" class="image_bouton_index" />';
                  echo '</a>';
                echo '</div>';
              echo '</form>';
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
