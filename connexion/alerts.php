<?php
  if (isset($_SESSION['pseudo_changed'])
  OR  isset($_SESSION['avatar_changed'])
  OR  isset($_SESSION['avatar_deleted'])
  OR  isset($_SESSION['wrong_password'])
  OR  isset($_SESSION['preferences_updated'])
  OR  isset($_SESSION['ask_desinscription']))
  {
    echo '<div class="message_alerte_profil">';
      // Changement pseudo
      if (isset($_SESSION['pseudo_changed']) AND $_SESSION['pseudo_changed'] == true)
      {
        echo 'Le pseudo a bien été modifié.';
        $_SESSION['pseudo_changed'] = NULL;
      }

      // Changement avatar
      if (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == true)
      {
        echo 'L\'avatar a bien été modifié.';
        $_SESSION['avatar_changed'] = NULL;
      }
      elseif (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == false)
      {
        echo 'Un problème a eu lieu lors de la modification de l\'avatar.';
        $_SESSION['avatar_changed'] = NULL;
      }
      else
      {
        $_SESSION['avatar_changed'] = NULL;
      }

      // Suppression avatar
      if (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == true)
      {
        echo 'L\'avatar a bien été supprimé.';
        $_SESSION['avatar_deleted'] = NULL;
      }
      elseif (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == false)
      {
        echo 'Un problème a eu lieu lors de la suppression de l\'avatar.';
        $_SESSION['avatar_deleted'] = NULL;
      }
      else
      {
        $_SESSION['avatar_deleted'] = NULL;
      }

      // Changement mot de passe
      if (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == true)
      {
        echo 'Mauvais mot de passe d\'origine ou mauvaise confirmation du nouveau mot de passe.';
        $_SESSION['wrong_password'] = NULL;
      }
      elseif (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == false)
      {
        echo 'Le mot de passe a été modifié avec succès.';
        $_SESSION['wrong_password'] = NULL;
      }
      else
      {
        $_SESSION['wrong_password'] = NULL;
      }

      // Mise à jour préférences
      if (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == false)
      {
        echo 'Les préférences n\'ont pas été modifiées.';
        $_SESSION['preferences_updated'] = NULL;
      }
      elseif (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == true)
      {
        echo 'Les préférences ont été mises à jour avec succès.';
        $_SESSION['preferences_updated'] = NULL;
      }
      else
      {
        $_SESSION['preferences_updated'] = NULL;
      }

      // Demande de désinscription
      if (isset($_SESSION['ask_desinscription']) AND $_SESSION['ask_desinscription'] == true)
      {
        echo 'La demande de désinscription a bien été soumise.';
        $_SESSION['ask_desinscription'] = NULL;
      }
    echo '</div>';
  }
?>
