<?php
  include_once('../../includes/functions/appel_bdd.php');
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

  // METIER : Lecture liste des bugs/évolutions
  // RETOUR : Tableau des bugs/évolutions
  function getBugs($view, $type)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R") ORDER BY date DESC, id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND resolved = "N" ORDER BY date DESC, id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du pseudo et de l'avatar de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if ($reponse2->rowCount() > 0)
      {
        $bug->setPseudo($donnees2['pseudo']);
        $bug->setAvatar($donnees2['avatar']);
      }

      $reponse2->closeCursor();

      array_push($listeBugs, $bug);
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Insertion d'un bug
  // RETOUR : Id enregistrement créé
  function insertBug($post, $files, $author)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données
    $subject  = $post['subject_bug'];
    $type     = $post['type_bug'];
    $content  = $post['content_bug'];
    $date     = date("Ymd");
    $resolved = "N";
    $picture  = "";

    // Sauvegarde des données
    $_SESSION['save']['subject_bug'] = $post['subject_bug'];
    $_SESSION['save']['type_bug']    = $post['type_bug'];
    $_SESSION['save']['content_bug'] = $post['content_bug'];

    // On insère l'image si présente
    if (!empty($files['image']['name']))
    {
      // On vérifie la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/reports";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Dossier de destination et nom du fichier
      $image_dir = $dossier . '/';
      $name      = rand();

      // Contrôles fichier
      $fileDatas = controlsUploadFile($files['image'], $name, 'all');

      // Récupération contrôles
      $control_ok = $fileDatas['control_ok'];

      if ($fileDatas['control_ok'] == true)
      {
        // Upload fichier
        $control_ok = uploadFile($files['image'], $fileDatas, $image_dir);

        // Rotation de l'image
        if ($control_ok == true)
        {
          $picture    = $fileDatas['new_name'];
          $type_image = $fileDatas['type_file'];

          if ($type_image == 'jpg' OR $type_image == 'jpeg')
            $rotate = rotateImage($image_dir . $picture, $type_image);
        }
      }
    }

    if ($control_ok == true)
    {
      // On insère dans la table
      $bugs = array('subject'  => $subject,
                    'date'     => $date,
                    'author'   => $author,
                    'content'  => $content,
                    'picture'  => $picture,
                    'type'     => $type,
                    'resolved' => $resolved
                   );

      global $bdd;

      $req = $bdd->prepare('INSERT INTO bugs(subject,
                                             date,
                                             author,
                                             content,
                                             picture,
                                             type,
                                             resolved
                                            )
                                      VALUES(:subject,
                                             :date,
                                             :author,
                                             :content,
                                             :picture,
                                             :type,
                                             :resolved
                                            )');
      $req->execute($bugs);
      $req->closeCursor();

      $new_id = $bdd->lastInsertId();

      // Génération succès
      insertOrUpdateSuccesValue('debugger', $author, 1);

      // Ajout expérience
      insertExperience($author, 'add_bug');

      $_SESSION['alerts']['bug_submitted'] = true;
    }

    return $new_id;
  }
?>
