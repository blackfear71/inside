<?php
  include_once('../../includes/functions/appel_bdd.php');
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
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status = "D" OR status = "R" ORDER BY date DESC, id DESC');
    elseif ($view == "inprogress")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status = "O" OR status = "C" OR status = "P" ORDER BY date DESC, id DESC');
    elseif ($view == "mine")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $_SESSION['user']['identifiant'] . '" ORDER BY date DESC, id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM ideas ORDER BY date DESC, id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Initilialisation variables
      $auteur_idee      = "";
      $developpeur_idee = "";

      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $idea = Ideas::withData($donnees);

      // Recherche du pseudo et de l'avatar de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $idea->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['pseudo']) AND !empty($donnees2['pseudo']))
        $idea->setPseudo_a($donnees2['pseudo']);
      else
        $idea->setPseudo_a("Un ancien utilisateur");

      if (isset($donnees2['avatar']) AND !empty($donnees2['avatar']))
        $idea->setAvatar_a($donnees2['avatar']);

      $reponse2->closeCursor();

      // Recherche du pseudo et de l'avatar du developpeur si renseigné
      if (!empty($idea->getDevelopper()))
      {
        $reponse3 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $idea->getDevelopper() . '"');
        $donnees3 = $reponse3->fetch();

        if (isset($donnees3['pseudo']) AND !empty($donnees3['pseudo']))
          $idea->setPseudo_d($donnees3['pseudo']);
        else
          $idea->setPseudo_d("Un ancien utilisateur");

        if (isset($donnees3['avatar']) AND !empty($donnees3['avatar']))
          $idea->setAvatar_d($donnees3['avatar']);

        $reponse3->closeCursor();
      }

      array_push($listeIdeas, $idea);
    }

    $reponse->closeCursor();

    return $listeIdeas;
  }

  // METIER : Insertion d'une idée
  // RETOUR : Id enregistrement créé
  function insertIdea($post, $author)
  {
    $new_id     = NULL;

    // Récupération des données
    $subject    = $post['subject_idea'];
    $content    = $post['content_idea'];
    $date       = date("Ymd");
    $status     = "O";
    $developper = "";

    // On construit un tableau avec les données
    $idea = array('subject'    => $subject,
                  'date'       => $date,
                  'author'     => $author,
                  'content'    => $content,
                  'status'     => $status,
                  'developper' => $developper
                 );

    // On insère dans la table
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
    $req->execute($idea);
    $req->closeCursor();

    // Génération notification idée ajoutée
    $new_id = $bdd->lastInsertId();

    insertNotification($author, 'idee', $new_id);

    // Génération succès
    insertOrUpdateSuccesValue('creator', $author, 1);

    // Ajout expérience
    insertExperience($author, 'add_idea');

    $_SESSION['alerts']['idea_submitted'] = true;

    return $new_id;
  }

  // METIER : Mise à jour du statut d'une idée
  // RETOUR : Vue à afficher
  function updateIdea($id, $view, $post)
  {
    global $bdd;

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
        $view       = "done";
        break;

      case 'reject':
        $status     = "R";
        $developper = $_SESSION['user']['identifiant'];
        $view       = "done";
        break;

      case 'reset':
      default:
        $status     = "O";
        $developper = "";
        $view       = "inprogress";
        break;
    }

    // On construit un tableau avec les données à modifier
    $data = array('status'     => $status,
                  'developper' => $developper
                 );

    // Génération succès
    if ($status == "O")
    {
      $reponse = $bdd->query('SELECT * FROM ideas WHERE id = ' . $id);
      $donnees = $reponse->fetch();
      insertOrUpdateSuccesValue('applier', $donnees['developper'], -1);
      $reponse->closeCursor();
    }

    // On met à jour la table
    $req = $bdd->prepare('UPDATE ideas SET status     = :status,
                                           developper = :developper
                                     WHERE id         = ' . $id);
    $req->execute($data);
    $req->closeCursor();

    // Génération succès
    if ($status == "D")
      insertOrUpdateSuccesValue('applier', $developper, 1);

    return $view;
  }
?>
