<?php
  /*******************/
  /* Initialisations */
  /*******************/
  $alerte = NULL;

  // Initialisation messages connexion
  if (!isset($_SESSION['wrong_connexion']))
   $_SESSION['wrong_connexion'] = NULL;

  if (!isset($_SESSION['not_yet']))
   $_SESSION['not_yet'] = NULL;

  // Initialisation messages inscription
  if (!isset($_SESSION['too_short']))
    $_SESSION['too_short'] = NULL;

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

  if (!isset($_SESSION['bug_deleted']))
    $_SESSION['bug_deleted'] = NULL;

  // Initialisations profil
  if (!isset($_SESSION['pseudo_changed']))
    $_SESSION['pseudo_changed'] = NULL;

  if (!isset($_SESSION['avatar_changed']))
    $_SESSION['avatar_changed'] = NULL;

  if (!isset($_SESSION['avatar_deleted']))
    $_SESSION['avatar_deleted'] = NULL;

  if (!isset($_SESSION['duration_not_correct']))
    $_SESSION['duration_not_correct'] = NULL;

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

  if (!isset($_SESSION['parcours_added']))
    $_SESSION['parcours_added'] = NULL;

  if (!isset($_SESSION['parcours_modified']))
    $_SESSION['parcours_modified'] = NULL;

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

  if (!isset($_SESSION['level_not_numeric']))
    $_SESSION['level_not_numeric'] = NULL;

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

  // Initialisations CRON
  if (!isset($_SESSION['daily_cron']))
    $_SESSION['daily_cron'] = NULL;

  if (!isset($_SESSION['weekly_cron']))
    $_SESSION['weekly_cron'] = NULL;

  /***********/
  /* Alertes */
  /***********/
  // Alertes connexion
  if (isset($_SESSION['wrong_connexion']) AND $_SESSION['wrong_connexion'] == true)
  {
    $alerte = 'Mot de passe incorrect ou utilisateur inconnu.';
    $_SESSION['wrong_connexion'] = NULL;
  }
  elseif (isset($_SESSION['not_yet']) AND $_SESSION['not_yet'] == true)
  {
    $alerte = 'Veuillez patienter jusqu\'à ce que l\'administrateur valide votre inscription.';
    $_SESSION['not_yet'] = NULL;
  }
  // Alertes inscription
  elseif (isset($_SESSION['too_short']) AND $_SESSION['too_short'] == true)
  {
    $alerte = 'Le trigramme doit faire 3 caractères.';
    $_SESSION['too_short'] = NULL;
  }
  elseif (isset($_SESSION['already_exist']) AND $_SESSION['already_exist'] == true)
  {
    $alerte = 'Cet identifiant existe déjà.';
    $_SESSION['already_exist'] = NULL;
  }
  elseif (isset($_SESSION['wrong_confirm']) AND $_SESSION['wrong_confirm'] == true)
  {
    $alerte = 'Mauvaise confirmation du mot de passe.';
    $_SESSION['wrong_confirm'] = NULL;
  }
  elseif (isset($_SESSION['ask_inscription']) AND $_SESSION['ask_inscription'] == true)
  {
    $alerte = 'Votre demande d\'inscription a été soumise.';
    $_SESSION['ask_inscription'] = NULL;
  }
  // Alertes changement mot de passe
  elseif (isset($_SESSION['wrong_id']) AND $_SESSION['wrong_id'] == true)
  {
    $alerte = 'Cet identifiant n\'existe pas.';
    $_SESSION['wrong_id'] = NULL;
  }
  elseif (isset($_SESSION['asked']) AND $_SESSION['asked'] == true)
  {
    $alerte = 'La demande de réinitialisation du mot de passe a bien été effectuée.';
    $_SESSION['asked'] = NULL;
  }
  elseif (isset($_SESSION['already_asked']) AND $_SESSION['already_asked'] == true)
  {
    $alerte = 'Une demande de réinitialisation du mot de passe est déjà en cours pour cet utilisateur.';
    $_SESSION['already_asked'] = NULL;
  }
  // Alertes gestion des films (Administrateur)
  elseif (isset($_SESSION['film_deleted'])
  OR      isset($_SESSION['film_reseted']))
  {
    // Film supprimé
    if (isset($_SESSION['film_deleted']) AND $_SESSION['film_deleted'] == true)
    {
      $alerte = 'Le film a bien été supprimé de la base de données.';
      $_SESSION['film_deleted'] = NULL;
    }
    // Film réinitialisé
    elseif (isset($_SESSION['film_reseted']) AND $_SESSION['film_reseted'] == true)
    {
      $alerte = 'Le film a bien été remis dans la liste.';
      $_SESSION['film_reseted'] = NULL;
    }
  }
  // Alertes Movie House (Utilisateurs)
  // Format date invalide (saisie rapide et saisie avancée)
  elseif (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
  {
    $alerte = 'La date n\'a pas un format valide (jj/mm/yyyy).';
    $_SESSION['wrong_date'] = NULL;
  }
  // Film inexistant
  elseif (isset($_SESSION['doesnt_exist']) AND $_SESSION['doesnt_exist'] == true)
  {
    $alerte = 'Ce film n\'existe pas !';
    $_SESSION['doesnt_exist'] = NULL;
  }
  // Film ajouté
  elseif (isset($_SESSION['film_added']) AND $_SESSION['film_added'] == true)
  {
    $alerte = 'Le film a bien été ajouté.';
    $_SESSION['film_added'] = NULL;
  }
  // Film modifié
  elseif (isset($_SESSION['film_modified']) AND $_SESSION['film_modified'] == true)
  {
    $alerte = 'La fiche du film a bien été modifiée.';
    $_SESSION['film_modified'] = NULL;
  }
  // Film supprimé
  elseif (isset($_SESSION['film_removed']) AND $_SESSION['film_removed'] == true)
  {
    $alerte = 'La demande de suppression a bien été prise en compte.';
    $_SESSION['film_removed'] = NULL;
  }
  // Alertes #TheBox (Utilisateurs)
  elseif (isset($_SESSION['idea_submitted']))
  {
    // Idée soumise
    if (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == false)
    {
      $alerte = 'Problème lors de l\'envoi de l\'idée.';
      $_SESSION['idea_submitted'] = NULL;
    }
    elseif (isset($_SESSION['idea_submitted']) AND $_SESSION['idea_submitted'] == true)
    {
      $alerte = 'L\'idée a été soumise avec succès.';
      $_SESSION['idea_submitted'] = NULL;
    }
    else
    {
      $_SESSION['idea_submitted'] = NULL;
    }
  }
  // Alerte soumission bug (Utilisateurs)
  elseif (isset($_SESSION['bug_submitted']) AND $_SESSION['bug_submitted'] == true)
  {
    $alerte = 'Votre message a été envoyé à l\'administrateur.';
    $_SESSION['bug_submitted'] = NULL;
  }
  // Alertes suppression bug (Admin)
  elseif (isset($_SESSION['bug_deleted']) AND $_SESSION['bug_deleted'] == true)
  {
    $alerte = 'Le rapport a été supprimé.';
    $_SESSION['bug_deleted'] = NULL;
  }
  // Alertes profil (Utilisateurs)
  elseif (isset($_SESSION['pseudo_changed'])
  OR      isset($_SESSION['avatar_changed'])
  OR      isset($_SESSION['avatar_deleted'])
  OR      isset($_SESSION['duration_not_correct'])
  OR      isset($_SESSION['wrong_password'])
  OR      isset($_SESSION['preferences_updated'])
  OR      isset($_SESSION['mail_updated'])
  OR      isset($_SESSION['ask_desinscription']))
  {
    // Changement pseudo
    if (isset($_SESSION['pseudo_changed']) AND $_SESSION['pseudo_changed'] == true)
    {
      $alerte = 'Le pseudo a bien été modifié.';
      $_SESSION['pseudo_changed'] = NULL;
    }

    // Changement avatar
    if (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == true)
    {
      $alerte = 'L\'avatar a bien été modifié.';
      $_SESSION['avatar_changed'] = NULL;
    }
    elseif (isset($_SESSION['avatar_changed']) AND $_SESSION['avatar_changed'] == false)
    {
      $alerte = 'Un problème a eu lieu lors de la modification de l\'avatar.';
      $_SESSION['avatar_changed'] = NULL;
    }
    else
    {
      $_SESSION['avatar_changed'] = NULL;
    }

    // Suppression avatar
    if (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == true)
    {
      $alerte = 'L\'avatar a bien été supprimé.';
      $_SESSION['avatar_deleted'] = NULL;
    }
    elseif (isset($_SESSION['avatar_deleted']) AND $_SESSION['avatar_deleted'] == false)
    {
      $alerte = 'Un problème a eu lieu lors de la suppression de l\'avatar.';
      $_SESSION['avatar_deleted'] = NULL;
    }
    else
    {
      $_SESSION['avatar_deleted'] = NULL;
    }

    // Durée affichage film incorrecte
    if (isset($_SESSION['duration_not_correct']) AND $_SESSION['duration_not_correct'] == true)
    {
      $alerte = 'La durée correspondant à l\'affichage des films doit être un entier numérique positif.';
      $_SESSION['duration_not_correct'] = NULL;
    }

    // Email mis à jour
    if (isset($_SESSION['mail_updated']) AND $_SESSION['mail_updated'] == true)
    {
      $alerte = 'L\'adresse mail a été mise à jour.';
      $_SESSION['mail_updated'] = NULL;
    }

    // Changement mot de passe
    if (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == true)
    {
      $alerte = 'Mauvais mot de passe d\'origine ou mauvaise confirmation du nouveau mot de passe.';
      $_SESSION['wrong_password'] = NULL;
    }
    elseif (isset($_SESSION['wrong_password']) AND $_SESSION['wrong_password'] == false)
    {
      $alerte = 'Le mot de passe a été modifié avec succès.';
      $_SESSION['wrong_password'] = NULL;
    }
    else
    {
      $_SESSION['wrong_password'] = NULL;
    }

    // Mise à jour préférences
    if (isset($_SESSION['preferences_updated']) AND $_SESSION['preferences_updated'] == true)
    {
      $alerte = 'Les préférences ont été mises à jour avec succès.';
      $_SESSION['preferences_updated'] = NULL;
    }

    // Demande de désinscription
    if (isset($_SESSION['ask_desinscription']) AND $_SESSION['ask_desinscription'] == true)
    {
      $alerte = 'La demande de désinscription a bien été soumise.';
      $_SESSION['ask_desinscription'] = NULL;
    }
  }
  // Alerte dépenses : prix non numérique ou > 0
  elseif (isset($_SESSION['not_numeric']) AND $_SESSION['not_numeric'] == true)
  {
    $alerte = 'Le prix doit être numérique.';
    $_SESSION['not_numeric'] = NULL;
  }
  // Alerte dépense ajoutée
  elseif (isset($_SESSION['depense_added']) AND $_SESSION['depense_added'] == true)
  {
    $alerte = 'La dépense a bien été ajoutée.';
    $_SESSION['depense_added'] = NULL;
  }
  // Alerte dépense modifiée
  elseif (isset($_SESSION['depense_modified']) AND $_SESSION['depense_modified'] == true)
  {
    $alerte = 'La dépense a bien été modifiée.';
    $_SESSION['depense_modified'] = NULL;
  }
  // Alerte dépense supprimée
  elseif (isset($_SESSION['depense_deleted']) AND $_SESSION['depense_deleted'] == true)
  {
    $alerte = 'La dépense a bien été supprimée.';
    $_SESSION['depense_deleted'] = NULL;
  }
  // Alerte parcours : distance non numérique
  elseif (isset($_SESSION['erreur_distance']))
  {
    $alerte = 'La distance doit être un nombre ;)';
    $_SESSION['erreur_distance'] = NULL;
  }
  // Alerte parcours ajouté
  elseif (isset($_SESSION['parcours_added']))
  {
    $alerte = 'Le parcours a bien été ajouté.';
    $_SESSION['parcours_added'] = NULL;
  }
  // Alerte parcours modifié
  elseif (isset($_SESSION['parcours_modified']))
  {
    $alerte = 'Le parcours a bien été modifié.';
    $_SESSION['parcours_modified'] = NULL;
  }
  // Alerte calendrier ajouté
  elseif (isset($_SESSION['calendar_added']) AND $_SESSION['calendar_added'] == true)
  {
    $alerte = 'Le calendrier a bien été ajouté.';
    $_SESSION['calendar_added'] = NULL;
  }
  // Alerte calendrier supprimé
  elseif (isset($_SESSION['calendar_removed']) AND $_SESSION['calendar_removed'] == true)
  {
    $alerte = 'La demande de suppression a bien été prise en compte.';
    $_SESSION['calendar_removed'] = NULL;
  }
  // Alertes gestion des calendriers (Administrateur)
  elseif (isset($_SESSION['calendar_deleted'])
  OR      isset($_SESSION['calendar_reseted']))
  {
    // Calendrier supprimé
    if (isset($_SESSION['calendar_deleted']) AND $_SESSION['calendar_deleted'] == true)
    {
      $alerte = 'Le calendrier a bien été supprimé de la base de données.';
      $_SESSION['calendar_deleted'] = NULL;
    }

    // Calendrier réinitialisé
    if (isset($_SESSION['calendar_reseted']) AND $_SESSION['calendar_reseted'] == true)
    {
      $alerte = 'Le calendrier a bien été remis dans la liste.';
      $_SESSION['calendar_reseted'] = NULL;
    }
  }
  // Alerte autorisations mises à jour (Administrateur)
  elseif (isset($_SESSION['autorizations_updated']) AND $_SESSION['autorizations_updated'] == true)
  {
    $alerte = 'Les autorisations ont été mises à jour.';
    $_SESSION['autorizations_updated'] = NULL;
  }
  // ALerte phrase culte ajoutée
  elseif (isset($_SESSION['collector_added']) AND $_SESSION['collector_added'] == true)
  {
    $alerte = 'La phrase culte a été ajoutée.';
    $_SESSION['collector_added'] = NULL;
  }
  // Alerte phrase culte supprimée
  elseif (isset($_SESSION['collector_deleted']) AND $_SESSION['collector_deleted'] == true)
  {
    $alerte = 'La phrase culte a été supprimée.';
    $_SESSION['collector_deleted'] = NULL;
  }
  // Alerte phrase culte modifiée
  elseif (isset($_SESSION['collector_modified']) AND $_SESSION['collector_modified'] == true)
  {
    $alerte = 'La phrase culte a été modifiée.';
    $_SESSION['collector_modified'] = NULL;
  }
  // Alerte email film
  elseif (isset($_SESSION['mail_film_send']) AND $_SESSION['mail_film_send'] == true)
  {
    $alerte = 'L\'email a bien été envoyé.';
    $_SESSION['mail_film_send'] = NULL;
  }
  // Alerte erreur mail film
  elseif (isset($_SESSION['mail_film_error']) AND $_SESSION['mail_film_error'] == true)
  {
    $alerte = 'Une erreur est survenue lors de l\'envoi. Contactez l\'administrateur.';
    $_SESSION['mail_film_error'] = NULL;
  }
  // Alerte succès : référence déjà existante
  elseif (isset($_SESSION['already_referenced']) AND $_SESSION['already_referenced'] == true)
  {
    $alerte = 'Cette référence existe déjà.';
    $_SESSION['already_referenced'] = NULL;
  }
  // Alerte succès : niveau non numérique ou <= 0
  elseif (isset($_SESSION['level_not_numeric']) AND $_SESSION['level_not_numeric'] == true)
  {
    $alerte = 'Le niveau doit être numérique et supérieur à 0.';
    $_SESSION['level_not_numeric'] = NULL;
  }
  // Alerte succès : ordonnancement non numérique
  elseif (isset($_SESSION['order_not_numeric']) AND $_SESSION['order_not_numeric'] == true)
  {
    $alerte = 'L\'ordonnancement doit être numérique.';
    $_SESSION['order_not_numeric'] = NULL;
  }
  // ALerte succès : ordonnancement déjà pris
  elseif (isset($_SESSION['already_ordered']) AND $_SESSION['already_ordered'] == true)
  {
    $alerte = 'Cette ordonnancement est déjà pris pour ce niveau.';
    $_SESSION['already_ordered'] = NULL;
  }
  // Alerte succès : condition non numérique
  elseif (isset($_SESSION['limit_not_numeric']) AND $_SESSION['limit_not_numeric'] == true)
  {
    $alerte = 'La condition doit être numérique.';
    $_SESSION['limit_not_numeric'] = NULL;
  }
  // Alerte succès ajouté
  elseif (isset($_SESSION['success_added']) AND $_SESSION['success_added'] == true)
  {
    $alerte = 'Succès ajouté, ne pas oublier de modifier le code.';
    $_SESSION['success_added'] = NULL;
  }
  // Alerte succès supprimé
  elseif (isset($_SESSION['success_deleted']) AND $_SESSION['success_deleted'] == true)
  {
    $alerte = 'Succès supprimé, ne pas oublier de modifier le code.';
    $_SESSION['success_deleted'] = NULL;
  }
  // Alerte succès mis à jour
  elseif (isset($_SESSION['success_updated']) AND $_SESSION['success_updated'] == true)
  {
    $alerte = 'Succès mis à jour.';
    $_SESSION['success_updated'] = NULL;
  }
  // Alerte CRON journalier exécuté
  elseif (isset($_SESSION['daily_cron']) AND $_SESSION['daily_cron'] == true)
  {
    $alerte = 'CRON journalier exécuté.';
    $_SESSION['daily_cron'] = NULL;
  }
  // Alerte CRON hebdomadaire exécuté
  elseif (isset($_SESSION['weekly_cron']) AND $_SESSION['weekly_cron'] == true)
  {
    $alerte = 'CRON hebdomadaire exécuté.';
    $_SESSION['weekly_cron'] = NULL;
  }

  /*************/
  /* Affichage */
  /*************/
  if (!empty($alerte))
  {
    echo '<div class="message_alerte" id="alerte">';
      echo '<div class="inside_alerte">';
        echo 'Inside';
      echo '</div>';
      echo '<div class="texte_alerte">';
        echo $alerte;
      echo '</div>';
      echo '<div class="boutons_alerte">';
        echo '<a onclick="masquerAlerte(\'alerte\')" class="close_alerte">Fermer</a>';
      echo '</div>';
    echo '</div>';

    $alerte = NULL;
  }
?>
