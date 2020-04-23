<?php
  include_once('../../includes/classes/class.php');

  // METIER : Description de la fonction
  // RETOUR : Retour de la fonction
  function exempleFonction($parametres)
  {
    // Initialisations
    $control_ok = true;
    $retour     = NULL;

    // Récupération des données
    $id     = $parametres;
    $champ1 = 'champ1';
    $champ2 = 'champ2';

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['fonction'] = $id;

    // Contrôle
    $control_ok = controleFonction($champ1, $champ2);

    // Lecture
    if ($control_ok == true)
      $retour = physiqueSelect($id);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
      physiqueInsert($champ1, $champ2);

    // Modification de l'enregistrement en base
    if ($control_ok == true)
      physiqueUpdate($champ1, $champ2, $id);

    // Suppression de l'enregistrement en base
    if ($control_ok == true)
      physiqueDelete($id);

    // Retour
    return $retour;
  }
?>
