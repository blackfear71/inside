<?php
  /*******************/
  /* Initialisations */
  /*******************/

  // Initialisation messages connexion
  if (!isset($_SESSION['wrong_connexion']))
   $_SESSION['wrong_connexion'] = NULL;

  if (!isset($_SESSION['not_yet']))
   $_SESSION['not_yet'] = NULL;

  // Initialisation messages inscription
  if (!isset($_SESSION['already_exist']))
    $_SESSION['already_exist'] = NULL;

  if (!isset($_SESSION['wrong_confirm']))
    $_SESSION['wrong_confirm'] = NULL;

  if (!isset($_SESSION['ask_inscription']))
    $_SESSION['ask_inscription'] = NULL;

  // Initialisation messages changement mot de passe
  if (!isset($_SESSION['wrong_id']))
    $_SESSION['wrong_id'] = NULL;

  if (!isset($_SESSION['asked']))
    $_SESSION['asked'] = NULL;

  if (!isset($_SESSION['already_asked']))
    $_SESSION['already_asked'] = NULL;

  // Initialisations Movie House
  if (!isset($_SESSION['film_deleted']))
    $_SESSION['film_deleted'] = NULL;

  if (!isset($_SESSION['film_reseted']))
    $_SESSION['film_reseted'] = NULL;

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

  if (!isset($_SESSION['mail_updated']))
    $_SESSION['mail_updated'] = NULL;

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

  // Initialisations Calendars (Administrateur)
  if (!isset($_SESSION['calendar_deleted']))
    $_SESSION['calendar_deleted'] = NULL;

  if (!isset($_SESSION['calendar_reseted']))
    $_SESSION['calendar_reseted'] = NULL;

  if (!isset($_SESSION['autorizations_updated']))
    $_SESSION['autorizations_updated'] = NULL;

  // Initialisations Collector Room
  if (!isset($_SESSION['collector_added']))
    $_SESSION['collector_added'] = NULL;

  if (!isset($_SESSION['collector_deleted']))
    $_SESSION['collector_deleted'] = NULL;

  if (!isset($_SESSION['collector_modified']))
    $_SESSION['collector_modified'] = NULL;

  // Initialisations emails
  if (!isset($_SESSION['mail_film_send']))
    $_SESSION['mail_film_send'] = NULL;

  if (!isset($_SESSION['mail_film_error']))
    $_SESSION['mail_film_error'] = NULL;

  // Initialisations gestion succès
  if (!isset($_SESSION['already_referenced']))
    $_SESSION['already_referenced'] = NULL;

  if (!isset($_SESSION['order_not_numeric']))
    $_SESSION['order_not_numeric'] = NULL;

  if (!isset($_SESSION['already_ordered']))
    $_SESSION['already_ordered'] = NULL;

  if (!isset($_SESSION['limit_not_numeric']))
    $_SESSION['limit_not_numeric'] = NULL;

  if (!isset($_SESSION['success_added']))
    $_SESSION['success_added'] = NULL;

  if (!isset($_SESSION['success_deleted']))
    $_SESSION['success_deleted'] = NULL;

  if (!isset($_SESSION['success_updated']))
    $_SESSION['success_updated'] = NULL;

  /***********/
  /* Alertes */
  /***********/

  // Alertes connexion
  if (isset($_SESSION['wrong_connexion']) AND $_SESSION['wrong_connexion'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Mot de passe incorrect ou utilisateur inconnu.';
    echo '</div>';
    $_SESSION['wrong_connexion'] = NULL;
  }
  elseif (isset($_SESSION['not_yet']) AND $_SESSION['not_yet'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Veuillez patienter jusqu\'à ce que l\'administrateur valide votre inscription.';
    echo '</div>';
    $_SESSION['not_yet'] = NULL;
  }
  // Alertes inscription
  elseif (isset($_SESSION['already_exist']) AND $_SESSION['already_exist'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Cet identifiant existe déjà.';
    echo '</div>';
    $_SESSION['already_exist'] = NULL;
  }

  if (isset($_SESSION['wrong_confirm']) AND $_SESSION['wrong_confirm'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Mauvaise confirmation du mot de passe.';
    echo '</div>';
    $_SESSION['wrong_confirm'] = NULL;
  }
  elseif (isset($_SESSION['ask_inscription']) AND $_SESSION['ask_inscription'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Votre demande d\'inscription a été soumise.';
    echo '</div>';
    $_SESSION['ask_inscription'] = NULL;
  }
  // Alertes changement mot de passe
  elseif (isset($_SESSION['wrong_id']) AND $_SESSION['wrong_id'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Cet identifiant n\'existe pas.';
    echo '</div>';
    $_SESSION['wrong_id'] = NULL;
  }
  elseif (isset($_SESSION['asked']) AND $_SESSION['asked'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'La demande de réinitialisation du mot de passe a bien été effectuée.';
    echo '</div>';
    $_SESSION['asked'] = NULL;
  }
  elseif (isset($_SESSION['already_asked']) AND $_SESSION['already_asked'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Une demande de réinitialisation du mot de passe est déjà en cours pour cet utilisateur.';
    echo '</div>';
    $_SESSION['already_asked'] = NULL;
  }
  // Alertes gestion des films (Administrateur)
  elseif (isset($_SESSION['film_deleted'])
  OR      isset($_SESSION['film_reseted']))
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
  OR  isset($_SESSION['mail_updated'])
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

    // Email mis à jour
    if (isset($_SESSION['mail_updated']) AND $_SESSION['mail_updated'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'L\'adresse mail a été mise à jour.';
      echo '</div>';
      $_SESSION['mail_updated'] = NULL;
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
      echo 'Le prix doit être numérique.';
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
  // Alerte calendrier ajouté
  elseif (isset($_SESSION['calendar_added']) AND $_SESSION['calendar_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Le calendrier a bien été ajouté.';
    echo '</div>';
    $_SESSION['calendar_added'] = NULL;
  }
  // Alerte calendrier supprimé
  elseif (isset($_SESSION['calendar_removed']) AND $_SESSION['calendar_removed'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La demande de suppression a bien été prise en compte.';
    echo '</div>';
    $_SESSION['calendar_removed'] = NULL;
  }
  // Alertes gestion des calendriers (Administrateur)
  elseif (isset($_SESSION['calendar_deleted'])
  OR      isset($_SESSION['calendar_reseted']))
  {
    // Calendrier supprimé
    if (isset($_SESSION['calendar_deleted']) AND $_SESSION['calendar_deleted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le calendrier a bien été supprimé de la base de données.';
      echo '</div>';
      $_SESSION['calendar_deleted'] = NULL;
    }

    // Calendrier réinitialisé
    if (isset($_SESSION['calendar_reseted']) AND $_SESSION['calendar_reseted'] == true)
    {
      echo '<div class="message_alerte">';
        echo 'Le calendrier a bien été remis dans la liste.';
      echo '</div>';
      $_SESSION['calendar_reseted'] = NULL;
    }
  }
  // Alerte autorisations mises à jour (Administrateur)
  elseif (isset($_SESSION['autorizations_updated']) AND $_SESSION['autorizations_updated'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Les autorisations ont été mises à jour.';
    echo '</div>';
    $_SESSION['autorizations_updated'] = NULL;
  }
  // ALerte phrase culte ajoutée
  elseif (isset($_SESSION['collector_added']) AND $_SESSION['collector_added'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La phrase culte a été ajoutée.';
    echo '</div>';
    $_SESSION['collector_added'] = NULL;
  }
  // Alerte phrase culte supprimée
  elseif (isset($_SESSION['collector_deleted']) AND $_SESSION['collector_deleted'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La phrase culte a été supprimée.';
    echo '</div>';
    $_SESSION['collector_deleted'] = NULL;
  }
  // Alerte phrase culte modifiée
  elseif (isset($_SESSION['collector_modified']) AND $_SESSION['collector_modified'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'La phrase culte a été modifiée.';
    echo '</div>';
    $_SESSION['collector_modified'] = NULL;
  }
  // Alerte email film
  elseif (isset($_SESSION['mail_film_send']) AND $_SESSION['mail_film_send'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'L\'email a bien été envoyé.';
    echo '</div>';
    $_SESSION['mail_film_send'] = NULL;
  }
  // Alerte erreur mail film
  elseif (isset($_SESSION['mail_film_error']) AND $_SESSION['mail_film_error'] == true)
  {
    echo '<div class="message_alerte_2">';
      echo 'Une erreur est survenue lors de l\'envoi. Contactez l\'administrateur.';
    echo '</div>';
    $_SESSION['mail_film_error'] = NULL;
  }
  // Alerte référence déjà existante
  elseif (isset($_SESSION['already_referenced']) AND $_SESSION['already_referenced'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Cette référence existe déjà.';
    echo '</div>';
    $_SESSION['already_referenced'] = NULL;
  }
  // Alerte ordonnancement non numérique
  elseif (isset($_SESSION['order_not_numeric']) AND $_SESSION['order_not_numeric'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'L\'ordonnancement doit être numérique.';
    echo '</div>';
    $_SESSION['order_not_numeric'] = NULL;
  }
  // ALerte ordonnancement déjà pris
  elseif (isset($_SESSION['already_ordered']) AND $_SESSION['already_ordered'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Cette ordonnancement est déjà pris.';
    echo '</div>';
    $_SESSION['already_ordered'] = NULL;
  }
  // Alerte condition non numérique
  elseif (isset($_SESSION['limit_not_numeric']) AND $_SESSION['limit_not_numeric'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'La condition doit être numérique.';
    echo '</div>';
    $_SESSION['limit_not_numeric'] = NULL;
  }
  // Alerte succès ajouté
  elseif (isset($_SESSION['success_added']) AND $_SESSION['success_added'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Succès ajouté, ne pas oublier de modifier le code.';
    echo '</div>';
    $_SESSION['success_added'] = NULL;
  }
  // Alerte succès supprimé
  elseif (isset($_SESSION['success_deleted']) AND $_SESSION['success_deleted'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Succès supprimé, ne pas oublier de modifier le code.';
    echo '</div>';
    $_SESSION['success_deleted'] = NULL;
  }
  // Alerte succès mis à jour
  elseif (isset($_SESSION['success_updated']) AND $_SESSION['success_updated'] == true)
  {
    echo '<div class="message_alerte">';
      echo 'Succès mis à jour.';
    echo '</div>';
    $_SESSION['success_updated'] = NULL;
  }
?>
