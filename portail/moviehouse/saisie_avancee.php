<?php
	// Contrôles communs Utilisateurs
	include('../../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	include('../../includes/init_session.php');

	// Fonctions
	include('../../includes/fonctions_dates.php');

	// Initialisation sauvegarde saisie
	if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] != true)
	{
		$_SESSION['nom_film_saisi'] = "";
		$_SESSION['date_theater_saisie'] = "";
		$_SESSION['date_release_saisie'] = "";
		$_SESSION['trailer_saisi'] = "";
		$_SESSION['link_saisi'] = "";
		$_SESSION['poster_saisi'] = "";
		$_SESSION['doodle_saisi'] = "";
		$_SESSION['date_doodle_saisie'] = "";
		$_SESSION['hours_doodle_saisies'] = "";
		$_SESSION['minutes_doodle_saisies'] = "";
		$_SESSION['restaurant_saisi'] = "";
		$_SESSION['place_saisie'] = "";
		$wrong_date = false;
	}

	// Récupération top si erreur date (écrasé par les alertes sinon)
	if (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
		$wrong_date = true;

	// Contrôle film non à supprimer
	if (isset($_GET['modify_id']))
	{
		include('../../includes/appel_bdd.php');

		$reponse = $bdd->query('SELECT id, to_delete FROM movie_house WHERE id = ' . $_GET['modify_id']);
		$donnees = $reponse->fetch();

		if ($donnees['to_delete'] == "Y")
			header('location: ../moviehouse.php?view=main&year=' . date("Y"));

		$reponse->closeCursor();
	}

	// Contrôle film existant
	if (isset($_GET['modify_id']))
	{
		$reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $_GET['modify_id']);
		$donnees = $reponse->fetch();

		if ($reponse->rowCount() == 0)
			$_SESSION['doesnt_exist'] = true;

		$reponse->closeCursor();
	}
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../../favicon.png" />
	<link rel="stylesheet" href="../../style.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<title>Inside - MH</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<!-- A remplacer par un logo -->
				<!--<div class="bandeau_titre_article">
					<?php
						/*if (!isset($_GET['modify_id']))
							echo '<div class="previs_article">Ajout avancé de film</div>';
						else
							echo '<div class="previs_article">Modification de film</div>';*/
					?>
				</div>-->

				<div class="zone_saisie_avancee">
					<div class="titre_saisie_avancee">
						<?php
							if (!isset($_GET['modify_id']))
								echo 'Ajout avancé de film';
							else
								echo 'Modification de film';
						?>
					</div>

					<div class="contenu_saisie_avancee">
						<?php
							// On affiche le tableau avec les données de la base (MODIFICATION)
							if (isset($_GET['modify_id']) AND !empty($_GET['modify_id']))
							{
								include('../../includes/appel_bdd.php');

								$reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $_GET['modify_id']);
								$donnees = $reponse->fetch();

								// On formate les dates
								$date_theater = "";
								$date_release = "";
								$date_doodle = "";

								if (!empty($donnees['date_theater']))
									$date_theater = formatDateForDisplay($donnees['date_theater']);

								if (!empty($donnees['date_release']))
									$date_release = formatDateForDisplay($donnees['date_release']);

								if (!empty($donnees['date_doodle']))
									$date_doodle = formatDateForDisplay($donnees['date_doodle']);

								// On réinsère les données qu'on vient de saisir en cas de mauvaise saisie
								if ($wrong_date == true)
								{
									echo '<form method="post" action="actions_films.php?modify_id=' . $_GET['modify_id'] . '" class="form_saisie_avancee">';

										echo '<div class="zone_saisie_avancee_infos">';
											echo '<div class="sous_titre_saisie_avancee">';
												echo 'Informations sur le film';
											echo '</div>';

											// Titre du film
											echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';

											// Date de sortie cinéma
											//SMI - déb
											if (isBlankDate($donnees['date_theater']))
												echo '<input type="text" name="date_theater" value="" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
											else
												echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
											//SMI - fin

											// Date de sortie DVD
											echo '<input type="text" name="date_release" value="' . $_SESSION['date_release_saisie']  . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="monoligne_film" />';

											// Lien trailer
											echo '<input type="text" name="trailer" value="' . $_SESSION['trailer_saisi'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';

											// Lien fiche
											echo '<input type="text" name="link" value="' . $_SESSION['link_saisi'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';

											// Lien poster
											echo '<input type="text" name="poster" value="' . $_SESSION['poster_saisi'] . '" placeholder="URL poster" class="monoligne_film" style="margin-bottom: 0px;" />';
										echo '</div>';

										echo '<div class="zone_saisie_avancee_orga">';
											echo '<div class="sous_titre_saisie_avancee">';
												echo 'Organisation sortie';
											echo '</div>';

											// Lien Doodle
											echo '<input type="text" name="doodle" value="' . $_SESSION['doodle_saisi'] . '" placeholder="Doodle" class="monoligne_film" />';

											// Date sortie
											echo '<input type="text" name="date_doodle" value="' . $_SESSION['date_doodle_saisie'] . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" id="datepicker3" class="monoligne_film_short" />';

											// Selection de l'heure
											echo '<select name="hours_doodle" class="select_time">';
												if (isset($_SESSION['hours_doodle_saisies']) AND empty($_SESSION['hours_doodle_saisies']))
													echo '<option value="" disabled selected hidden>hh</option>';
												else
													echo '<option value="" disabled hidden>hh</option>';

												for ($i = 0; $i <= 23; $i++)
												{
													if (isset($_SESSION['hours_doodle_saisies']) AND !empty($_SESSION['hours_doodle_saisies']) AND $_SESSION['hours_doodle_saisies'] == $i)
													{
														if ($i < 10)
															echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
														else
															echo '<option value="' . $i . '" selected>' . $i . '</option>';
													}
													else
													{
														if ($i < 10)
															echo '<option value="0' . $i . '">0' . $i . '</option>';
														else
															echo '<option value="' . $i . '">' . $i . '</option>';
													}
												}
											echo '</select>';

											// Selection des minutes
											echo '<select name="minutes_doodle" class="select_time">';
												if (isset($_SESSION['minutes_doodle_saisies']) AND empty($_SESSION['minutes_doodle_saisies']))
													echo '<option value="" disabled selected hidden>mm</option>';
												else
													echo '<option value="" disabled hidden>mm</option>';

												for ($i = 0; $i <= 11; $i++)
												{
													if (isset($_SESSION['minutes_doodle_saisies']) AND !empty($_SESSION['minutes_doodle_saisies']) AND ($_SESSION['minutes_doodle_saisies'] / 5) == $i)
													{
														if ($i < 2)
															echo '<option value="0' . 5*$i . '" selected>0' . 5*$i . '</option>';
														else
															echo '<option value="' . 5*$i . '" selected>' . 5*$i . '</option>';
													}
													else
													{
														if ($i < 2)
															echo '<option value="0' . 5*$i . '">0' . 5*$i . '</option>';
														else
															echo '<option value="' . 5*$i . '">' . 5*$i . '</option>';
													}
												}
											echo '</select>';

											// Choix restaurant
											echo '<div class="restaurant">Restaurant</div>';
											echo '<div class="zone_select_restaurant">';
												if (isset($_SESSION['restaurant_saisi']) AND ($_SESSION['restaurant_saisi'] == "N" OR $_SESSION['restaurant_saisi'] == ""))
													echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" checked />';
												else
													echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" />';
												echo '<label for="none" class="label_restaurant">Aucun</label>';
												echo '<br />';

												if (isset($_SESSION['restaurant_saisi']) AND $_SESSION['restaurant_saisi'] == "B")
											 		echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" checked />';
												else
											  	echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" />';
												echo '<label for="before" class="label_restaurant">Avant</label>';
												echo '<br />';

												if (isset($_SESSION['restaurant_saisi']) AND $_SESSION['restaurant_saisi'] == "A")
											 		echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" checked />';
												else
											  	echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" />';
												echo '<label for="after" class="label_restaurant">Après</label>';
												echo '<br />';
											echo '</div>';

											// Lieu restaurant
											echo '<input type="text" name="place" value="' . $_SESSION['place_saisie'] . '" placeholder="Lieu proposé" class="monoligne_film" style="margin-bottom: 0px;" />';

										echo '</div>';

										echo '<input type="submit" name="modification_avancee" value="Valider" class="saisie_valider_film" />';

									echo '</form>';

									$wrong_date = false;
								}
								// Sinon on affiche les données de la table
								else
								{
									echo '<form method="post" action="actions_films.php?modify_id=' . $_GET['modify_id'] . '" class="form_saisie_avancee">';

										echo '<div class="zone_saisie_avancee_infos">';
											echo '<div class="sous_titre_saisie_avancee">';
												echo 'Informations sur le film';
											echo '</div>';

											// Titre du film
											echo '<input type="text" name="nom_film" value="' . $donnees['film'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';

											// Date de sortie cinéma
											//SMI - déb
											if (isBlankDate($donnees['date_theater']))
												echo '<input type="text" name="date_theater" value="" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
											else
												echo '<input type="text" name="date_theater" value="' . $date_theater . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
											//SMI - fin

											// Date de sortie DVD
											echo '<input type="text" name="date_release" value="' . $date_release  . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="monoligne_film" />';

											// Lien trailer
											echo '<input type="text" name="trailer" value="' . $donnees['trailer'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';

											// Lien fiche
											echo '<input type="text" name="link" value="' . $donnees['link'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';

											// Lien poster
											echo '<input type="text" name="poster" value="' . $donnees['poster'] . '" placeholder="URL poster" class="monoligne_film" style="margin-bottom: 0px;" />';
										echo '</div>';

										echo '<div class="zone_saisie_avancee_orga">';
											echo '<div class="sous_titre_saisie_avancee">';
												echo 'Organisation sortie';
											echo '</div>';

											// Lien Doodle
											echo '<input type="text" name="doodle" value="' . $donnees['doodle'] . '" placeholder="Doodle" class="monoligne_film" />';

											// Date sortie
											echo '<input type="text" name="date_doodle" value="' . $date_doodle . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" id="datepicker3" class="monoligne_film_short" />';

											// Décomposition heure
											if (isset($donnees['date_doodle']) AND !empty($donnees['date_doodle']))
											{
												$heure_doodle = substr($donnees['time_doodle'], 0, 2);
												$minutes_doodle = substr($donnees['time_doodle'], 2, 2);
											}
											else
											{
												$heure_doodle = "";
												$minutes_doodle = "";
											}

											// Selection de l'heure
											echo '<select name="hours_doodle" class="select_time">';
												if (!isset($heure_doodle) OR empty($heure_doodle))
													echo '<option value="" disabled selected hidden>hh</option>';
												else
													echo '<option value="" disabled hidden>hh</option>';

												for ($i = 0; $i <= 23; $i++)
												{
													if (isset($heure_doodle) AND !empty($heure_doodle) AND $heure_doodle == $i)
													{
														if ($i < 10)
															echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
														else
															echo '<option value="' . $i . '" selected>' . $i . '</option>';
													}
													else
													{
														if ($i < 10)
															echo '<option value="0' . $i . '">0' . $i . '</option>';
														else
															echo '<option value="' . $i . '">' . $i . '</option>';
													}
												}
											echo '</select>';

											// Selection des minutes
											echo '<select name="minutes_doodle" class="select_time">';
												if (!isset($minutes_doodle) OR empty($minutes_doodle))
													echo '<option value="" disabled selected hidden>mm</option>';
												else
													echo '<option value="" disabled hidden>mm</option>';

												for ($i = 0; $i <= 11; $i++)
												{
													if (isset($minutes_doodle) AND !empty($minutes_doodle) AND ($minutes_doodle / 5) == $i)
													{
														if ($i < 2)
															echo '<option value="0' . 5*$i . '" selected>0' . 5*$i . '</option>';
														else
															echo '<option value="' . 5*$i . '" selected>' . 5*$i . '</option>';
													}
													else
													{
														if ($i < 2)
															echo '<option value="0' . 5*$i . '">0' . 5*$i . '</option>';
														else
															echo '<option value="' . 5*$i . '">' . 5*$i . '</option>';
													}
												}
											echo '</select>';

											// Choix restaurant
											echo '<div class="restaurant">Restaurant</div>';

											echo '<div class="zone_select_restaurant">';
												if (isset($donnees['restaurant']) AND ($donnees['restaurant'] == "N" OR $donnees['restaurant'] == ""))
													echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" checked />';
												else
													echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" />';
												echo '<label for="none" class="label_restaurant">Aucun</label>';
												echo '<br />';

												if (isset($donnees['restaurant']) AND $donnees['restaurant'] == "B")
													echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" checked />';
												else
													echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" />';
												echo '<label for="before" class="label_restaurant">Avant</label>';
												echo '<br />';

												if (isset($donnees['restaurant']) AND $donnees['restaurant'] == "A")
													echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" checked />';
												else
													echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" />';
												echo '<label for="after" class="label_restaurant">Après</label>';
												echo '<br />';
											echo '</div>';

											// Lieu restaurant
											echo '<input type="text" name="place" value="' . $donnees['place'] . '" placeholder="Lieu proposé" class="monoligne_film" style="margin-bottom: 0px;" />';
										echo '</div>';

										echo '<input type="submit" name="modification_avancee" value="Valider" class="saisie_valider_film" />';

									echo '</form>';
								}

								$reponse->closeCursor();
							}
							// Saisie à vide par défaut (CREATION)
							else
							{
								echo '<form method="post" action="actions_films.php" class="form_saisie_avancee">';

									echo '<div class="zone_saisie_avancee_infos">';
										echo '<div class="sous_titre_saisie_avancee">';
											echo 'Informations sur le film';
										echo '</div>';

										// Titre du film
										echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';

										// Date de sortie cinéma
										echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';

										// Date de sortie DVD
										echo '<input type="text" name="date_release" value="' . $_SESSION['date_release_saisie'] . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="monoligne_film" />';

										// Lien trailer
										echo '<input type="text" name="trailer" value="' . $_SESSION['trailer_saisi'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';

										// Lien fiche
										echo '<input type="text" name="link" value="' . $_SESSION['link_saisi'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';

										// Lien poster
										echo '<input type="text" name="poster" value="' . $_SESSION['poster_saisi'] . '" placeholder="URL poster" class="monoligne_film" style="margin-bottom: 0px;" />';
									echo '</div>';

									echo '<div class="zone_saisie_avancee_orga">';
										echo '<div class="sous_titre_saisie_avancee">';
											echo 'Organisation sortie';
										echo '</div>';

										// Lien Doodle
										echo '<input type="text" name="doodle" value="' . $_SESSION['doodle_saisi'] . '" placeholder="Doodle" class="monoligne_film" />';

										// Date sortie
										echo '<input type="text" name="date_doodle" value="' . $_SESSION['date_doodle_saisie'] . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" id="datepicker3" class="monoligne_film_short" />';

										// Selection de l'heure
										echo '<select name="hours_doodle" class="select_time">';
											if (isset($_SESSION['hours_doodle_saisies']) AND empty($_SESSION['hours_doodle_saisies']))
												echo '<option value="" disabled selected hidden>hh</option>';
											else
												echo '<option value="" disabled hidden>hh</option>';

											for ($i = 0; $i <= 23; $i++)
											{
												if (isset($_SESSION['hours_doodle_saisies']) AND !empty($_SESSION['hours_doodle_saisies']) AND $_SESSION['hours_doodle_saisies'] == $i)
												{
													if ($i < 10)
														echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
													else
														echo '<option value="' . $i . '" selected>' . $i . '</option>';
												}
												else
												{
													if ($i < 10)
														echo '<option value="0' . $i . '">0' . $i . '</option>';
													else
														echo '<option value="' . $i . '">' . $i . '</option>';
												}
											}
										echo '</select>';

										// Selection des minutes
										echo '<select name="minutes_doodle" class="select_time">';
											if (isset($_SESSION['minutes_doodle_saisies']) AND empty($_SESSION['minutes_doodle_saisies']))
												echo '<option value="" disabled selected hidden>mm</option>';
											else
												echo '<option value="" disabled hidden>mm</option>';

											for ($i = 0; $i <= 11; $i++)
											{
												if (isset($_SESSION['minutes_doodle_saisies']) AND !empty($_SESSION['minutes_doodle_saisies']) AND ($_SESSION['minutes_doodle_saisies'] / 5) == $i)
												{
													if ($i < 2)
														echo '<option value="0' . 5*$i . '" selected>0' . 5*$i . '</option>';
													else
														echo '<option value="' . 5*$i . '" selected>' . 5*$i . '</option>';
												}
												else
												{
													if ($i < 2)
														echo '<option value="0' . 5*$i . '">0' . 5*$i . '</option>';
													else
														echo '<option value="' . 5*$i . '">' . 5*$i . '</option>';
												}
											}
										echo '</select>';

										// Choix restaurant
										echo '<div class="restaurant">Restaurant</div>';
										echo '<div class="zone_select_restaurant">';
											if (isset($_SESSION['restaurant_saisi']) AND ($_SESSION['restaurant_saisi'] == "N" OR $_SESSION['restaurant_saisi'] == ""))
												echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" checked />';
											else
												echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" />';
											echo '<label for="none" class="label_restaurant">Aucun</label>';
											echo '<br />';

											if (isset($_SESSION['restaurant_saisi']) AND $_SESSION['restaurant_saisi'] == "B")
										 		echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" checked />';
											else
										  	echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" />';
											echo '<label for="before" class="label_restaurant">Avant</label>';
											echo '<br />';

											if (isset($_SESSION['restaurant_saisi']) AND $_SESSION['restaurant_saisi'] == "A")
										 		echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" checked />';
											else
										  	echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" />';
											echo '<label for="after" class="label_restaurant">Après</label>';
											echo '<br />';
										echo '</div>';

										// Lieu restaurant
										echo '<input type="text" name="place" value="' . $_SESSION['place_saisie'] . '" placeholder="Lieu proposé" class="monoligne_film" style="margin-bottom: 0px;" />';
									echo '</div>';

									echo '<input type="submit" name="saisie_avancee" value="Valider" class="saisie_valider_film" />';

								echo '</form>';
							}
						?>
					</div>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script>
		$(function()
		{
			$( "#datepicker" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
			$( "#datepicker2" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker2",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
			$( "#datepicker3" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker3",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
		});
	</script>

</html>
