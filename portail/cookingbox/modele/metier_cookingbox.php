<?php
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['quantity_not_numeric']) OR $_SESSION['alerts']['quantity_not_numeric'] != true)
    AND (!isset($_SESSION['alerts']['file_too_big'])         OR $_SESSION['alerts']['file_too_big']         != true)
    AND (!isset($_SESSION['alerts']['temp_not_found'])       OR $_SESSION['alerts']['temp_not_found']       != true)
    AND (!isset($_SESSION['alerts']['wrong_file_type'])      OR $_SESSION['alerts']['wrong_file_type']      != true)
    AND (!isset($_SESSION['alerts']['wrong_file'])           OR $_SESSION['alerts']['wrong_file']           != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['year_recipe']           = '';
      $_SESSION['save']['week_recipe']           = '';
      $_SESSION['save']['name_recipe']           = '';
      $_SESSION['save']['ingredients']           = array();
      $_SESSION['save']['quantites_ingredients'] = array();
      $_SESSION['save']['unites_ingredients']    = array();
      $_SESSION['save']['preparation']           = '';
      $_SESSION['save']['remarks']               = '';
    }
  }

  // METIER : Récupère les données d'une semaine (N ou N+1)
  // RETOUR : Données de la semaine
  function getWeek($equipe, $week, $year)
  {
    // Récupération des données de la semaine concernée
    $semaineGateau = physiqueSemaineGateau($equipe, $week, $year);

    // Récupération des données utilisateur
    $user = physiqueUser($semaineGateau->getIdentifiant());

    // Ajout des données complémentaires à la semaine
    if (!empty($user))
    {
      $semaineGateau->setPseudo($user->getPseudo());
      $semaineGateau->setAvatar($user->getAvatar());
    }

    // Retour
    return $semaineGateau;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers($equipe)
  {
    // Récupération de la liste des utilisateurs
    $listeUsers = physiqueUsers($equipe);

    // Retour
    return $listeUsers;
  }

  // METIER : Filtrage et conversion de la liste d'objets des utilisateurs de l'équipe en tableau simple pour JSON
  // RETOUR : Tableau des recettes
  function convertForJsonListeCookers($listeCookers, $equipe)
  {
    // Initialisations
    $listeCookersAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeCookers as $identifiant => $cooker)
    {
      if ($cooker['team'] == $equipe)
        $listeCookersAConvertir[$identifiant] = $cooker;
    }

    // Retour
    return $listeCookersAConvertir;
  }

  // METIER : Récupère les semaines par années pour la saisie de recette
  // RETOUR : Liste des semaines par années
  function getWeeks($sessionUser)
  {
    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Récupération des semaines avec recette saisissable pour l'utilisateur
    $listeSemainesParAnnees = physiqueSemainesGateauUser($identifiant, $equipe);

    // Retour
    return $listeSemainesParAnnees;
  }

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year, $equipe)
  {
    // Initialisations
    $anneeExistante = false;

    // Vérification année présente en base
    if (isset($year) AND is_numeric($year))
      $anneeExistante = physiqueAnneeExistante($year, $equipe);

    // Retour
    return $anneeExistante;
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

  // METIER : Lecture des recettes saisies
  // RETOUR : Liste des recettes
  function getRecipes($year, $equipe, $listeCookers)
  {
    // Récupération de la liste des recettes
    $listeRecettes = physiqueRecettes($year, $equipe);

    // Recherche pseudo et avatar recettes
    foreach ($listeRecettes as $recette)
    {
      if (isset($listeCookers[$recette->getIdentifiant()]))
      {
        $recette->setPseudo($listeCookers[$recette->getIdentifiant()]['pseudo']);
        $recette->setAvatar($listeCookers[$recette->getIdentifiant()]['avatar']);
      }
    }

    // Retour
    return $listeRecettes;
  }

  // METIER : Conversion de la liste d'objets des recettes en tableau simple pour JSON
  // RETOUR : Tableau des recettes
  function convertForJsonListeRecettes($listeRecettes)
  {
    // Initialisations
    $listeRecettesAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeRecettes as $recette)
    {
      $recetteAConvertir = array('id'          => $recette->getId(),
                                 'identifiant' => $recette->getIdentifiant(),
                                 'pseudo'      => $recette->getPseudo(),
                                 'avatar'      => $recette->getAvatar(),
                                 'team'        => $recette->getTeam(),
                                 'week'        => $recette->getWeek(),
                                 'year'        => $recette->getYear(),
                                 'cooked'      => $recette->getCooked(),
                                 'name'        => $recette->getName(),
                                 'picture'     => $recette->getPicture(),
                                 'ingredients' => $recette->getIngredients(),
                                 'recipe'      => $recette->getRecipe(),
                                 'tips'        => $recette->getTips()
                                );

      $listeRecettesAConvertir[$recette->getId()] = $recetteAConvertir;
    }

    // Retour
    return $listeRecettesAConvertir;
  }

  // METIER : Insère ou met à jour l'utilisateur d'une semaine
  // RETOUR : Aucun
  function updateCake($post, $equipe)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = $post['select_user'];
    $week        = $post['week'];
    $year        = date('Y');

    // Vérification si enregistrement existant
    $semaineExistante = physiqueSemaineExistante($equipe, $week, $year);

    // Contrôle recette semaine déjà validée
    $control_ok = controleSemaineValidee($semaineExistante);

    if ($control_ok == true)
    {
      // Insertion de l'enregistrement en base (si semaine non existante)
      if ($semaineExistante['exist'] == false)
      {
        $cooking = array('identifiant' => $identifiant,
                         'team'        => $equipe,
                         'week'        => $week,
                         'year'        => $year,
                         'cooked'      => 'N',
                         'name'        => '',
                         'picture'     => '',
                         'ingredients' => '',
                         'recipe'      => '',
                         'tips'        => ''
                        );

        physiqueInsertionSemaineGateau($cooking);
      }
      // Modification de l'enregistrement en base
      else
        physiqueUpdateSemaineGateau($week, $year, $identifiant, $equipe);
    }
  }

  // METIER : Valide le gâteau de la semaine d'un utilisateur
  // RETOUR : Aucun
  function validateCake($cooked, $week, $year, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Vérification si enregistrement existant
    $semaineExistante = physiqueSemaineExistante($equipe, $week, $year);

    // Contrôle recette déjà validée par un autre utilisateur
    $control_ok = controleSemaineValideeAutre($semaineExistante['identifiant'], $identifiant);

    // Mise à jour du statut de la recette de la semaine
    if ($control_ok == true)
    {
      // Modification de l'enregistrement en base
      physiqueUpdateStatusSemaineGateau($equipe, $week, $year, $cooked);

      // Génération succès
      if ($cooked == 'Y')
        insertOrUpdateSuccesValue('cooker', $semaineExistante['identifiant'], 1);
      else
        insertOrUpdateSuccesValue('cooker', $semaineExistante['identifiant'], -1);
    }
  }

  // METIER : Insère une recette (mise à jour d'une semaine en base)
  // RETOUR : Id recette
  function insertRecipe($post, $files, $sessionUser)
  {
    // Initialisations
    $idRecette  = NULL;
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];
    $yearRecipe  = $post['year_recipe'];
    $weekRecipe  = formatWeekForInsert($post['week_recipe']);
    $nameRecipe  = $post['name_recipe'];
    $recipe      = $post['preparation'];
    $tips        = $post['remarks'];
    $ingredients = '';
    $nameFile    = $yearRecipe . '-' . $weekRecipe . '-' . rand();

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['year_recipe']           = $post['year_recipe'];
    $_SESSION['save']['week_recipe']           = $post['week_recipe'];
    $_SESSION['save']['name_recipe']           = $post['name_recipe'];
    $_SESSION['save']['ingredients']           = $post['ingredients'];
    $_SESSION['save']['quantites_ingredients'] = $post['quantites_ingredients'];
    $_SESSION['save']['unites_ingredients']    = $post['unites_ingredients'];
    $_SESSION['save']['preparation']           = $post['preparation'];
    $_SESSION['save']['remarks']               = $post['remarks'];

    // Contrôle et formatage de la liste des ingrédients à insérer en base
    foreach ($post['ingredients'] as $key => $ingredient)
    {
      if (!empty($ingredient))
      {
        // Formatage des quantités associées
        $quantiteIngredient         = str_replace(',', '.', $post['quantites_ingredients'][$key]);
        $quantiteIngredientFormated = str_replace('.', ',', $post['quantites_ingredients'][$key]);

        // Contrôle quantité numérique et positif
        $control_ok = controleNumerique($quantiteIngredient, 'quantity_not_numeric');

        // Filtrage des ingrédients
        if ($control_ok == true)
        {
          // Suppression des caractères réservés
          $search     = array('@', ';');
          $replace    = array(' ', ' ');
          $ingredient = str_replace($search, $replace, $ingredient);

          // Formatage de l'ingrédient en fonction de l'unité utilisée
          if ($post['unites_ingredients'][$key] == 'sans')
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@;';
          else
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@' . $post['unites_ingredients'][$key] . ';';
        }

        // Arrêt de la boucle en cas d'erreur
        if ($control_ok == false)
          break;
      }
    }

    // Lecture et contrôle des données existantes
    if ($control_ok == true)
    {
      // Récupération des données de la semaine en cours d'insertion
      $semaineGateau = physiqueSemaineGateau($equipe, $weekRecipe, $yearRecipe);

      // Récupération de l'id pour rediriger vers la recette déjà existante après mise à jour ou en cas de saisie en doublon
      $idRecette = $semaineGateau->getId();

      // Contrôle doublon si on double-clique sur "Ajouter"
      $control_ok = controleInsertionDoublon($semaineGateau);
    }

    // Insertion image
    if ($control_ok == true)
    {
      $imageRecipe = uploadImage($files, $nameFile, $yearRecipe);

      // Contrôle image insérée (obligatoire)
      $control_ok = controleImageInseree($imageRecipe);
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $recette = array('name'        => $nameRecipe,
                       'picture'     => $imageRecipe,
                       'ingredients' => $ingredients,
                       'recipe'      => $recipe,
                       'tips'        => $tips
                      );

      physiqueUpdateRecette($idRecette, $recette);

      // Insertion notification
      insertNotification($identifiant, 'recipe', $idRecette);

      // Génération succès
      insertOrUpdateSuccesValue('recipe-master', $identifiant, 1);

      // Ajout expérience
      insertExperience($identifiant, 'add_recipe');

      // Message d'alerte
      $_SESSION['alerts']['recipe_added'] = true;
    }

    // Retour
    return $idRecette;
  }

  // METIER : Met à jour une recette
  // RETOUR : Id recette
  function updateRecipe($post, $files, $sessionUser)
  {
    // Initialisations
    $idRecette  = NULL;
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];
    $yearRecipe  = $post['hidden_year_recipe'];
    $weekRecipe  = formatWeekForInsert($post['hidden_week_recipe']);
    $nameRecipe  = $post['name_recipe'];
    $recipe      = $post['preparation'];
    $tips        = $post['remarks'];
    $ingredients = '';
    $nameFile    = $yearRecipe . '-' . $weekRecipe . '-' . rand();

    // Contrôle et formatage de la liste des ingrédients à modifier en base
    foreach ($post['ingredients'] as $key => $ingredient)
    {
      if (!empty($ingredient))
      {
        // Formatage des quantités associées
        $quantiteIngredient         = str_replace(',', '.', $post['quantites_ingredients'][$key]);
        $quantiteIngredientFormated = str_replace('.', ',', $post['quantites_ingredients'][$key]);

        // Contrôle quantité numérique et positif
        $control_ok = controleNumerique($quantiteIngredient, 'quantity_not_numeric');

        // Filtrage des ingrédients
        if ($control_ok == true)
        {
          // Suppression des caractères réservés
          $search     = array('@', ';');
          $replace    = array(' ', ' ');
          $ingredient = str_replace($search, $replace, $ingredient);

          // Formatage de l'ingrédient en fonction de l'unité utilisée
          if ($post['unites_ingredients'][$key] == 'sans')
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@;';
          else
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@' . $post['unites_ingredients'][$key] . ';';
        }

        // Arrêt de la boucle en cas d'erreur
        if ($control_ok == false)
          break;
      }
    }

    // Lecture des données existantes et insertion de l'image (seulement si nouvelle image saisie)
    if ($control_ok == true)
    {
      // Récupération des données de la semaine en cours d'insertion
      $semaineGateau = physiqueSemaineGateau($equipe, $weekRecipe, $yearRecipe);

      // Récupération de l'id pour rediriger vers la recette déjà existante après mise à jour ou en cas de saisie en doublon
      $idRecette = $semaineGateau->getId();

      // Si une nouvelle image est saisie
      if (!empty($files['image']['name']))
      {
        // Insertion image
        $imageRecipe = uploadImage($files, $nameFile, $yearRecipe);

        // Contrôle saisie non vide
        $control_ok = controleImageInseree($imageRecipe);

        // Suppression des anciennes images
        if ($control_ok == true)
        {
          if (!empty($semaineGateau->getPicture()))
          {
            unlink('../../includes/images/cookingbox/' . $yearRecipe . '/' . $semaineGateau->getPicture());
            unlink('../../includes/images/cookingbox/' . $yearRecipe . '/' . $semaineGateau->getPicture());
          }
        }
      }
      else
        $imageRecipe = $semaineGateau->getPicture();
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $recette = array('name'        => $nameRecipe,
                       'picture'     => $imageRecipe,
                       'ingredients' => $ingredients,
                       'recipe'      => $recipe,
                       'tips'        => $tips
                      );

      physiqueUpdateRecette($idRecette, $recette);

      // Message d'alerte
      $_SESSION['alerts']['recipe_updated'] = true;
    }

    // Retour
    return $idRecette;
  }

  // METIER : Suppression recette
  // RETOUR : Aucun
  function deleteRecipe($post, $year, $sessionUser)
  {
    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];
    $week        = $post['week_cake'];

    // Lecture des données de la recette
    $recette = physiqueRecette($equipe, $week, $year);

    // Suppression des images
    if (!empty($recette->getPicture()))
    {
      unlink('../../includes/images/cookingbox/' . $year . '/' . $recette->getPicture());
      unlink('../../includes/images/cookingbox/' . $year . '/mini/' . $recette->getPicture());
    }

    // Modification de l'enregistrement en base
    $reinitialisationRecette = array('name'        => '',
                                     'picture'     => '',
                                     'ingredients' => '',
                                     'recipe'      => '',
                                     'tips'        => ''
                                    );

    physiqueResetRecette($week, $year, $reinitialisationRecette);

    // Suppression des notifications
    deleteNotification('recipe', $recette->getId());

    // Génération succès
    insertOrUpdateSuccesValue('recipe-master', $identifiant, -1);
  }

  // METIER : Formatage et insertion image Cooking Box
  // RETOUR : Nom fichier avec extension
  function uploadImage($files, $name, $year)
  {
    // Initialisations
    $newName    = '';
    $control_ok = true;

    // Dossier de destination des miniatures
    $dossier           = '../../includes/images/cookingbox/' . $year;
    $dossierMiniatures = $dossier . '/mini';

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    if (!is_dir($dossierMiniatures))
      mkdir($dossierMiniatures, 0777, true);

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['image'], $name, 'all');

    // Récupération contrôles
    $control_ok = controleFichier($fileDatas);

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($fileDatas, $dossier);

    // Traitement des images
    if ($control_ok == true)
    {
      $newName   = $fileDatas['new_name'];
      $typeImage = $fileDatas['type_file'];

      // Rotation de l'image (si JPEG)
      if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
        rotateImage($dossier . '/' . $newName, $typeImage);

      // Redimensionne l'image avec une hauteur/largeur max de 2000px
      imageThumb($dossier . '/' . $newName, $dossier . '/' . $newName, 2000, false, false);

      // Création miniature avec une hauteur/largeur max de 500px
      imageThumb($dossier . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, false, false);
    }

    // Retour
    return $newName;
  }
?>
