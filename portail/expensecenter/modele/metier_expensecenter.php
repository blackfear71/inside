<?php
    include_once('../../includes/classes/expenses.php');
    include_once('../../includes/classes/profile.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['wrong_date'])              OR $_SESSION['alerts']['wrong_date']              != true)
        AND (!isset($_SESSION['alerts']['expense_date'])            OR $_SESSION['alerts']['expense_date']            != true)
        AND (!isset($_SESSION['alerts']['expense_not_numeric'])     OR $_SESSION['alerts']['expense_not_numeric']     != true)
        AND (!isset($_SESSION['alerts']['no_parts_regularization']) OR $_SESSION['alerts']['no_parts_regularization'] != true)
        AND (!isset($_SESSION['alerts']['parts_not_integer'])       OR $_SESSION['alerts']['parts_not_integer']       != true)
        AND (!isset($_SESSION['alerts']['empty_amount'])            OR $_SESSION['alerts']['empty_amount']            != true)
        AND (!isset($_SESSION['alerts']['amount_not_positive'])     OR $_SESSION['alerts']['amount_not_positive']     != true)
        AND (!isset($_SESSION['alerts']['reduction_not_correct'])   OR $_SESSION['alerts']['reduction_not_correct']   != true)
        AND (!isset($_SESSION['alerts']['amounts_not_numeric'])     OR $_SESSION['alerts']['amounts_not_numeric']     != true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['buyer']     = '';
            $_SESSION['save']['price']     = '';
            $_SESSION['save']['reduction'] = '';
            $_SESSION['save']['comment']   = '';

            if ($_SESSION['index']['plateforme'] == 'mobile')
                $_SESSION['save']['date_expense'] = date('Y-m-d');
            else
                $_SESSION['save']['date_expense'] = date('d/m/Y');
        }
    }

    // METIER : Contrôle année existante (pour les onglets)
    // RETOUR : Booléen
    function controlYear($annee, $equipe)
    {
        // Initialisations
        $anneeExistante = false;

        // Vérification année présente en base
        if (isset($annee) AND is_numeric($annee))
            $anneeExistante = physiqueAnneeExistante($annee, $equipe);

        // Retour
        return $anneeExistante;
    }

    // METIER : Lecture liste des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getUsers($annee, $equipe)
    {
        // Récupération de la liste des utilisateurs
        $listeUsers = physiqueUsers($annee, $equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Génération de la liste des filtres
    // RETOUR : Tableau des filtres
    function getFilters()
    {
        // Génération du tableau
        $tableauFiltres = array(
            array('label' => 'Toutes les dépenses', 'value' => 'all'),
            array('label' => 'Mes dépenses',        'value' => 'myExpenses'),
            array('label' => 'Mes parts',           'value' => 'myParts')
        );

        // Retour
        return $tableauFiltres;
    }

    // METIER : Lecture années distinctes pour les onglets
    // RETOUR : Liste des années existantes
    function getOnglets($equipe)
    {
        // Récupération de la liste des années existantes
        $onglets = physiqueOnglets($equipe);

        // Retour
        return $onglets;
    }

    // METIER : Lecture liste des dépenses
    // RETOUR : Liste des dépenses
    function getExpenses($get, $sessionUser, $listeUsers)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];
        $annee       = $get['year'];
        $filtre      = $get['filter'];

        // Récupération de la liste des dépenses
        $listeDepenses = physiqueDepenses($annee, $filtre, $identifiant, $equipe);

        // Récupération des données complémentaires
        foreach ($listeDepenses as $depense)
        {
            // Initialisation
            $prixMontants = $depense->getPrice();

            // Récupération de la répartition de la dépense
            $listePartsDepense = physiquePartsDepense($depense->getId());

            // Récupération des données acheteur
            if (isset($listeUsers[$depense->getBuyer()]))
            {
                $depense->setPseudo($listeUsers[$depense->getBuyer()]->getPseudo());
                $depense->setAvatar($listeUsers[$depense->getBuyer()]->getAvatar());
            }

            // Récupération des données utilisateurs
            foreach ($listePartsDepense as $partDepense)
            {
                if (isset($listeUsers[$partDepense->getIdentifiant()]))
                {
                    $partDepense->setPseudo($listeUsers[$partDepense->getIdentifiant()]->getPseudo());
                    $partDepense->setAvatar($listeUsers[$partDepense->getIdentifiant()]->getAvatar());
                }
                else
                    $partDepense->setInscrit(false);

                if ($depense->getType() == 'M')
                {
                    if (!empty($prixMontants))
                        $prixMontants += $partDepense->getParts();
                    else
                        $prixMontants = $partDepense->getParts();
                }
            }

            // Récupération du nombre de participants
            $nombreUsers = count($listePartsDepense);

            // Ajout des données complémentaires à la dépense
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

    // METIER : Conversion de la liste d'objets des utilisateurs en tableau simple pour JSON
    // RETOUR : Tableau des utilisateurs
    function convertForJsonListeUsers($listeUsers)
    {
        // Initialisations
        $listeUsersAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($listeUsers as $userAConvertir)
        {
            $user = array(
                'id'          => $userAConvertir->getId(),
                'identifiant' => $userAConvertir->getIdentifiant(),
                'team'        => $userAConvertir->getTeam(),
                'pseudo'      => $userAConvertir->getPseudo(),
                'avatar'      => $userAConvertir->getAvatar()
            );

            $listeUsersAConvertir[$userAConvertir->getIdentifiant()] = $user;
        }

        // Retour
        return $listeUsersAConvertir;
    }

    // METIER : Conversion de la liste d'objets des dépenses et des parts en tableau simple pour JSON
    // RETOUR : Tableau des dépenses
    function convertForJsonListeDepenses($listeDepenses)
    {
        // Initialisations
        $listeDepensesAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($listeDepenses as $depenseAConvertir)
        {
            $depense = array(
                'id'      => $depenseAConvertir->getId(),
                'team'    => $depenseAConvertir->getTeam(),
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
                $listePartsDepense[$parts->getIdentifiant()] = array(
                    'pseudo'  => $parts->getPseudo(),
                    'avatar'  => $parts->getAvatar(),
                    'team'    => $parts->getTeam(),
                    'parts'   => $parts->getParts(),
                    'inscrit' => $parts->getInscrit()
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
    function insertExpense($post, $sessionUser, $isMobile)
    {
        // Initialisations
        $idDepense  = NULL;
        $control_ok = true;

        // Récupération des données
        $userConnected       = $sessionUser['identifiant'];
        $equipeUserConnected = $sessionUser['equipe'];
        $price               = formatAmountForInsert($post['depense']);
        $buyer               = $post['buyer_user'];
        $comment             = $post['comment'];
        $type                = 'P';

        if ($isMobile == true)
            $date = formatDateForInsertMobile($post['date_expense']);
        else
            $date = formatDateForInsert($post['date_expense']);

        $listeParts = array();

        foreach ($post['identifiant_quantite'] as $id => $identifiant)
        {
            if (isset($post['quantite_user'][$id]) AND !empty($post['quantite_user'][$id]))
                $listeParts[$identifiant] = $post['quantite_user'][$id];
        }

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['buyer']         = $buyer;
        $_SESSION['save']['date_expense']  = $post['date_expense'];
        $_SESSION['save']['price']         = $post['depense'];
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

        // Contrôle date de saisie
        if ($control_ok == true)
            $control_ok = controleFormatDate($post['date_expense'], $isMobile);

        // Contrôle date cohérente
        if ($control_ok == true)
            $control_ok = controleDateSaisie($post['date_expense'], $isMobile);

        // Contrôle parts entières
        if ($control_ok == true)
            $control_ok = controlePartsEntieres($listeParts, $regularisationSansParts);

        // Insertion de l'enregistrement en base et traitement des utilisateurs
        if ($control_ok == true)
        {
            $depense = array(
                'team'    => $equipeUserConnected,
                'date'    => $date,
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
                    $partUser = array(
                        'id_expense'  => $idDepense,
                        'team'        => $equipeUserConnected,
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
            insertExperience($userConnected, 'add_expense');

            // Message d'alerte
            $_SESSION['alerts']['expense_added'] = true;
        }

        // Retour
        return $idDepense;
    }

    // METIER : Insertion de montants & mise à jour des dépenses utilisateurs
    // RETOUR : Id dépense
    function insertMontants($post, $sessionUser, $isMobile)
    {
        // Initialisations
        $idDepense  = NULL;
        $control_ok = true;

        // Récupération des données
        $userConnected       = $sessionUser['identifiant'];
        $equipeUserConnected = $sessionUser['equipe'];
        $buyer               = $post['buyer_user'];
        $comment             = $post['comment'];
        $frais               = formatAmountForInsert($post['depense']);
        $type                = 'M';

        if ($isMobile == true)
            $date = formatDateForInsertMobile($post['date_expense']);
        else
            $date = formatDateForInsert($post['date_expense']);

        if (!empty($post['reduction']))
            $reduction = $post['reduction'];
        else
            $reduction = 0;

        $listeMontants = array();

        foreach ($post['identifiant_montant'] as $id => $identifiant)
        {
            if (isset($post['montant_user'][$id]) AND !empty($post['montant_user'][$id]))
                $listeMontants[$identifiant] = $post['montant_user'][$id];
        }

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['buyer']            = $buyer;
        $_SESSION['save']['date_expense']     = $post['date_expense'];
        $_SESSION['save']['price']            = $post['depense'];
        $_SESSION['save']['reduction']        = $post['reduction'];
        $_SESSION['save']['comment']          = $comment;
        $_SESSION['save']['tableau_montants'] = $listeMontants;

        // Contrôle si frais numérique et positif (seulement si renseignés)
        if ($post['depense'] != '')
            $control_ok = controleFraisPositifs($frais);

        // Contrôle si réduction numérique et comprise entre 1 et 100 (seulement si renseignée)
        if ($control_ok == true)
        {
            if (!empty($reduction))
                $control_ok = controlePourcentageIntervalle($reduction, 1, 100);
        }

        // Contrôle date de saisie
        if ($control_ok == true)
            $control_ok = controleFormatDate($post['date_expense'], $isMobile);

        // Contrôle date cohérente
        if ($control_ok == true)
            $control_ok = controleDateSaisie($post['date_expense'], $isMobile);

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
            $depense = array(
                'team'    => $equipeUserConnected,
                'date'    => $date,
                'price'   => $frais,
                'buyer'   => $buyer,
                'comment' => $comment,
                'type'    => $type
            );

            $idDepense = physiqueInsertionDepense($depense);

            // Calcul du montant total
            $montantTotal = 0;

            foreach ($listeMontants as $montant)
            {
                $montantTotal += formatAmountForInsert($montant);
            }

            // Récupération des données acheteur
            $acheteur      = physiqueUser($buyer);
            $bilanAcheteur = $acheteur->getExpenses();

            // Mise à jour du bilan de l'acheteur (on ajoute le montant total, les frais et on déduit l'éventuelle réduction)
            if (empty($frais))
                $frais = 0;

            $montantTotalBase = formatAmountForInsert($montantTotal + $frais - (($montantTotal * $reduction) / 100));
            $bilanAcheteur   += $montantTotalBase;

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
            $montantTotalInsert = 0;

            foreach ($listeMontants as $identifiant => $montant)
            {
                // Calcul du montant de l'utilisateur avec la réduction (tronqué à 2 chiffres après la virgule)
                $montantUser = formatAmountForInsert(formatAmountForInsert($montant) * ((100 - $reduction) / 100));

                // Insertion de l'enregistrement en base
                $partUser = array(
                    'id_expense'  => $idDepense,
                    'team'        => $equipeUserConnected,
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

                // Ajout au total des montants insérés pour vérification
                $montantTotalInsert += $montantUser + ($frais / $nombreTotalUsers);
            }

            // Vérification écart entre dépense totale et dépenses stockées (les nombres flotants impliquent une perte de précision lors des calculs)
            gestionEcartDepense($montantTotalBase, $montantTotalInsert, $buyer, $listeMontants, $reduction, $idDepense);

            // Génération succès (pour l'acheteur)
            insertOrUpdateSuccesValue('buyer', $buyer, 1);

            // Génération succès (dépense sans parts)
            if ($acheteurSansMontant == true)
                insertOrUpdateSuccesValue('generous', $buyer, 1);

            // Ajout expérience
            insertExperience($userConnected, 'add_expense');

            // Message d'alerte
            $_SESSION['alerts']['expense_added'] = true;
        }

        // Retour
        return $idDepense;
    }

    // METIER : Modification d'une dépense
    // RETOUR : Id dépense
    function updateExpense($post, $equipe, $isMobile)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idDepense  = $post['id_expense_saisie'];
        $newPrice   = formatAmountForInsert($post['depense']);
        $newBuyer   = $post['buyer_user'];
        $newComment = $post['comment'];

        if ($isMobile == true)
            $newDate = formatDateForInsertMobile($post['date_expense']);
        else
            $newDate = formatDateForInsert($post['date_expense']);

        $newListeParts = array();

        foreach ($post['identifiant_quantite'] as $id => $identifiant)
        {
            if (isset($post['quantite_user'][$id]) AND !empty($post['quantite_user'][$id]))
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

        // Contrôle date de saisie
        if ($control_ok == true)
            $control_ok = controleFormatDate($post['date_expense'], $isMobile);

        // Contrôle date cohérente
        if ($control_ok == true)
            $control_ok = controleDateSaisie($post['date_expense'], $isMobile);

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
            $depense = array(
                'date'    => $newDate,
                'price'   => $newPrice,
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
                    $partUser = array(
                        'id_expense'  => $idDepense,
                        'team'        => $equipe,
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
            $_SESSION['alerts']['expense_updated'] = true;
        }

        // Retour
        return $idDepense;
    }

    // METIER : Modification de montants
    // RETOUR : Id dépense
    function updateMontants($post, $equipe, $isMobile)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idDepense  = $post['id_expense_saisie'];
        $newFrais   = formatAmountForInsert($post['depense']);
        $newBuyer   = $post['buyer_user'];
        $newComment = $post['comment'];

        if ($isMobile == true)
            $newDate = formatDateForInsertMobile($post['date_expense']);
        else
            $newDate = formatDateForInsert($post['date_expense']);

        if (!empty($post['reduction']))
            $newReduction = $post['reduction'];
        else
            $newReduction = 0;

        $newListeMontants = array();

        foreach ($post['identifiant_montant'] as $id => $identifiant)
        {
            if (isset($post['montant_user'][$id]) AND !empty($post['montant_user'][$id]))
                $newListeMontants[$identifiant] = $post['montant_user'][$id];
        }

        // Contrôle si frais numérique et positif (seulement si renseignés)
        if ($post['depense'] != '')
            $control_ok = controleFraisPositifs($newFrais);

        // Contrôle si réduction numérique et comprise entre 1 et 100 (seulement si renseignée)
        if ($control_ok == true)
        {
            if (!empty($reduction))
                $control_ok = controlePourcentageIntervalle($reduction, 1, 100);
        }

        // Contrôle date de saisie
        if ($control_ok == true)
            $control_ok = controleFormatDate($post['date_expense'], $isMobile);

        // Contrôle date cohérente
        if ($control_ok == true)
            $control_ok = controleDateSaisie($post['date_expense'], $isMobile);

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
            $oldMontantTotal = 0;

            foreach ($oldListeMontants as $montant)
            {
                $oldMontantTotal += formatAmountForInsert($montant);
            }

            // Récupération des données acheteur
            $oldAcheteur      = physiqueUser($oldDepense->getBuyer());
            $bilanOldAcheteur = $oldAcheteur->getExpenses();

            // Mise à jour du bilan pour l'acheteur (on retire l'ancienne dépense)
            if (empty($oldFrais))
                $oldFrais = 0;

            $bilanOldAcheteur -= $oldMontantTotal + $oldFrais;

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
            $depense = array(
                'date'    => $newDate,
                'price'   => $newFrais,
                'buyer'   => $newBuyer,
                'comment' => $newComment
            );

            physiqueUpdateDepense($idDepense, $depense);

            // Calcul du montant total
            $newMontantTotal = 0;

            foreach ($newListeMontants as $montant)
            {
                $newMontantTotal += formatAmountForInsert($montant);
            }

            // Récupération des données nouvel acheteur
            $newAcheteur      = physiqueUser($newBuyer);
            $bilanNewAcheteur = $newAcheteur->getExpenses();

            // Mise à jour du bilan du nouvel acheteur (on ajoute le montant total, les frais et on déduit l'éventuelle réduction)
            if (empty($newFrais))
                $newFrais = 0;

            $newMontantTotalBase = formatAmountForInsert($newMontantTotal + $newFrais - (($newMontantTotal * $newReduction) / 100));
            $bilanNewAcheteur   += $newMontantTotalBase;

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
            $newMontantTotalInsert = 0;

            foreach ($newListeMontants as $identifiant => $montant)
            {
                // Calcul du montant de l'utilisateur avec la réduction
                $montantUser = formatAmountForInsert(formatAmountForInsert($montant) * ((100 - $newReduction) / 100));

                // Insertion de l'enregistrement en base
                $partUser = array(
                    'id_expense'  => $idDepense,
                    'team'        => $equipe,
                    'identifiant' => $identifiant,
                    'parts'       => $montantUser
                );

                physiqueInsertionPart($partUser);

                // Récupération des données utilisateur
                $user = physiqueUser($identifiant);

                // Traitement de l'utilisateur (si toujours inscrit)
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

                // Ajout au total des montants insérés pour vérification
                $newMontantTotalInsert += $montantUser + ($newFrais / $newNombreTotalUsers);
            }

            // Vérification écart entre dépense totale et dépenses stockées (les nombres flotants impliquent une perte de précision lors des calculs)
            gestionEcartDepense($newMontantTotalBase, $newMontantTotalInsert, $newBuyer, $newListeMontants, $newReduction, $idDepense);

            // Génération succès (pour l'acheteur si modifié)
            if ($newBuyer != $oldDepense->getBuyer())
                insertOrUpdateSuccesValue('buyer', $newBuyer, 1);

            // Génération succès (dépense sans parts)
            if ($newAcheteurSansMontant == true)
                insertOrUpdateSuccesValue('generous', $newBuyer, 1);

            // Message d'alerte
            $_SESSION['alerts']['expense_updated'] = true;
        }

        // Retour
        return $idDepense;
    }

    // METIER : Vérification écart entre dépense totale et dépenses stockées (les nombres flotants impliquent une perte de précision lors des calculs)
    // RETOUR : Aucun
    function gestionEcartDepense($montantTotalBase, $montantTotalInsert, $buyer, $listeMontants, $reduction, $idDepense)
    {
        // Calcul de l'écart entre le montant réel et la somme des montants inséré
        $ecartMontantTotal = $montantTotalBase - $montantTotalInsert;
        
        // Répartition en cas d'écart
        if (abs($ecartMontantTotal) >= 0.01)
        {
            $userFounded = false;

            if (isset($listeMontants[$buyer]))
            {
                // Récupération des informations de l'acheteur (le bilan a été mis à jour entre temps)
                $user = physiqueUser($buyer);

                // L'acheteur prend en charge l'éventuel écart (centimes arrondis)
                $userEcart   = $buyer;
                $newBilan    = $user->getExpenses() - $ecartMontantTotal;
                $userFounded = true;
            }
            else
            {
                // Par défault le premier utilisateur inscrit ayant une part prend en charge l'éventuel écart (centimes arrondis)
                foreach ($listeMontants as $identifiant => $montant)
                {
                    // Récupération des informations de l'utilisateur courant (le bilan a été mis à jour entre temps)
                    $user = physiqueUser($identifiant);

                    // On arrête la boucle pour sélectionner le premier utilisateur inscrit
                    if (!empty($user))
                    {
                        $userEcart   = $identifiant;
                        $newBilan    = $user->getExpenses() - $ecartMontantTotal;
                        $userFounded = true;
                        break;
                    }
                }

                // Si les parts ne concernent que des utilisateurs désinscrits, on sélectionne le premier
                if (empty($userEcart) AND $userFounded != true)
                {
                    reset($listeMontants);
                    $userEcart = key($listeMontants);
                }
            }

            // Formatage de l'écart
            $montantEcart = formatAmountForInsert(formatAmountForInsert($listeMontants[$userEcart]) * ((100 - $reduction) / 100) + $ecartMontantTotal);

            // Mise à jour de la part de l'utilisateur
            physiqueUpdatePart($idDepense, $userEcart, $montantEcart);

            // Mise à jour du bilan de l'utilisateur (si trouvé)
            if ($userFounded == true)
                physiqueUpdateBilan($userEcart, $newBilan);
        }
    }

    // METIER : Suppression d'une dépense
    // RETOUR : Aucun
    function deleteExpense($post)
    {
        // Récupération des données
        $idDepense = $post['id_expense_delete'];

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

        // Message d'alerte
        $_SESSION['alerts']['expense_deleted'] = true;
    }

    // METIER : Suppression d'une dépense en montants
    // RETOUR : Aucun
    function deleteMontants($post)
    {
        // Récupération des données
        $idDepense = $post['id_expense_delete'];

        // Lecture des données de la dépense
        $depense = physiqueDepense($idDepense);
        $frais   = formatAmountForInsert($depense->getPrice());

        // Lecture des montants des utilisateurs
        $listeMontants = physiquePartsDepenseUsers($depense->getId());

        // Calcul du montant total
        $montantTotal = 0;

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
            if (empty($frais))
                $frais = 0;

            $bilanAcheteur -= $montantTotal + $frais;

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

        // Message d'alerte
        $_SESSION['alerts']['expense_deleted'] = true;
    }
?>