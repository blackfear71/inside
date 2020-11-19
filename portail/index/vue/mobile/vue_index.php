<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = '';
      $styleHead      = 'styleIndex.css';
      $scriptHead     = 'scriptIndex.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

      include('includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php include('includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('includes/common/alerts.php'); ?>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="includes/icons/common/loading.png" alt="" id="loading_image" class="loading_image" />
      </div>

      <!-- Contenu -->
      <article>
        <?php
          // Formulaires
          echo '<div class="zone_forms_index">';
            echo '<div class="zone_form_index">';
              // Connexion
              if ($erreursIndex['erreurInscription'] == true OR $erreursIndex['erreurPassword'] == true)
                echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index" style="display: none;">';
              else
                echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index">';
                echo '<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" id="focus_identifiant" required />';
                echo '<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '<input type="submit" name="connect" value="CONNEXION" class="bouton_index" />';
              echo '</form>';

              // Inscription
              if ($erreursIndex['erreurInscription'] == true AND $erreursIndex['erreurPassword'] == false)
                echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index">';
              else
                echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index" style="display: none;">';
                echo '<input type="text" name="trigramme" value="' . $_SESSION['save']['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_2" required />';
                echo '<input type="text" name="pseudo" value="' . $_SESSION['save']['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne_index" required />';
                echo '<input type="password" name="password" value="' . $_SESSION['save']['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '<input type="password" name="confirm_password" value="' . $_SESSION['save']['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne_index" required />';
                echo '<input type="submit" name="ask_inscription" value="INSCRIPTION" class="bouton_index" />';
              echo '</form>';

              // Réinitialisation mot de passe
              if ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == true)
                echo '<form method="post" action="index.php?action=doDemanderMdp" id="formPassword" class="form_index">';
              else
                echo '<form method="post" action="index.php?action=doDemanderMdp" id="formPassword" class="form_index" style="display: none;">';
                echo '<input type="text" name="login" value="' . $_SESSION['save']['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_3" required />';
                echo '<input type="submit" name="ask_password" value="MOT DE PASSE" class="bouton_index" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';

          // Boutons
          echo '<div class="zone_boutons_index">';
            // Lien connexion
            if ($erreursIndex['erreurInscription'] == true OR $erreursIndex['erreurPassword'] == true)
              echo '<a id="afficherConnexion" class="lien_index lien_index_margin_right">Se connecter</a>';
            else
              echo '<a id="afficherConnexion" class="lien_index lien_index_margin_right" style="display: none;">Se connecter</a>';

            // Lien inscription
            if ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == false)
              echo '<a id="afficherInscription" class="lien_index lien_index_margin_right">Inscription</a>';
            else if ($erreursIndex['erreurInscription'] == true AND $erreursIndex['erreurPassword'] == false)
              echo '<a id="afficherInscription" class="lien_index" style="display: none;">Inscription</a>';
            else
              echo '<a id="afficherInscription" class="lien_index">Inscription</a>';

            // Lien mot de passe perdu
            if ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == true)
              echo '<a id="afficherPassword" class="lien_index" style="display: none;">Mot de passe oublié</a>';
            else
              echo '<a id="afficherPassword" class="lien_index">Mot de passe oublié</a>';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
