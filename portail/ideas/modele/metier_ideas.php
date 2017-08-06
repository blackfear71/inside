<?php
  include_once('../../../includes/appel_bdd.php');
  include_once('../../../includes/classes/ideas.php');

  // METIER : Lecture liste des idées
  function readIdeas($view)
  {
    // Initialisation tableau d'idées
    $listeIdeas = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "done")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status="D" OR status="R" ORDER BY id DESC');
    elseif ($view == "inprogress")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE status="O" OR status="C" OR status="P" ORDER BY id DESC');
    elseif ($view == "mine")
      $reponse = $bdd->query('SELECT * FROM ideas WHERE (status="O" OR status="C" OR status="P") AND developper="' . $_SESSION['identifiant'] . '" ORDER BY id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM ideas ORDER BY id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Ideas à partir des données remontées de la bdd
      $idea = Ideas::withData($donnees);

      // Recherche du nom complet de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $idea->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['full_name']) AND !empty($donnees2['full_name']))
        $auteur_idee = $donnees2['full_name'];
      else
        $auteur_idee = "<i>un ancien utilisateur</i>";

      $reponse2->closeCursor();

      // Recherche du nom complet du developpeur
      $reponse3 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $idea->getDevelopper() . '"');
      $donnees3 = $reponse3->fetch();

      if (isset($donnees3['full_name']) AND !empty($donnees3['full_name']))
        $developpeur_idee = $donnees3['full_name'];
      else
        $developpeur_idee = "<i>un ancien utilisateur</i>";

      $reponse3->closeCursor();

      // On construit un tableau avec les données d'une idée
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

      array_push($listeIdeas, $myIdea);
    }

    $reponse->closeCursor();

    return $listeIdeas;
  }

  // METIER : Insertion d'un idée

     /******\
   /         \
  |    !!    |
  \         /
   \******/

  // Renvoie un objet Ideas
  function insertIdea()
  {
    // Récupération des données
    $subject    = htmlspecialchars($_POST['subject_idea']);
    $date       = date("Ymd");
    $author     = $_SESSION['identifiant'];
    $content    = htmlspecialchars($_POST['content_idea']);
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

    $myIdeas = Ideas::withData($ideas);

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
    }

    /******\
  /         \
 |    !!    |
 \         /
  \******/

    //return $idea;
  }

  // METIER : Mise à jour du statut d'une idée
  // Renvoie un objet Ideas
  function updateIdea($id, $post)
  {
    switch ($post)
    {

      /******\
    /         \
   |    !!    |
   \         /
    \******/

      case isset($post['take']):
        $status     = "C";
        $developper = $_SESSION['identifiant'];
        break;

      case isset($post['developp']):
        $status     = "P";
        $developper = $_SESSION['identifiant'];
        break;

      case isset($post['end']):
        $status     = "D";
        $developper = $_SESSION['identifiant'];
        break;

      case isset($post['reject']):
        $status     = "R";
        $developper = $_SESSION['identifiant'];
        break;

      case isset($post['reset']):
      default:
        $status     = "O";
        $developper = "";
        break;
    }

    // On construit un tableau avec les données
    $data = array('status'     => $status,
                  'developper' => $developper
                 );

    $ideas = Ideas::withData($data);

    // On met à jour la table
    global $bdd;
    $req = $bdd->prepare('UPDATE ideas SET status     = :status,
                                           developper = :developper
                                     WHERE id         = ' . $id);
    $req->execute($data);
    $req->closeCursor();

    /******\
  /         \
 |    !!    |
 \         /
  \******/

    //return $ideas;
  }
?>
