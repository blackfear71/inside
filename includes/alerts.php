<?php
  /*******************/
  /* Initialisations */
  /*******************/

  // Initialisations Movie House
  if (!isset($_SESSION['film_deleted']))
    $_SESSION['film_deleted'] = NULL;

  if (!isset($_SESSION['film_reseted']))
    $_SESSION['film_reseted'] = NULL;

  if (!isset($_SESSION['purged']))
    $_SESSION['purged'] = NULL;

  if (!isset($_SESSION['wrong_date']))
    $_SESSION['wrong_date'] = NULL;

  if (!isset($_SESSION['doesnt_exist']))
    $_SESSION['doesnt_exist'] = NULL;

  if (!isset($_SESSION['film_added']))
    $_SESSION['film_added'] = NULL;

  if (!isset($_SESSION['film_modified']))
    $_SESSION['film_modified'] = NULL;

  if (!isset($_SESSION['film_removed']))
    $_SESSION['film_removed'] = NULL;

  // Initialisations #TheBox
  if (!isset($_SESSION['idea_submitted']))
    $_SESSION['idea_submitted'] = NULL;

  // Initialisations bugs
  if (!isset($_SESSION['bug_submitted']))
    $_SESSION['bug_submitted'] = NULL;

  // Initialisations profil
  if (!isset($_SESSION['pseudo_changed']))
    $_SESSION['pseudo_changed'] = NULL;

  if (!isset($_SESSION['avatar_changed']))
    $_SESSION['avatar_changed'] = NULL;

  if (!isset($_SESSION['avatar_deleted']))
    $_SESSION['avatar_deleted'] = NULL;

  if (!isset($_SESSION['wrong_password']))
    $_SESSION['wrong_password'] = NULL;

  if (!isset($_SESSION['preferences_updated']))
    $_SESSION['preferences_updated'] = NULL;

  if (!isset($_SESSION['ask_desinscription']))
    $_SESSION['ask_desinscription'] = NULL;

  // Initialisations Expense Center
  if (!isset($_SESSION['not_numeric']))
    $_SESSION['not_numeric'] = NULL;

  if (!isset($_SESSION['depense_added']))
    $_SESSION['depense_added'] = NULL;

  if (!isset($_SESSION['depense_modified']))
    $_SESSION['depense_modified'] = NULL;

  if (!isset($_SESSION['depense_deleted']))
    $_SESSION['depense_deleted'] = NULL;

  /***********/
  /* Alertes */
  /***********/

  // Alertes gestion des films (Administrateur)
  if (isset($_SESSION['film_deleted'])
  OR  isset($_SESSION['film_reseted']))
  {
    // Film supprimé
    if (isset($_SESSION['film_deleted']) AND $_SESSION['film_deleted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le film a bien été supprimé de la base de données.';
        $_SESSION['film_deleted'] = NULL;
      echo '</div>';
    }

    // Film réinitialisé
    if (isset($_SESSION['film_reseted']) AND $_SESSION['film_reseted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le film a bien été remis dans la liste.';
        $_SESSION['film_reseted'] = NULL;
      echo '</div>';
    }
  }
  // Alertes purge (Administrateur)
  elseif (isset($_SESSION['purged']) AND $_SESSION['purged'] == true)
  {
    echo '<div class="message_alerte">';
      // Dossier purgé
  		echo 'Les fichiers ont bien été purgés.';
  		$_SESSION['purged'] = NULL;
    echo '</div>';
  }
  // Alertes Movie House (Utilisateurs)
  elseif (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
  {
    echo '<div class="message_alerte_2">';
      // Format date invalide (saisie rapide et saisie avancée)
      echo 'La date n\'a pas un format valide (jj/mm/yyyy).';
      $_SESSION['wrong_date'] = NULL;
    echo '</div>';
  }
  // Film inexistant
  elseif (isset($_SESSION['doesnt_exist']) AND $_SESSION['doesnt_exist'] == true)
  {
    echo '<div class="message_alerte_2">';
      // Film inexistant
      echo 'Ce film n\'existe pas !';
      $_SESSION['doesnt_exist'] = NULL;
    echo '</div>';
  }
  // Film ajouté
  elseif (isset($_SESSION['film_added']) AND $_SESSION['film_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Le film a bien été ajouté.';
      $_SESSION['film_added'] = NULL;
    echo '</div>';
  }
  // Film modifié
  elseif (isset($_SESSION['film_modified']) AND $_SESSION['film_modified'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La fiche du film a bien été modifiée.';
      $_SESSION['film_modified'] = NULL;
    echo '</div>';
  }
  // Film supprimé
  elseif (isset($_SESSION['film_removed']) AND $_SESSION['film_removed'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La demande de suppression a bien été prise en compte.';
      $_SESSION['film_removed'] = NULL;
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
  elseif (isset($_SESSION['bug_submitted']) AND $_SESSION['bug_submitted'] == true)
  {
    echo '<div class="message_alerte">';
			echo 'Votre message a été envoyé à l\'administrateur.';
			$_SESSION['bug_submitted'] = NULL;
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
    // Changement pseudo
    if (isset($_SESSION['pseudo_changed']) AND $_SESSION['pseudo_changed'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le pseudo a bien été modifié.';
        $_SESSION['pseudo_changed'] = NULL;
      echo '</div>';
    }

    // Changement avatar
    if (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'L\'avatar a bien été modifié.';
        $_SESSION['avatar_changed'] = NULL;
      echo '</div>';
    }
    elseif (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Un problème a eu lieu lors de la modification de l\'avatar.';
        $_SESSION['avatar_changed'] = NULL;
      echo '</div>';
    }
    else
    {
      $_SESSION['avatar_changed'] = NULL;
    }

    // Suppression avatar
    if (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'L\'avatar a bien été supprimé.';
        $_SESSION['avatar_deleted'] = NULL;
      echo '</div>';
    }
    elseif (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Un problème a eu lieu lors de la suppression de l\'avatar.';
        $_SESSION['avatar_deleted'] = NULL;
      echo '</div>';
    }
    else
    {
      $_SESSION['avatar_deleted'] = NULL;
    }

    // Changement mot de passe
    if (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Mauvais mot de passe d\'origine ou mauvaise confirmation du nouveau mot de passe.';
        $_SESSION['wrong_password'] = NULL;
      echo '</div>';
    }
    elseif (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Le mot de passe a été modifié avec succès.';
        $_SESSION['wrong_password'] = NULL;
      echo '</div>';
    }
    else
    {
      $_SESSION['wrong_password'] = NULL;
    }

    // Mise à jour préférences
    if (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Les préférences n\'ont pas été modifiées.';
        $_SESSION['preferences_updated'] = NULL;
      echo '</div>';
    }
    elseif (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Les préférences ont été mises à jour avec succès.';
        $_SESSION['preferences_updated'] = NULL;
      echo '</div>';
    }
    else
    {
      $_SESSION['preferences_updated'] = NULL;
    }

    // Demande de désinscription
    if (isset($_SESSION['ask_desinscription']) AND $_SESSION['ask_desinscription'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'La demande de désinscription a bien été soumise.';
        $_SESSION['ask_desinscription'] = NULL;
      echo '</div>';
    }
  }
  // Prix non numérique ou > 0
  elseif (isset($_SESSION['not_numeric']) AND $_SESSION['not_numeric'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Le prix doit être numérique et positif.';
      $_SESSION['not_numeric'] = NULL;
    echo '</div>';
  }
  // Dépense ajoutée
  elseif (isset($_SESSION['depense_added']) AND $_SESSION['depense_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été ajoutée.';
      $_SESSION['depense_added'] = NULL;
    echo '</div>';
  }
  // Dépense modifiée
  elseif (isset($_SESSION['depense_modified']) AND $_SESSION['depense_modified'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été modifiée.';
      $_SESSION['depense_modified'] = NULL;
    echo '</div>';
  }
  // Dépense supprimée
  elseif (isset($_SESSION['depense_deleted']) AND $_SESSION['depense_deleted'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été suprimée.';
      $_SESSION['depense_deleted'] = NULL;
    echo '</div>';
  }
?>
