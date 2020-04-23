<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($identifiant)
  {
    // Récupération des données du profil
    $profil = physiqueProfil($identifiant);

    // Retour
    return $profil;
  }

  // METIER : Mise à jour de l'avatar
  // RETOUR : Aucun
  function updateAvatar($identifiant, $files)
  {
    // Initialisations
    $control_ok = true;
    $avatar     = rand();

    // On vérifie la présence du dossier, sinon on le créé
    $dossier = '../../includes/images/profil';

    if (!is_dir($dossier))
      mkdir($dossier);

    // On vérifie la présence du dossier d'avatars, sinon on le créé
    $dossierAvatars = $dossier . '/avatars';

    if (!is_dir($dossierAvatars))
      mkdir($dossierAvatars);

    // Dossier de destination
    $avatarDir = $dossierAvatars . '/';

    // Contrôle du fichier
    $fileDatas = controlsUploadFile($files['avatar'], $avatar, 'all');

    // Récupération contrôles
    $control_ok = $fileDatas['control_ok'];

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($files['avatar'], $fileDatas, $avatarDir);

    if ($control_ok == true)
    {
      $newName = $fileDatas['new_name'];

      // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
      imagethumb($avatarDir . $newName, $avatarDir . $newName, 400, FALSE, TRUE);

      // Suppression de l'ancien avatar si présent
      $oldAvatar = physiqueAvatarUser($identifiant);

      if (!empty($oldAvatar))
        unlink($avatarDir . $oldAvatar . '');

      // Modification de l'enregistrement en base
      physiqueUpdateAvatarUser($identifiant, $newName);

      // Mise à jour de la session
      $_SESSION['user']['avatar'] = $newName;

      // Message d'alerte
      $_SESSION['alerts']['avatar_updated'] = true;
    }
  }

  // METIER : Suppression de l'avatar
  // RETOUR : Aucun
  function deleteAvatar($identifiant)
  {
    // Dossier de destination
    $avatarDir = '../../includes/images/profil/avatars/';

    // Suppression de l'ancien avatar si présent
    $oldAvatar = physiqueAvatarUser($identifiant);

    if (!empty($oldAvatar))
      unlink($avatarDir . $oldAvatar . '');

    // Modification de l'enregistrement en base
    physiqueUpdateAvatarUser($identifiant, '');

    // Mise à jour de la session
    $_SESSION['user']['avatar'] = '';

    // Message d'alerte
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des informations
  // RETOUR : Aucun
  function updateInfos($identifiant, $post)
  {
    // Récupération des données
    $pseudo = trim($post['pseudo']);

    // Mise à jour pseudo si renseigné
    if (!empty($pseudo))
    {
      physiqueUpdatePseudoUser($identifiant, $pseudo);

      // Mise à jour de la session
      $_SESSION['user']['pseudo'] = $pseudo;

      // Message d'alerte
      $_SESSION['alerts']['infos_updated'] = true;
    }
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function updatePassword($identifiant, $post)
  {
    // Initialisations
    $control_ok = true;

    // Si on a saisi toutes les données
    if (!empty($post['old_password'])
    AND !empty($post['new_password'])
    AND !empty($post['confirm_new_password']))
    {
      // Récupération des données du mot de passe
      $crypt = physiqueDonneesPasswordUser($identifiant);

      // Cryptage ancien mot de passe saisi
      $oldPassword = htmlspecialchars(hash('sha1', $post['old_password'] . $crypt['salt']));

      // Contrôle correspondance ancien mot de passe
      $control_ok = controleCorrespondancePassword($oldPassword, $crypt['password']);

      // Contrôle correspondance nouveau mot de passe
      if ($control_ok == true)
      {
        $salt               = rand();
        $newPassword        = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
        $confirmNewPassword = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

        $control_ok = controleCorrespondancePassword($confirmNewPassword, $newPassword);
      }

      // Modification de l'enregistrement en base
      if ($control_ok == true)
      {
        physiqueUpdatePasswordUser($salt, $newPassword, $identifiant);

        // Message d'alerte
        $_SESSION['alerts']['password_updated'] = true;
      }
    }
  }
?>
