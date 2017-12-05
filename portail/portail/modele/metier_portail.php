<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/notifications.php');
  include_once('../../includes/classes/missions.php');

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Récupération missions actives + 7 jours pour les résultats
  // RETOUR : Objet mission
  function getMessagesMissions()
  {
    $missions  = array();
    $date_jour = date('Ymd');

    global $bdd;

    $date_jour_moins_7 = date("Ymd", strtotime(date("Ymd") . ' - 7 days'));

    $reponse = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . $date_jour . ' AND date_fin >= ' . $date_jour_moins_7 . ' ORDER BY date_deb ASC');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }
?>
