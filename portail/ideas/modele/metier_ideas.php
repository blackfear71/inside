<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/ideas.php');

  // METIER : Lecture nombre de pages en fonction de la vue
  // RETOUR : Nombre de pages
  function getPages($vue, $user)
  {
    $nb_pages     = 0;
    $nb_idees     = 0;
    $nb_par_page  = 18;

    global $bdd;

    // Calcul du nombre total d'enregistrements pour chaque vue
    switch ($vue)
    {
      case 'inprogress':
        $req = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE status = "O" OR status = "C" OR status = "P"');
        break;

      case 'mine':
        $req = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $user . '"');
        break;

      case 'done':
        $req = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE status = "D" OR status = "R"');
        break;

      case 'all':
      default:
        $req = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas');
        break;
    }

    $data = $req->fetch();

    if (isset($data['nb_idees']))
      $nb_idees = $data['nb_idees'];

    $req->closeCursor();

    $nb_pages = ceil($nb_idees / $nb_par_page);

    return $nb_pages;
  }

  // METIER : Lecture liste des idées
  // RETOUR : Tableau d'idées
  function getIdeas($view, $page, $nb_pages)
  {
    // Initialisation tableau d'idées
    $listeIdeas  = array();
    $nb_par_page = 18;

    // Contrôle dernière page
    if ($page > $nb_pages)
      $page = $nb_pages;

    // Calcul première entrée
    $premiere_entree = ($page - 1) * $nb_par_page;

    global $bdd;

    // Lecture de la base en fonction de la vue
    switch ($view)
    {
      case 'done':
        $reponse = $bdd->query('SELECT *
                                FROM ideas
                                WHERE status = "D" OR status = "R"
                                ORDER BY date DESC, id DESC
                                LIMIT ' . $premiere_entree . ', ' . $nb_par_page
                              );
        break;

      case 'inprogress':
        $reponse = $bdd->query('SELECT *
                                FROM ideas
                                WHERE status = "O" OR status = "C" OR status = "P"
                                ORDER BY date DESC, id DESC
                                LIMIT ' . $premiere_entree . ', ' . $nb_par_page
                              );
        break;

      case 'mine':
        $reponse = $bdd->query('SELECT *
                                FROM ideas
                                WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $_SESSION['user']['identifiant'] . '"
                                ORDER BY date DESC, id DESC
                                LIMIT ' . $premiere_entree . ', ' . $nb_par_page
                              );
        break;

      case 'all':
      default:
        $reponse = $bdd->query('SELECT *
                                FROM ideas
                                ORDER BY date DESC, id DESC
                                LIMIT ' . $premiere_entree . ', ' . $nb_par_page
                              );
        break;
    }

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

      if ($reponse2->rowCount() > 0)
      {
        $idea->setPseudo_author($donnees2['pseudo']);
        $idea->setAvatar_author($donnees2['avatar']);
      }

      $reponse2->closeCursor();

      // Recherche du pseudo et de l'avatar du developpeur si renseigné
      if (!empty($idea->getDevelopper()))
      {
        $reponse3 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $idea->getDevelopper() . '"');
        $donnees3 = $reponse3->fetch();

        if ($reponse3->rowCount() > 0)
        {
          $idea->setPseudo_developper($donnees3['pseudo']);
          $idea->setAvatar_developper($donnees3['avatar']);
        }

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
  function updateIdea($post, $view)
  {
    $id_idea = $post['id_idea'];
    $action  = $post;

    unset($action['id_idea']);

    global $bdd;

    switch (key($action))
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
    $datas = array('status'     => $status,
                   'developper' => $developper
                  );

    // Lecture des données
    $req1 = $bdd->query('SELECT * FROM ideas WHERE id = ' . $id_idea);
    $data1 = $req1->fetch();
    $author     = $data1['author'];
    $developper = $data1['developper'];
    $old_status = $data1['status'];
    $req1->closeCursor();

    // On met à jour la table
    $req2 = $bdd->prepare('UPDATE ideas SET status     = :status,
                                           developper = :developper
                                     WHERE id         = ' . $id_idea);
    $req2->execute($datas);
    $req2->closeCursor();

    // Génération succès
    switch ($status)
    {
      case "D":
        insertOrUpdateSuccesValue('applier', $developper, 1);
        break;

      case "R":
        insertOrUpdateSuccesValue('creator', $author, -1);
        break;

      case "O":
        if ($old_status == "D")
          insertOrUpdateSuccesValue('applier', $developper, -1);

        if ($old_status == "R")
          insertOrUpdateSuccesValue('creator', $author, 1);
        break;

      default:
        break;
    }

    return $view;
  }

  // METIER : Récupère le numéro de page pour une notification #TheBox
  // RETOUR : Numéro de page
  function numPageIdea($id, $view)
  {
    $numPage     = 0;
    $nb_par_page = 18;
    $position    = 1;

    global $bdd;

    // On cherche la position de l'idée dans la table en fonction de la vue
    switch ($view)
    {
      case 'done':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE status = "D" OR status = "R"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'inprogress':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE status = "O" OR status = "C" OR status = "P"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'mine':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $_SESSION['user']['identifiant'] . '"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'all':
      default:
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                ORDER BY date DESC, id DESC'
                              );
        break;
    }

    while ($donnees = $reponse->fetch())
    {
      if ($id == $donnees['id'])
        break;
      else
        $position++;
    }
    $reponse->closeCursor();

    $numPage = ceil($position / $nb_par_page);

    return $numPage;
  }
?>
