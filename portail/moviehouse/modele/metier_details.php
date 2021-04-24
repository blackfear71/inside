<?php
  // METIER : Contrôle film existant et non à supprimer
  // RETOUR : Booléen
  function isFilmDisponible($idFilm)
  {
    // Contrôle film disponible
    $filmDisponible = controleFilmDisponible($idFilm);

    // Retour
    return $filmDisponible;
  }

  // METIER : Récupération détails film
  // RETOUR : Objet Movie
  function getDetails($idFilm, $identifiant)
  {
    // Récupération des données du film
    $film = physiqueFilm($idFilm);

    // Récupération des étoiles et de la participation de l'utilisateur
    $actionsUser = physiqueActionsUser($idFilm, $identifiant);

    $film->setStars_user($actionsUser['etoiles']);
    $film->setParticipation($actionsUser['participation']);

    // Récupération du nombre de participants
    $film->setNb_users(physiqueNombreParticipants($idFilm));

    // Retour
    return $film;
  }

  // METIER : Récupération films précédent et suivant pour navigation
  // RETOUR : Liste des films précédent et suivant
  function getNavigation($film)
  {
    // Initialisations
    $boutonPrecedent = array('id' => '',
                             'film' => ''
                            );
    $boutonSuivant   = array('id' => '',
                             'film' => ''
                            );

    // Récupération des données
    $idFilm      = $film->getId();
    $titreFilm   = $film->getFilm();
    $dateTheater = $film->getDate_theater();
    $anneeFilm   = substr($dateTheater, 0, 4);

    // Vérification film précédent existant
    $filmPrecedentExistant = physiqueFilmPrecedentExistant($idFilm, $titreFilm, $anneeFilm, $dateTheater);

    // Récupération du film précédent
    if ($filmPrecedentExistant == true)
      $filmPrecedent = physiqueFilmPrecedent($idFilm, $titreFilm, $anneeFilm, $dateTheater);

    // Vérification film suivant existant
    $filmSuivantExistant = physiqueFilmSuivantExistant($idFilm, $titreFilm, $anneeFilm, $dateTheater);

    // Récupération du film suivant
    if ($filmSuivantExistant == true)
      $filmSuivant = physiqueFilmSuivant($idFilm, $titreFilm, $anneeFilm, $dateTheater);

    // Création du bouton film précédent
    if (isset($filmPrecedent) AND !empty($filmPrecedent))
    {
      $titreFilmPrecedent      = formatString($filmPrecedent->getFilm(), 15);
      $boutonPrecedent['id']   = $filmPrecedent->getId();
      $boutonPrecedent['film'] = $titreFilmPrecedent;
    }

    // Création du bouton film suivant
    if (isset($filmSuivant) AND !empty($filmSuivant))
    {
      $titreFilmSuivant      = formatString($filmSuivant->getFilm(), 15);
      $boutonSuivant['id']   = $filmSuivant->getId();
      $boutonSuivant['film'] = $titreFilmSuivant;
    }

    // On ajoute la ligne au tableau
    $listeNavigation = array('previous' => $boutonPrecedent,
                             'next'     => $boutonSuivant
                            );

    // Retour
    return $listeNavigation;
  }

  // METIER : Récupération des étoiles utilisateurs d'un film
  // RETOUR : Liste des étoiles utilisateurs
  function getEtoilesDetailsFilm($idFilm, $listeUsers)
  {
    // Récupération des étoiles
    $listeEtoilesFilm = physiqueEtoilesFilm($idFilm);

    // Récupération pseudo et avatar
    foreach ($listeEtoilesFilm as $etoilesFilm)
    {
      $etoilesFilm->setPseudo($listeUsers[$etoilesFilm->getIdentifiant()]['pseudo']);
      $etoilesFilm->setAvatar($listeUsers[$etoilesFilm->getIdentifiant()]['avatar']);
      $etoilesFilm->setEmail($listeUsers[$etoilesFilm->getIdentifiant()]['email']);
    }

    // Retour
    return $listeEtoilesFilm;
  }

  // METIER : Conversion de la liste d'objets des détails d'un film en tableau simple pour JSON
  // RETOUR : Tableau des détails
  function convertForJsonDetailsFilm($detailsFilm)
  {
    // Conversion de l'objet en tableau pour envoyer au Javascript
    $detailsAConvertir = array('id'             => $detailsFilm->getId(),
                               'film'           => $detailsFilm->getFilm(),
                               'date_theater'   => formatDateForDisplay($detailsFilm->getDate_theater()),
                               'date_release'   => formatDateForDisplay($detailsFilm->getDate_release()),
                               'trailer'        => $detailsFilm->getTrailer(),
                               'link'           => $detailsFilm->getLink(),
                               'poster'         => $detailsFilm->getPoster(),
                               'synopsis'       => $detailsFilm->getSynopsis(),
                               'doodle'         => $detailsFilm->getDoodle(),
                               'date_doodle'    => formatDateForDisplay($detailsFilm->getDate_doodle()),
                               'hours_doodle'   => substr($detailsFilm->getTime_doodle(), 0, 2),
                               'minutes_doodle' => substr($detailsFilm->getTime_doodle(), 2, 2),
                               'restaurant'     => $detailsFilm->getRestaurant(),
                               'place'          => $detailsFilm->getPlace(),
                              );

    // Retour
    return $detailsAConvertir;
  }

  // METIER : Modification d'un film
  // RETOUR : Id film
  function updateFilm($post, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idFilm      = $post['id_film'];
    $nomFilm     = $post['nom_film'];
    $dateTheater = $post['date_theater'];
    $dateRelease = $post['date_release'];
    $link        = $post['link'];
    $poster      = $post['poster'];
    $synopsis    = $post['synopsis'];
    $trailer     = $post['trailer'];
    $doodle      = $post['doodle'];
    $dateDoodle  = $post['date_doodle'];

    if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
      $timeDoodle = $post['hours_doodle'] . $post['minutes_doodle'];
    else
      $timeDoodle = '';

    $restaurant = $post['restaurant'];
    $place      = $post['place'];

    // Contrôle date sortie cinéma
    if (isset($dateTheater) AND !empty($dateTheater))
    {
      // Contrôle format date sortie cinéma
      if ($control_ok == true)
        $control_ok = controleFormatDate($dateTheater);

      // Formatage de la date de sortie cinéma pour insertion
      if ($control_ok == true)
        $dateTheater = formatDateForInsert($dateTheater);
    }

    // Contrôle date sortie DVD / Bluray
    if ($control_ok == true)
    {
      if (isset($dateRelease) AND !empty($dateRelease))
      {
        // Contrôle format date sortie DVD / Bluray
        if ($control_ok == true)
          $control_ok = controleFormatDate($dateRelease);

        // Formatage de la date de sortie DVD / Bluray pour insertion
        if ($control_ok == true)
          $dateRelease = formatDateForInsert($dateRelease);
      }
    }

    // Contrôle date Doodle
    if ($control_ok == true)
    {
      if (isset($dateDoodle) AND !empty($dateDoodle))
      {
        // Contrôle format date Doodle
        if ($control_ok == true)
          $control_ok = controleFormatDate($dateDoodle);

        // Formatage de la date Doodle pour insertion
        if ($control_ok == true)
          $dateDoodle = formatDateForInsert($dateDoodle);
      }
    }

    // Contrôle date sortie film <= date Doodle
    if ($control_ok == true)
    {
      if (isset($dateTheater) AND !empty($dateTheater) AND isset($dateDoodle) AND !empty($dateDoodle))
        $control_ok = controleOrdreDates($dateTheater, $dateDoodle);
    }

    // Extraction de l'ID vidéo et modification de l'enregistrement en base
    if ($control_ok == true)
    {
      // Extraction de l'ID de la vidéo à partir de l'URL
      $idUrl = extractUrl($trailer);

      // Modification de l'enregistrement en base
      $film = array('film'         => $nomFilm,
                    'synopsis'     => $synopsis,
                    'date_theater' => $dateTheater,
                    'date_release' => $dateRelease,
                    'link'         => $link,
                    'poster'       => $poster,
                    'trailer'      => $trailer,
                    'id_url'       => $idUrl,
                    'doodle'       => $doodle,
                    'date_doodle'  => $dateDoodle,
                    'time_doodle'  => $timeDoodle,
                    'restaurant'   => $restaurant,
                    'place'        => $place
                   );

      physiqueUpdateFilm($idFilm, $film);

      // Gestion notification Doodle
      if (empty($doodle))
      {
        // Suppression notification si Doodle supprimé
        deleteNotification('doodle', $idFilm);
      }
      else
      {
        // Vérification si Doodle renseigné et notification existante
        $notificationDoodleExist = controlNotification('doodle', $idFilm);

        // Insertion notification
        if ($notificationDoodleExist != true)
          insertNotification($identifiant, 'doodle', $idFilm);
      }

      // Gestion notification sortie cinéma
      if (empty($dateDoodle))
      {
        // Suppression notification si Date sortie supprimée (cas notification générée par batch puis date supprimée le jour même)
        deleteNotification('cinema', $idFilm);
      }
      else
      {
        // Si la sortie est programmée pour le jour même
        if ($dateDoodle == date('Ymd'))
        {
          // Vérification si sortie cinéma programmée et notification existante
          $notificationCinemaExist = controlNotification('cinema', $idFilm);

          // Insertion notification
          if ($notificationCinemaExist != true)
            insertNotification('admin', 'cinema', $idFilm);
        }
      }

      // Message d'alerte
      $_SESSION['alerts']['film_updated'] = true;
    }

    // Retour
    return $idFilm;
  }

  // METIER : Lecture de la vue à partir des préférences utilisateur
  // RETOUR : Vue
  function getVueSuppression($identifiant)
  {
    // Lecture des préférences utilisateur
    $preferences = physiquePreferences($identifiant);

    // Détermination de la vue
    switch ($preferences->getView_movie_house())
    {
      case 'C':
        $viewMovieHouse = 'cards';
        break;

      case 'H':
      default:
        $viewMovieHouse = 'home';
        break;
    }

    // Retour
    return $viewMovieHouse;
  }

  // METIER : Demande de suppression d'un film
  // RETOUR : Aucun
  function deleteFilm($post, $identifiant)
  {
    // Récupération des données
    $idFilm   = $post['id_film'];
    $toDelete = 'Y';

    // Modification de l'enregistrement en base
    physiqueUpdateStatusFilm($idFilm, $toDelete, $identifiant);

    // Mise à jour du statut des notifications
    updateNotification('film', $idFilm, $toDelete);
    updateNotification('doodle', $idFilm, $toDelete);
    updateNotification('cinema', $idFilm, $toDelete);
    updateNotification('comments', $idFilm, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['film_removed'] = true;
  }

  // METIER : Récupération des commentaires d'un film
  // RETOUR : Liste des commentaires
  function getCommentaires($idFilm, $listeUsers)
  {
    // Récupération de la liste des commentaires
    $listeCommentaires = physiqueCommentaires($idFilm);

    // Récupération pseudo et avatar
    foreach ($listeCommentaires as $commentaire)
    {
      $commentaire->setPseudo($listeUsers[$commentaire->getAuthor()]['pseudo']);
      $commentaire->setAvatar($listeUsers[$commentaire->getAuthor()]['avatar']);
    }

    // Retour
    return $listeCommentaires;
  }

  // METIER : Insertion commentaire sur un détail film
  // RETOUR : Id film
  function insertCommentaire($post, $identifiant)
  {
    // Récupération des données
    $idFilm  = $post['id_film'];
    $comment = $post['comment'];

    // Insertion de l'enregistrement en table
    $commentaire = array('id_film' => $idFilm,
                         'author'  => $identifiant,
                         'date'    => date('Ymd'),
                         'time'    => date('His'),
                         'comment' => $comment,
                        );

    physiqueInsertionCommentaire($commentaire);

    // Vérification notification déjà présente
    $notificationCommentsExist = controlNotification('comments', $idFilm);

    // Insertion notification
    if ($notificationCommentsExist != true)
      insertNotification($identifiant, 'comments', $idFilm);

    // Génération succès
    insertOrUpdateSuccesValue('commentator', $identifiant, 1);

    // Retour
    return $idFilm;
  }

  // METIER : Modification commentaire sur un détail film
  // RETOUR : Id film et commentaire
  function updateCommentaire($post)
  {
    // Récupération des données
    $commentaire       = $post['comment'];
    $idFilmCommentaire = array('id_film'    => $post['id_film'],
                               'id_comment' => $post['id_comment']
                              );

    // Modification de l'enregistrement en base
    physiqueUpdateCommentaire($idFilmCommentaire['id_comment'], $commentaire);

    // Retour
    return $idFilmCommentaire;
  }

  // METIER : Suppression commentaire sur un détail film
  // RETOUR : Id film
  function deleteCommentaire($post, $identifiant)
  {
    // Récupération des données
    $idFilm        = $post['id_film'];
    $idCommentaire = $post['id_comment'];

    // Suppression de l'enregistrement en base
    physiqueDeleteCommentaire($idCommentaire);

    // Vérification dernier commentaire de la journée pour ce film
    $dernierCommentaireJour = physiqueDernierCommentaireJour($idFilm);

    // Suppression notification
    if ($dernierCommentaireJour == true)
      deleteNotification('comments', $idFilm);

    // Génération succès
    insertOrUpdateSuccesValue('commentator', $identifiant, -1);

    // Retour
    return $idFilm;
  }

  // METIER : Envoi de mail pour sortie film
  // RETOUR : Aucun
  function sendMail($details, $participants)
  {
    // Traitement de sécurité
    Movie::secureData($details);

    foreach ($participants as $participant)
    {
      Stars::secureData($participant);
    }

    // Envoi d'un mail par personne
    foreach ($participants as $participant)
    {
      if (!isset($_SESSION['alerts']['mail_film_error']) OR $_SESSION['alerts']['mail_film_error'] != true)
      {
        if (!empty($participant->getEmail()))
        {
          // Connexion au serveur de mails et initialisations
          include_once('../../includes/functions/appel_mail.php');

          // Destinataire du mail
          $mail->clearAddresses();
          $mail->AddAddress($participant->getEmail(), $participant->getPseudo());

          // Objet du mail
          $mail->Subject = 'Inside - Votre participation à "' . $details->getFilm() . '"';

          // Contenu du mail
          $message = getModeleMailFilm($details, $participants);
          $mail->MsgHTML($message);

          // Envoi du mail avec message d'alerte
          if (!$mail->Send())
            $_SESSION['alerts']['mail_film_error'] = true;
          else
            $_SESSION['alerts']['mail_film_send']  = true;
        }
      }
    }
  }
?>
