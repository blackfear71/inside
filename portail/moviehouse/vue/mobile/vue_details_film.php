<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Movie House';
      $styleHead       = 'styleMH.css';
      $scriptHead      = 'scriptMH.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'moviehouse';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
			<article>
				<?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($detailsFilm->getFilm()) . '</div>';

          /**********/
          /* Saisie */
          /**********/
          include('vue/mobile/vue_saisie_film.php');

          /***********/
          /* Détails */
          /***********/
          if ($filmExistant == true)
          {
            // Poster
            if (!empty($detailsFilm->getPoster()))
              echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="image_details_film" />';
            else
              echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="image_details_film" />';

            // Actions
            echo '<div class="zone_details_film_actions">';
              // Modifier
              echo '<a id="modifierFilm" class="lien_details_film">';
                echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" title="Modifier le film" class="icone_details_film" />';
              echo '</a>';

              // Supprimer
              echo '<form id="delete_film" method="post" action="details.php?action=doSupprimer" class="lien_details_film">';
                echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                echo '<input type="submit" name="delete_film" value="" title="Demander la suppression" class="icone_details_film_delete eventConfirm" />';
                echo '<input type="hidden" value="Demander la suppression de ce film ?" class="eventMessage" />';
              echo '</form>';

              // Mailing (si on participe)
              if ($detailsFilm->getStars_user() > 0)
              {
                echo '<a href="mailing.php?id_film=' . $detailsFilm->getId() . '&action=goConsulter" class="lien_details_film">';
                  echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing" title="Envoyer mail" class="icone_details_film" />';
                echo '</a>';
              }

              // Doodle
              if (!empty($detailsFilm->getDoodle()))
              {
                echo '<a href="' . $detailsFilm->getDoodle() . '" target="_blank" class="lien_details_film">';
                  echo '<img src="../../includes/icons/moviehouse/doodle.png" alt="doodle" title="Doodle" class="icone_details_film" />';
                echo '</a>';
              }
              else
              {
                echo '<a id="doodleFilm" href="https://doodle.com/fr/" target="_blank" class="lien_details_film">';
                  echo '<img src="../../includes/icons/moviehouse/doodle_none.png" alt="doodle_none" title="Doodle" class="icone_details_film" />';
                echo '</a>';
              }

              // Fiche du film
              if (!empty($detailsFilm->getLink()))
              {
                echo '<a href="' . $detailsFilm->getLink() . '" target="_blank" class="lien_details_film">';
                  echo '<img src="../../includes/icons/moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="icone_details_film" />';
                echo '</a>';
              }
            echo '</div>';

            // Vote et participation utilisateur
            echo '<div class="zone_details_film_user">';
              echo '<form method="post" action="details.php?action=doVoterFilm" class="form_preference_details">';
                echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                // Etoiles utilisateur
                for ($j = 0; $j <= 5; $j++)
                {
                  if ($j == $detailsFilm->getStars_user())
                  {
                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="icone_preference" />';
                    echo '<input type="submit" name="preference_' . $j . '" value="" class="input_preference rounded" />';
                  }
                  else
                  {
                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="icone_preference" />';
                    echo '<input type="submit" name="preference_' . $j . '" value="" class="input_preference" />';
                  }
                }
              echo '</form>';

              // Actions de participation
              if ($detailsFilm->getStars_user() > 0)
              {
                echo '<form method="post" action="details.php?action=doParticiperFilm" class="form_participation_details">';
                  echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                  // Participation
                  if ($detailsFilm->getParticipation() == 'P')
                    echo '<input type="submit" name="participate" value="" class="input_participate_yes" />';
                  else
                    echo '<input type="submit" name="participate" value="" class="input_participate_no" />';

                  // Vue
                  if ($detailsFilm->getParticipation() == 'S')
                    echo '<input type="submit" name="seen" value="" class="input_seen_yes" />';
                  else
                    echo '<input type="submit" name="seen" value="" class="input_seen_no" />';

                echo '</form>';
              }
            echo '</div>';

            // Personnes intéressées
            echo '<div class="zone_details_film_votes">';
              // Titre
              echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/moviehouse/users_grey.png" alt="moviehouse" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Personnes intéressées</div>';
              echo '</div>';

              // Liste des étoiles
              if (!empty($listeEtoiles))
              {
                foreach ($listeEtoiles as $etoiles)
                {
                  if ($etoiles->getParticipation() == 'S')
                    echo '<div class="zone_etoiles_user seen_interested">';
                  elseif ($etoiles->getParticipation() == 'P')
                    echo '<div class="zone_etoiles_user participate_interested">';
                  elseif ($etoiles->getIdentifiant() == $_SESSION['user']['identifiant'])
                    echo '<div class="zone_etoiles_user current_user_interested">';
                  else
                    echo '<div class="zone_etoiles_user">';

                    // Avatar
                    $avatarFormatted = formatAvatar($etoiles->getAvatar(), $etoiles->getPseudo(), 2, 'avatar');

                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_interested" />';

                    // Pseudo
                    echo '<div class="pseudo_interested">' . formatString($etoiles->getPseudo(), 30) . '</div>';

                    // Etoiles
                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $etoiles->getStars() . '.png" alt="star' . $etoiles->getStars() . '" class="star_interested" />';
                  echo '</div>';
                }
              }
              else
                echo '<div class="empty">Aucune personne encore intéressée...</div>';
            echo '</div>';

            // Détails du film
            echo '<div class="zone_details_film">';
              // Titre
              echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">À propos du film</div>';
              echo '</div>';

              // Vidéo
              if (!empty($detailsFilm->getId_url()))
              {
                echo '<div class="video_container">';
                  $exp  = explode(':_:', $detailsFilm->getId_url());
                  $html = '';

                  switch ($exp[0])
                  {
                    case 'youtube':
                      $html = '<iframe src="http://www.youtube.com/embed/' . $exp[1] . '" allowfullscreen class="video_details_film"></iframe>';
                      break;

                    case 'vimeo':
                      $html = '<iframe src="http://player.vimeo.com/video/'.$exp[1].'" allowFullScreen class="video_details_film"></iframe>';
                      break;

                    case 'dailymotion':
                      $html = '<iframe src="http://www.dailymotion.com/embed/video/'.$exp[1].'" allowfullscreen class="video_details_film"></iframe>';
                      break;

                    default:
                      break;
                  }

                  echo $html;
                echo '</div>';
              }

              // Synopsis
              if (!empty($detailsFilm->getSynopsis()))
                echo '<div class="contenu_synopsis">' . nl2br($detailsFilm->getSynopsis()) . '</div>';

              // Date sortie cinéma
              echo '<div class="zone_details_film_date_1">';
                echo '<img src="../../includes/icons/moviehouse/date.png" alt="date" class="icone_details_film_date" />';

                echo '<div class="date_details_film">';
                  if (!empty($detailsFilm->getDate_theater()))
                    echo formatDateForDisplay($detailsFilm->getDate_theater());
                  else
                    echo 'N. C.';
                echo '</div>';
              echo '</div>';

              // Date sortie DVD / Bluray
              echo '<div class="zone_details_film_date_2">';
                echo '<img src="../../includes/icons/moviehouse/disk.png" alt="disk" class="icone_details_film_date" />';

                echo '<div class="date_details_film">';
                  if (!empty($detailsFilm->getDate_release()))
                    echo formatDateForDisplay($detailsFilm->getDate_release());
                  else
                    echo '-';
                echo '</div>';
              echo '</div>';

              // Date Doodle proposée
              echo '<div class="zone_details_film_date_doodle">';
                echo '<img src="../../includes/icons/moviehouse/doodle_white.png" alt="doodle_white" class="icone_details_film_date" />';

                echo '<div class="date_details_film">';
                  if (!empty($detailsFilm->getDate_doodle()))
                  {
                    echo formatDateForDisplay($detailsFilm->getDate_doodle());

                    if (!empty($detailsFilm->getTime_doodle()))
                    {
                      $heureDoodle   = substr($detailsFilm->getTime_doodle(), 0, 2);
                      $minutesDoodle = substr($detailsFilm->getTime_doodle(), 2, 2);

                      echo ' à ' . $heureDoodle . ':' . $minutesDoodle;
                    }
                  }
                  else
                    echo '-';
                echo '</div>';
              echo '</div>';

              // Restaurant
              echo '<div class="zone_details_film_restaurant_1">';
                echo '<img src="../../includes/icons/moviehouse/restaurants.png" alt="restaurants" class="icone_details_film_date" />';

                echo '<div class="restaurant_details_film_1">';
                  switch ($detailsFilm->getRestaurant())
                  {
                    case 'B':
                      echo 'Avant';
                      break;

                    case 'A':
                      echo 'Après';
                      break;

                    case 'N':
                    default:
                      echo 'Aucun';
                      break;
                  }
                echo '</div>';
              echo '</div>';

              echo '<div class="zone_details_film_restaurant_2">';
                echo '<div class="restaurant_details_film_2">';
                if (!empty($detailsFilm->getPlace()))
                  echo formatString($detailsFilm->getPlace(), 15);
                else
                  echo '-';
                echo '</div>';
              echo '</div>';
            echo '</div>';

            // Commentaires
            echo '<div class="zone_details_film_commentaires">';
              // Titre
              echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/moviehouse/comments_grey.png" alt="comments_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Commentaires</div>';
              echo '</div>';

              // Liste des commentaires
              if (!empty($listeCommentaires))
              {
                // Affichage des commentaires
                foreach ($listeCommentaires as $comment)
                {
                  echo '<div class="zone_commentaire_user" id="' . $comment->getId() . '">';
                    // Avatar
                    echo '<div class="zone_avatar_commentaire">';
                      $avatarFormatted = formatAvatar($comment->getAvatar(), $comment->getPseudo(), 2, 'avatar');

                      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_commentaire" />';
                    echo '</div>';

                    echo '<div class="zone_infos_commentaire_film">';
                      // Pseudo
                      echo '<div class="pseudo_commentaire_film">' . formatUnknownUser($comment->getPseudo(), true, true) . '</div>';

                      // Date et heure
                      echo '<div class="date_commentaire_film">Le ' . formatDateForDisplay($comment->getDate()) . ' à ' . formatTimeForDisplay($comment->getTime()) . '</div>';
                    echo '</div>';

                    // Actions sur commentaires seulement si l'auteur correspond à l'utilisateur connecté
                    if ($comment->getAuthor() == $_SESSION['user']['identifiant'])
                    {
                      /***************************************************/
                      /* Ligne visualisation normale (sans modification) */
                      /***************************************************/
                      // Boutons d'action (visualisation)
                      echo '<div id="actions_visualisation_comment_' . $comment->getId() . '" class="zone_actions_commentaire_film">';
                        // Modification commentaire
                        echo '<span class="lien_actions_commentaire lien_actions_commentaire_margin">';
                          echo '<a id="modifier_commentaire_' . $comment->getId() . '" title="Modifier le commentaire" class="icone_modifier_commentaire modifierCommentaire"></a>';
                        echo '</span>';

                        // Suppression commentaire
                        echo '<form id="delete_comment_' . $comment->getId() . '" method="post" action="details.php?action=doSupprimerCommentaire" class="lien_actions_commentaire">';
                          echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                          echo '<input type="hidden" name="id_comment" value="' . $comment->getId() . '" />';
                          echo '<input type="submit" name="delete_comment" value="" title="Supprimer le commentaire" class="icone_supprimer_commentaire eventConfirm" />';
                          echo '<input type="hidden" value="Supprimer ce commentaire ?" class="eventMessage" />';
                        echo '</form>';
                      echo '</div>';

                      // Commentaire
                      echo '<div id="visualiser_comment_' . $comment->getId() . '">';
                        // On cherche les smileys dans les commentaires
                        $commentaire = extractSmiley($comment->getComment());

                        // On cherche les liens dans les commentaires
                        $commentaire = extractLink(nl2br($commentaire));

                        // Commentaire
                        echo '<div class="texte_commentaire_film">' . $commentaire . '</div>';
                      echo '</div>';

                      /**********************************/
                      /* Ligne cachée pour modification */
                      /**********************************/
                      // Saisie commentaire
                      echo '<form method="post" action="details.php?action=doModifierCommentaire" id="modifier_comment_' . $comment->getId() . '" class="modification_commentaire_hidden">';
                        // Id film et commentaire
                        echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                        echo '<input type="hidden" name="id_comment" value="' . $comment->getId() . '" />';

                        // Saisie commentaire
                        echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="textarea_comment_' . $comment->getId() . '" class="saisie_commentaire_textarea" required>' . $comment->getComment() . '</textarea>';

                        // Insertion smiley
                        echo '<div class="zone_saisie_smileys">';
                          echo '<a id="modifier_smiley_1_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/1.png" alt="smiley" title=":)" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_2_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/2.png" alt="smiley" title=";)" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_3_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/3.png" alt="smiley" title=":(" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_4_' . $comment->getId() . '" class="modifierSmiley""><img src="../../includes/icons/common/smileys/4.png" alt="smiley" title=":|" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_5_' . $comment->getId() . '" class="modifierSmiley""><img src="../../includes/icons/common/smileys/5.png" alt="smiley" title=":D" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_6_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/6.png" alt="smiley" title=":O" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_7_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/7.png" alt="smiley" title=":P" class="smiley_2" /></a>';
                          echo '<a id="modifier_smiley_8_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/8.png" alt="smiley" title=":facepalm:" class="smiley_2" /></a>';
                        echo '</div>';

                        // Boutons d'action (modification)
                        echo '<div class="zone_actions_commentaire_film">';
                          // Validation modification commentaire
                          echo '<span class="lien_actions_commentaire lien_actions_commentaire_margin">';
                            echo '<input type="submit" name="update_comment" value="" title="Valider la modification" class="icone_valider_comment" />';
                          echo '</span>';

                          // Annulation modification commentaire
                          echo '<span class="lien_actions_commentaire">';
                            echo '<a id="annuler_commentaire_' . $comment->getId() . '" title="Annuler la modification" class="icone_annuler_comment annulerCommentaire"></a>';
                          echo '</span>';
                        echo '</div>';
                      echo '</form>';
                    }
                    // Affichage commentaire normal
                    else
                    {
                      // On cherche les smileys dans les commentaires
                      $commentaire = extractSmiley($comment->getComment());

                      // On cherche les liens dans les commentaires
                      $commentaire = extractLink(nl2br($commentaire));

                      // Commentaire
                      echo '<div class="texte_commentaire_film">' . $commentaire . '</div>';
                    }
                  echo '</div>';
                }
              }
              else
                echo '<div class="empty">Pas encore de commentaires...</div>';

              // Saisie commentaire
  						echo '<form method="post" action="details.php?action=doCommenter" id="comments">';
                // Id film
  							echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                // Saisie commentaire
                echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="textarea_comment_0" class="saisie_commentaire_textarea" required></textarea>';

                // Insertion smileys
                echo '<div class="zone_saisie_smileys">';
                	echo '<a id="modifier_smiley_1_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/1.png" alt="smiley" title=":)" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_2_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/2.png" alt="smiley" title=";)" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_3_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/3.png" alt="smiley" title=":(" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_4_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/4.png" alt="smiley" title=":|" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_5_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/5.png" alt="smiley" title=":D" class="smiley_2" /></a>';
                  echo '<a id="modifier_smiley_6_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/6.png" alt="smiley" title=":O" class="smiley_2" /></a>';
                  echo '<a id="modifier_smiley_7_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/7.png" alt="smiley" title=":P" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_8_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/8.png" alt="smiley" title=":facepalm:" class="smiley_2" /></a>';
                echo '</div>';

                // Bouton d'action
                echo '<input type="submit" name="submit_comment" value="Envoyer" class="bouton_saisie_commentaire" />';
  						echo '</form>';
            echo '</div>';
          }
				?>
			</article>
		</section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer_mobile.php'); ?>
    </footer>

    <!-- Données JSON -->
    <script>
      // Récupération des détails du film pour le script
      var detailsFilm = <?php if (isset($detailsFilmJson) AND !empty($detailsFilmJson)) echo $detailsFilmJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
