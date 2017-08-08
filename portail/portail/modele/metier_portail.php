<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/profile.php');

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
?>
