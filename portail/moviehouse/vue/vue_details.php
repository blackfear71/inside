<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />
    
  	<title>Inside - MH</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect  = true;
					$profil      = true;
					$modify_film = true;
					$delete_film = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<?php
          if ($filmExistant == true)
          {
  					// Titre du film + boutons navigation
  					echo '<div class="bandeau_titre_article">';
              // Lien film précédent
              if (!empty($listeNavigation['previous']['id']) AND !empty($listeNavigation['previous']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['previous']['id'] . '&action=goConsulter" title="' . $listeNavigation['previous']['film'] . '" class="zone_prev_movie">';
                  echo '<img src="../../includes/icons/back.png" alt="prev" title="Film précédent" class="prev_movie" />';
                  echo '<div class="title_previous_next_film" style="padding-right: 10px;">' . $listeNavigation['previous']['film'] . '</div>';
                  echo '<div class="triangle_previous"></div>';
                  echo '<div class="triangle_previous_2"></div>';
                  echo '<div class="triangle_previous_3"></div>';
                echo '</a>';
              }
              // Zone vide si pas de film précédent (pour conserver le décalage et laisser le titre centré)
              else
                echo '<div class="empty_previous"></div>';

              // Titre
              echo '<div class="previs_article">' . $detailsFilm->getFilm() . '</div>';

              // Lien film suivant
              if (!empty($listeNavigation['next']['id']) AND !empty($listeNavigation['next']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['next']['id'] . '&action=goConsulter" title="' . $listeNavigation['next']['film'] . '" class="zone_next_movie">';
                  echo '<div class="triangle_next_3"></div>';
                  echo '<div class="triangle_next_2"></div>';
                  echo '<div class="triangle_next"></div>';
                  echo '<div class="title_previous_next_film" style="padding-left: 10px;">' . $listeNavigation['next']['film'] . '</div>';
                  echo '<img src="../../includes/icons/back.png" alt="next" title="Film suivant" class="next_movie" />';
                echo '</a>';
              }
              // Zone vide si pas de film suivant (pour conserver le décalage et laisser le titre centré)
              else
                echo '<div class="empty_next"></div>';

            echo '</div>';

  					// Zone de détails du film
  					echo '<div class="details">';
  						echo '<div class="detail_gauche">';
  							// Vidéo
  							if (!empty($detailsFilm->getId_url()))
  							{
  								echo '<div class="video_container">';
  									$exp = explode(':_:', $detailsFilm->getId_url());
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

  							// Zone de liens
  							echo '<div class="links_details">';
  								// Lien vers la fiche du film
  								if (!empty($detailsFilm->getLink()))
  								{
  									echo '<a href="' . $detailsFilm->getLink() . '" target="_blank" class="link_fiche">';
  										echo '<div class="fiche_align">Fiche du film</div>';
  									echo '</a>';
  								}
  								else
  									echo '<div class="fiche_absente">Pas de fiche</div>';

  								// Lien vers le doodle
  								if (!empty($detailsFilm->getDoodle()))
  									echo '<a href="' . $detailsFilm->getDoodle() . '" target="_blank" class="link_doodle"><img src="../../includes/icons/doodle.png" alt="doodle" class="logo_doodle" /></a>';

  								// Etoiles utilisateur
  								echo '<div class="form_stars_details">';
  									echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doVoterFilm">';
  										// Boutons vote
  										for($j = 0; $j <= 5; $j++)
  										{
  											if ($j == $detailsFilm->getStars_user())
  											{
  												echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="new_star_3" />';
  												echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_3" style="padding-bottom: 8px; border-bottom: solid 3px rgb(200, 25, 50);" />';
  											}
  											else
  											{
  												echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="new_star_3" />';
  												echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_3" />';
  											}
  										}
  									echo '</form>';
  								echo '</div>';

  								// Si l'utilisateur a des étoiles
  								if ($detailsFilm->getStars_user() > 0)
  								{
  									echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doParticiperFilm" class="form_not_interested">';
  										// Participation
  										if ($detailsFilm->getParticipation() == "P")
  											echo '<input type="submit" name="participate" value="Je ne participe plus..." class="not_interested" style="background-color: #2f891f;" />';
  										else
  											echo '<input type="submit" name="participate" value="Je participe !" class="not_interested" style="background-color: #2f891f;" />';

  										// Vue
  										if ($detailsFilm->getParticipation() == "S")
  											echo '<input type="submit" name="seen" value="Je n\'ai pas vu ..." class="not_interested" style="background-color: #2eb2f4;" />';
  										else
  											echo '<input type="submit" name="seen" value="J\'ai vu !" class="not_interested" style="background-color: #2eb2f4;" />';

  									echo '</form>';
  								}
  							echo '</div>';

  							// Dates
  							echo '<div class="date_sortie">Sortie cinéma</div><div class="date_sortie_2">';
  							if (isBlankDate($detailsFilm->getDate_theater()))
  								echo 'N.C.';
  							else
  								echo formatDateForDisplay($detailsFilm->getDate_theater());
  						 	echo '</div>';

                if (!empty($detailsFilm->getDate_release()))
  					      echo '<div class="date_sortie">Sortie DVD/Bluray</div><div class="date_sortie_2">' . formatDateForDisplay($detailsFilm->getDate_release()) . '</div>';
                else
                  echo '<div class="date_sortie">Sortie DVD/Bluray</div><div class="date_sortie_2">-</div>';

  							echo '<div class="date_sortie">Date proposée</div>';
  							echo '<div class="date_sortie_2">';
                  if (!empty($detailsFilm->getDate_doodle()))
                  {
  		 	            echo formatDateForDisplay($detailsFilm->getDate_doodle());

                    if (!empty($detailsFilm->getTime_doodle()))
                    {
                      $heure_doodle   = substr($detailsFilm->getTime_doodle(), 0, 2);
                      $minutes_doodle = substr($detailsFilm->getTime_doodle(), 2, 2);

                      echo ' à ' . $heure_doodle . ':' . $minutes_doodle;
                    }
                  }
                  else
                    echo '-';
  							echo '</div>';

  							// Restaurant
  							echo '<div class="date_sortie">Restaurant</div>';
  							echo '<div class="date_sortie_2">';
  								switch ($detailsFilm->getRestaurant())
  								{
  									case "B":
  										echo 'Avant';
  										if (!empty($detailsFilm->getPlace()))
  											echo ' : ' . $detailsFilm->getPlace();
  										break;

  									case "A":
  										echo 'Après';
  										if (!empty($detailsFilm->getPlace()))
  											echo ' : ' . $detailsFilm->getPlace();
  										break;

  									case "N":
  									default:
  										echo 'Aucun';
  										break;
  								}
  							echo '</div>';

  							// On récupère la liste des personne souhaitant visionner le film
  							echo '<div class="date_sortie">Intéresse</div>';
  							echo '<div class="view_by">';

                if (!empty($listeEtoiles))
                {
                  foreach ($listeEtoiles as $etoiles)
                  {
                    // On affiche le nom correspondant à l'utilisateur et ses étoiles
                    echo '<table class="table_view_by">';
                      echo '<tr>';
                        echo '<td class="td_view_by" style="border-right: solid 1px white;">';
                          echo '<div class="zone_avatar_details_film">';
                            if (!empty($etoiles->getAvatar()))
                              echo '<img src="../../profil/avatars/' . $etoiles->getAvatar() . '" alt="avatar" title="' . $etoiles->getPseudo() . '" class="avatar_details_film" />';
                            else
                              echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $etoiles->getPseudo() . '" class="avatar_details_film" />';
                          echo '</div>';

                          echo '<div class="user_view_by">' . $etoiles->getPseudo() . '</div>';
                        echo '</td>';

                        if ($etoiles->getParticipation() == "S")
                          echo '<td class="td_view_by" style="background-color: #74cefb;">';
                        elseif ($etoiles->getParticipation() == "P")
                          echo '<td class="td_view_by" style="background-color: #91d784;">';
                        else
                          echo '<td class="td_view_by">';

                          echo '<div class="link_vote_details">';
                            echo '<img src="icons/stars/star' . $etoiles->getStars() . '.png" alt="star' . $etoiles->getStars() . '" class="new_star" />';
                          echo '</div>';

                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  }
                }
                else
                  echo '<span style="padding-left: 4%; padding-right: 4%;">-</span>';

  							echo '</div>';
  						echo '</div>';

  						echo '<div class="detail_droite">';
  							// Affichage du poster
  							if (!empty($detailsFilm->getPoster()))
  								echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="img_details" /><br />';
  							else
  								echo '<img src="images/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="img_details"/>';
  						echo '</div>';
  					echo '</div>';

  					// Commentaires
  					echo '<div class="zone_comments_films">';
  						// Titre
  						echo '<div class="title_comments_films">';
  							echo 'Commentaires';
  						echo '</div>';

  						// Affichage des commentaires
              foreach ($listeCommentaires as $comment)
              {
  							echo '<div class="content_comments_films">';
  								// Photo de profil
  								echo '<div class="zone_avatar_comments">';
  									if (!empty($comment->getAvatar()))
  										echo '<img src="../../profil/avatars/' . $comment->getAvatar() . '" alt="avatar" title="' . $comment->getPseudo() . '" class="avatar_comments" />';
  									else
  										echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $comment->getPseudo() . '" class="avatar_comments" />';
  								echo '</div>';

  								// Pseudo
  								if (!empty($comment->getPseudo()))
  									echo '<div class="pseudo_comments_films">' . $comment->getPseudo() . '</div>';
  								else
  									echo '<div class="pseudo_comments_films"><i>un ancien utilisateur</i></div>';

  								// Date et heure
  								echo '<div class="date_comments_films">Le ' . formatDateForDisplay($comment->getDate()) . ' à ' . formatTimeForDisplay($comment->getTime()) . '</div>';

  								// On cherche les liens dans les commentaires
  								$commentaire = extract_link(nl2br($comment->getComment()));

  								// Commentaire
  								echo '<div class="comment_comments_films">' . $commentaire . '</div>';
                echo '</div>';
              }

  						// Saisie commentaire
  						echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doCommenter" id="comments" class="saisie_comments_films">';
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
