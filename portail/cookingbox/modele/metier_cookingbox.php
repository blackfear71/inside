<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

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
  // RETOUR : Données semaine
  function getWeek($week, $year)
  {
    $gateauSemaine = new WeekCake();

    global $bdd;

    // Données semaine
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $gateauSemaine = WeekCake::withData($data1);

      // Données utilisateur
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $gateauSemaine->getIdentifiant() . '"');
      $data2 = $req2->fetch();
      $gateauSemaine->setPseudo($data2['pseudo']);
      $gateauSemaine->setAvatar($data2['avatar']);
      $req2->closeCursor();
    }

    $req1->closeCursor();

    return $gateauSemaine;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant != "admin" AND status != "I" AND status != "D" ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $listUsers[$user->getIdentifiant()] = $user->getPseudo();
    }
    $reponse->closeCursor();

    return $listUsers;
  }

  // METIER : Récupères les semaines par années pour la saisie de recette
  // RETOUR : Liste des semaines par années
  function getWeeks($user)
  {
    $listYears    = array();
    $previousYear = '';

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM cooking_box WHERE identifiant = "' . $user . '"
                                                        AND name        = ""
                                                        AND picture     = ""
                                                        AND (year < ' . date('Y') . ' OR (year = ' . date('Y') . ' AND week <= ' . date('W') . '))
                                                      ORDER BY year DESC, week DESC');
    while ($donnees = $reponse->fetch())
    {
      if ($donnees['year'] != $previousYear)
      {
        if (!empty($previousYear))
          $listYears[$previousYear] = $listWeeks;

        $listWeeks    = array();
        $previousYear = $donnees['year'];
      }

      array_push($listWeeks, formatWeekForDisplay($donnees['week']));
    }
    $reponse->closeCursor();

    // Dernière occurence
    end($listYears);
    $lastKey = key($listYears);

    if ($lastKey != $previousYear)
      $listYears[$previousYear] = $listWeeks;

    return $listYears;
  }

  // METIER : Insère ou met à jour l'utilisateur
  // RETOUR : Aucun
  function updateCake($post)
  {
    $week        = $post['week'];
    $year        = date('Y');
    $identifiant = $post['select_user'];
    $exist       = false;

    global $bdd;

    // Contrôle si enregistrement existant
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $already_cooked = $data1['cooked'];
      $exist          = true;
    }

    $req1->closeCursor();

    // Si non existant : insertion
    if ($exist == false)
    {
      $cooking = array('identifiant' => $identifiant,
                       'week'        => $week,
                       'year'        => $year,
                       'cooked'      => "N",
                       'name'        => "",
                       'picture'     => "",
                       'ingredients' => "",
                       'recipe'      => "",
                       'tips'        => ""
                      );

      $req2 = $bdd->prepare('INSERT INTO cooking_box(identifiant,
                                                     week,
                                                     year,
                                                     cooked,
                                                     name,
                                                     picture,
                                                     ingredients,
                                                     recipe,
                                                     tips
                                                    )
                                             VALUES(:identifiant,
                                                    :week,
                                                    :year,
                                                    :cooked,
                                                    :name,
                                                    :picture,
                                                    :ingredients,
                                                    :recipe,
                                                    :tips
                                                   )');
      $req2->execute($cooking);
      $req2->closeCursor();
    }
    // Sinon : mise à jour
    else
    {
      if ($already_cooked != 'Y')
      {
        $req2 = $bdd->prepare('UPDATE cooking_box SET identifiant = :identifiant WHERE week = "' . $week . '" AND year = "' . $year . '"');
        $req2->execute(array(
          'identifiant' => $identifiant
        ));
        $req2->closeCursor();
      }
      else
        $_SESSION['alerts']['already_cooked'] = true;
    }
  }

  // METIER : Valide le gâteau de la semaine
  // RETOUR : Aucun
  function validateCake($cooked, $week, $year, $user)
  {
    global $bdd;

    $otherCooker = false;

    // Lecture des anciennes données
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $data1 = $req1->fetch();

    if ($data1['identifiant'] != $user)
      $otherCooker = true;

    $req1->closeCursor();

    if ($otherCooker == false)
    {
      // Mise à jour du statut
      $req2 = $bdd->prepare('UPDATE cooking_box SET cooked = :cooked WHERE week = "' . $week . '" AND year = "' . $year . '"');
      $req2->execute(array(
        'cooked' => $cooked
      ));
      $req2->closeCursor();

      // Lecture des nouvelles données
      $req3 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
      $data3 = $req3->fetch();
      $identifiant = $data3['identifiant'];
      $req3->closeCursor();

      if ($cooked == 'Y')
        insertOrUpdateSuccesValue('cooker', $identifiant, 1);
      else
        insertOrUpdateSuccesValue('cooker', $identifiant, -1);
    }
    else
      $_SESSION['alerts']['other_cooker'] = true;
  }

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $anneeExistante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $year . '" AND name != "" AND picture != "" ORDER BY year DESC');

      if ($reponse->rowCount() > 0)
        $anneeExistante = true;

      $reponse->closeCursor();
    }

    return $anneeExistante;
  }

  // METIER : Lecture des années distinctes
  // RETOUR : Liste des années
  function getOnglets()
  {
    $listOnglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT year FROM cooking_box WHERE name != "" AND picture != "" ORDER BY year DESC');
    while ($donnees = $reponse->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($listOnglets, $donnees['year']);
    }
    $reponse->closeCursor();

    return $listOnglets;
  }

  // METIER : Lecture des recettes
  // RETOUR : Liste des recettes
  function getRecipes($year)
  {
    $listeRecettes = array();

    global $bdd;

    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $year . '" AND picture != "" ORDER BY week DESC');
    while ($data1 = $req1->fetch())
    {
      $recette = WeekCake::withData($data1);

      // Données utilisateur
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $recette->getIdentifiant() . '"');
      $data2 = $req2->fetch();

      $recette->setPseudo($data2['pseudo']);
      $recette->setAvatar($data2['avatar']);

      $req2->closeCursor();

      // On ajoute la ligne au tableau
      array_push($listeRecettes, $recette);
    }
    $req1->closeCursor();

    // Retour
    return $listeRecettes;
  }

  // METIER : Converstion du tableau d'objet des recettes en tableau simple pour JSON
  // RETOUR : Tableau des recettes
  function convertForJson($recipes)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listeRecettesAConvertir = array();

    foreach ($recipes as $recipe)
    {
      $recetteAConvertir = array('id'          => $recipe->getId(),
                                 'identifiant' => $recipe->getIdentifiant(),
                                 'pseudo'      => $recipe->getPseudo(),
                                 'avatar'      => $recipe->getAvatar(),
                                 'week'        => $recipe->getWeek(),
                                 'year'        => $recipe->getYear(),
                                 'cooked'      => $recipe->getCooked(),
                                 'name'        => $recipe->getName(),
                                 'picture'     => $recipe->getPicture(),
                                 'ingredients' => $recipe->getIngredients(),
                                 'recipe'      => $recipe->getRecipe(),
                                 'tips'        => $recipe->getTips()
                                );

      $listeRecettesAConvertir[$recipe->getId()] = $recetteAConvertir;
    }

    // Retour
    return $listeRecettesAConvertir;
  }

  // METIER : Insère une recette (mise à jour)
  // RETOUR : Id recette
  function insertRecipe($post, $files, $user)
  {
    $newId      = NULL;
    $control_ok = true;

    global $bdd;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['year_recipe']           = $post['year_recipe'];
    $_SESSION['save']['week_recipe']           = $post['week_recipe'];
    $_SESSION['save']['name_recipe']           = $post['name_recipe'];
    $_SESSION['save']['ingredients']           = $post['ingredients'];
    $_SESSION['save']['quantites_ingredients'] = $post['quantites_ingredients'];
    $_SESSION['save']['unites_ingredients']    = $post['unites_ingredients'];
    $_SESSION['save']['preparation']           = $post['preparation'];
    $_SESSION['save']['remarks']               = $post['remarks'];

    // Récupération des données
    $yearRecipe = $post['year_recipe'];
    $weekRecipe = formatWeekForInsert($post['week_recipe']);
    $nameRecipe = $post['name_recipe'];
    $recipe      = $post['preparation'];
    $tips        = $post['remarks'];
    $ingredients = '';

    foreach ($post['ingredients'] as $key => $ingredient)
    {
      if (!empty($ingredient))
      {
        $quantiteIngredient          = str_replace(',', '.', $post['quantites_ingredients'][$key]);
        $quantiteIngredientFormated = str_replace('.', ',', $post['quantites_ingredients'][$key]);

        if  (!empty($quantiteIngredient)
        AND (!is_numeric($quantiteIngredient) OR $quantiteIngredient <= 0))
        {
          $_SESSION['alerts']['quantity_not_numeric'] = true;
          $control_ok                                 = false;
          break;
        }
        else
        {
          // Filtrage
          $ingredient = str_replace('@', ' ', $ingredient);

          if ($post['unites_ingredients'][$key] == 'sans')
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@;';
          else
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@' . $post['unites_ingredients'][$key] . ';';
        }
      }
    }

    // Contrôle doublon si on double-clique sur Ajouter (on ne met pas à jour mais on récupère l'id pour rediriger vers la recette déjà existante)
    if ($control_ok == true)
    {
      $req1 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $yearRecipe . '" AND week = "' . $weekRecipe . '"');
      $data1 = $req1->fetch();
      $datasRecipe = WeekCake::withData($data1);
      $req1->closeCursor();

      if (!empty($datasRecipe->getName()) OR !empty($datasRecipe->getPicture()) OR !empty($datasRecipe->getIngredients()) OR !empty($datasRecipe->getRecipe()) OR !empty($datasRecipe->getTips()))
      {
        $control_ok = false;
        $newId      = $data1['id'];
      }
    }

    if ($control_ok == true)
    {
      // Enregistrement image
      $newName = '';

      // Dossiers de destination
      $dossierAnnee      = '../../includes/images/cookingbox/' . $yearRecipe;
      $dossierMiniatures = $dossierAnnee . '/mini';

      // On vérifie la présence du dossier des miniatures, sinon on le créé
      if (!is_dir($dossierMiniatures))
        mkdir($dossierMiniatures, 0777, true);

      // Nom du fichier
      $name = $yearRecipe . '-' . $weekRecipe . '-' . rand();

      // Contrôles fichier
      $fileDatas = controlsUploadFile($files['image'], $name, 'all');

      // Traitements fichier
      if ($fileDatas['control_ok'] == true)
      {
        // Upload fichier
        $control_ok = uploadFile($fileDatas, $dossierAnnee);

        if ($control_ok == true)
        {
          $newName   = $fileDatas['new_name'];
          $typeImage = $fileDatas['type_file'];

          // Rotation de l'image (si JPEG)
          if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
            rotateImage($dossierAnnee . '/' . $newName, $typeImage);

          // Redimensionne l'image avec une hauteur/largeur max de 2000px (cf fonction imagethumb.php)
          imagethumb($dossierAnnee . '/' . $newName, $dossierAnnee . '/' . $newName, 2000, FALSE, FALSE);

          // Créé une miniature de la source vers la destination en la redimensionnant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($dossierAnnee . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, FALSE, FALSE);

          // Mise à jour de l'enregistrement concerné
          $recette = array('name'        => $nameRecipe,
                           'picture'     => $newName,
                           'ingredients' => $ingredients,
                           'recipe'      => $recipe,
                           'tips'        => $tips
                          );

          $req2 = $bdd->prepare('UPDATE cooking_box SET name        = :name,
                                                        picture     = :picture,
                                                        ingredients = :ingredients,
                                                        recipe      = :recipe,
                                                        tips        = :tips
                                                  WHERE year        = "' . $yearRecipe . '"
                                                    AND week        = "' . $weekRecipe . '"
                                                    AND identifiant = "' . $user . '"');
          $req2->execute($recette);
          $req2->closeCursor();

          // Lecture Id recette
          $req3 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $yearRecipe . '" AND week = "' . $weekRecipe . '"');
          $data3 = $req3->fetch();
          $newId = $data3['id'];
          $req3->closeCursor();

          // Génération notification nouvelle recette
          insertNotification($user, 'recipe', $newId);

          // Génération succès
          insertOrUpdateSuccesValue('recipe-master', $user, 1);

          // Ajout expérience
          insertExperience($user, 'add_recipe');

          $_SESSION['alerts']['recipe_added'] = true;
        }
      }
    }

    return $newId;
  }

  // METIER : Met à jour une recette
  // RETOUR : Id recette
  function updateRecipe($post, $files, $user)
  {
    $idRecipe   = NULL;
    $control_ok = true;

    global $bdd;

    // Récupération des données
    $yearRecipe  = $post['hidden_year_recipe'];
    $weekRecipe  = formatWeekForInsert($post['hidden_week_recipe']);
    $nameRecipe  = $post['name_recipe'];
    $recipe      = $post['preparation'];
    $tips        = $post['remarks'];
    $ingredients = '';

    foreach ($post['ingredients'] as $key => $ingredient)
    {
      if (!empty($ingredient))
      {
        $quantiteIngredient         = str_replace(',', '.', $post['quantites_ingredients'][$key]);
        $quantiteIngredientFormated = str_replace('.', ',', $post['quantites_ingredients'][$key]);

        if  (!empty($quantiteIngredient)
        AND (!is_numeric($quantiteIngredient) OR $quantiteIngredient <= 0))
        {
          $_SESSION['alerts']['quantity_not_numeric'] = true;
          $control_ok                                 = false;
          break;
        }
        else
        {
          // Filtrage
          $ingredient = str_replace('@', ' ', $ingredient);

          if ($post['unites_ingredients'][$key] == 'sans')
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@;';
          else
            $ingredients .= $ingredient . '@' . $quantiteIngredientFormated . '@' . $post['unites_ingredients'][$key] . ';';
        }
      }
    }

    if ($control_ok == true)
    {
      // Récupération des données
      $req1 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $yearRecipe . '" AND week = "' . $weekRecipe . '"');
      $data1 = $req1->fetch();
      $datasRecipe = WeekCake::withData($data1);
      $req1->closeCursor();

      $idRecipe = $datasRecipe->getId();
      $newName  = $datasRecipe->getPicture();

      // Dossiers de destination
      $dossierAnnee      = '../../includes/images/cookingbox/' . $yearRecipe;
      $dossierMiniatures = $dossierAnnee . '/mini';

      // Nom du fichier
      $name = $yearRecipe . '-' . $weekRecipe . '-' . rand();

      // Contrôles fichier
      $fileDatas = controlsUploadFile($files['image'], $name, 'all');

      // Traitements fichier
      if ($fileDatas['control_ok'] == true)
      {
        // Upload fichier
        $control_ok = uploadFile($fileDatas, $dossierAnnee);

        if ($control_ok == true)
        {
          $newName   = $fileDatas['new_name'];
          $typeImage = $fileDatas['type_file'];

          // Rotation de l'image (si JPEG)
          if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
            rotateImage($dossierAnnee . '/' . $newName, $typeImage);

          // Redimensionne l'image avec une hauteur/largeur max de 2000px (cf fonction imagethumb.php)
          imagethumb($dossierAnnee . '/' . $newName, $dossierAnnee . '/' . $newName, 2000, FALSE, FALSE);

          // Créé une miniature de la source vers la destination en la redimensionnant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($dossierAnnee . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, FALSE, FALSE);

          // Suppression des anciennes images
          if (!empty($datasRecipe->getPicture()))
          {
            unlink($dossierAnnee . '/' . $datasRecipe->getPicture());
            unlink($dossierMiniatures . '/' . $datasRecipe->getPicture());
          }
        }
      }

      if ($control_ok == true)
      {
        // Mise à jour de l'enregistrement concerné
        $recette = array('name'        => $nameRecipe,
                         'picture'     => $newName,
                         'ingredients' => $ingredients,
                         'recipe'      => $recipe,
                         'tips'        => $tips
                        );

        $req2 = $bdd->prepare('UPDATE cooking_box SET name        = :name,
                                                      picture     = :picture,
                                                      ingredients = :ingredients,
                                                      recipe      = :recipe,
                                                      tips        = :tips
                                                WHERE year        = "' . $yearRecipe . '"
                                                  AND week        = "' . $weekRecipe . '"
                                                  AND identifiant = "' . $user . '"');
        $req2->execute($recette);
        $req2->closeCursor();

        $_SESSION['alerts']['recipe_updated'] = true;
      }
    }

    // Retour
    return $idRecipe;
  }

  // METIER : Supprime une recette
  // RETOUR : Aucun
  function deleteRecipe($post, $year, $user)
  {
    $week = $post['week_cake'];

    global $bdd;

    // Lecture des données recette
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $year . '" AND week = "' . $week . '"');
    $data1 = $req1->fetch();
    $recipe = WeekCake::withData($data1);
    $req1->closeCursor();

    // Suppression des images
    if (!empty($recipe->getPicture()))
    {
      unlink('../../includes/images/cookingbox/' . $year . '/' . $recipe->getPicture());
      unlink('../../includes/images/cookingbox/' . $year . '/mini/' . $recipe->getPicture());
    }

    // Mise à jour des données
    $update = array('name'        => '',
                    'picture'     => '',
                    'ingredients' => '',
                    'recipe'      => '',
                    'tips'        => ''
                   );
    $req2 = $bdd->prepare('UPDATE cooking_box SET name        = :name,
                                                  picture     = :picture,
                                                  ingredients = :ingredients,
                                                  recipe      = :recipe,
                                                  tips        = :tips
                                            WHERE year = "' . $year . '"
                                            AND   week = "' . $week . '"');
    $req2->execute($update);
    $req2->closeCursor();

    // Suppression des notifications
    deleteNotification('recipe', $recipe->getId());

    // Génération succès
    insertOrUpdateSuccesValue('recipe-master', $user, -1);
  }
?>
