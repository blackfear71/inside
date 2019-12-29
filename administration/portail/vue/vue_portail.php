<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Administration";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Administration";

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';
        ?>

				<div class="menu_admin">
          <!-- Informations utilisateurs -->
          <a href="../infosusers/infosusers.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Infos
                <div class="saut_ligne">UTILISATEURS</div>
              </div>
            </div>
          </a>

          <!-- Gestion utilisateurs -->
					<a href="manage_users.php?action=goConsulter" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">UTILISATEURS
  								<?php
                    if ($alerteUsers == true)
                      echo '( ! )';
  								?>
								</div>
							</div>
						</div>
					</a>

          <!-- Gestion thèmes -->
          <a href="../themes/themes.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">THÈMES
                </div>
              </div>
            </div>
          </a>

          <!-- Gestion succès -->
          <a href="manage_success.php?action=goConsulter" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">SUCCÈS
								</div>
							</div>
						</div>
					</a>

          <!-- Gestion films -->
					<a href="../movies/movies.php?action=goConsulter" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">MOVIE<br />HOUSE
                  <?php
                    if ($alerteFilms == true)
                      echo '( ! )';
                  ?>
								</div>
							</div>
						</div>
					</a>

          <!-- Gestion calendriers -->
          <a href="../calendars/calendars.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">CALENDARS
                  <?php
                    if ($alerteCalendars == true OR $alerteAnnexes == true)
                      echo '( ! )';
                  ?>
                </div>
              </div>
            </div>
          </a>

          <!-- Gestion missions -->
          <a href="manage_missions.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">MISSIONS
                </div>
              </div>
            </div>
          </a>

          <!-- Gestion bugs/évolutions -->
					<a href="../reports/reports.php?view=unresolved&action=goConsulter" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Rapports
								<div class="saut_ligne">
									BUGS
  								<?php
  									echo ' (' . $nbBugs . ')';
  								?>
									<br />EVOLUTIONS
									<?php
										echo ' (' . $nbEvols . ')';
									?>
								</div>
							</div>
						</div>
					</a>

          <!-- Gestion alertes -->
          <a href="../alerts/alerts.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">
                  ALERTES
                </div>
              </div>
            </div>
          </a>

          <!-- Gestion CRON -->
          <a href="../cron/cron.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">
                  CRON
                </div>
              </div>
            </div>
          </a>

          <!-- Générateur de code -->
          <a href="../codegenerator/codegenerator.php?action=goConsulter" class="menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Générateur
                <div class="saut_ligne">
                  de code
                </div>
              </div>
            </div>
          </a>

          <!-- Accès phpMyAdmin -->
					<a href="/phpmyadmin/" target="_blank" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">phpMyAdmin</div>
						</div>
					</a>

          <!-- Sauvegarde BDD -->
					<form method="post" action="../../includes/functions/export_bdd.php" class="menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<input type="submit" name="export" value="Sauvegarde" class="export_bdd" />
							<div class="title_admin">
								<div class="saut_ligne" style="margin-top: 65px;">BDD</div>
							</div>
						</div>
					</form>
				</div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
