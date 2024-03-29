<?php
    include_once('../../includes/classes/missions.php');
    include_once('../../includes/classes/movies.php');
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/success.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true)
        AND (!isset($_SESSION['alerts']['level_not_numeric'])  OR $_SESSION['alerts']['level_not_numeric']  != true)
        AND (!isset($_SESSION['alerts']['order_not_numeric'])  OR $_SESSION['alerts']['order_not_numeric']  != true)
        AND (!isset($_SESSION['alerts']['already_ordered'])    OR $_SESSION['alerts']['already_ordered']    != true)
        AND (!isset($_SESSION['alerts']['limit_not_numeric'])  OR $_SESSION['alerts']['limit_not_numeric']  != true)
        AND (!isset($_SESSION['alerts']['file_too_big'])       OR $_SESSION['alerts']['file_too_big']       != true)
        AND (!isset($_SESSION['alerts']['temp_not_found'])     OR $_SESSION['alerts']['temp_not_found']     != true)
        AND (!isset($_SESSION['alerts']['wrong_file_type'])    OR $_SESSION['alerts']['wrong_file_type']    != true)
        AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['reference_success']   = '';
            $_SESSION['save']['level']               = '';
            $_SESSION['save']['unicity']             = '';
            $_SESSION['save']['mission']             = '';
            $_SESSION['save']['order_success']       = '';
            $_SESSION['save']['title_success']       = '';
            $_SESSION['save']['description_success'] = '';
            $_SESSION['save']['limit_success']       = '';
            $_SESSION['save']['explanation_success'] = '';
        }
    }

    // METIER : Lecture liste des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getUsers()
    {
        // Récupération de la liste des utilisateurs
        $listeUsers = physiqueUsers();

        // Retour
        return $listeUsers;
    }

    // METIER : Lecture liste des succès
    // RETOUR : Liste des succès
    function getSuccess()
    {
        // Récupération de la liste des succès
        $listeSuccess = physiqueListeSuccess();

        // Retour
        return $listeSuccess;
    }

    // METIER : Lecture liste des mission
    // RETOUR : Liste des missions
    function getMissions()
    {
        // Récupération de la liste des missions
        $listeMissions = physiqueListeMissions();

        // Retour
        return $listeMissions;
    }

    // METIER : Insertion nouveau succès
    // RETOUR : Id succès
    function insertSuccess($post, $files)
    {
        // Initialisations
        $idSucces   = NULL;
        $control_ok = true;

        // Récupération des données
        $reference    = $post['reference'];
        $level        = $post['level'];
        $orderSuccess = $post['order_success'];
        $defined      = 'N';
        $mission      = $post['mission'];
        $title        = $post['title'];
        $description  = $post['description'];
        $limitSuccess = formatNumericForInsert($post['limit_success']);
        $explanation  = $post['explanation'];

        if (isset($post['unicity']))
            $unicity = 'Y';
        else
            $unicity = 'N';

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['reference_success']   = $post['reference'];
        $_SESSION['save']['level']               = $post['level'];
        $_SESSION['save']['unicity']             = $unicity;
        $_SESSION['save']['mission']             = $post['mission'];
        $_SESSION['save']['order_success']       = $post['order_success'];
        $_SESSION['save']['title_success']       = $post['title'];
        $_SESSION['save']['description_success'] = $post['description'];
        $_SESSION['save']['limit_success']       = $post['limit_success'];
        $_SESSION['save']['explanation_success'] = $post['explanation'];

        // Contrôle référence unique
        $control_ok = controleReferenceUnique($reference);

        // Contrôle niveau numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($level, 'level_not_numeric');

        // Contrôle ordonnancement numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($orderSuccess, 'order_not_numeric');

        // Contrôle ordonnancement unique
        if ($control_ok == true)
            $control_ok = controleOrdonnancementUnique($level, $orderSuccess, $reference, false);

        // Contrôle condition numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($limitSuccess, 'limit_not_numeric');

        // Traitement de l'image
        if ($control_ok == true)
            uploadImage($files['success'], $reference);

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $success = array(
                'reference'     => $reference,
                'level'         => $level,
                'order_success' => $orderSuccess,
                'defined'       => $defined,
                'unicity'       => $unicity,
                'mission'       => $mission,
                'title'         => $title,
                'description'   => $description,
                'limit_success' => $limitSuccess,
                'explanation'   => $explanation
            );

            $idSucces = physiqueInsertionSuccess($success);

            // Message d'alerte
            $_SESSION['alerts']['success_added'] = true;
        }

        // Retour
        return $idSucces;
    }

    // METIER : Modification succès
    // RETOUR : Id succès
    function updateSuccess($post, $files)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idSucces     = $post['id_succes'];
        $reference    = $post['reference_succes'];
        $niveau       = $post['niveau_succes'];
        $ordre        = $post['ordre_succes'];
        $defini       = $post['code_succes'];
        $unicite      = $post['unicite_succes'];
        $mission      = $post['mission_succes'];
        $titre        = $post['titre_succes'];
        $description  = $post['description_succes'];
        $limite       = formatNumericForInsert($post['limite_succes']);
        $explications = $post['explications_succes'];

        // Contrôle niveau numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($niveau, 'level_not_numeric');

        // Contrôle ordonnancement numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($ordre, 'order_not_numeric');

        // Contrôle ordonnancement unique
        if ($control_ok == true)
            $control_ok = controleOrdonnancementUnique($niveau, $ordre, $reference, true);

        // Contrôle condition numérique et positif
        if ($control_ok == true)
            $control_ok = controleNumerique($limite, 'limit_not_numeric');

        // Traitement de l'image
        if ($control_ok == true)
        {
            // Suppression ancienne image et insertion nouvelle
            if (!empty($files['success']['name']))
            {
                // Suppression des anciennes images
                unlink('../../includes/images/profil/success/' . $reference . '.png');

                // Insertion image
                uploadImage($files['success'], $reference);
            }
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $succes = array(
                'level'         => $niveau,
                'order_success' => $ordre,
                'defined'       => $defini,
                'unicity'       => $unicite,
                'mission'       => $mission,
                'title'         => $titre,
                'description'   => $description,
                'limit_success' => $limite,
                'explanation'   => $explications
            );

            physiqueUpdateSuccess($idSucces, $succes);

            // Message d'alerte
            $_SESSION['alerts']['success_updated'] = true;
        }

        // Retour
        return $idSucces;
    }

    // METIER : Formatage et insertion image succès
    // RETOUR : Aucun
    function uploadImage($file, $name)
    {
        // Initialisations
        $control_ok = true;

        // Dossier de destination
        $dossier = '../../includes/images/profil/success';

        // Contrôles fichier
        $fileDatas = controlsUploadFile($file, $name, 'png');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Upload fichier
        if ($control_ok == true)
            $control_ok = uploadFile($fileDatas, $dossier);

        // Traitement de l'image (miniature)
        if ($control_ok == true)
        {
            // Création miniature avec une hauteur/largeur max de 500px
            imageThumb($dossier . '/' . $fileDatas['new_name'], $dossier . '/' . $fileDatas['new_name'], 500, false, true);
        }
    }

    // METIER : Suppression succès
    // RETOUR : Aucun
    function deleteSuccess($post)
    {
        // Récupération des données du succès
        $success = physiqueSuccess($post['id_success']);

        // Suppression de l'image
        unlink('../../includes/images/profil/success/' . $success->getReference() . '.png');

        // Suppression des données utilisateurs
        physiqueDeleteSuccessUsers($success->getReference());

        // Suppression du succès de la base
        physiqueDeleteSuccess($success->getReference());

        // Message d'alerte
        $_SESSION['alerts']['success_deleted'] = true;
    }

    // METIER : Initialisation des succès
    // RETOUR : Aucun
    function initializeSuccess($listeSuccess, $listeUsers)
    {
        // Détermination de chaque succès pour chaque utilisateur
        if (!empty($listeSuccess) AND !empty($listeUsers))
        {
            foreach ($listeUsers as $user)
            {
                // Boucle de traitement sur les succès
                foreach ($listeSuccess as $success)
                {
                    // Initialisations
                    $value           = NULL;
                    $action          = NULL;
                    $listeConditions = array();

                    // Détermination valeur à insérer
                    switch ($success->getReference())
                    {
                        // J'étais là
                        case 'beginning':
                        // Je l'ai fait !
                        case 'developper':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'reference',
                                    'test'     => '=',
                                    'value'    => $success->getReference()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                )
                            );

                            physiqueValueSuccess('success_users', $listeConditions, 'value');
                            break;

                        // Cinéphile amateur
                        case 'publisher':
                            $listeConditions = array(array(
                                    'operator' => '',
                                    'column'   => 'identifiant_add',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('movie_house', $listeConditions);
                            break;

                        // Cinéphile professionnel
                        case 'viewer':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'participation',
                                    'test'     => '=',
                                    'value'    => 'S'
                                )
                            );

                            $value = physiqueCountSuccess('movie_house_users', $listeConditions);
                            break;

                        // Commentateur sportif
                        case 'commentator':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('movie_house_comments', $listeConditions);
                            break;

                        // Expert acoustique
                        case 'listener':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'author',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('collector', $listeConditions);
                            break;

                        // Dommage collatéral
                        case 'speaker':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'speaker',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('collector', $listeConditions);
                            break;

                        // Rigolo compulsif
                        case 'funny':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('collector_users', $listeConditions);
                            break;

                        // Auto-satisfait
                        case 'self-satisfied':
                            $value = physiqueSelfSatisfiedSuccess($user->getIdentifiant());
                            break;

                        // Désigné volontaire
                        case 'buyer':
                            $value = physiqueBuyerSuccess($user->getIdentifiant());
                            break;

                        // Profiteur occasionnel
                        case 'eater':
                            $value = physiqueEaterSuccess($user->getIdentifiant());
                            break;

                        // Mer il et fou !
                        case 'generous':
                            $value = physiqueGenerousSuccess($user->getIdentifiant());
                            break;

                        // Economie de marché
                        case 'greedy':
                            // Récupération du bilan de l'utilisateur
                            $bilan = physiqueBilanUser($user->getIdentifiant());

                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'reference',
                                    'test'     => '=',
                                    'value'    => $success->getReference()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                )
                            );

                            $value = physiqueValueSuccess('success_users', $listeConditions, 'value');

                            if (is_null($value) OR $bilan > $value)
                                $value = $bilan;
                            break;

                        // Génie créatif
                        case 'creator':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'author',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('ideas', $listeConditions);
                            break;

                        // Top développeur
                        case 'applier':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'developper',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'status',
                                    'test'     => '=',
                                    'value'    => 'D'
                                )
                            );

                            $value = physiqueCountSuccess('ideas', $listeConditions);
                            break;

                        // Débugger aguerri
                        case 'debugger':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('bugs', $listeConditions);
                            break;

                        // Compilateur intégré
                        case 'compiler':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'resolved',
                                    'test'     => '=',
                                    'value'    => 'Y'
                                )
                            );

                            $value = physiqueCountSuccess('bugs', $listeConditions);
                            break;

                        // Véritable Jedi
                        case 'padawan':
                            // Récupération date de sortie Star Wars VIII
                            $listeFilms = physiqueRechercheFilms('Les derniers Jedi', $user->getTeam());

                            if (!empty($listeFilms))
                            {
                                foreach ($listeFilms as $film)
                                {
                                    if (date('Ymd') >= $film->getDate_theater())
                                    {
                                        $listeConditions = array(
                                            array(
                                                'operator' => '',
                                                'column'   => 'id_film',
                                                'test'     => '=',
                                                'value'    => $film->getId()
                                            ),
                                            array(
                                                'operator' => 'AND',
                                                'column'   => 'identifiant',
                                                'test'     => '=',
                                                'value'    => $user->getIdentifiant()
                                            ),
                                            array(
                                                'operator' => 'AND',
                                                'column'   => 'participation',
                                                'test'     => '=',
                                                'value'    => 'S'
                                            )
                                        );

                                        $isSeen = physiqueCountSuccess('movie_house_users', $listeConditions);

                                        if ($isSeen > 0)
                                        {
                                            $value = 1;
                                            break;
                                        }
                                    }
                                }
                            }
                            break;

                        // Radar à bouffe
                        case 'restaurant-finder':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant_add',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('food_advisor_restaurants', $listeConditions);
                            break;

                        // Chef étoilé
                        case 'star-chief':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'caller',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('food_advisor_choices', $listeConditions);
                            break;

                        // Cuisto expérimental
                        case 'cooker':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'cooked',
                                    'test'     => '=',
                                    'value'    => 'Y'
                                )
                            );

                            $value = physiqueCountSuccess('cooking_box', $listeConditions);
                            break;

                        // Maître pâtissier
                        case 'recipe-master':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'name',
                                    'test'     => '!=',
                                    'value'    => ''
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'picture',
                                    'test'     => '!=',
                                    'value'    => ''
                                )
                            );

                            $value = physiqueCountSuccess('cooking_box', $listeConditions);
                            break;

                        // Explorateur
                        case 'explorer':
                            $listeConditions = array(array(
                                    'operator' => '',
                                    'column'   => 'identifiant_add',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('petits_pedestres_parcours', $listeConditions);
                            break;

                        // Bien dans mes baskets !
                        case 'runner':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $value = physiqueCountSuccess('petits_pedestres_users', $listeConditions);
                            break;

                        // Marathonien confirmé
                        case 'marathon':
                            $value = physiqueMarathonSuccess($user->getIdentifiant());
                            break;

                        // On n'est pas ici pour perdre
                        case 'competitor':
                            $listeConditions = array(
                                array(
                                    'operator' => '',
                                    'column'   => 'identifiant',
                                    'test'     => '=',
                                    'value'    => $user->getIdentifiant()
                                ),
                                array(
                                    'operator' => 'AND',
                                    'column'   => 'competition',
                                    'test'     => '=',
                                    'value'    => 'Y'
                                )
                            );

                            $value = physiqueCountSuccess('petits_pedestres_users', $listeConditions);
                            break;

                        // Niveaux
                        case 'level_1':
                        case 'level_5':
                        case 'level_10':
                            $listeConditions = array(array(
                                'operator' => '',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            ));

                            $experience = physiqueValueSuccess('users', $listeConditions, 'experience');

                            if ($experience > 0)
                                $value = convertExperience($experience);
                            break;

                        // Lutin de Noël
                        case 'christmas2017':
                        // Je suis ton Père Noël !
                        case 'christmas2017_2':
                        // Un coeur en or
                        case 'golden-egg':
                        // Mettre tous ses oeufs dans le même panier
                        case 'rainbow-egg':
                        // Apprenti sorcier
                        case 'wizard':
                        // Le plein de cadeaux !
                        case 'christmas2018':
                        // C'est tout ce que j'ai ?!
                        case 'christmas2018_2':
                        // Première étoile
                        case 'christmas2019':
                        // Paquet livré !
                        case 'delivery':
                            // Récupération des données de la mission
                            $mission = physiqueDonneesMission($success->getMission());

                            if (date('Ymd') > $mission->getDate_fin())
                            {
                                $listeConditions = array(
                                    array(
                                        'operator' => '',
                                        'column'   => 'id_mission',
                                        'test'     => '=',
                                        'value'    => $mission->getId()
                                    ),
                                    array(
                                        'operator' => 'AND',
                                        'column'   => 'identifiant',
                                        'test'     => '=',
                                        'value'    => $user->getIdentifiant()
                                    )
                                );

                                $value = physiqueSommeSucces('missions_users', $listeConditions, 'avancement');
                            }
                            break;

                        default:
                            break;
                    }

                    // Détermination action à effectuer
                    if (!is_null($value) AND $value != 0)
                    {
                        $listeConditions = array(
                            array(
                                'operator' => '',
                                'column'   => 'reference',
                                'test'     => '=',
                                'value'    => $success->getReference()
                            ),
                            array(
                                'operator' => 'AND',
                                'column'   => 'identifiant',
                                'test'     => '=',
                                'value'    => $user->getIdentifiant()
                            )
                        );

                        $oldValue = physiqueValueSuccess('success_users', $listeConditions, 'value');

                        // Mise à jour seulement si la nouvelle valeur est supérieure à l'ancienne
                        if (!is_null($oldValue))
                        {
                            if ($value > $oldValue)
                                $action = 'update';
                        }
                        else
                            $action = 'insert';
                    }

                    // Insertion / modification de chaque succès
                    switch ($action)
                    {
                        case 'insert':
                            $successUser = array(
                                'reference'   => $success->getReference(),
                                'identifiant' => $user->getIdentifiant(),
                                'value'       => $value
                            );

                            physiqueInsertionSuccessUser($successUser);
                            break;

                        case 'update':
                            physiqueUpdateSuccessUser($success->getReference(), $user->getIdentifiant(), $value);
                            break;

                        default:
                            break;
                    }

                    // Purge éventuelle des succès à 0
                    physiqueDeleteSuccessNoValue();
                }
            }
        }

        // Message d'alerte
        $_SESSION['alerts']['success_initialized'] = true;
    }

    // METIER : Purge tous les succès
    // RETOUR : Aucun
    function purgeSuccess()
    {
        // Suppression des succès (sauf exceptions)
        physiqueDeleteSuccessAdmin();

        // Rénumérotation des enregistrements restants
        physiqueRenumerotationSuccess();

        // Message d'alerte
        $_SESSION['alerts']['success_purged'] = true;
    }
?>