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

  // Initialisations Petits Pédestres
  if (!isset($_SESSION['erreur_distance']))
    $_SESSION['erreur_distance'] = NULL;

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
      echo '</div>';
      $_SESSION['film_deleted'] = NULL;
    }

    // Film réinitialisé
    if (isset($_SESSION['film_reseted']) AND $_SESSION['film_reseted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le film a bien été remis dans la liste.';
      echo '</div>';
      $_SESSION['film_reseted'] = NULL;
    }
  }
  // Alertes purge (Administrateur)
  elseif (isset($_SESSION['purged']) AND $_SESSION['purged'] == true)
  {
    echo '<div class="message_alerte">';
  		echo 'Les fichiers ont bien été purgés.';
    echo '</div>';
    $_SESSION['purged'] = NULL;
  }
  // Alertes Movie House (Utilisateurs)
  // Format date invalide (saisie rapide et saisie avancée)
  elseif (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La date n\'a pas un format valide (jj/mm/yyyy).';
    echo '</div>';
    $_SESSION['wrong_date'] = NULL;
  }
  // Film inexistant
  elseif (isset($_SESSION['doesnt_exist']) AND $_SESSION['doesnt_exist'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Ce film n\'existe pas !';
    echo '</div>';
    $_SESSION['doesnt_exist'] = NULL;
  }
  // Film ajouté
  elseif (isset($_SESSION['film_added']) AND $_SESSION['film_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Le film a bien été ajouté.';
    echo '</div>';
    $_SESSION['film_added'] = NULL;
  }
  // Film modifié
  elseif (isset($_SESSION['film_modified']) AND $_SESSION['film_modified'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La fiche du film a bien été modifiée.';
    echo '</div>';
    $_SESSION['film_modified'] = NULL;
  }
  // Film supprimé
  elseif (isset($_SESSION['film_removed']) AND $_SESSION['film_removed'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La demande de suppression a bien été prise en compte.';
    echo '</div>';
    $_SESSION['film_removed'] = NULL;
  }
  // Alertes #TheBox (Utilisateurs)
  elseif (isset($_SESSION['idea_submitted']))
  {
    // Idée soumise
    if (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Problème lors de l\'envoi de l\'idée.';
      echo '</div>';
      $_SESSION['idea_submitted'] = NULL;
    }
    elseif (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'L\'idée a été soumise avec succès.';
      echo '</div>';
      $_SESSION['idea_submitted'] = NULL;
    }
    else
    {
      $_SESSION['idea_submitted'] = NULL;
    }
  }
  // Alertes bugs (Utilisateurs)
  elseif (isset($_SESSION['bug_submitted']) AND $_SESSION['bug_submitted'] == true)
  {
    echo '<div class="message_alerte">';
			echo 'Votre message a été envoyé à l\'administrateur.';
    echo '</div>';
    $_SESSION['bug_submitted'] = NULL;
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
      echo '</div>';
      $_SESSION['pseudo_changed'] = NULL;
    }

    // Changement avatar
    if (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'L\'avatar a bien été modifié.';
      echo '</div>';
      $_SESSION['avatar_changed'] = NULL;
    }
    elseif (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Un problème a eu lieu lors de la modification de l\'avatar.';
      echo '</div>';
      $_SESSION['avatar_changed'] = NULL;
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
      echo '</div>';
      $_SESSION['avatar_deleted'] = NULL;
    }
    elseif (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Un problème a eu lieu lors de la suppression de l\'avatar.';
      echo '</div>';
      $_SESSION['avatar_deleted'] = NULL;
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
      echo '</div>';
      $_SESSION['wrong_password'] = NULL;
    }
    elseif (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == false)
    {
      echo '<div class="message_alerte">';
        echo 'Le mot de passe a été modifié avec succès.';
      echo '</div>';
      $_SESSION['wrong_password'] = NULL;
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
      echo '</div>';
      $_SESSION['preferences_updated'] = NULL;
    }
    elseif (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Les préférences ont été mises à jour avec succès.';
      echo '</div>';
      $_SESSION['preferences_updated'] = NULL;
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
      echo '</div>';
      $_SESSION['ask_desinscription'] = NULL;
    }
  }
  // Prix non numérique ou > 0
  elseif (isset($_SESSION['not_numeric']) AND $_SESSION['not_numeric'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Le prix doit être numérique et positif.';
    echo '</div>';
    $_SESSION['not_numeric'] = NULL;
  }
  // Dépense ajoutée
  elseif (isset($_SESSION['depense_added']) AND $_SESSION['depense_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été ajoutée.';
    echo '</div>';
    $_SESSION['depense_added'] = NULL;
  }
  // Dépense modifiée
  elseif (isset($_SESSION['depense_modified']) AND $_SESSION['depense_modified'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été modifiée.';
    echo '</div>';
    $_SESSION['depense_modified'] = NULL;
  }
  // Dépense supprimée
  elseif (isset($_SESSION['depense_deleted']) AND $_SESSION['depense_deleted'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La dépense a bien été suprimée.';
    echo '</div>';
    $_SESSION['depense_deleted'] = NULL;
  }
  // Distance parcours non numérique
  elseif (isset($_SESSION['erreur_distance']))
  {
    echo '<div class="message_alerte_2">';
      echo 'La distance doit être un nombre ;)';
    echo '</div>';
    $_SESSION['erreur_distance'] = NULL;
  }
?>
