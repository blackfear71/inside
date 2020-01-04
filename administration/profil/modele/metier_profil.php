<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($user)
  {
    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $profile = Profile::withData($donnees);

    $reponse->closeCursor();

    return $profile;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function updateAvatar($user, $files)
  {
    global $bdd;

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "../../includes/images/profil";

    if (!is_dir($dossier))
      mkdir($dossier);

    // On contrôle la présence du dossier d'avatars, sinon on le créé
    $dossier_avatars = $dossier . "/avatars";

    if (!is_dir($dossier_avatars))
      mkdir($dossier_avatars);

    $avatar = rand();

    // Si on a bien une image
    if ($files['avatar']['name'] != NULL)
    {
      // Dossier de destination
      $avatar_dir = $dossier_avatars . '/';

      // Données du fichier
      $file      = $files['avatar']['name'];
      $tmp_file  = $files['avatar']['tmp_name'];
      $size_file = $files['avatar']['size'];
      $maxsize   = 15728640; // 15 Mo

      // Si le fichier n'est pas trop grand
      if ($size_file < $maxsize)
      {
        // Contrôle fichier temporaire existant
        if (!is_uploaded_file($tmp_file))
          exit("Le fichier est introuvable");

        // Contrôle type de fichier
        $type_file = $files['avatar']['type'];

        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
          exit("Le fichier n'est pas une image valide");
        else
        {
          $type_image = pathinfo($file, PATHINFO_EXTENSION);
          $new_name   = $avatar . '.' . $type_image;
        }

        // Contrôle upload (si tout est bon, l'image est envoyée)
        if (!move_uploaded_file($tmp_file, $avatar_dir . $new_name))
          exit("Impossible de copier le fichier dans $avatar_dir");

        // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
        imagethumb($avatar_dir . $new_name, $avatar_dir . $new_name, 400, FALSE, TRUE);

        // echo "Le fichier a bien été uploadé";

        // On efface l'ancien avatar si présent
        $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
        $donnees1 = $reponse1->fetch();

        if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
          unlink ($avatar_dir . $donnees1['avatar'] . "");

        $reponse1->closeCursor();

        // On stocke la référence du nouvel avatar dans la base
        $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
        $reponse2->execute(array(
          'avatar' => $new_name
        ));
        $reponse2->closeCursor();

        $_SESSION['user']['avatar']           = $new_name;
        $_SESSION['alerts']['avatar_updated'] = true;
      }
    }
  }

  // METIER : Suppression de l'avatar (base + fichier)
  // RETOUR : Aucun
  function deleteAvatar($user)
  {
    global $bdd;

    // On efface l'ancien avatar si présent
    $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
      unlink ("../../includes/images/profil/avatars/" . $donnees1['avatar'] . "");

    $reponse1->closeCursor();

    // On efface la référence de l'ancien avatar dans la base
    $new_name = "";

    $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'avatar' => $new_name
    ));
    $reponse2->closeCursor();

    $_SESSION['user']['avatar']           = '';
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des informations
  // RETOUR : Aucun
  function updateInfos($user, $post)
  {
    global $bdd;

    // Récupération des données
    $pseudo = trim($post['pseudo']);

    // Mise à jour pseudo seulement si renseigné
    if (!empty($pseudo))
    {
      $req1 = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE identifiant = "' . $user . '"');
      $req1->execute(array(
        'pseudo' => $pseudo
      ));
      $req1->closeCursor();

      // Mise à jour du pseudo stocké en SESSION
      $_SESSION['user']['pseudo'] = $pseudo;

      $_SESSION['alerts']['infos_updated'] = true;
    }
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function updatePassword($user, $post)
  {
    if (!empty($post['old_password'])
    AND !empty($post['new_password'])
    AND !empty($post['confirm_new_password']))
    {
      global $bdd;

      // Lecture des données actuelles de l'utilisateur
      $reponse = $bdd->query('SELECT id, identifiant, salt, password FROM users WHERE identifiant = "' . $user . '"');
      $donnees = $reponse->fetch();

      $old_password = htmlspecialchars(hash('sha1', $post['old_password'] . $donnees['salt']));

      if ($old_password == $donnees['password'])
      {
        $salt                 = rand();
        $new_password         = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
        $confirm_new_password = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

        if ($new_password == $confirm_new_password)
        {
          $req = $bdd->prepare('UPDATE users SET salt = :salt, password = :password WHERE identifiant = "' . $user . '"');
          $req->execute(array(
            'salt'     => $salt,
            'password' => $new_password
          ));
          $req->closeCursor();

          $_SESSION['alerts']['password_updated'] = true;
        }
        else
          $_SESSION['alerts']['wrong_password'] = true;
      }
      else
        $_SESSION['alerts']['wrong_password'] = true;

      $reponse->closeCursor();
    }
  }
?>
