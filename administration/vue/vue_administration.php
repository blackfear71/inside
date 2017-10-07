<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

		<title>Inside - Administration</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../includes/images/administration_band.png" alt="administration_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<div class="new_menu_admin">
          <a href="infos_users.php?action=goConsulter" class="new_menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Infos
                <div class="saut_ligne">UTILISATEURS</div>
              </div>
            </div>
          </a>

					<a href="manage_users.php?action=goConsulter" class="new_menu_link_admin">
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

          <a href="manage_success.php?action=goConsulter" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">SUCCES
								</div>
							</div>
						</div>
					</a>

					<a href="manage_films.php?action=goConsulter" class="new_menu_link_admin">
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

          <a href="manage_calendars.php?action=goConsulter" class="new_menu_link_admin">
            <div class="menu_admin_box">
              <div class="mask_admin"></div>
              <div class="mask_admin_triangle"></div>
              <div class="title_admin">Gestion
                <div class="saut_ligne">CALENDARS
                  <?php
                    if ($alerteCalendars == true)
                      echo '( ! )';
                  ?>
                </div>
              </div>
            </div>
          </a>

					<a href="reports.php?view=unresolved&action=goConsulter" class="new_menu_link_admin">
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

					<a href="/phpmyadmin/" target="_blank" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">phpMyAdmin</div>
						</div>
					</a>

					<form method="post" action="export_bdd.php" class="new_menu_link_admin">
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
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
