<?php
  // METIER : Contrôle film existant et non à supprimer
  // RETOUR : Booléen
  function controlFilm($id_film)
  {
    global $bdd;

    $filmExistant = false;

    // Contrôle film existant
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $filmExistant = true;

    $reponse->closeCursor();

    if ($filmExistant == true)
    {
      // Contrôle film non à supprimer
      $reponse2 = $bdd->query('SELECT id, to_delete FROM movie_house WHERE id = ' . $id_film);
      $donnees2 = $reponse2->fetch();

      if ($donnees2['to_delete'] == "Y")
        $filmExistant = false;

      $reponse2->closeCursor();
    }

    if ($filmExistant == false)
      $_SESSION['alerts']['film_doesnt_exist'] = true;

    return $filmExistant;
  }

  // METIER : Récupération film précédent et suivant pour navigation
  // RETOUR : Liste de films précédent et suivant
  function getNavigation($id_film)
  {
    $listNavigation   = array();
    $bouton_precedent = array();
    $bouton_suivant   = array();

    global $bdd;

    // On récupère l'année du film
    $reponse = $bdd->query('SELECT id, date_theater FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    $anneeCourante = substr($donnees['date_theater'], 0, 4);

    $reponse->closeCursor();

    // On récupère la liste des films pour trouver le film précédent et suivant
    $listFilms = getFilms($anneeCourante, NULL);

    // On cherche le film précédent et suivant dans la liste
    for ($i = 0; $i < count($listFilms); $i++)
    {
      if ($listFilms[$i]->getId() == $id_film)
      {
        // Bouton précédent
        if (isset($listFilms[$i - 1]) AND !empty($listFilms[$i - 1]->getId()) AND !empty($listFilms[$i - 1]->getFilm()))
        {
          // On raccourci le texte s'il est trop long
          $max_caracteres = 15;
          $titre          = $listFilms[$i - 1]->getFilm();

          // Test si la longueur du texte dépasse la limite
          if (strlen($titre) > $max_caracteres)
          {
            // Sélection du maximum de caractères
            $titre = substr($titre, 0, $max_caracteres);

            // Ajout des "..."
            $titre = $titre . '...';
          }

          // Stockage
          $bouton_precedent = array('id'   => $listFilms[$i - 1]->getId(),
                                    'film' => $titre
                                  );
        }

        // Bouton suivant
        if (isset($listFilms[$i + 1]) AND !empty($listFilms[$i + 1]->getId()) AND !empty($listFilms[$i + 1]->getFilm()))
        {
          // On raccourci le texte s'il est trop long
          $max_caracteres = 15;
          $titre          = $listFilms[$i + 1]->getFilm();

          // Test si la longueur du texte dépasse la limite
          if (strlen($titre) > $max_caracteres)
          {
            // Sélection du maximum de caractères
            $titre = substr($titre, 0, $max_caracteres);

            // Ajout des "..."
            $titre = $titre . '...';
          }

          // Stockage
          $bouton_suivant = array('id'   => $listFilms[$i + 1]->getId(),
                                  'film' => $titre
                                 );
        }

        $listNavigation = array('previous' => $bouton_precedent,
                                'next'     => $bouton_suivant
                               );
      }
    }

    return $listNavigation;
  }

  // METIER : Récupération détails film
  // RETOUR : Objet film
  function getDetails($id_film, $user)
  {
    global $bdd;

    // On récupère les données du film
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    $film = Movie::withData($donnees);

    $reponse->closeCursor();

    // On récupère les étoiles et la participation de l'utilisateur connecté
    if (isset($user))
    {
      $reponse2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['stars']))
        $film->setStars_user($donnees2['stars']);

      if (isset($donnees2['participation']))
        $film->setParticipation($donnees2['participation']);

      $reponse2->closeCursor();
    }

    // On récupère le nombre de participants
    $reponse3 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $id_film);
    $donnees3 = $reponse3->fetch();

    $film->setNb_users($donnees3['nb_users']);

    $reponse3->closeCursor();

    return $film;
  }

  // METIER : Récupération étoiles utilisateur sur détails film
  // RETOUR : Liste des étoiles utilisateurs
  function getDetailsStars($id_film)
  {
    $listStars = array();

    global $bdd;

    // Récupération d'une liste des étoiles
    $reponse = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // On récupère le pseudo des utilisateurs
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar, email FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();
      {
        $pseudo = $donnees2['pseudo'];
        $avatar = $donnees2['avatar'];
        $email  = $donnees2['email'];
      }
      $reponse2->closeCursor();

      $stars = Stars::withData($donnees);
      $stars->setPseudo($pseudo);
      $stars->setAvatar($avatar);
      $stars->setEmail($email);

      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listStars, $stars);
    }
    $reponse->closeCursor();

    return $listStars;
  }

  // METIER : Conversion du tableau d'objets des détails d'un film en tableau simple pour JSON
  // RETOUR : Tableau des détails
  function convertForJson($detailsFilm)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
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

    return $detailsAConvertir;
  }

  // METIER : Modification film
  // RETOUR : Id film
  function updateFilm($post, $user)
  {
    $control_ok = true;

    // Récupération des variables
    $id_film      = $post['id_film'];
    $nom_film     = $post['nom_film'];
    $to_delete    = "N";
    $date_theater = "";
    $date_release = "";
    $link         = $post['link'];
    $poster       = $post['poster'];
    $synopsis     = $post['synopsis'];
    $trailer      = $post['trailer'];
    $doodle       = $post['doodle'];
    $date_doodle  = "";

    if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
      $time_doodle = $post['hours_doodle'] . $post['minutes_doodle'];
    else
      $time_doodle = "";

    $restaurant   = $post['restaurant'];
    $place        = $post['place'];

    // Récupération ID vidéo
    $id_url = extract_url($trailer);

    // Contrôle date sortie cinéma
    if ($control_ok == true)
    {
      // Récupération date sortie cinéma
      $date_a_verifier_1 = $post['date_theater'];

      // Vérification date à vide
      if (empty($date_a_verifier_1))
      {
        if (isLastDayOfYearWednesday(date('Y')))
        {
          $date_a_verifier_1 = '30/12/' . date('Y');
          $date_theater      = date('Y') . '1230';
        }
        else
        {
          $date_a_verifier_1 = '31/12/' . date('Y');
          $date_theater      = date('Y') . '1231';
        }
      }
      else
        $date_theater = formatDateForInsert($date_a_verifier_1);

      // On contrôle la date
      if (validateDate($date_a_verifier_1, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    // Contrôle date sortie DVD / Bluray
    if ($control_ok == true)
    {
      if (isset($post['date_release']) AND !empty($post['date_release']))
      {
        $date_a_verifier_2 = $post['date_release'];

        // On contrôle la date
        if (validateDate($date_a_verifier_2, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_release = formatDateForInsert($date_a_verifier_2);
      }
    }

    // Contrôle date Doodle
    if ($control_ok == true)
    {
      if (isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        $date_a_verifier_3 = $post['date_doodle'];

        // On contrôle la date
        if (validateDate($date_a_verifier_3, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_doodle = formatDateForInsert($date_a_verifier_3);
      }
    }

    // Contrôle date Doodle >= date sortie film
    if ($control_ok == true)
    {
      if (isset($post['date_theater']) AND !empty($post['date_theater']) AND isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        if ($date_doodle < $date_theater)
        {
          $_SESSION['alerts']['wrong_date_doodle'] = true;
          $control_ok                              = false;
        }
      }
    }

    // Modification en base
    if ($control_ok == true)
    {
      $film = array('film'         => $nom_film,
                    'synopsis'     => $synopsis,
                    'date_theater' => $date_theater,
                    'date_release' => $date_release,
                    'link'         => $link,
                    'poster'       => $poster,
                    'trailer'      => $trailer,
                    'id_url'       => $id_url,
                    'doodle'       => $doodle,
                    'date_doodle'  => $date_doodle,
                    'time_doodle'  => $time_doodle,
                    'restaurant'   => $restaurant,
                    'place'        => $place
                   );

      global $bdd;

      // Modification de l'enregistrement en table
      $req = $bdd->prepare('UPDATE movie_house SET film         = :film,
                                                   synopsis     = :synopsis,
                                                   date_theater = :date_theater,
                                                   date_release = :date_release,
                                                   link         = :link,
                                                   poster       = :poster,
                                                   trailer      = :trailer,
                                                   id_url       = :id_url,
                                                   doodle       = :doodle,
                                                   date_doodle  = :date_doodle,
                                                   time_doodle  = :time_doodle,
                                                   restaurant   = :restaurant,
                                                   place        = :place
                                             WHERE id = ' . $id_film);
      $req->execute($film);
      $req->closeCursor();

      // Génération notification si Doodle renseigné et notification inexistante
      $notification_doodle_exist = controlNotification('doodle', $id_film);

      if ($notification_doodle_exist != true AND !empty($doodle))
        insertNotification($user, 'doodle', $id_film);

      // Suppression notification si Doodle supprimé
      if (empty($doodle))
        deleteNotification('doodle', $id_film);

      // Suppression notification si Date sortie supprimée (cas notification générée par batch puis date supprimée le jour même)
      if (empty($date_doodle))
        deleteNotification('cinema', $id_film);

      $_SESSION['alerts']['film_updated'] = true;
    }
    else
      $_SESSION['alerts']['wrong_date'] = true;

    return $id_film;
  }

  // METIER : Demande de suppression d'un film
  // RETOUR : Aucun
  function deleteFilm($post, $user)
  {
    global $bdd;

    $id_film   = $post['id_film'];
    $to_delete = "Y";

    // Modification de l'enregistrement en table
    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete, identifiant_del = :identifiant_del WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete'       => $to_delete,
      'identifiant_del' => $user
    ));
    $req->closeCursor();

    $_SESSION['alerts']['film_removed'] = true;
  }

  // METIER : Récupération des commentaires sur détails film
  // RETOUR : Liste des commentaires
  function getComments($id_film)
  {
    $listComments = array();

    global $bdd;

    // Récupération d'une liste des commentaires
    $reponse = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $id_film . ' ORDER BY id ASC');
    while ($donnees = $reponse->fetch())
    {
      // On récupère le pseudo des utilisateurs
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['author'] . '"');
      $donnees2 = $reponse2->fetch();

      $pseudo = $donnees2['pseudo'];
      $avatar = $donnees2['avatar'];

      $reponse2->closeCursor();

      $comment = Comments::withData($donnees);
      $comment->setPseudo($pseudo);
      $comment->setAvatar($avatar);

      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listComments, $comment);
    }
    $reponse->closeCursor();

    return $listComments;
  }

  // METIER : Insertion commentaire sur un détail film
  // RETOUR : Id film
  function insertComment($post, $user)
  {
    global $bdd;

    // On récupère les données
    $id_film = $post['id_film'];
    $author  = $user;
    $date    = date("Ymd");
    $time    = date("His");
    $comment = $post['comment'];

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO movie_house_comments(id_film, author, date, time, comment) VALUES(:id_film, :author, :date, :time, :comment)');
    $req->execute(array(
      'id_film' => $id_film,
      'author'  => $author,
      'date'    => $date,
      'time'    => $time,
      'comment' => $comment
        ));
    $req->closeCursor();

    // Génération notification commentaires une fois par jour et par film
    $notification_comments_exist = controlNotification('comments', $id_film);

    if ($notification_comments_exist != true)
      insertNotification($user, 'comments', $id_film);

    // Génération succès
    insertOrUpdateSuccesValue('commentator', $user, 1);

    return $id_film;
  }

  // METIER : Modification commentaire sur un détail film
  // RETOUR : Id film et commentaire
  function updateComment($post)
  {
    $ids = array('id_film' => $post['id_film'], 'id_comment' => $post['id_comment']);

    global $bdd;

    // Modification de l'enregistrement en table
    $req = $bdd->prepare('UPDATE movie_house_comments SET comment = :comment WHERE id = ' . $ids['id_comment']);
    $req->execute(array(
      'comment' => $post['comment']
    ));
    $req->closeCursor();

    return $ids;
  }

  // METIER : Suppression commentaire sur un détail film
  // RETOUR : Id film
  function deleteComment($post, $user)
  {
    $id_film    = $post['id_film'];
    $id_comment = $post['id_comment'];

    global $bdd;

    // Suppression commentaire
    $reponse1 = $bdd->exec('DELETE FROM movie_house_comments WHERE id = ' . $id_comment);

    // Vérification dernier commentaire de la journée et sinon suppression notification
    $reponse2 = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $id_film . ' AND date = ' . date('Ymd'));
    $donnees2 = $reponse2->fetch();

    if ($reponse2->rowCount() == 0)
      deleteNotification('comments', $id_film);

    $reponse2->closeCursor();

    // Génération succès
    insertOrUpdateSuccesValue('commentator', $user, -1);

    return $id_film;
  }

  // METIER : Envoi mail sortie film
  // RETOUR : Aucun
  function sendMail($details, $participants)
  {
    // Traitement de sécurité
    $details->setId(htmlspecialchars($details->getId()));
    $details->setFilm(htmlspecialchars($details->getFilm()));
    $details->setTo_delete(htmlspecialchars($details->getTo_delete()));
    $details->setDate_add(htmlspecialchars($details->getDate_add()));
    $details->setIdentifiant_add(htmlspecialchars($details->getIdentifiant_add()));
    $details->setPseudo_add(htmlspecialchars($details->getPseudo_add()));
    $details->setIdentifiant_del(htmlspecialchars($details->getIdentifiant_del()));
    $details->setPseudo_del(htmlspecialchars($details->getPseudo_del()));
    $details->setSynopsis(htmlspecialchars($details->getSynopsis()));
    $details->setDate_theater(htmlspecialchars($details->getDate_theater()));
    $details->setDate_release(htmlspecialchars($details->getDate_release()));
    $details->setLink(htmlspecialchars($details->getLink()));
    $details->setPoster(htmlspecialchars($details->getPoster()));
    $details->setTrailer(htmlspecialchars($details->getTrailer()));
    $details->setId_url(htmlspecialchars($details->getId_url()));
    $details->setDoodle(htmlspecialchars($details->getDoodle()));
    $details->setDate_doodle(htmlspecialchars($details->getDate_doodle()));
    $details->setTime_doodle(htmlspecialchars($details->getTime_doodle()));
    $details->setRestaurant(htmlspecialchars($details->getRestaurant()));
    $details->setNb_comments(htmlspecialchars($details->getNb_comments()));
    $details->setStars_user(htmlspecialchars($details->getStars_user()));
    $details->setParticipation(htmlspecialchars($details->getParticipation()));
    $details->setNb_users(htmlspecialchars($details->getNb_users()));
    $details->setAverage(htmlspecialchars($details->getAverage()));

    foreach ($participants as $participant)
    {
      $participant->setId(htmlspecialchars($participant->getId()));
      $participant->setId_film(htmlspecialchars($participant->getId_film()));
      $participant->setIdentifiant(htmlspecialchars($participant->getIdentifiant()));
      $participant->setPseudo(htmlspecialchars($participant->getPseudo()));
      $participant->setAvatar(htmlspecialchars($participant->getAvatar()));
      $participant->setEmail(htmlspecialchars($participant->getEmail()));
      $participant->setStars(htmlspecialchars($participant->getStars()));
      $participant->setParticipation(htmlspecialchars($participant->getParticipation()));
    }

    // On envoie un mail par personne et non un mail groupé
    foreach ($participants as $participant)
    {
      if (!isset($_SESSION['alerts']['mail_film_error']) OR $_SESSION['alerts']['mail_film_error'] != true)
      {
        if (!empty($participant->getEmail()))
        {
          include_once('../../includes/functions/appel_mail.php');

          // Destinataire
          $mail->clearAddresses();
          $mail->AddAddress($participant->getEmail(), $participant->getPseudo());

          // Objet
          $mail->Subject = 'Votre participation à "' . $details->getFilm() . '"';

          // Contenu message
          $message = getModeleFilm($details, $participants);
          $mail->MsgHTML($message);

          // Envoi du mail avec gestion des erreurs
          if (!$mail->Send())
          {
            echo 'Erreur : ' . $mail->ErrorInfo;
            $_SESSION['alerts']['mail_film_error'] = true;
          }
          else
            $_SESSION['alerts']['mail_film_send']  = true;

          //var_dump($mail);
          //echo $message;
        }
      }
    }
  }
?>
