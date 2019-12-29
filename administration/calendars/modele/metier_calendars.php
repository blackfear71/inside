<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/calendars.php');

  // METIER : Récupération préférences tous utilisateurs
  // RETOUR : Liste des préférences
  function getListePreferences()
  {
    $listPreferences = array();
    $pseudo          = '';

    global $bdd;

    $req = $bdd->query('SELECT * FROM preferences ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      // Récupération pseudo
      $req2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $data['identifiant'] . '"');
      $data2 = $req2->fetch();

      $pseudo = $data2['pseudo'];

      $req2->closeCursor();

      $myPreference = array('id'               => $data['id'],
                            'identifiant'      => $data['identifiant'],
                            'pseudo'           => $pseudo,
                            'manage_calendars' => $data['manage_calendars']
                           );
      array_push($listPreferences, $myPreference);
    }
    $req->closeCursor();

    return $listPreferences;
  }

  // METIER : Lecture des calendriers à supprimer
  // RETOUR : Liste des calendriers à supprimer
  function getCalendarsToDelete()
  {
    $listToDelete = array();

    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars WHERE to_delete = "Y" ORDER BY year DESC, month DESC, id DESC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Calendrier::withData($donnees);
      $myDelete->setTitle($listeMois[$myDelete->getMonth()] . " " . $myDelete->getYear());

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

    return $listToDelete;
  }

  // METIER : Contrôle alertes Calendars
  // RETOUR : Booléen
  function getAlerteCalendars()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars WHERE to_delete = "Y"');
    while($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Lecture des annexes à supprimer
  // RETOUR : Liste des annexes à supprimer
  function getAnnexesToDelete()
  {
    $listToDelete = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE to_delete = "Y" ORDER BY id DESC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Annexe::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

    return $listToDelete;
  }

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars_annexes WHERE to_delete = "Y"');
    while($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Mise à jour des autorisations sur les calendriers
  // RETOUR : Aucun
  function updateAutorisations($post)
  {
    global $bdd;

    $req = $bdd->query('SELECT * FROM preferences');
    while($data = $req->fetch())
    {
      // Par défaut, le top autorisation est à Non
      $manage_calendars = "N";

      if (!empty($post['autorization']))
      {
        foreach ($post['autorization'] as $id => $ligne)
        {
          if ($data['id'] == $id)
          {
            // Si seulement on a activé bouton, on passe le top autorisation à Oui
            $manage_calendars = "Y";
            break;
          }
        }
      }

      // Dans tous les cas on met à jour chaque préférence de profil
      $req2 = $bdd->prepare('UPDATE preferences SET manage_calendars = :manage_calendars WHERE id = ' . $data['id']);
      $req2->execute(array(
        'manage_calendars' => $manage_calendars
      ));
      $req2->closeCursor();
    }
    $req->closeCursor();

    $_SESSION['alerts']['autorizations_updated'] = true;
  }

  // METIER : Supprime un calendrier de la base
  // RETOUR : Aucun
  function deleteCalendrier($post)
  {
    $id_cal = $post['id_cal'];

    global $bdd;

    // On efface le calendrier si présent
    $reponse = $bdd->query('SELECT * FROM calendars WHERE id = ' . $id_cal);
    $donnees = $reponse->fetch();

    if (isset($donnees['calendar']) AND !empty($donnees['calendar']))
    {
      unlink ("../../includes/images/calendars/" . $donnees['year'] . "/" . $donnees['calendar']);
      unlink ("../../includes/images/calendars/" . $donnees['year'] . "/mini/" . $donnees['calendar']);
    }

    $reponse->closeCursor();

    // On efface la ligne de la base
    $reponse2 = $bdd->exec('DELETE FROM calendars WHERE id = ' . $id_cal);

    // Suppression des notifications
    deleteNotification('calendrier', $id_cal);

    $_SESSION['alerts']['calendar_deleted'] = true;
  }

  // METIER : Supprime une annexe de la base
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    $id_annexe = $post['id_annexe'];

    global $bdd;

    // On efface l'annexe si présent
    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE id = ' . $id_annexe);
    $donnees = $reponse->fetch();

    if (isset($donnees['annexe']) AND !empty($donnees['annexe']))
    {
      unlink ("../../includes/images/calendars/annexes/" . $donnees['annexe']);
      unlink ("../../includes/images/calendars/annexes/mini/" . $donnees['annexe']);
    }

    $reponse->closeCursor();

    // On efface la ligne de la base
    $reponse2 = $bdd->exec('DELETE FROM calendars_annexes WHERE id = ' . $id_annexe);

    $_SESSION['alerts']['annexe_deleted'] = true;
  }

  // METIER : Réinitialise un calendrier de la base
  // RETOUR : Aucun
  function resetCalendrier($post)
  {
    $id_cal = $post['id_cal'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE calendars SET to_delete = :to_delete WHERE id = ' . $id_cal);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['alerts']['calendar_reseted'] = true;
  }

  // METIER : Réinitialise une annexe de la base
  // RETOUR : Aucun
  function resetAnnexe($post)
  {
    $id_annexe = $post['id_annexe'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE calendars_annexes SET to_delete = :to_delete WHERE id = ' . $id_annexe);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['alerts']['annexe_reseted'] = true;
  }
?>
