<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Administrateur";
      $style_head      = "styleProfil.css";
      $script_head     = "scriptProfil.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = true;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Administrateur";

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Contenu */
          /***********/
          echo '<div class="zone_profil_admin">';
            echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Mes informations</div></div>';

            // Avatar actuel & suppression
            echo '<div class="zone_profil_avatar_parametres">';
              $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, "avatar");

              echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil" />';

              echo '<div class="texte_parametres">Avatar actuel</div>';

              echo '<form method="post" action="profil.php?action=doSupprimerAvatar" enctype="multipart/form-data">';
                echo '<input type="submit" name="delete_avatar" value="Supprimer" class="bouton_validation" />';
              echo '</form>';
            echo '</div>';

            // Modification avatar
            echo '<form method="post" action="profil.php?action=doModifierAvatar" enctype="multipart/form-data" class="form_update_avatar">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

              echo '<span class="zone_parcourir_avatar">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_avatar loadAvatar" required /></span>';

              echo '<div class="mask_avatar">';
                echo '<img id="avatar" alt="" class="avatar_update_profil" />';
              echo '</div>';

              echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" class="bouton_validation" />';
            echo '</form>';

            // Mise à jour informations
            echo '<form method="post" action="profil.php?action=doUpdateInfos" class="form_update_infos">';
              // Pseudo
              echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_parametres" />';
              echo '<input type="text" name="pseudo" placeholder="Pseudo" value="' . $profil->getPseudo() . '" maxlength="255" class="monoligne_saisie" />';

              echo '<input type="submit" name="saisie_pseudo" value="Mettre à jour" class="bouton_validation" />';
            echo '</form>';
          echo '</div>';

          // Mot de passe
          echo '<div class="zone_profil_bottom">';
            echo '<div class="titre_section"><img src="../../includes/icons/profil/connexion_grey.png" alt="connexion_grey" class="logo_titre_section" /><div class="texte_titre_section">Administrateur</div></div>';

            echo '<div class="zone_action_user">';
              echo '<div class="titre_contribution">CHANGER MOT DE PASSE</div>';

              // Modification mot de passe
              echo '<form method="post" action="profil.php?action=doUpdatePassword">';
                echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_saisie" required />';
                echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';
                echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';

                echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_validation" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>