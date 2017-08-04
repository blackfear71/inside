<?php
  session_start();

  if (isset($_POST['submit_comment']))
  {
    include('../../includes/appel_bdd.php');

    // On récupère les données
    $id_film = $_GET['id_film'];
    $author  = $_SESSION['identifiant'];
    $date    = date("Ymd");
    $time    = date("His");
    $comment = htmlspecialchars($_POST['comment']);

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO movie_house_comments(id_film, author, date, time, comment) VALUES(:id_film, :author, :date, :time, :comment)');
    $req->execute(array(
      'id_film' => $id_film,
      'author' => $author,
      'date' => $date,
      'time' => $time,
      'comment' => $comment
        ));
    $req->closeCursor();

    // Redirection
    header('location: details_film.php?id_film=' . $id_film . '#comments');
  }
?>
