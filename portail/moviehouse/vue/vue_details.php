<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />

    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptMH.js"></script>

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
					$profil_user = true;
					$modify_film = true;
					$delete_film = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;
          $notifs      = true;

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
            // Bandeau avec poster
            echo '<div class="bandeau_details">';
              // Lien film précédent
              if (!empty($listeNavigation['previous']['id']) AND !empty($listeNavigation['previous']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['previous']['id'] . '&action=goConsulter" class="link_prev_movie"><img src="icons/left.png" alt="left" class="fleche_detail" style="padding-right: 50px;" /></a>';
                echo '<span class="titre_prev_movie">' . $listeNavigation['previous']['film'] . '</span>';
              }
              // Lien film suivant
              if (!empty($listeNavigation['next']['id']) AND !empty($listeNavigation['next']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['next']['id'] . '&action=goConsulter" class="link_next_movie"><img src="icons/right.png" alt="right" class="fleche_detail" style="padding-left: 50px;" /></a>';
                echo '<span class="titre_next_movie">' . $listeNavigation['next']['film'] . '</span>';
              }
              // Fond
              if (!empty($detailsFilm->getPoster()))
                echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="bandeau_poster" />';
              else
                echo '<img src="images/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="bandeau_poster" />';

              // Titre du film
              echo '<div class="titre_film_details">' . $detailsFilm->getFilm() . '</div>';
            echo '</div>';

            echo '<div class="details">';
              // Partie gauche
              echo '<div class="left_details">';
                echo '<table class="table_left_details">';
                  echo '<tr>';
                    echo '<td class="zone_poster_detail">';
                      // Affichage du poster
                      if (!empty($detailsFilm->getPoster()))
                        echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="img_details" /><br />';
                      else
                        echo '<img src="images/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="img_details" />';
                    echo '</td>';

                    echo '<td class="zone_link">';
                      // Mailing
                      if ($detailsFilm->getNb_users() > 0)
                      {
                        echo '<a href="mailing.php?id_film=' . $detailsFilm->getId() . '&action=goConsulter" class="link_details">';
                          echo '<img src="icons/mailing_red.png" alt="mailing" title="Envoyer mail" class="icon_details" />';
                        echo '</a>';
                      }

                      // Doodle
                      if (!empty($detailsFilm->getDoodle()))
                      {
                        echo '<a href="' . $detailsFilm->getDoodle() . '" target="_blank" class="link_details">';
                          echo '<img src="icons/doodle.png" alt="doodle" title="Doodle" class="icon_details" />';
                        echo '</a>';
                      }
                      else
                      {
                        echo '<a href="https://doodle.com/fr/" target="_blank" class="link_details">';
                          echo '<img src="icons/doodle_grey.png" alt="doodle_grey" title="Doodle" class="icon_details" />';
                        echo '</a>';
                      }

                      // Fiche du film
                      if (!empty($detailsFilm->getLink()))
                      {
                        echo '<a href="' . $detailsFilm->getLink() . '" target="_blank" class="link_details">';
                          echo '<img src="icons/pellicule.png" alt="pellicule" title="Fiche du film" class="icon_details" />';
                        echo '</a>';
                      }
                    echo '</td>';
                  echo '</tr>';
              echo '</table>';

              // Actions
              echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doVoterFilm" class="form_stars_details_left">';
                // Etoiles utilisateur
                for($j = 0; $j <= 5; $j++)
                {

                  if ($j == $detailsFilm->getStars_user())
                  {
                    echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="star_vote" style="border: solid 1px #7c7c7c;" />';
                    echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_star_vote" />';
                  }
                  else
                  {
                    echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="star_vote" />';
                    echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_star_vote" />';
                  }
                }
              echo '</form>';

              // Si l'utilisateur a des étoiles
              if ($detailsFilm->getStars_user() > 0)
              {
                echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doParticiperFilm" class="form_stars_details_right">';
                  // Participation
                  if ($detailsFilm->getParticipation() == "P")
                  {
                    echo '<img src="icons/participate.png" alt="participate" class="star_vote" style="background-color: #ffad01;" />';
                    echo '<input type="submit" name="participate" class="link_star_vote" />';
                  }
                  else
                  {
                    echo '<img src="icons/participate.png" alt="participate" class="star_vote" />';
                    echo '<input type="submit" name="participate" class="link_star_vote" />';
                  }

                  // Vue
                  if ($detailsFilm->getParticipation() == "S")
                  {
                    echo '<img src="icons/seen.png" alt="seen" class="star_vote" style="background-color: #ffad01;" />';
                    echo '<input type="submit" name="seen" class="link_star_vote" />';
                  }
                  else
                  {
                    echo '<img src="icons/seen.png" alt="seen" class="star_vote" />';
                    echo '<input type="submit" name="seen" class="link_star_vote" />';
                  }

                echo '</form>';
              }

              // Tableau des personnes intéressées
              echo '<div class="entete_detail">';
                echo 'Personnes intéressées';
              echo '</div>';

              echo '<div class="contenu_detail">';
                echo '<table class="table_interested">';
                  if (!empty($listeEtoiles))
                  {
                    foreach ($listeEtoiles as $etoiles)
                    {
                      // On affiche le nom correspondant à l'utilisateur et ses étoiles
                      if ($etoiles->getIdentifiant() == $_SESSION['identifiant'])
                        echo '<tr class="tr_interested" style="background-color: #fffde8;">';
                      else
                        echo '<tr class="tr_interested">';

                        echo '<td class="td_interested_left">';
                          if (!empty($etoiles->getAvatar()))
                            echo '<img src="../../profil/avatars/' . $etoiles->getAvatar() . '" alt="avatar" title="' . $etoiles->getPseudo() . '" class="avatar_interested" />';
                          else
                            echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $etoiles->getPseudo() . '" class="avatar_interested" />';
                        echo '</td>';

                        echo '<td class="td_interested_middle">';
                          echo $etoiles->getPseudo();
                        echo '</td>';

                        echo '<td class="td_interested_right">';
                          if ($etoiles->getParticipation() == "S")
                            echo '<div class="circle_star_interested" style="background-color: #74cefb;">';
                          elseif ($etoiles->getParticipation() == "P")
                            echo '<div class="circle_star_interested" style="background-color: #91d784;">';
                          else
                            echo '<div class="circle_star_interested">';

                            echo '<img src="icons/stars/star' . $etoiles->getStars() . '.png" alt="star' . $etoiles->getStars() . '" class="star_interested" />';
                          echo '</div>';
                        echo '</td>';
                      echo '</tr>';
                    }
                  }
                  else
                    echo '<div class="no_interested">Aucune personne encore intéressée.</div>';
                echo '</table>';
              echo '</div>';
            echo '</div>';

            // Partie droite
            echo '<div class="right_details">';
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

              // Sortie cinéma
              echo '<div class="entete_detail">';
                echo 'Sortie au cinéma';
              echo '</div>';

              echo '<div class="contenu_detail">';
                if (isBlankDate($detailsFilm->getDate_theater()))
                  echo 'N.C.';
                else
                  echo formatDateForDisplay($detailsFilm->getDate_theater());
              echo '</div>';

              // Sortie DVD / Bluray
              echo '<div class="entete_detail">';
                echo 'Sortie en DVD / Bluray';
              echo '</div>';

              echo '<div class="contenu_detail">';
                if (!empty($detailsFilm->getDate_release()))
                  echo formatDateForDisplay($detailsFilm->getDate_release());
                else
                  echo '-';
              echo '</div>';

              // Date proposée
              echo '<div class="entete_detail">';
                echo 'Sortie proposée';
              echo '</div>';

              echo '<div class="contenu_detail">';
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
              echo '<div class="entete_detail">';
                echo 'Restaurant';
              echo '</div>';

              echo '<div class="contenu_detail">';
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
            echo '</div>';

            // Commentaires
            echo '<div class="zone_commentaires">';
              // Titre
              echo '<div class="entete_detail">';
                echo 'Commentaires';
              echo '</div>';

              // Affichage des commentaires
              foreach ($listeCommentaires as $comment)
              {
                echo '<div class="zone_commentaire_user" id="' . $comment->getId() . '">';
                  // Photo de profil
                  if (!empty($comment->getAvatar()))
                    echo '<img src="../../profil/avatars/' . $comment->getAvatar() . '" alt="avatar" title="' . $comment->getPseudo() . '" class="avatar_comments" />';
                  else
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $comment->getPseudo() . '" class="avatar_comments" />';

                  // Pseudo
                  if (!empty($comment->getPseudo()))
                    echo '<div class="pseudo_comments_films">' . $comment->getPseudo() . '</div>';
                  else
                    echo '<div class="pseudo_comments_films"><i>un ancien utilisateur</i></div>';

                  // Date et heure
                  echo '<div class="date_comments_films">Le ' . formatDateForDisplay($comment->getDate()) . ' à ' . formatTimeForDisplay($comment->getTime()) . '</div>';

                  // Actions sur commentaires seulement si l'auteur correspond à l'utilisateur connecté
                  if ($comment->getAuthor() == $_SESSION['identifiant'])
                  {
                    /***************************************************/
                    /* Ligne visualisation normale (sans modification) */
                    /***************************************************/
                    echo '<span id="modifier_comment_2[' . $comment->getId() . ']">';
                      echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&comment_id=' . $comment->getId() . '&action=doSupprimerCommentaire">';
                        echo '<div class="actions_commentaires">';
                          // Modification commentaire
                          echo '<span class="link_actions_commentaires">';
                            echo '<a onclick="afficherMasquer(\'modifier_comment[' . $comment->getId() . ']\'); afficherMasquer(\'modifier_comment_2[' . $comment->getId() . ']\');" title="Modifier le commentaire" class="icone_modifier_comment"></a>';
                          echo '</span>';

                          // Suppression commentaire
                          echo '<span class="link_actions_commentaires">';
                            echo '<input type="submit" name="delete_comment" value="" title="Supprimer le commentaire" onclick="if(!confirm(\'Supprimer ce commentaire ?\')) return false;" class="icone_supprimer_comment" />';
                          echo '</span>';
                        echo '</div>';
                      echo '</form>';

                      // On cherche les smileys dans les commentaires
                      $commentaire = extract_smiley($comment->getComment());

                      // On cherche les liens dans les commentaires
                      $commentaire = extract_link(nl2br($commentaire));

                      // Commentaire
                      echo '<div class="texte_commentaire">' . $commentaire . '</div>';
                    echo '</span>';

                    /**********************************/
                    /* Ligne cachée pour modification */
                    /**********************************/
                    echo '<span id="modifier_comment[' . $comment->getId() . ']" style="display: none;">';
                      echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&comment_id=' . $comment->getId() . '&action=doModifierCommentaire">';
                        echo '<div class="actions_commentaires" style="margin-top: -45px;">';
                          // Validation modification
                          echo '<span class="link_actions_commentaires">';
                            echo '<input type="submit" name="modify_comment" value="" title="Valider la modification" class="icone_valider_comment" />';
                          echo '</span>';

                          // Annulation modification
                          echo '<span class="link_actions_commentaires">';
                            echo '<a onclick="afficherMasquer(\'modifier_comment[' . $comment->getId() . ']\'); afficherMasquer(\'modifier_comment_2[' . $comment->getId() . ']\');" title="Annuler la modification" class="icone_annuler_comment"></a>';
                          echo '</span>';
                        echo '</div>';

                        echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="modifyComment' . $comment->getId() . '" class="zone_modification_commentaire" required>' . $comment->getComment() . '</textarea>';

                        echo '<div class="zone_saisie_smileys">';
                          echo '<a onclick="insert_smiley(\':)\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/1.png" alt="smile" title=":)" class="smiley_2" /></a>';
                          echo '<a onclick="insert_smiley(\';)\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/2.png" alt="smile" title=";)" class="smiley_2" /></a>';
                          echo '<a onclick="insert_smiley(\':(\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/3.png" alt="smile" title=":(" class="smiley_2" /></a>';
                          echo '<a onclick="insert_smiley(\':|\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/4.png" alt="smile" title=":|" class="smiley_2" /></a>';
                          echo '<a onclick="insert_smiley(\':D\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/5.png" alt="smile" title=":D" class="smiley_2" /></a>';
                          echo '<a onclick="insert_smiley(\':O\', \'modifyComment' . $comment->getId() . '\')"><img src="../../includes/icons/smileys/6.png" alt="smile" title=":O" class="smiley_2" /></a>';
                        echo '</div>';
                      echo '</form>';
                    echo '</span>';
                  }
                  // Affichage commentaire normal
                  else
                  {
                    // On cherche les liens dans les commentaires
                    $commentaire = extract_link(nl2br($comment->getComment()));

                    // Commentaire
                    echo '<div class="texte_commentaire">' . $commentaire . '</div>';
                  }
                echo '</div>';
              }

              // Saisie commentaire
  						echo '<form method="post" action="details.php?id_film=' . $detailsFilm->getId() . '&action=doCommenter" id="comments" class="saisie_commentaires_films">';
  							echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="insertComment" class="zone_saisie_comment" required></textarea>';
  							echo '<input type="submit" name="submit_comment" value="Envoyer" class="bouton_commentaires" />';

                echo '<div class="zone_saisie_smileys">';
                	echo '<a onclick="insert_smiley(\':)\', \'insertComment\')"><img src="../../includes/icons/smileys/1.png" alt="smile" title=":)" class="smiley_2" /></a>';
                	echo '<a onclick="insert_smiley(\';)\', \'insertComment\')"><img src="../../includes/icons/smileys/2.png" alt="smile" title=";)" class="smiley_2" /></a>';
                	echo '<a onclick="insert_smiley(\':(\', \'insertComment\')"><img src="../../includes/icons/smileys/3.png" alt="smile" title=":(" class="smiley_2" /></a>';
                	echo '<a onclick="insert_smiley(\':|\', \'insertComment\')"><img src="../../includes/icons/smileys/4.png" alt="smile" title=":|" class="smiley_2" /></a>';
                	echo '<a onclick="insert_smiley(\':D\', \'insertComment\')"><img src="../../includes/icons/smileys/5.png" alt="smile" title=":D" class="smiley_2" /></a>';
                	echo '<a onclick="insert_smiley(\':O\', \'insertComment\')"><img src="../../includes/icons/smileys/6.png" alt="smile" title=":O" class="smiley_2" /></a>';
                echo '</div>';
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
