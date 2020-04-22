<?php
  include_once('../../includes/classes/bugs.php');

  // METIER : Lecture liste des bugs/évolutions
  // RETOUR : Liste des bugs/évolutions
  function getBugs($view, $type)
  {
    // Récupération des rapports en fonction de la vue et du type
    $rapports = physiqueListeRapports($view, $type);

    // Récupération des données complémentaires
    foreach ($rapports as $rapport)
    {
      physiqueDonneesUser($rapport);
    }

    // Retour
    return $rapports;
  }

  // METIER : Mise à jour du statut d'un bug
  // RETOUR : Top redirection
  function updateBug($post)
  {
    // Récupération des données
    $idRapport = $post['id_report'];
    $action    = $post;
    $resolved  = "N";

    unset($action['id_report']);

    // Lecture des données du rapport
    $rapport = physiqueRapport($idRapport);

    // Détermination du statut
    switch (key($action))
    {
      case 'resolve_bug':
        $resolved = "Y";
        break;

      case 'unresolve_bug':
        $resolved = "N";
        break;

      case 'reject_bug':
        $resolved = "R";
        break;

      default:
        break;
    }

    // Mise à jour du statut
    physiqueUpdateRapport($idRapport, $resolved);

    // Génération succès (sauf si rejeté ou remis en cours après rejet)
    if ($resolved != "R" AND $rapport->getResolved() != "R")
    {
      if ($resolved == "Y")
        insertOrUpdateSuccesValue('compiler', $rapport->getAuthor(), 1);
      else
        insertOrUpdateSuccesValue('compiler', $rapport->getAuthor(), -1);
    }

    // Retour
    return $resolved;
  }

  // METIER : Suppression d'un bug
  // RETOUR : Aucun
  function deleteBug($post)
  {
    // Récupération des données
    $idRapport = $post['id_report'];

    // Lecture des données du rapport
    $rapport = physiqueRapport($idRapport);

    // Suppression image si présente
    if (!empty($rapport->getPicture()))
      unlink('../../includes/images/reports/' . $rapport->getPicture());

    // Suppression de l'enregistrement en base
    physiqueDeleteRapport($idRapport);

    // Génération succès
    insertOrUpdateSuccesValue('debugger', $rapport->getAuthor(), -1);

    if ($resolved == "Y")
      insertOrUpdateSuccesValue('compiler', $rapport->getAuthor(), -1);

    // Message d'alerte
    $_SESSION['alerts']['bug_deleted'] = true;
  }
?>
