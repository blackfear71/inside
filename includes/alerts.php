<?php
  // Alertes gestion des films (Administrateur)
  if (isset($_SESSION['film_deleted'])
  OR  isset($_SESSION['film_reseted']))
  {
    echo '<div class="message_alerte">';
      // Film supprimé
      if (isset($_SESSION['film_deleted']) AND $_SESSION['film_deleted'] == true)
      {
        echo 'Le film a bien été supprimé de la base de données.';
        $_SESSION['film_deleted'] = NULL;
      }

      // Film réinitialisé
      if (isset($_SESSION['film_reseted']) AND $_SESSION['film_reseted'] == true)
      {
        echo 'Le film a bien été remis dans la liste.';
        $_SESSION['film_reseted'] = NULL;
      }
    echo '</div>';
  }
  // Alertes purge (Administrateur)
  elseif (isset($_SESSION['purged']))
  {
    echo '<div class="message_alerte">';
      // Dossier purgé
      if (isset($_SESSION['purged']) AND $_SESSION['purged'] == true)
    	{
    		echo 'Les fichiers ont bien été purgés.';
    		$_SESSION['purged'] = NULL;
    	}
    echo '</div>';
  }
  // Alertes Movie House (Utilisateurs)
  elseif (isset($_SESSION['wrong_date']))
  {
    echo '<div class="message_alerte_2">';
      // Format date invalide
      if (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
      {
        echo 'La date n\'a pas un format valide (jj/mm/yyyy).';
        $_SESSION['wrong_date'] = NULL;
      }
    echo '</div>';
  }
  // Alertes #TheBox (Utilisateurs)
  elseif (isset($_SESSION['idea_submitted']))
  {
    echo '<div class="message_alerte">';
      // Idée soumise
      if (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == false)
      {
        echo 'Problème lors de l\'envoi de l\'idée.';
        $_SESSION['idea_submitted'] = NULL;
      }
      elseif (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == true)
      {
        echo 'L\'idée a été soumise avec succès.';
        $_SESSION['idea_submitted'] = NULL;
      }
      else
      {
        $_SESSION['idea_submitted'] = NULL;
      }
    echo '</div>';
  }
  // Alertes bugs (Utilisateurs)
  elseif (isset($_SESSION['bug_submitted']))
  {
    echo '<div class="message_alerte">';
      if (isset($_SESSION['bug_submitted']) AND $_SESSION['bug_submitted'] == true)
  		{
  			echo 'Votre message a été envoyé à l\'administrateur.';
  			$_SESSION['bug_submitted'] = NULL;
  		}
    echo '</div>';
  }
  // Alertes profil (Utilisateurs)
  elseif (isset($_SESSION['pseudo_changed'])
  OR  isset($_SESSION['avatar_changed'])
  OR  isset($_SESSION['avatar_deleted'])
  OR  isset($_SESSION['wrong_password'])
  OR  isset($_SESSION['preferences_updated'])
  OR  isset($_SESSION['ask_desinscription']))
  {
    echo '<div class="message_alerte">';
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
