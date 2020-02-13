<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/class.php');

  // METIER : Description de la fonction
  // RETOUR : Retour de la fonction
  function exempleFonction($parametres)
  {
    $retour = NULL;
    $id     = 1;
    $champ1 = 'champ1';
    $champ2 = 'champ2';

    global $bdd;

    // Lecture BDD
    $reponse = $bdd->query('SELECT * FROM table WHERE id = ' . $id . ' ORDER BY id DESC');
    while ($donnees = $reponse->fetch())
    {
      $myDatas = Class::withData($donnees);

      array_push($retour, $myDatas);
    }
    $reponse->closeCursor();

    // Insertion BDD
    $reponse = $bdd->prepare('INSERT INTO table(champ1, champ2) VALUES(:champ1, :champ2)');
    $reponse->execute(array(
      'champ1' => $champ1,
      'champ2' => $champ2
    ));
    $reponse->closeCursor();

    // Mise à jour BDD
    $reponse = $bdd->prepare('UPDATE table SET champ1 = :champ1, champ2 = :champ2 WHERE id = ' . $id);
    $reponse->execute(array(
      'champ1' => $champ1,
      'champ2' => $champ2
    ));
    $reponse->closeCursor();

    // Suppression BDD
    $reponse = $bdd->exec('DELETE FROM table WHERE id = ' . $id);

    return $retour;
  }
?>
