<?php
  include_once('appel_bdd.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/missions.php');

  // Contrôles Index, initialisation session
  // RETOUR : aucun
  function controlsIndex()
  {
    // Lancement de la session
  	if (empty(session_id()))
  	 session_start();

  	// Si déjà connecté
  	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
  	 header('location: /inside/portail/portail/portail.php?action=goConsulter');
  	elseif (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
  	 header('location: /inside/administration/administration.php?action=goConsulter');
  	else
  	 $_SESSION['connected'] = false;
  }

  // Contrôles Utilisateur, initialisation session
  // RETOUR : aucun
  function controlsUser()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non administrateur
  	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
      header('location: /inside/administration/administration.php?action=goConsulter');

    // Détermination fond d'écran
    $_SESSION['theme'] = setTheme();

    // Contrôle utilisateur connecté
  	if ($_SESSION['connected'] == false)
      header('location: /inside/index.php');
  }

  // Contrôles Administrateur, initialisation session
  // RETOUR : aucun
  function controlsAdmin()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non utilisateur normal
    if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
      header('location: /inside/portail/portail/portail.php?action=goConsulter');

    // Contrôle administrateur connecté
    if ($_SESSION['connected'] == false)
      header('location: /inside/index.php');
  }

  // METIER : Détermine le thème
  // RETOUR : Tableau chemins & types de thème
  function setTheme()
  {
    $theme = array();

    // Détermination fond d'écran mission (prioritaire)
    $missionActive = NULL;
    $date_jour     = date('Ymd');

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE ' . $date_jour . ' >= date_deb AND ' . $date_jour . ' <= date_fin');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $missionActive = Mission::withData($donnees);

    $reponse->closeCursor();

    if (isset($missionActive) AND !empty($missionActive))
    {
      $theme = array('background' => '/inside/includes/themes/backgrounds/' . $missionActive->getReference() . '.png',
                     'header'     => '/inside/includes/themes/headers/' . $missionActive->getReference() . '_h.png',
                     'footer'     => '/inside/includes/themes/footers/' . $missionActive->getReference() . '_f.png',
                    );
    }

    /*// Détermination fond d'écran utilisateur (à développer)
    if (!isset($missionActive) OR empty($missionActive))
    {
      // Lecture données utilisateur
      // ici, lire le background stocké sur le profil

      $background = '/inside/includes/backgrounds/' . $missionActive->getReference() . '.png';
    }*/

    return $theme;
  }

  // Formatage titres niveaux (succès)
  // RETOUR : titre niveau formaté
  function formatTitleLvl($lvl)
  {
    $name_lvl = "";

    switch($lvl)
    {
      case "1";
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Seuls les plus forts y parviendront.</span></div>';
        break;

      case "2";
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Vous êtes encore là ?</span></div>';
        break;

      case "3";
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Votre charisme doit être impressionnant.</span></div>';
        break;

      default:
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . '</div>';
        break;
    }

    return $name_lvl;
  }

  // Génération notification
  // RETOUR : Aucun
  function insertNotification($author, $category, $content)
  {
    $date = date("Ymd");
    $time = date("His");

    global $bdd;

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO notifications(author, date, time, category, content) VALUES(:author, :date, :time, :category, :content)');
    $req->execute(array(
      'author'   => $author,
      'date'     => $date,
      'time'     => $time,
      'category' => $category,
      'content'  => $content
        ));
    $req->closeCursor();
  }

  // Suppression notification
  // RETOUR : Aucun
  function deleteNotification($category, $content)
  {
    global $bdd;

    // Suppression de la table
    $req = $bdd->exec('DELETE FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
  }

  // Contrôle notification existante
  // RETOUR : Booléen
  function controlNotification($category, $content)
  {
    $exist = false;

    global $bdd;

    if ($category == 'comments')
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '" AND date = ' . date(Ymd));
    else
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
    $data = $req->fetch();

    if ($req->rowCount() > 0)
      $exist = true;

    $req->closeCursor();

    return $exist;
  }
?>
