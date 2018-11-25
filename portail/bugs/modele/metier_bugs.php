<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/bugs.php');

  // METIER : Lecture liste des bugs
  // RETOUR : Tableau des bugs
  function getBugs($view)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved = "Y" ORDER BY id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved = "N" ORDER BY id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Initilialisation variables
      $auteur_bug = "";

      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du nom complet de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo FROM users WHERE identifiant = "' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['pseudo']) AND !empty($donnees2['pseudo']))
        $auteur_bug = $donnees2['pseudo'];
      else
        $auteur_bug = "un ancien utilisateur";

      $reponse2->closeCursor();

      // On construit un tableau qu'on alimente avec les données d'un bug
      $myBug = array('id'       => $bug->getId(),
                     'subject'  => $bug->getSubject(),
                     'date'     => $bug->getDate(),
                     'author'   => $bug->getAuthor(),
                     'name_a'   => $auteur_bug,
                     'content'  => $bug->getContent(),
                     'type'     => $bug->getType(),
                     'resolved' => $bug->getResolved()
                     );

      array_push($listeBugs, Bugs::withData($myBug));
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Insertion d'un bug
  // RETOUR : Id enregistrement créé
  function insertBug($post)
  {
    $new_id   = NULL;

    // Récupération des données
    $subject  = $post['subject_bug'];
    $date     = date("Ymd");
    $author   = $_SESSION['user']['identifiant'];
    $content  = $post['content_bug'];
    $type     = $post['type_bug'];
    $resolved = "N";

    // On construit un tableau avec les données
    $bugs = array('subject'  => $subject,
                  'date'     => $date,
                  'author'   => $author,
                  'content'  => $content,
                  'type'     => $type,
                  'resolved' => $resolved
                 );

    // On insère dans la table
    if (!empty($subject))
    {
      global $bdd;
      $req = $bdd->prepare('INSERT INTO bugs(subject,
                                             date,
                                             author,
                                             content,
                                             type,
                                             resolved
                                            )
                                      VALUES(:subject,
                                             :date,
                                             :author,
                                             :content,
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
