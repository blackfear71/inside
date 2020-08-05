<?php
  include_once('../../includes/classes/expenses.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['depense_not_numeric']) OR $_SESSION['alerts']['depense_not_numeric'] != true)
    AND (!isset($_SESSION['alerts']['regul_no_parts'])      OR $_SESSION['alerts']['regul_no_parts']      != true)
    AND (!isset($_SESSION['alerts']['parts_not_integer'])   OR $_SESSION['alerts']['parts_not_integer']   != true)
    AND (!isset($_SESSION['alerts']['empty_amount'])        OR $_SESSION['alerts']['empty_amount']        != true)
    AND (!isset($_SESSION['alerts']['amount_not_positive']) OR $_SESSION['alerts']['amount_not_positive'] != true)
    AND (!isset($_SESSION['alerts']['amounts_not_numeric']) OR $_SESSION['alerts']['amounts_not_numeric'] != true))
  	{
      unset($_SESSION['save']);

  		$_SESSION['save']['price']   = '';
  		$_SESSION['save']['buyer']   = '';
  		$_SESSION['save']['comment'] = '';
  	}
  }

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    // Initialisations
    $anneeExistante = false;

    // Vérification année présente en base
    if (isset($year) AND is_numeric($year))
      $anneeExistante = physiqueAnneeExistante($year);

    // Retour
    return $anneeExistante;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Liste des utilisateurs
  function getUsers()
  {
    // Récupération de la liste des utilisateurs (sauf ceux en cours d'inscription)
    $listeUsers = physiqueUsers();

    // Retour
    return $listeUsers;
  }

  // METIER : Lecture années distinctes pour les onglets
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    // Récupération de la liste des années existantes
    $onglets = physiqueOnglets();

    // Retour
    return $onglets;
  }

  // METIER : Lecture liste des dépenses
  // RETOUR : Liste des dépenses
  function getExpenses($year)
  {
    // Récupération de la liste des dépenses
    $listeDepenses = physiqueDepenses($year);

    // Récupération des données complémentaires
    foreach ($listeDepenses as $depense)
    {
      // Initialisation
      $prixMontants = $depense->getPrice();

      // Récupération des données acheteur
      $acheteur = physiqueUser($depense->getBuyer());

      // Récupération de la répartition de la dépense
      $listePartsDepense = physiquePartsDepense($depense->getId());

      // Récupération des données utilisateurs
      foreach ($listePartsDepense as $partDepense)
      {
        $user = physiqueUser($partDepense->getIdentifiant());

        if (!empty($user))
        {
          $partDepense->setId_identifiant($user->getId());
          $partDepense->setPseudo($user->getPseudo());
          $partDepense->setAvatar($user->getAvatar());
        }
        else
          $partDepense->setInscrit(false);

        if ($depense->getType() == 'M')
          $prixMontants += $partDepense->getParts();
      }

      // Récupération du nombre de participants
      $nombreUsers = count($listePartsDepense);

      // Ajout des données complémentaires à la dépense
      if (!empty($acheteur))
      {
        $depense->setPseudo($acheteur->getPseudo());
        $depense->setAvatar($acheteur->getAvatar());
      }

      $depense->setNb_users($nombreUsers);
      $depense->setParts($listePartsDepense);

      if ($depense->getType() == 'M')
      {
        if (!empty($depense->getPrice()))
          $depense->setFrais($depense->getPrice());

        $depense->setPrice($prixMontants);
      }
    }

    // Retour
    return $listeDepenses;
  }

  // METIER : Conversion du tableau d'objets des dépenses et des parts en tableau simple pour JSON
  // RETOUR : Tableau des dépenses
  function convertForJsonListeDepenses($listeDepenses)
  {
    // Initialisations
    $listeDepensesAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeDepenses as $depenseAConvertir)
    {
      $depense = array('id'      => $depenseAConvertir->getId(),
                       'date'    => $depenseAConvertir->getDate(),
                       'price'   => $depenseAConvertir->getPrice(),
                       'buyer'   => $depenseAConvertir->getBuyer(),
                       'pseudo'  => $depenseAConvertir->getPseudo(),
                       'avatar'  => $depenseAConvertir->getAvatar(),
                       'comment' => $depenseAConvertir->getComment(),
                       'frais'   => $depenseAConvertir->getFrais(),
                       'type'    => $depenseAConvertir->getType(),
                       'parts'   => array()
                      );

      $listePartsDepense = array();

      foreach ($depenseAConvertir->getParts() as $parts)
      {
        $listePartsDepense[$parts->getIdentifiant()] = array('id_identifiant' => $parts->getId_identifiant(),
                                                             'pseudo'         => $parts->getPseudo(),
                                                             'avatar'         => $parts->getAvatar(),
                                                             'parts'          => $parts->getParts(),
                                                             'inscrit'        => $parts->getInscrit()
                                                            );
      }

      $depense['parts']                                     = $listePartsDepense;
      $listeDepensesAConvertir[$depenseAConvertir->getId()] = $depense;
    }

    // Retour
    return $listeDepensesAConvertir;
  }

  // METIER : Insertion d'une dépense & mise à jour des dépenses utilisateurs
  // RETOUR : Id dépense
  function insertExpense($post)
  {
    // Initialisations
    $idDepense  = NULL;
    $control_ok = true;

    // Récupération des données
    $date    = date('Ymd');
    $price   = formatAmountForInsert($post['depense']);
    $buyer   = $post['buyer_user'];
    $comment = $post['comment'];
    $type    = 'P';

    $listeParts = array();

    foreach ($post['identifiant_quantite'] as $id => $identifiant)
    {
      if ($post['quantite_user'][$id] != 0)
        $listeParts[$identifiant] = $post['quantite_user'][$id];
    }

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['price']         = $post['depense'];
    $_SESSION['save']['buyer']         = $buyer;
    $_SESSION['save']['comment']       = $comment;
    $_SESSION['save']['tableau_parts'] = $listeParts;

    // Vérification si régularisation négative sans parts
    $regularisationSansParts = true;

    if (!empty($listeParts))
      $regularisationSansParts = false;

    // Contrôle si aucune part pour une régularisation
    $control_ok = controleRegularisation($price, $regularisationSansParts);

    // Contrôle si prix numérique et non nul (négatif = régularisation, positif = régularisation ou dépense, nul = aucun sens)
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($price);

    // Contrôle parts entières
    if ($control_ok == true)
      $control_ok = controlePartsEntieres($listeParts, $regularisationSansParts);

    // Insertion de l'enregistrement en base et traitement des utilisateurs
    if ($control_ok == true)
    {
      $depense = array('date'    => $date,
                       'price'   => $price,
                       'buyer'   => $buyer,
                       'comment' => $comment,
                       'type'    => $type
                      );

      $idDepense = physiqueInsertionDepense($depense);

      // Récupération des données acheteur
      $acheteur      = physiqueUser($buyer);
      $bilanAcheteur = $acheteur->getExpenses();

      // Mise à jour du bilan de l'acheteur (on ajoute la dépense)
      $bilanAcheteur += $price;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($buyer, $bilanAcheteur);

      // Vérification si part acheteur nulle (pour une dépense positive hors régularisation)
      $acheteurSansParts = true;

      if ($regularisationSansParts == false AND $price > 0 AND isset($listeParts[$buyer]) AND $listeParts[$buyer] > 0)
        $acheteurSansParts = false;

      // Génération succès (total max pour l'acheteur s'il n'a pas de parts)
      if ($acheteurSansParts == true)
        insertOrUpdateSuccesValue('greedy', $buyer, $bilanAcheteur);

      // Insertions des parts & mise à jour du bilan pour chaque utilisateur seulement pour une dépense positive avec parts
      if ($price > 0 AND $regularisationSansParts == false)
      {
        // Récupération du nombre total de parts
        $nombreTotalParts = array_sum($listeParts);

        // Insertion des parts
        foreach ($listeParts as $identifiant => $parts)
        {
          // Insertion de l'enregistrement en base
          $partUser = array('id_expense'  => $idDepense,
                            'identifiant' => $identifiant,
                            'parts'       => $parts
                           );

          physiqueInsertionPart($partUser);

          // Récupération des données utilisateur
          $user      = physiqueUser($identifiant);
          $bilanUser = $user->getExpenses();

          // Mise à jour du bilan pour chaque utilisateur (on retire au total)
          $bilanUser -= ($price / $nombreTotalParts) * $parts;

          // Modification de l'enregistrement en base
          physiqueUpdateBilan($identifiant, $bilanUser);

          // Génération succès (ajout des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, $parts);

          // Génération succès (total max)
          insertOrUpdateSuccesValue('greedy', $identifiant, $bilanUser);
        }

        // Génération succès (pour l'acheteur)
        insertOrUpdateSuccesValue('buyer', $buyer, 1);

        // Génération succès (dépense sans parts)
        if ($acheteurSansParts == true)
          insertOrUpdateSuccesValue('generous', $buyer, 1);
      }

      // Ajout expérience
      insertExperience($_SESSION['user']['identifiant'], 'add_expense');

      // Message d'alerte
      $_SESSION['alerts']['depense_added'] = true;
    }

    // Retour
    return $idDepense;
  }

  // METIER : Insertion de montants & mise à jour des dépenses utilisateurs
  // RETOUR : Id dépense
  function insertMontants($post)
  {
    // Initialisations
    $idDepense  = NULL;
    $control_ok = true;

    // Récupération des données
    $date    = date('Ymd');
    $buyer   = $post['buyer_user'];
    $comment = $post['comment'];
    $frais   = formatAmountForInsert($post['depense']);
    $type    = 'M';

    $listeMontants = array();

    foreach ($post['identifiant_montant'] as $id => $identifiant)
    {
      if ($post['montant_user'][$id] != '')
        $listeMontants[$identifiant] = $post['montant_user'][$id];
    }

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['price']            = $post['depense'];
    $_SESSION['save']['buyer']            = $buyer;
    $_SESSION['save']['comment']          = $comment;
    $_SESSION['save']['tableau_montants'] = $listeMontants;

    // Contrôle si frais numérique et positif (seulement si renseignés)
    if ($post['depense'] != '')
      $control_ok = controleFraisPositifs($frais);

    // Contrôle si au moins 1 montant saisi
    if ($control_ok == true)
      $control_ok = controleMontantsSaisis($listeMontants);

    // Contrôle montants numériques
    if ($control_ok == true)
      $control_ok = controleMontantsPositifs($listeMontants);

    // Insertion de l'enregistrement en base et traitement des utilisateurs
    if ($control_ok == true)
    {
      // Insertion de l'enregistrement en base
      $depense = array('date'    => $date,
                       'price'   => $frais,
                       'buyer'   => $buyer,
                       'comment' => $comment,
                       'type'    => $type
                      );

      $idDepense = physiqueInsertionDepense($depense);

      // Calcul du montant total
      $montantTotal = $frais;

      foreach ($listeMontants as $montant)
      {
        $montantTotal += formatAmountForInsert($montant);
      }

      // Récupération des données acheteur
      $acheteur      = physiqueUser($buyer);
      $bilanAcheteur = $acheteur->getExpenses();

      // Mise à jour du bilan de l'acheteur (on ajoute le montant total)
      $bilanAcheteur += $montantTotal;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($buyer, $bilanAcheteur);

      // Vérification si montant acheteur nul
      $acheteurSansMontant = true;

      if (isset($listeMontants[$buyer]) AND $listeMontants[$buyer] > 0)
        $acheteurSansMontant = false;

      // Génération succès (total max pour l'acheteur s'il n'a pas de montant saisi)
      if ($acheteurSansMontant == true)
        insertOrUpdateSuccesValue('greedy', $buyer, $bilanAcheteur);

      // Récupération du nombre total d'utilisateurs pour répartir les frais additionnels
      $nombreTotalUsers = count($listeMontants);

      // Insertion des montants & mise à jour du bilan pour chaque utilisateur
      foreach ($listeMontants as $identifiant => $montant)
      {
        $montantUser = formatAmountForInsert($montant);

        // Insertion de l'enregistrement en base
        $partUser = array('id_expense'  => $idDepense,
                          'identifiant' => $identifiant,
                          'parts'       => $montantUser
                         );

        physiqueInsertionPart($partUser);

        // Récupération des données utilisateur
        $user      = physiqueUser($identifiant);
        $bilanUser = $user->getExpenses();

        // Mise à jour du bilan pour chaque utilisateur (on retire au total)
        $bilanUser -= $montantUser + ($frais / $nombreTotalUsers);

        // Modification de l'enregistrement en base
        physiqueUpdateBilan($identifiant, $bilanUser);

        // Génération succès (ajout des parts)
        insertOrUpdateSuccesValue('eater', $identifiant, 1);

        // Génération succès (total max)
        insertOrUpdateSuccesValue('greedy', $identifiant, $bilanUser);
      }

      // Génération succès (pour l'acheteur)
      insertOrUpdateSuccesValue('buyer', $buyer, 1);

      // Génération succès (dépense sans parts)
      if ($acheteurSansMontant == true)
        insertOrUpdateSuccesValue('generous', $buyer, 1);

      // Ajout expérience
      insertExperience($_SESSION['user']['identifiant'], 'add_expense');

      // Message d'alerte
      $_SESSION['alerts']['depense_added'] = true;
    }

    // Retour
    return $idDepense;
  }

  // METIER : Modification d'une dépense
  // RETOUR : Id dépense
  function updateExpense($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idDepense  = $post['id_expense'];
    $newPrice   = formatAmountForInsert($post['depense']);
    $newBuyer   = $post['buyer_user'];
    $newComment = $post['comment'];

    $newListeParts = array();

    foreach ($post['identifiant_quantite'] as $id => $identifiant)
    {
      if ($post['quantite_user'][$id] != 0)
        $newListeParts[$identifiant] = $post['quantite_user'][$id];
    }

    // Vérification si régularisation négative sans parts
    $newRegularisationSansParts = true;

    if (!empty($newListeParts))
      $newRegularisationSansParts = false;

    // Contrôle si aucune part pour une régularisation
    $control_ok = controleRegularisation($newPrice, $newRegularisationSansParts);

    // Contrôle si prix numérique et non nul (négatif = régularisation, positif = régularisation ou dépense, nul = aucun sens)
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($newPrice);

    // Modification de la dépense
    if ($control_ok == true)
    {
      /*****************************************/
      /*** Retrait ancienne dépense et parts ***/
      /*****************************************/
      // Lecture dépense (avant mise à jour)
      $oldDepense = physiqueDepense($idDepense);

      // Récupération des données acheteur
      $acheteur      = physiqueUser($oldDepense->getBuyer());
      $bilanAcheteur = $acheteur->getExpenses();

      // Mise à jour du bilan pour l'acheteur (on retire l'ancienne dépense)
      $bilanAcheteur -= $oldDepense->getPrice();

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($oldDepense->getBuyer(), $bilanAcheteur);

      // Lecture des utilisateurs ayant déjà une part
      $oldListeParts = physiquePartsDepenseUsers($idDepense);

      // Récupération du nombre total de parts existantes
      $oldNombreTotalParts = array_sum($oldListeParts);

      // Vérification si régularisation négative sans parts
      $oldRegularisationSansParts = true;

      if (!empty($oldNombreTotalParts))
        $oldRegularisationSansParts = false;

      // Vérification si ancienne part acheteur nulle (pour une dépense positive hors régularisation)
      $oldAcheteurSansParts = true;

      if ($oldRegularisationSansParts == false AND $oldDepense->getPrice() > 0 AND isset($oldListeParts[$oldDepense->getBuyer()]) AND $oldListeParts[$oldDepense->getBuyer()] > 0)
        $oldAcheteurSansParts = false;

      // Mise à jour du bilan pour chaque utilisateur (retour arrière sur la dépense)
      foreach ($oldListeParts as $identifiant => $parts)
      {
        // Récupération des données utilisateur
        $user = physiqueUser($identifiant);

        // Traitement de l'utilisateur
        if (!empty($user))
        {
          $bilanUser = $user->getExpenses();

          // Mise à jour du bilan pour chaque utilisateur inscrit (on ajoute au bilan)
          $bilanUser += ($oldDepense->getPrice() / $oldNombreTotalParts) * $parts;

          // Modification de l'enregistrement en base
          physiqueUpdateBilan($identifiant, $bilanUser);

          // Génération succès (suppression des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, -$parts);
        }
      }

      // Suppression de toutes les anciennes parts
      physiqueDeleteParts($idDepense);

      // Génération succès (pour l'acheteur si modifié)
      if ($oldRegularisationSansParts == false AND ($newBuyer != $oldDepense->getBuyer() OR $newRegularisationSansParts == true))
        insertOrUpdateSuccesValue('buyer', $oldDepense->getBuyer(), -1);

      // Génération succès (dépense sans parts)
      if ($oldRegularisationSansParts == false AND $oldAcheteurSansParts == true)
        insertOrUpdateSuccesValue('generous', $oldDepense->getBuyer(), -1);

      /*********************************************/
      /*** Mise à jour nouvelle dépense et parts ***/
      /*********************************************/
      // Modification de l'enregistrement en base
      $depense = array('price'   => $newPrice,
                       'buyer'   => $newBuyer,
                       'comment' => $newComment
                      );

      physiqueUpdateDepense($idDepense, $depense);

      // Récupération des données nouvel acheteur
      $newAcheteur      = physiqueUser($newBuyer);
      $bilanNewAcheteur = $newAcheteur->getExpenses();

      // Mise à jour du bilan du nouvel acheteur (on ajoute la dépense)
      $bilanNewAcheteur += $newPrice;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($newBuyer, $bilanNewAcheteur);

      // Vérification si part acheteur nulle (pour une dépense positive hors régularisation)
      $newAcheteurSansParts = true;

      if ($newRegularisationSansParts == false AND $newPrice > 0 AND isset($newListeParts[$newBuyer]) AND $newListeParts[$newBuyer] > 0)
        $newAcheteurSansParts = false;

      // Génération succès (total max pour l'acheteur s'il n'a pas de parts)
      if ($newAcheteurSansParts == true)
        insertOrUpdateSuccesValue('greedy', $newBuyer, $bilanNewAcheteur);

      // Insertions des nouvelles parts & mise à jour du bilan pour chaque utilisateur seulement pour une dépense positive avec parts
      if ($newPrice > 0 AND $newRegularisationSansParts == false)
      {
        // Récupération du nombre total de nouvelles parts
        $nombreTotalPartsNew = array_sum($newListeParts);

        foreach ($newListeParts as $identifiant => $parts)
        {
          // Insertion de l'enregistrement en base
          $partUser = array('id_expense'  => $idDepense,
                            'identifiant' => $identifiant,
                            'parts'       => $parts
                           );

          physiqueInsertionPart($partUser);

          // Récupération des données utilisateur
          $user = physiqueUser($identifiant);

          // Traitement de l'utilisateur
          if (!empty($user))
          {
            $bilanUser = $user->getExpenses();

            // Mise à jour du bilan pour chaque utilisateur inscrit (on retire au total)
            $bilanUser -= ($newPrice / $nombreTotalPartsNew) * $parts;

            // Modification de l'enregistrement en base
            physiqueUpdateBilan($identifiant, $bilanUser);

            // Génération succès (ajout des parts)
            insertOrUpdateSuccesValue('eater', $identifiant, $parts);

            // Génération succès (total max)
            insertOrUpdateSuccesValue('greedy', $identifiant, $bilanUser);
          }
        }

        // Génération succès (pour l'acheteur si modifié)
        if ($newBuyer != $oldDepense->getBuyer() OR $oldRegularisationSansParts == true)
          insertOrUpdateSuccesValue('buyer', $newBuyer, 1);

        // Génération succès (dépense sans parts)
        if ($newAcheteurSansParts == true)
          insertOrUpdateSuccesValue('generous', $newBuyer, 1);
      }

      // Message d'alerte
      $_SESSION['alerts']['depense_modified'] = true;
    }

    // Retour
    return $idDepense;
  }

  // METIER : Modification de montants
  // RETOUR : Id dépense
  function updateMontants($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idDepense  = $post['id_expense'];
    $newFrais   = formatAmountForInsert($post['depense']);
    $newBuyer   = $post['buyer_user'];
    $newComment = $post['comment'];

    $newListeMontants = array();

    foreach ($post['identifiant_montant'] as $id => $identifiant)
    {
      if ($post['montant_user'][$id] != 0)
        $newListeMontants[$identifiant] = $post['montant_user'][$id];
    }

    // Contrôle si frais numérique et positif (seulement si renseignés)
    if ($post['depense'] != '')
      $control_ok = controleFraisPositifs($newFrais);

    // Contrôle montants numériques
    if ($control_ok == true)
      $control_ok = controleMontantsPositifs($newListeMontants);

    // Modification de la dépense
    if ($control_ok == true)
    {
      /********************************************/
      /*** Retrait ancienne dépense et montants ***/
      /********************************************/
      // Lecture dépense (avant mise à jour)
      $oldDepense = physiqueDepense($idDepense);
      $oldFrais   = formatAmountForInsert($oldDepense->getPrice());

      // Lecture des montants déjà existants des utilisateurs
      $oldListeMontants = physiquePartsDepenseUsers($idDepense);

      // Calcul du montant total
      $oldMontantTotal = $oldFrais;

      foreach ($oldListeMontants as $montant)
      {
        $oldMontantTotal += formatAmountForInsert($montant);
      }

      // Récupération des données acheteur
      $oldAcheteur      = physiqueUser($oldDepense->getBuyer());
      $bilanOldAcheteur = $oldAcheteur->getExpenses();

      // Mise à jour du bilan pour l'acheteur (on retire l'ancienne dépense)
      $bilanOldAcheteur -= $oldMontantTotal;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($oldDepense->getBuyer(), $bilanOldAcheteur);

      // Vérification si montant acheteur nul
      $oldAcheteurSansMontant = true;

      if (isset($oldListeMontants[$oldDepense->getBuyer()]) AND $oldListeMontants[$oldDepense->getBuyer()] > 0)
        $oldAcheteurSansMontant = false;

      // Récupération du nombre total d'utilisateurs pour répartir les frais additionnels
      $oldNombreTotalUsers = count($oldListeMontants);

      // Mise à jour du bilan pour chaque utilisateur (retour arrière sur la dépense)
      foreach ($oldListeMontants as $identifiant => $montant)
      {
        $montantUser = formatAmountForInsert($montant);

        // Récupération des données utilisateur
        $user = physiqueUser($identifiant);

        // Traitement de l'utilisateur
        if (!empty($user))
        {
          $bilanUser = $user->getExpenses();

          // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
          $bilanUser += $montantUser + ($oldFrais / $oldNombreTotalUsers);

          // Modification de l'enregistrement en base
          physiqueUpdateBilan($identifiant, $bilanUser);

          // Génération succès (suppression des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, -1);
        }
      }

      // Suppression de tous les anciens montants
      physiqueDeleteParts($idDepense);

      // Génération succès (pour l'acheteur si modifié)
      if ($newBuyer != $oldDepense->getBuyer())
        insertOrUpdateSuccesValue('buyer', $oldDepense->getBuyer(), -1);

      // Génération succès (dépense sans parts)
      if ($oldAcheteurSansMontant == true)
        insertOrUpdateSuccesValue('generous', $oldDepense->getBuyer(), -1);

      /************************************************/
      /*** Mise à jour nouvelle dépense et montants ***/
      /************************************************/
      // Modification de l'enregistrement en base
      $depense = array('price'   => $newFrais,
                       'buyer'   => $newBuyer,
                       'comment' => $newComment
                      );

      physiqueUpdateDepense($idDepense, $depense);

      // Calcul du montant total
      $newMontantTotal = $newFrais;

      foreach ($newListeMontants as $montant)
      {
        $newMontantTotal += formatAmountForInsert($montant);
      }

      // Récupération des données nouvel acheteur
      $newAcheteur      = physiqueUser($newBuyer);
      $bilanNewAcheteur = $newAcheteur->getExpenses();

      // Mise à jour du bilan du nouvel acheteur (on ajoute le montant total)
      $bilanNewAcheteur += $newMontantTotal;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($newBuyer, $bilanNewAcheteur);

      // Vérification si montant acheteur nul
      $newAcheteurSansMontant = true;

      if (isset($newListeMontants[$newBuyer]) AND $newListeMontants[$newBuyer] > 0)
        $newAcheteurSansMontant = false;

      // Génération succès (total max pour l'acheteur s'il n'a pas de montant saisi)
      if ($newAcheteurSansMontant == true)
        insertOrUpdateSuccesValue('greedy', $newBuyer, $bilanNewAcheteur);

      // Récupération du nombre total d'utilisateurs pour répartir les frais additionnels
      $newNombreTotalUsers = count($newListeMontants);

      // Insertions des nouveaux montants & mise à jour du bilan pour chaque utilisateur
      foreach ($newListeMontants as $identifiant => $montant)
      {
        $montantUser = formatAmountForInsert($montant);

        // Insertion de l'enregistrement en base
        $partUser = array('id_expense'  => $idDepense,
                          'identifiant' => $identifiant,
                          'parts'       => $montantUser
                         );

        physiqueInsertionPart($partUser);

        // Récupération des données utilisateur
        $user = physiqueUser($identifiant);

        // Traitement de l'utilisateur
        if (!empty($user))
        {
          $bilanUser = $user->getExpenses();

          // Mise à jour du bilan pour chaque utilisateur (on retire au total)
          $bilanUser -= $montantUser + ($newFrais / $newNombreTotalUsers);

          // Modification de l'enregistrement en base
          physiqueUpdateBilan($identifiant, $bilanUser);

          // Génération succès (ajout des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, 1);

          // Génération succès (total max)
          insertOrUpdateSuccesValue('greedy', $identifiant, $bilanUser);
        }
      }

      // Génération succès (pour l'acheteur si modifié)
      if ($newBuyer != $oldDepense->getBuyer())
        insertOrUpdateSuccesValue('buyer', $newBuyer, 1);

      // Génération succès (dépense sans parts)
      if ($newAcheteurSansMontant == true)
        insertOrUpdateSuccesValue('generous', $newBuyer, 1);

      // Message d'alerte
      $_SESSION['alerts']['depense_modified'] = true;
    }

    // Retour
    return $idDepense;
  }

  // METIER : Suppression d'une dépense
  // RETOUR : Aucun
  function deleteExpense($post)
  {
    // Récupération des données
    $idDepense = $post['id_expense'];

    // Lecture des données de la dépense
    $depense = physiqueDepense($idDepense);

    // Récupération des données acheteur
    $acheteur = physiqueUser($depense->getBuyer());

    // Traitement de l'utilisateur
    if (!empty($acheteur))
    {
      $bilanAcheteur = $acheteur->getExpenses();

      // Mise à jour du bilan pour l'acheteur inscrit (on retire la dépense)
      $bilanAcheteur -= $depense->getPrice();

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($depense->getBuyer(), $bilanAcheteur);
    }

    // Lecture des utilisateurs ayant une part
    $listeParts = physiquePartsDepenseUsers($depense->getId());

    // Vérification si régularisation négative sans parts
    $regularisationSansParts = true;

    if (!empty($listeParts))
      $regularisationSansParts = false;

    // Vérification si part acheteur nulle (pour une dépense positive hors régularisation)
    $acheteurSansParts = true;

    if ($regularisationSansParts == false AND $depense->getPrice() > 0 AND isset($listeParts[$depense->getBuyer()]) AND $listeParts[$depense->getBuyer()] > 0)
      $acheteurSansParts = false;

    // Récupération du nombre total de parts
    $nombreTotalParts = array_sum($listeParts);

    // Suppression des parts & mise à jour du bilan pour chaque utilisateur
    foreach ($listeParts as $identifiant => $parts)
    {
      // Récupération des données utilisateur
      $user = physiqueUser($identifiant);

      // Traitement de l'utilisateur
      if (!empty($user))
      {
        $bilanUser = $user->getExpenses();

        // Mise à jour du bilan pour chaque utilisateur inscrit (on ajoute au bilan)
        $bilanUser += ($depense->getPrice() / $nombreTotalParts) * $parts;

        // Modification de l'enregistrement en base
        physiqueUpdateBilan($identifiant, $bilanUser);

        // Génération succès (suppression des parts)
        insertOrUpdateSuccesValue('eater', $identifiant, -$parts);
      }
    }

    // Suppression de toutes les parts
    physiqueDeleteParts($idDepense);

    // Suppression de la dépense
    physiqueDeleteDepense($idDepense);

    // Générations succès
    if (!empty($acheteur))
    {
      // Génération succès (pour l'acheteur)
      if ($depense->getPrice() > 0 AND $regularisationSansParts == false)
        insertOrUpdateSuccesValue('buyer', $depense->getBuyer(), -1);

      // Génération succès (dépense sans parts)
      if ($regularisationSansParts == false AND $acheteurSansParts == true)
        insertOrUpdateSuccesValue('generous', $depense->getBuyer(), -1);
    }

    // Message suppression effectuée
    $_SESSION['alerts']['depense_deleted'] = true;
  }

  // METIER : Suppression d'une dépense en montants
  // RETOUR : Aucun
  function deleteMontants($post)
  {
    // Récupération des données
    $idDepense = $post['id_expense'];

    // Lecture des données de la dépense
    $depense = physiqueDepense($idDepense);
    $frais   = formatAmountForInsert($depense->getPrice());

    // Lecture des montants des utilisateurs
    $listeMontants = physiquePartsDepenseUsers($depense->getId());

    // Calcul du montant total
    $montantTotal = $frais;

    foreach ($listeMontants as $montant)
    {
      $montantTotal += formatAmountForInsert($montant);
    }

    // Récupération des données acheteur
    $acheteur = physiqueUser($depense->getBuyer());

    // Traitement de l'utilisateur
    if (!empty($acheteur))
    {
      $bilanAcheteur = $acheteur->getExpenses();

      // Mise à jour du bilan pour l'acheteur inscrit (on retire le montant total)
      $bilanAcheteur -= $montantTotal;

      // Modification de l'enregistrement en base
      physiqueUpdateBilan($depense->getBuyer(), $bilanAcheteur);
    }

    // Vérification si montant acheteur nul
    $acheteurSansMontant = true;

    if (isset($listeMontants[$depense->getBuyer()]) AND $listeMontants[$depense->getBuyer()] > 0)
      $acheteurSansMontant = false;

    // Récupération du nombre total d'utilisateurs pour répartir les frais additionnels
    $nombreTotalUsers = count($listeMontants);

    // Suppression des montants & mise à jour du bilan pour chaque utilisateur
    foreach ($listeMontants as $identifiant => $montant)
    {
      $montantUser = formatAmountForInsert($montant);

      // Récupération des données utilisateur
      $user = physiqueUser($identifiant);

      // Traitement de l'utilisateur
      if (!empty($user))
      {
        $bilanUser = $user->getExpenses();

        // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
        $bilanUser += $montantUser + ($frais / $nombreTotalUsers);

        // Modification de l'enregistrement en base
        physiqueUpdateBilan($identifiant, $bilanUser);

        // Génération succès (suppression des parts)
        insertOrUpdateSuccesValue('eater', $identifiant, -1);
      }
    }

    // Suppression de tous les montants
    physiqueDeleteParts($idDepense);

    // Suppression de la dépense
    physiqueDeleteDepense($idDepense);

    // Générations succès
    if (!empty($acheteur))
    {
      // Génération succès (pour l'acheteur)
      insertOrUpdateSuccesValue('buyer', $depense->getBuyer(), -1);

      // Génération succès (dépense sans parts)
      if ($acheteurSansMontant == true)
        insertOrUpdateSuccesValue('generous', $depense->getBuyer(), -1);
    }

    // Message suppression effectuée
    $_SESSION['alerts']['depense_deleted'] = true;
  }
?>
