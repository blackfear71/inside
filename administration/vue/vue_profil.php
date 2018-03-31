<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="/inside/profil/styleProfil.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="/inside/profil/scriptProfil.js"></script>

		<title>Inside - Administrateur</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Administrateur";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article>
        <!-- Bloc utilisateur 1 -->
        <div class="zone_profil_utilisateur" style="margin-top: -30px;">
          <!-- Affichage pseudo -->
          <div class="zone_profil_utilisateur_titre">
            <?php
              echo '<img src="../profil/icons/profile.png" alt="profile" class="icone_profil" />' . $profil->getPseudo();
            ?>
          </div>

          <!-- Tableau modification pseudo & avatar -->
          <table class="zone_profil_utilisateur_table">
            <tr>
              <!-- Saisie pseudo -->
              <td class="zone_profil_utilisateur_pseudo">
                <?php
                  echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangePseudo" class="zone_profil_utilisateur_pseudo_form">';
                    echo '<input type="text" name="new_pseudo" placeholder="Nouveau pseudo" maxlength="255" class="monoligne_profil" required />';
                    echo '<input type="submit" name="saisie_pseudo" value="Valider" class="bouton_profil" />';
                  echo '</form>';
                ?>
              </td>

              <!-- Saisie avatar -->
              <td class="zone_profil_utilisateur_avatar">
                <div class="zone_avatar">
                  <?php
                    echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangeAvatar" enctype="multipart/form-data" runat="server">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                      echo '<span class="zone_parcourir_avatar">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_avatar" onchange="loadFile(event)" required /></span>';

                      echo '<div class="mask_avatar">';
                        echo '<img id="output" class="avatar_profil" />';
                      echo '</div>';

                      echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" class="bouton_profil" />';
                    echo '</form>';
                  ?>
                </div>
              </td>

              <!-- Suppression avatar -->
              <td class="zone_profil_utilisateur_suppr">
                <?php
                  // Affichage avatar
                  if (!empty($profil->getAvatar()))
                  {
                    echo '<div class="zone_profil_utilisateur_suppr_mask">';
                      echo '<img src="../profil/avatars/' . $profil->getAvatar() . '" alt="avatar" title="' . $profil->getPseudo() . '" class="zone_profil_utilisateur_suppr_avatar" />';
                    echo '</div>';

                    echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doSupprimerAvatar" enctype="multipart/form-data" runat="server">';
                      echo '<input type="submit" name="delete_avatar" value="Supprimer l\'avatar" class="bouton_profil" />';
                    echo '</form>';
                  }
                  else
                  {
                    echo '<div class="zone_profil_utilisateur_suppr_mask">';
                      echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $profil->getPseudo() . '" class="zone_profil_utilisateur_suppr_avatar" />';
                    echo '</div>';
                  }
                ?>
              </td>
            </tr>
          </table>
        </div>

        <!-- Bloc utilisateur 2 -->
        <div class="zone_profil_generique">
          <!-- Titre -->
          <div class="zone_profil_utilisateur_titre">
            <img src="../profil/icons/connexion.png" alt="connexion" class="icone_profil" />Utilisateur
          </div>

          <!-- Tableau modification mot de passe & désinscription -->
          <table class="zone_profil_utilisateur_table">
            <tr>
              <!-- Saisie mot de passe -->
              <td class="zone_profil_utilisateur_mdp">
                <?php
                  echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangeMdp" class="zone_profil_utilisateur_pseudo_form">';
                    echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_profil" required />';
                    echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_profil" required />';
                    echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_profil" required />';
                    echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_profil" />';
                  echo '</form>';
                ?>
              </td>
            </tr>
          </table>
        </div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
