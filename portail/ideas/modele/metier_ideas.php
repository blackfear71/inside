<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/ideas.php');

  // METIER : Lecture liste des idées
  // RETOUR : Tableau d'idées
  function getIdeas($view)
  {
    // Initialisation tableau d'idées
    $listeIdeas = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "done")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status = "D" OR status = "R" ORDER BY id DESC');
    elseif ($view == "inprogress")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status = "O" OR status = "C" OR status = "P" ORDER BY id DESC');
    elseif ($view == "mine")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $_SESSION['user']['identifiant'] . '" ORDER BY id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM ideas ORDER BY id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Initilialisation variables
      $auteur_idee      = "";
      $developpeur_idee = "";

      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $idea = Ideas::withData($donnees);

      // Recherche du nom complet de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo FROM users WHERE identifiant = "' . $idea->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['pseudo']) AND !empty($donnees2['pseudo']))
        $auteur_idee = $donnees2['pseudo'];
      else
        $auteur_idee = "<i>un ancien utilisateur</i>";

      $reponse2->closeCursor();

      // Recherche du nom complet du developpeur si renseigné
      if (!empty($idea->getDevelopper()))
      {
        $reponse3 = $bdd->query('SELECT identifiant, pseudo FROM users WHERE identifiant = "' . $idea->getDevelopper() . '"');
        $donnees3 = $reponse3->fetch();

        if (isset($donnees3['pseudo']) AND !empty($donnees3['pseudo']))
          $developpeur_idee = $donnees3['pseudo'];
        else
          $developpeur_idee = "<i>un ancien utilisateur</i>";

        $reponse3->closeCursor();
      }

      // On construit un tableau qu'on alimente avec les données d'une idée
      $myIdea = array('id'         => $idea->getId(),
                      'subject'    => $idea->getSubject(),
                      'date'       => $idea->getDate(),
                      'author'     => $idea->getAuthor(),
                      'name_a'     => $auteur_idee,
                      'content'    => $idea->getContent(),
                      'status'     => $idea->getStatus(),
                      'developper' => $idea->getDevelopper(),
                      'name_d'     => $developpeur_idee
                     );

      array_push($listeIdeas, Ideas::withData($myIdea));
    }

    $reponse->closeCursor();

    return $listeIdeas;
  }

  // METIER : Insertion d'une idée
  // RETOUR : Aucun
  function insertIdea($post, $user)
  {
    // Récupération des données
    $subject    = $post['subject_idea'];
    $date       = date("Ymd");
    $author     = $user;
    $content    = $post['content_idea'];
    $status     = "O";
    $developper = "";

    // On construit un tableau avec les données
    $ideas = array('subject'    => $subject,
                   'date'       => $date,
                   'author'     => $author,
                   'content'    => $content,
                   'status'     => $status,
                   'developper' => $developper
                  );

    // On insère dans la table
    if (!empty($subject))
    {
      global $bdd;
      $req = $bdd->prepare('INSERT INTO ideas(subject,
                                              date,
                                              author,
                                              content,
                                              status,
                                              developper
                                             )
                                       VALUES(:subject,
                                              :date,
                                              :author,
                                              :content,
                                              :status,
                                              :developper
                                             )');
      $req->execute($ideas);
      $req->closeCursor();

      // Génération notification idée ajoutée
      $new_id = $bdd->lastInsertId();

      insertNotification($user, 'idee', $new_id);

      $_SESSION['alerts']['idea_submitted'] = true;
    }
    else
    {
      $_SESSION['alerts']['idea_submitted'] = false;
    }
  }

  // METIER : Mise à jour du statut d'une idée
  // RETOUR : Aucun
  function updateIdea($id, $post)
  {
    switch (key($post))
    {
      case 'take':
        $status     = "C";
        $developper = $_SESSION['user']['identifiant'];
        break;

      case 'developp':
        $status     = "P";
        $developper = $_SESSION['user']['identifiant'];
        break;

      case 'end':
        $status     = "D";
        $developper = $_SESSION['user']['identifiant'];
        break;

      case 'reject':
        $status     = "R";
        $developper = $_SESSION['user']['identifiant'];
        break;

      case 'reset':
      default:
        $status     = "O";
        $developper = "";
        break;
    }

    // On construit un tableau avec les données à modifier
    $data = array('status'     => $status,
                  'developper' => $developper
                 );

    // On met à jour la table
    global $bdd;
    $req = $bdd->prepare('UPDATE ideas SET status     = :status,
                                           developper = :developper
                                     WHERE id         = ' . $id);
    $req->execute($data);
    $req->closeCursor();
  }
?>
