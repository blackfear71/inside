<?php
  include_once('../../includes/appel_bdd.php');
  //include_once('../../includes/classes/bugs.php');

  // METIER : Insertion d'un bug
  // RETOUR : Aucun
  function insertBug()
  {
    // Récupération des données
    $subject  = htmlspecialchars($_POST['subject_bug']);
    $date     = date("Ymd");
    $author   = $_SESSION['identifiant'];
    $content  = htmlspecialchars($_POST['content_bug']);
    $type     = $_POST['type_bug'];
    $resolved = "N";

    // On construit un tableau avec les données
    $bugs = array('subject'  => $subject,
                  'date'     => $date,
                  'author'   => $author,
                  'content'  => $content,
                  'type'     => $type,
                  'resolved' => $resolved
                 );

    var_dump($bugs);

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

      $_SESSION['bug_submitted'] = true;
    }
  }
?>
