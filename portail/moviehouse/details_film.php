<?php
	// Contrôles communs Utilisateurs
	include('../../includes/controls_users.php');

	include('../../includes/init_session.php');

	// Fonctions
	include('../../includes/fonctions_dates.php');

	// Contrôle film non à supprimer
	include('../../includes/appel_bdd.php');

	$reponse = $bdd->query('SELECT id, to_delete FROM movie_house WHERE id = ' . $_GET['id_film']);
	$donnees = $reponse->fetch();

	if ($donnees['to_delete'] == "Y")
		header('location: ../moviehouse.php?view=main&year=' . date("Y"));

	$reponse->closeCursor();

	// Contrôle film existant
	$reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $_GET['id_film']);
	$donnees = $reponse->fetch();

	if ($reponse->rowCount() == 0)
		$_SESSION['doesnt_exist'] = true;

	$reponse->closeCursor();
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../../favicon.png" />
	<link rel="stylesheet" href="../../style.css" />
  <title>Inside CGI - MH</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
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
					$modify_film = true;
					$delete_film = true;
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

				<?php
					include('../../includes/appel_bdd.php');

					$reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $_GET['id_film']);
					$donnees = $reponse->fetch();

					if ($reponse->rowCount() > 0)
					{
						$date_theater = "-";
						$date_release = "-";
						$date_doodle = "-";

						if (!empty($donnees['date_theater']))
							$date_theater = substr($donnees['date_theater'], 2, 2) . '/' . substr($donnees['date_theater'], 0, 2) . '/' .  substr($donnees['date_theater'], 4, 4);

						if (!empty($donnees['date_release']))
							$date_release = substr($donnees['date_release'], 2, 2) . '/' .  substr($donnees['date_release'], 0, 2) . '/' .  substr($donnees['date_release'], 4, 4);

						if (!empty($donnees['date_doodle']))
							$date_doodle = substr($donnees['date_doodle'], 2, 2) . '/' .  substr($donnees['date_doodle'], 0, 2) . '/' .  substr($donnees['date_doodle'], 4, 4);


						// On récupère la liste des films pour trouver le film précédent et suivant
						$liste_films = array();
						$i = 0;
						$j = 0;

						$req0 = $bdd->query('SELECT id, film FROM movie_house WHERE SUBSTR(date_theater,5,4)=' . substr($donnees['date_theater'], 4, 4) . ' AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');
						while($data0 = $req0->fetch())
						{
							$liste_films[$i][1] = $data0['id'];
							$liste_films[$i][2] = $data0['film'];

							$i++;
						}
						$req0->closeCursor();

						// Titre du film + boutons navigation
						echo '<div class="bandeau_titre_article">';
							// On affiche un des boutons que si on a au moins un film de plus cette année
							if ($i > 1)
							{
								for ($j = 0; $j < $i; $j++)
								{
									if ($liste_films[$j][1] == $_GET['id_film'])
									{
										// Bouton précédent
										if (!empty($liste_films[$j - 1][1]) AND !empty($liste_films[$j - 1][2]))
										{
											// On raccourci le texte s'il est trop long
											$max_caracteres = 15;
											$titre = $liste_films[$j - 1][2];
											// Test si la longueur du texte dépasse la limite
											if (strlen($titre) > $max_caracteres)
											{
												// Sélection du maximum de caractères
												$titre = substr($titre, 0, $max_caracteres);

												// Ajout des "..."
												$titre = $titre . '...';
											}

											// On affiche le lien
											echo '<a href="details_film.php?id_film=' . $liste_films[$j - 1][1] . '" title="' . $liste_films[$j - 1][2] . '" class="zone_prev_movie">';
												echo '<img src="../../includes/icons/back.png" alt="prev" title="Film précédent" class="prev_movie" />';
												echo '<div class="title_previous_next_film" style="padding-right: 10px;">' . $titre . '</div>';
												echo '<div class="triangle_previous"></div>';
												echo '<div class="triangle_previous_2"></div>';
												echo '<div class="triangle_previous_3"></div>';
											echo '</a>';
										}
										// Zone vide si pas de film précédent (pour conserver le décalage et laisser le titre centré)
										else
											echo '<div class="empty_previous"></div>';

										echo '<div class="previs_article">' . $donnees['film'] . '</div>';

										// Bouton suivant
										if (!empty($liste_films[$j + 1][1]) AND !empty($liste_films[$j + 1][2]))
										{
											// On raccourci le texte s'il est trop long
											$max_caracteres = 15;
											$titre = $liste_films[$j + 1][2];
											// Test si la longueur du texte dépasse la limite
											if (strlen($titre) > $max_caracteres)
											{
												// Sélection du maximum de caractères
												$titre = substr($titre, 0, $max_caracteres);

												// Ajout des "..."
												$titre = $titre . '...';
											}

											// On affiche le lien
											echo '<a href="details_film.php?id_film=' . $liste_films[$j + 1][1] . '" title="' . $liste_films[$j + 1][2] . '" class="zone_next_movie">';
												echo '<div class="triangle_next_3"></div>';
												echo '<div class="triangle_next_2"></div>';
												echo '<div class="triangle_next"></div>';
												echo '<div class="title_previous_next_film" style="padding-left: 10px;">' . $titre . '</div>';
												echo '<img src="../../includes/icons/back.png" alt="next" title="Film suivant" class="next_movie" />';
											echo '</a>';
										}
										// Zone vide si pas de film suivant (pour conserver le décalage et laisser le titre centré)
										else
											echo '<div class="empty_next"></div>';
									}
								}
							}
							else
							{
								echo '<div class="previs_article">' . $donnees['film'] . '</div>';
							}
						echo '</div>';

						// Zone de détails du film
						echo '<div class="details">';
							echo '<div class="detail_gauche">';
								// Vidéo
								if (!empty($donnees['id_url']))
								{
									echo '<div class="video_container">';
										//echo '<iframe src="' . $donnees['trailer'] . '" allowfullscreen class="video"></iframe>';
										$exp = explode(':_:', $donnees['id_url']);
										$html = "";

										switch ($exp[0])
										{
											case 'youtube':
												$html = '<iframe src="http://www.youtube.com/embed/' . $exp[1] . '" allowfullscreen class="video"></iframe>';
												break;
											case 'vimeo':
												$html = '<iframe src="http://player.vimeo.com/video/'.$exp[1].'" allowFullScreen class="video"></iframe>';
												break;
											case 'dailymotion':
												$html = '<iframe src="http://www.dailymotion.com/embed/video/'.$exp[1].'" allowfullscreen class="video"></iframe>';
												break;

											default:
												break;
										}

										echo $html;
									echo '</div>';
								}

								// On récupère tout d'abord les noms correspondant aux identifiants
								$full_names = array();
								$i = 1;

								$req1 = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY id ASC');
								while ($data1 = $req1->fetch())
								{
									$full_names[$i][1] = $data1['identifiant'];
									$full_names[$i][2] = $data1['full_name'];
									$full_names[$i][3] = $data1['avatar'];

									$i++;
								}
								$req1->closeCursor();

								// On cherche si on a déjà participé pour afficher le bouton de non participation
								$present = false;

								$req2 = $bdd->query('SELECT id_film, identifiant, stars FROM movie_house_users WHERE id_film = ' . $_GET['id_film'] . ' AND identifiant != "admin"');
								while($data2 = $req2->fetch())
								{
									if ($_SESSION['identifiant'] == $data2['identifiant'])
									{
										$present = true;

										// On stoppe la boucle si on trouve que l'utilisateur est intéressé
										break;
									}
								}
								$req2->closeCursor();

								// Zone de liens
								echo '<div class="links_details">';
									// Lien vers la fiche du film
									if (!empty($donnees['link']))
									{
										echo '<a href="' . $donnees['link'] . '" target="_blank" class="link_fiche">';
											echo '<div class="fiche_align">Fiche du film</div>';
										echo '</a>';
									}
									else
										echo '<div class="fiche_absente">Pas de fiche</div>';

									// Lien vers le doodle
									if (!empty($donnees['doodle']))
										echo '<a href="' . $donnees['doodle'] . '" target="_blank" class="link_doodle"><img src="../../includes/icons/doodle.png" alt="doodle" class="logo_doodle" /></a>';

									// Etoiles utilisateur
									$req3 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $_GET['id_film'] . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
									$data3 = $req3->fetch();

									echo '<div class="form_stars_details">';
										echo '<form method="post" action="submit_film.php?id_film=' . $_GET['id_film'] . '">';
											// Boutons vote
											for($j = 0; $j <= 5; $j++)
											{
												if ($j == $data3['stars'])
												{
													echo '<img src="star' . $j .'.png" alt="star' . $j . '" class="new_star_3" />';
													echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_selected" />';
												}
												else
												{
													echo '<img src="star' . $j .'.png" alt="star' . $j . '" class="new_star_3" />';
													echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_3" />';
												}
											}
										echo '</form>';
									echo '</div>';

									$req3->closeCursor();

									// Si l'utilisateur a des étoiles
									if ($present == true)
									{
										echo '<form method="post" action="actions.php?id_film=' . $_GET['id_film'] . '" class="form_not_interested">';
											$req4 = $bdd->query('SELECT participation FROM movie_house_users WHERE id_film = ' . $_GET['id_film'] . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
											$data4 = $req4->fetch();

											// Participation
											if ($data4['participation'] == "P")
												echo '<input type="submit" name="participate" value="Je ne participe plus..." class="not_interested" style="background-color: #2f891f;" />';
											else
												echo '<input type="submit" name="participate" value="Je participe !" class="not_interested" style="background-color: #2f891f;" />';

											// Vue
											if ($data4['participation'] == "S")
												echo '<input type="submit" name="seen" value="Je n\'ai pas vu ..." class="not_interested" style="background-color: #2eb2f4;" />';
											else
												echo '<input type="submit" name="seen" value="J\'ai vu !" class="not_interested" style="background-color: #2eb2f4;" />';

											$req4->closeCursor();
										echo '</form>';
									}
								echo '</div>';

								// Dates
								echo '<div class="date_sortie">Sortie cinéma</div><div class="date_sortie_2">';
								if (isBlankDate($donnees['date_theater']))
									echo 'N.C.';
								else
									echo $date_theater;
							 	echo '</div>';
								echo '<div class="date_sortie">Sortie DVD/Bluray</div><div class="date_sortie_2">' . $date_release . '</div>';
								echo '<div class="date_sortie">Date proposée</div><div class="date_sortie_2">' . $date_doodle . '</div>';

								// On récupère la liste des personne souhaitant visionner le film
								echo '<div class="date_sortie">Intéresse</div>';
								echo '<div class="view_by">';

								$count = 0;

								$req5 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $_GET['id_film'] . ' AND identifiant != "admin" ORDER BY identifiant ASC');
								while($data5 = $req5->fetch())
								{
									// On recherche le nom correspondant à l'identifiant stocké dans le tableau précédent
									foreach ($full_names as $line)
									{
										if ($data5['identifiant'] == $line[1])
										{
											$utilisateur = $line[2];
											$avatar = $line[3];

											// Passe à l'itération suivante si on a trouvé le pseudo
											continue;
										}
									}
									// On affiche le nom correspondant à l'utilisateur et ses étoiles
									echo '<table class="table_view_by">';
										echo '<tr>';
											echo '<td class="td_view_by" style="border-right: solid 1px white;">';
												echo '<div class="zone_avatar_details_film">';
													if (isset($avatar) AND !empty($avatar))
														echo '<img src="../../connexion/avatars/' . $avatar . '" alt="avatar" title="' . $utilisateur . '" class="avatar_details_film" />';
													else
														echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $utilisateur . '" class="avatar_details_film" />';
												echo '</div>';

												echo '<div class="user_view_by">' . $utilisateur . '</div>';
											echo '</td>';

											if ($data5['participation'] == "S")
												echo '<td class="td_view_by" style="background: #74cefb;">';
											elseif ($data5['participation'] == "P")
												echo '<td class="td_view_by" style="background: #91d784;">';
											else
												echo '<td class="td_view_by">';

												echo '<div class="link_vote_details">';
													echo '<img src="star' . $data5['stars'] . '.png" alt="star' . $data5['stars'] . '" class="new_star" />';
												echo '</div>';

											echo '</td>';
										echo '</tr>';
									echo '</table>';

									$count++;
								}
								$req5->closeCursor();

								if ($count == 0)
								{
									echo '<span style="padding-left: 4%; padding-right: 4%;">-</span>';
								}

								echo '</div>';
							echo '</div>';

							echo '<div class="detail_droite">';
								// Affichage du poster
								if (!empty($donnees['poster']))
									echo '<img src="' . $donnees['poster'] . '" alt="' . $donnees['poster'] . '" title="' . $donnees['film'] . '" class="img_details" /><br />';
								else
									echo '<img src="cinema.jpg" alt="pellicule" title="' . $donnees['film'] . '" class="img_details"/>';
							echo '</div>';
						echo '</div>';

						$reponse->closeCursor();

						// Commentaires
						echo '<div class="zone_comments_films">';
							// Titre
							echo '<div class="title_comments_films">';
								echo 'Commentaires';
							echo '</div>';

							// Affichage des commentaires
							$reponse = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $_GET['id_film'] . ' ORDER BY id ASC');
							while($donnees = $reponse->fetch())
							{
								echo '<div class="content_comments_films">';
									$reponse2 = $bdd->query('SELECT full_name, avatar FROM users WHERE identifiant = "' . $donnees['author'] . '"');
									$donnees2 = $reponse2->fetch();
									// Photo de profil
									echo '<div class="zone_avatar_comments">';
										if (isset($donnees2['avatar']) AND !empty($donnees2['avatar']))
											echo '<img src="../../connexion/avatars/' . $donnees2['avatar'] . '" alt="avatar" title="' . $donnees2['full_name'] . '" class="avatar_comments" />';
										else
											echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $donnees2['full_name'] . '" class="avatar_comments" />';
									echo '</div>';

									// Pseudo
									echo '<div class="pseudo_comments_films">' . $donnees2['full_name'] . '</div>';
									$reponse2->closeCursor();

									// Date et heure
									echo '<div class="date_comments_films">Le ' . formatDateForDisplay($donnees['date']) . ' à ' . formatTimeForDisplay($donnees['time']) . '</div>';

									// Commentaire
									echo '<div class="comment_comments_films">' . nl2br($donnees['comment']) . '</div>';
								echo '</div>';
							}
							$reponse->closeCursor();

							// Saisie commentaire
							echo '<form method="post" action="comment_film.php?id_film=' . $_GET['id_film'] . '" id="comments" class="saisie_comments_films">';
								echo '<textarea placeholder="Votre commentaire ici..." name="comment" class="saisie_commentaire" required></textarea>';
								echo '<input type="submit" name="submit_comment" value="Envoyer" class="send_comment" />';
							echo '</form>';
						echo '</div>';
					}
				?>

			</article>
		</section>

		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

</html>
