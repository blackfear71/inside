<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Récupère les données d'une semaine (N ou N+1)
  // RETOUR : Données semaine
  function getWeek($week)
  {
    $myWeek = new WeekCake();

    global $bdd;

    // Données semaine
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $myWeek = WeekCake::withData($data1);

      // Données utilisateur
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myWeek->getIdentifiant() . '"');
      $data2 = $req2->fetch();
      $myWeek->setPseudo($data2['pseudo']);
      $myWeek->setAvatar($data2['avatar']);
      $req2->closeCursor();
    }

    $req1->closeCursor();

    return $myWeek;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant != "admin" AND status != "I" AND status != "D" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
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
    $previousYear = "";

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM cooking_box WHERE identifiant = "' . $user . '" AND name = "" AND picture = "" ORDER BY year DESC, week DESC');
    while($donnees = $reponse->fetch())
    {
      if ($donnees['year'] != $previousYear)
      {
        if (!empty($previousYear))
          $listYears[$previousYear] = $listWeeks;

        $listWeeks    = array();
        $previousYear = $donnees['year'];
      }

      array_push($listWeeks, $donnees['week']);
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
      $exist = true;

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
      $req2 = $bdd->prepare('UPDATE cooking_box SET identifiant = :identifiant WHERE week = "' . $week . '" AND year = "' . $year . '"');
      $req2->execute(array(
        'identifiant' => $identifiant
      ));
      $req2->closeCursor();
    }
  }

  // METIER : Valide le gâteau de la semaine
  // RETOUR : Aucun
  function validateCake($cooked, $week, $year)
  {
    global $bdd;

    // Mise à jour du statut
    $req1 = $bdd->prepare('UPDATE cooking_box SET cooked = :cooked WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $req1->execute(array(
      'cooked' => $cooked
    ));
    $req1->closeCursor();

    // Lecture des données
    $req2 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $data2 = $req2->fetch();
    $identifiant = $data2['identifiant'];
    $req2->closeCursor();

    if ($cooked == "Y")
      insertOrUpdateSuccesValue('cooker', $identifiant, 1);
    else
      insertOrUpdateSuccesValue('cooker', $identifiant, -1);
  }

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT DISTINCT year FROM cooking_box ORDER BY year ASC');
      while($donnees = $reponse->fetch())
      {
        if ($year == $donnees['year'])
          $annee_existante = true;
      }
      $reponse->closeCursor();
    }

    return $annee_existante;
  }

  // METIER : Lecture des années distinctes
  // RETOUR : Liste des années
  function getOnglets()
  {
    $listOnglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT year FROM cooking_box WHERE name != "" AND picture != "" ORDER BY year DESC');
    while($donnees = $reponse->fetch())
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
    $listRecipes = array();

    global $bdd;

    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $year . '" AND picture != "" ORDER BY week DESC');
    while($data1 = $req1->fetch())
    {
      $myRecipe = WeekCake::withData($data1);

      // Données utilisateur
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myRecipe->getIdentifiant() . '"');
      $data2 = $req2->fetch();

      $myRecipe->setPseudo($data2['pseudo']);
      $myRecipe->setAvatar($data2['avatar']);

      $req2->closeCursor();

      // On ajoute la ligne au tableau
      array_push($listRecipes, $myRecipe);
    }
    $req1->closeCursor();

    return $listRecipes;
  }

  // METIER : Converstion du tableau d'objet des recettes en tableau simple pour JSON
  // RETOUR : Tableau des recettes
  function convertForJson($recipes)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listRecipesToConvert = array();

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

      $listRecipesToConvert[$recipe->getId()] = $recetteAConvertir;
    }

    return $listRecipesToConvert;
  }

  // METIER : Met à jour une recette
  // RETOUR : Id recette
  function insertRecipe($post, $files, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

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
    $year_recipe = $post['year_recipe'];
    $week_recipe = $post['week_recipe'];
    $name_recipe = $post['name_recipe'];
    $recipe      = $post['preparation'];
    $tips        = $post['remarks'];
    $ingredients = "";

    foreach ($post['ingredients'] as $key => $ingredient)
    {
      if (!empty($ingredient))
      {
        if (!empty($post['quantites_ingredients'][$key]) AND !is_numeric($post['quantites_ingredients'][$key]))
        {
          $_SESSION['alerts']['quantity_not_numeric'] = true;
          $control_ok                                 = false;
          break;
        }
        else
        {
          $ingredient = str_replace(".", ",", $ingredient);

          if (empty($post['unites_ingredients'][$key]) OR $post['unites_ingredients'][$key] == "sans")
            $ingredients .= $ingredient . "@" . $post['quantites_ingredients'][$key] . ";";
          else
            $ingredients .= $ingredient . "@" . $post['quantites_ingredients'][$key] . $post['unites_ingredients'][$key] . ";";
        }
      }
    }

    if ($control_ok == true)
    {
      // Enregistrement image
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/cookingbox";

      if (!is_dir($dossier))
        mkdir($dossier);

      // On contrôle la présence du dossier d'avatars, sinon on le créé
      $dossier_annee = $dossier . "/" . $year_recipe;

      if (!is_dir($dossier_annee))
        mkdir($dossier_annee);

      // On contrôle la présence du dossier des miniatures, sinon on le créé
      $dossier_miniatures = $dossier_annee . "/mini";

      if (!is_dir($dossier_miniatures))
        mkdir($dossier_miniatures);

      // Si on a bien une image
      if ($files['image']['name'] != NULL)
      {
        // Dossier de destination
        $image_dir = $dossier_annee . '/';
        $mini_dir  = $dossier_miniatures . '/';

        // Données du fichier
        $file      = $files['image']['name'];
        $tmp_file  = $files['image']['tmp_name'];
        $size_file = $files['image']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['image']['type'];

          if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            exit("Le fichier n'est pas une image valide");
          else
          {
            $type_image = pathinfo($file, PATHINFO_EXTENSION);
            $new_name   = $year_recipe . '-' . $week_recipe . '-' . rand() . '.' . $type_image;
          }

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $image_dir . $new_name))
            exit("Impossible de copier le fichier dans $image_dir");

          // Rotation de l'image (si JPEG)
          if ($type_image == 'jpg' OR $type_image == 'jpeg')
            $rotate = rotateImage($image_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($image_dir . $new_name, $mini_dir . $new_name, 500, FALSE, FALSE);
        }
      }

      // Mise à jour de l'enregistrement concerné
      global $bdd;

      $myRecipe = array('name'        => $name_recipe,
                        'picture'     => $new_name,
                        'ingredients' => $ingredients,
                        'recipe'      => $recipe,
                        'tips'        => $tips
                       );

      $req1 = $bdd->prepare('UPDATE cooking_box SET name        = :name,
                                                   picture     = :picture,
                                                   ingredients = :ingredients,
                                                   recipe      = :recipe,
                                                   tips        = :tips
                                             WHERE year        = "' . $year_recipe . '"
                                               AND week        = "' . $week_recipe . '"
                                               AND identifiant = "' . $user . '"');
      $req1->execute($myRecipe);
      $req1->closeCursor();

      // Lecture Id recette
      $req2 = $bdd->query('SELECT * FROM cooking_box WHERE year = "' . $year_recipe . '" AND week = "' . $week_recipe . '"');
      $data2 = $req2->fetch();
      $new_id = $data2['id'];
      $req2->closeCursor();

      // Génération notification nouvelle recette
      insertNotification($user, 'recipe', $new_id);

      $_SESSION['alerts']['recipe_added'] = true;
    }

    return $new_id;
  }

  // METIER : Supprime une recette
  // RETOUR : Aucun
  function deleteRecipe($post, $year)
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
      unlink ("../../includes/images/cookingbox/" . $year . "/" . $recipe->getPicture());
      unlink ("../../includes/images/cookingbox/" . $year . "/mini/" . $recipe->getPicture());
    }

    // Mise à jour des données
    $update = array('name'        => "",
                    'picture'     => "",
                    'ingredients' => "",
                    'recipe'      => "",
                    'tips'        => ""
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
  }
?>
