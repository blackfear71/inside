<?php
  include_once('../../includes/classes/bugs.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['file_too_big'])    OR $_SESSION['alerts']['file_too_big']    != true)
    AND (!isset($_SESSION['alerts']['temp_not_found'])  OR $_SESSION['alerts']['temp_not_found']  != true)
    AND (!isset($_SESSION['alerts']['wrong_file_type']) OR $_SESSION['alerts']['wrong_file_type'] != true)
    AND (!isset($_SESSION['alerts']['wrong_file'])      OR $_SESSION['alerts']['wrong_file']      != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['subject_bug'] = '';
      $_SESSION['save']['type_bug']    = '';
      $_SESSION['save']['content_bug'] = '';
    }
  }

  // METIER : Lecture de la liste des utilisateurs
  // RETOUR : Liste des utilisateurs
  function getListeUsers()
  {
    // Lecture de la liste des équipes
    $listeUsers = physiqueListeUsers();

    // Retour
    return $listeUsers;
  }

  // METIER : Lecture liste des bugs/évolutions
  // RETOUR : Tableau des bugs/évolutions
  function getBugs($view, $type, $listeUsers)
  {
    // Récupération des rapports en fonction de la vue et du type
    $rapports = physiqueListeRapports($view, $type);

    // Récupération des données complémentaires
    foreach ($rapports as $rapport)
    {
      // Recherche des données de l'auteur
      if (isset($listeUsers[$rapport->getAuthor()]))
      {
        $rapport->setPseudo($listeUsers[$rapport->getAuthor()]['pseudo']);
        $rapport->setAvatar($listeUsers[$rapport->getAuthor()]['avatar']);
      }
    }

    // Retour
    return $rapports;
  }

  // METIER : Insertion d'un bug
  // RETOUR : Id enregistrement créé
  function insertBug($post, $files, $author)
  {
    // Initialisations
    $idBug      = NULL;
    $control_ok = true;

    // Récupération des données
    $subject  = $post['subject_bug'];
    $type     = $post['type_bug'];
    $content  = $post['content_bug'];
    $date     = date('Ymd');
    $resolved = 'N';
    $picture  = '';

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['subject_bug'] = $post['subject_bug'];
    $_SESSION['save']['type_bug']    = $post['type_bug'];
    $_SESSION['save']['content_bug'] = $post['content_bug'];

    // Vérification des dossiers et contrôle des fichiers
    if (!empty($files['image']['name']))
    {
      // Nom du fichier
      $name = rand();

      // Dossier de destination
      $dossier = '../../includes/images/reports';

      // Contrôle du fichier
      $fileDatas = controlsUploadFile($files['image'], $name, 'all');

      // Récupération contrôles
      $control_ok = $fileDatas['control_ok'];

      // Upload fichier
      if ($control_ok == true)
        $control_ok = uploadFile($fileDatas, $dossier);

      // Traitement de l'image
      if ($control_ok == true)
      {
        $picture   = $fileDatas['new_name'];
        $typeImage = $fileDatas['type_file'];

        // Rotation automatique de l'image (si JPEG)
        if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
          rotateImage($dossier . '/' . $picture, $typeImage);
      }
    }
    else
      $picture = '';

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      // On insère dans la table
      $bug = array('subject'  => $subject,
                   'date'     => $date,
                   'author'   => $author,
                   'content'  => $content,
                   'picture'  => $picture,
                   'type'     => $type,
                   'resolved' => $resolved
                  );

      $idBug = physiqueInsertionBug($bug);

      // Génération succès
      insertOrUpdateSuccesValue('debugger', $author, 1);

      // Ajout expérience
      insertExperience($author, 'add_bug');

      // Message d'alerte
      $_SESSION['alerts']['bug_submitted'] = true;
    }

    return $idBug;
  }
?>
