<?php
  /*******************/
  /* Initialisations */
  /*******************/
  $alerte = NULL;

  // Initialisation messages connexion
  if (!isset($_SESSION['alerts']['wrong_connexion']))
   $_SESSION['alerts']['wrong_connexion'] = NULL;

  if (!isset($_SESSION['alerts']['not_yet']))
   $_SESSION['alerts']['not_yet'] = NULL;

  // Initialisation messages inscription
  if (!isset($_SESSION['alerts']['too_short']))
    $_SESSION['alerts']['too_short'] = NULL;

  if (!isset($_SESSION['alerts']['already_exist']))
    $_SESSION['alerts']['already_exist'] = NULL;

  if (!isset($_SESSION['alerts']['wrong_confirm']))
    $_SESSION['alerts']['wrong_confirm'] = NULL;

  if (!isset($_SESSION['alerts']['ask_inscription']))
    $_SESSION['alerts']['ask_inscription'] = NULL;

  // Initialisation messages changement mot de passe
  if (!isset($_SESSION['alerts']['wrong_id']))
    $_SESSION['alerts']['wrong_id'] = NULL;

  if (!isset($_SESSION['alerts']['asked']))
    $_SESSION['alerts']['asked'] = NULL;

  if (!isset($_SESSION['alerts']['already_asked']))
    $_SESSION['alerts']['already_asked'] = NULL;

  // Initialisations Movie House
  if (!isset($_SESSION['alerts']['film_deleted']))
    $_SESSION['alerts']['film_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['film_reseted']))
    $_SESSION['alerts']['film_reseted'] = NULL;

  if (!isset($_SESSION['alerts']['wrong_date']))
    $_SESSION['alerts']['wrong_date'] = NULL;

  if (!isset($_SESSION['alerts']['wrong_date_doodle']))
    $_SESSION['alerts']['wrong_date_doodle'] = NULL;

  if (!isset($_SESSION['alerts']['film_doesnt_exist']))
    $_SESSION['alerts']['film_doesnt_exist'] = NULL;

  if (!isset($_SESSION['alerts']['film_added']))
    $_SESSION['alerts']['film_added'] = NULL;

  if (!isset($_SESSION['alerts']['film_modified']))
    $_SESSION['alerts']['film_modified'] = NULL;

  if (!isset($_SESSION['alerts']['film_removed']))
    $_SESSION['alerts']['film_removed'] = NULL;

  // Initialisations #TheBox
  if (!isset($_SESSION['alerts']['idea_submitted']))
    $_SESSION['alerts']['idea_submitted'] = NULL;

  // Initialisations Bugs
  if (!isset($_SESSION['alerts']['bug_submitted']))
    $_SESSION['alerts']['bug_submitted'] = NULL;

  if (!isset($_SESSION['alerts']['bug_deleted']))
    $_SESSION['alerts']['bug_deleted'] = NULL;

  // Initialisations Profil
  if (!isset($_SESSION['alerts']['pseudo_updated']))
    $_SESSION['alerts']['pseudo_updated'] = NULL;

  if (!isset($_SESSION['alerts']['avatar_updated']))
    $_SESSION['alerts']['avatar_updated'] = NULL;

  if (!isset($_SESSION['alerts']['avatar_deleted']))
    $_SESSION['alerts']['avatar_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['duration_not_correct']))
    $_SESSION['alerts']['duration_not_correct'] = NULL;

  if (!isset($_SESSION['alerts']['duration_too_long']))
    $_SESSION['alerts']['duration_too_long'] = NULL;

  if (!isset($_SESSION['alerts']['mail_updated']))
    $_SESSION['alerts']['mail_updated'] = NULL;

  if (!isset($_SESSION['alerts']['wrong_password']))
    $_SESSION['alerts']['wrong_password'] = NULL;

  if (!isset($_SESSION['alerts']['preferences_updated']))
    $_SESSION['alerts']['preferences_updated'] = NULL;

  if (!isset($_SESSION['alerts']['ask_desinscription']))
    $_SESSION['alerts']['ask_desinscription'] = NULL;

  if (!isset($_SESSION['alerts']['cancel_status']))
    $_SESSION['alerts']['cancel_status'] = NULL;

  // Initialisations Expense Center
  if (!isset($_SESSION['alerts']['not_numeric']))
    $_SESSION['alerts']['not_numeric'] = NULL;

  if (!isset($_SESSION['alerts']['depense_added']))
    $_SESSION['alerts']['depense_added'] = NULL;

  if (!isset($_SESSION['alerts']['depense_modified']))
    $_SESSION['alerts']['depense_modified'] = NULL;

  if (!isset($_SESSION['alerts']['depense_deleted']))
    $_SESSION['alerts']['depense_deleted'] = NULL;

  // Initialisations Petits Pédestres
  if (!isset($_SESSION['alerts']['erreur_distance']))
    $_SESSION['alerts']['erreur_distance'] = NULL;

  if (!isset($_SESSION['alerts']['parcours_added']))
    $_SESSION['alerts']['parcours_added'] = NULL;

  if (!isset($_SESSION['alerts']['parcours_modified']))
    $_SESSION['alerts']['parcours_modified'] = NULL;

  // Initialisations Calendars
  if (!isset($_SESSION['alerts']['calendar_added']))
    $_SESSION['alerts']['calendar_added'] = NULL;

  if (!isset($_SESSION['alerts']['calendar_removed']))
    $_SESSION['alerts']['calendar_removed'] = NULL;

  if (!isset($_SESSION['alerts']['annexe_removed']))
    $_SESSION['alerts']['annexe_removed'] = NULL;

  // Initialisations Calendars (Administrateur)
  if (!isset($_SESSION['alerts']['calendar_deleted']))
    $_SESSION['alerts']['calendar_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['calendar_reseted']))
    $_SESSION['alerts']['calendar_reseted'] = NULL;

  if (!isset($_SESSION['alerts']['annexe_deleted']))
    $_SESSION['alerts']['annexe_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['annexe_reseted']))
    $_SESSION['alerts']['annexe_reseted'] = NULL;

  if (!isset($_SESSION['alerts']['autorizations_updated']))
    $_SESSION['alerts']['autorizations_updated'] = NULL;

  // Initialisations Collector Room
  if (!isset($_SESSION['alerts']['collector_added']))
    $_SESSION['alerts']['collector_added'] = NULL;

  if (!isset($_SESSION['alerts']['image_collector_added']))
    $_SESSION['alerts']['image_collector_added'] = NULL;

  if (!isset($_SESSION['alerts']['collector_deleted']))
    $_SESSION['alerts']['collector_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['image_collector_deleted']))
    $_SESSION['alerts']['image_collector_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['collector_modified']))
    $_SESSION['alerts']['collector_modified'] = NULL;

  if (!isset($_SESSION['alerts']['image_collector_modified']))
    $_SESSION['alerts']['image_collector_modified'] = NULL;

  // Initialisations Emails
  if (!isset($_SESSION['alerts']['mail_film_send']))
    $_SESSION['alerts']['mail_film_send'] = NULL;

  if (!isset($_SESSION['alerts']['mail_film_error']))
    $_SESSION['alerts']['mail_film_error'] = NULL;

  // Initialisations Gestion succès
  if (!isset($_SESSION['alerts']['already_referenced']))
    $_SESSION['alerts']['already_referenced'] = NULL;

  if (!isset($_SESSION['alerts']['level_not_numeric']))
    $_SESSION['alerts']['level_not_numeric'] = NULL;

  if (!isset($_SESSION['alerts']['order_not_numeric']))
    $_SESSION['alerts']['order_not_numeric'] = NULL;

  if (!isset($_SESSION['alerts']['already_ordered']))
    $_SESSION['alerts']['already_ordered'] = NULL;

  if (!isset($_SESSION['alerts']['limit_not_numeric']))
    $_SESSION['alerts']['limit_not_numeric'] = NULL;

  if (!isset($_SESSION['alerts']['success_added']))
    $_SESSION['alerts']['success_added'] = NULL;

  if (!isset($_SESSION['alerts']['success_deleted']))
    $_SESSION['alerts']['success_deleted'] = NULL;

  if (!isset($_SESSION['alerts']['success_updated']))
    $_SESSION['alerts']['success_updated'] = NULL;

  // Initialisations CRON
  if (!isset($_SESSION['alerts']['daily_cron']))
    $_SESSION['alerts']['daily_cron'] = NULL;

  if (!isset($_SESSION['alerts']['weekly_cron']))
    $_SESSION['alerts']['weekly_cron'] = NULL;

  // Initialisation Missions
  if (!isset($_SESSION['alerts']['mission_achieved']))
    $_SESSION['alerts']['mission_achieved'] = NULL;

  if (!isset($_SESSION['alerts']['mission_doesnt_exist']))
    $_SESSION['alerts']['mission_doesnt_exist'] = NULL;

  // Initialisations Missions (admin)
  if (!isset($_SESSION['alerts']['already_ref_mission']))
    $_SESSION['alerts']['already_ref_mission'] = NULL;

  if (!isset($_SESSION['alerts']['objective_not_numeric']))
    $_SESSION['alerts']['objective_not_numeric'] = NULL;

  if (!isset($_SESSION['alerts']['date_less']))
    $_SESSION['alerts']['date_less'] = NULL;

  if (!isset($_SESSION['alerts']['missing_mission_file']))
    $_SESSION['alerts']['missing_mission_file'] = NULL;

  if (!isset($_SESSION['alerts']['wrong_file']))
    $_SESSION['alerts']['wrong_file'] = NULL;

  if (!isset($_SESSION['alerts']['mission_added']))
    $_SESSION['alerts']['mission_added'] = NULL;

  if (!isset($_SESSION['alerts']['mission_updated']))
    $_SESSION['alerts']['mission_updated'] = NULL;

  if (!isset($_SESSION['alerts']['mission_deleted']))
    $_SESSION['alerts']['mission_deleted'] = NULL;

  // Initialisations Thèmes (admin)
  if (!isset($_SESSION['alerts']['already_ref_theme']))
    $_SESSION['alerts']['already_ref_theme'] = NULL;

  if (!isset($_SESSION['alerts']['missing_theme_file']))
    $_SESSION['alerts']['missing_theme_file'] = NULL;

  if (!isset($_SESSION['alerts']['date_conflict']))
    $_SESSION['alerts']['date_conflict'] = NULL;

  if (!isset($_SESSION['alerts']['theme_added']))
    $_SESSION['alerts']['theme_added'] = NULL;

  if (!isset($_SESSION['alerts']['theme_modified']))
    $_SESSION['alerts']['theme_modified'] = NULL;

  if (!isset($_SESSION['alerts']['theme_deleted']))
    $_SESSION['alerts']['theme_deleted'] = NULL;

  /***********/
  /* Alertes */
  /***********/
  // Alertes connexion
  if (isset($_SESSION['alerts']['wrong_connexion']) AND $_SESSION['alerts']['wrong_connexion'] == true)
  {
    $alerte = 'Mot de passe incorrect ou utilisateur inconnu.';
    $_SESSION['alerts']['wrong_connexion'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['not_yet']) AND $_SESSION['alerts']['not_yet'] == true)
  {
    $alerte = 'Veuillez patienter jusqu\'à ce que l\'administrateur valide votre inscription.';
    $_SESSION['alerts']['not_yet'] = NULL;
  }
  // Alertes inscription
  elseif (isset($_SESSION['alerts']['too_short']) AND $_SESSION['alerts']['too_short'] == true)
  {
    $alerte = 'Le trigramme doit faire 3 caractères.';
    $_SESSION['alerts']['too_short'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['already_exist']) AND $_SESSION['alerts']['already_exist'] == true)
  {
    $alerte = 'Cet identifiant existe déjà.';
    $_SESSION['alerts']['already_exist'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['wrong_confirm']) AND $_SESSION['alerts']['wrong_confirm'] == true)
  {
    $alerte = 'Mauvaise confirmation du mot de passe.';
    $_SESSION['alerts']['wrong_confirm'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['ask_inscription']) AND $_SESSION['alerts']['ask_inscription'] == true)
  {
    $alerte = 'Votre demande d\'inscription a été soumise.';
    $_SESSION['alerts']['ask_inscription'] = NULL;
  }
  // Alertes changement mot de passe
  elseif (isset($_SESSION['alerts']['wrong_id']) AND $_SESSION['alerts']['wrong_id'] == true)
  {
    $alerte = 'Cet identifiant n\'existe pas.';
    $_SESSION['alerts']['wrong_id'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['asked']) AND $_SESSION['alerts']['asked'] == true)
  {
    $alerte = 'La demande de réinitialisation du mot de passe a bien été effectuée.';
    $_SESSION['alerts']['asked'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['already_asked']) AND $_SESSION['alerts']['already_asked'] == true)
  {
    $alerte = 'Une demande de réinitialisation du mot de passe est déjà en cours pour cet utilisateur.';
    $_SESSION['alerts']['already_asked'] = NULL;
  }
  // Alertes gestion des films (Administrateur)
  elseif (isset($_SESSION['alerts']['film_deleted'])
  OR      isset($_SESSION['alerts']['film_reseted']))
  {
    // Film supprimé
    if (isset($_SESSION['alerts']['film_deleted']) AND $_SESSION['alerts']['film_deleted'] == true)
    {
      $alerte = 'Le film a bien été supprimé de la base de données.';
      $_SESSION['alerts']['film_deleted'] = NULL;
    }
    // Film réinitialisé
    elseif (isset($_SESSION['alerts']['film_reseted']) AND $_SESSION['alerts']['film_reseted'] == true)
    {
      $alerte = 'Le film a bien été remis dans la liste.';
      $_SESSION['alerts']['film_reseted'] = NULL;
    }
  }
  // Alertes Movie House (Utilisateurs)
  // Format date invalide (saisie rapide et saisie avancée)
  elseif (isset($_SESSION['alerts']['wrong_date']) AND $_SESSION['alerts']['wrong_date'] == true)
  {
    $alerte = 'La date n\'a pas un format valide (jj/mm/yyyy).';
    $_SESSION['alerts']['wrong_date'] = NULL;
  }
  // Date Doodle < date sortie film
  elseif (isset($_SESSION['alerts']['wrong_date_doodle']) AND $_SESSION['alerts']['wrong_date_doodle'] == true)
  {
    $alerte = 'La date du Doodle ne peut pas être inférieure à la date de sortie du film.';
    $_SESSION['alerts']['wrong_date_doodle'] = NULL;
  }
  // Film inexistant
  elseif (isset($_SESSION['alerts']['film_doesnt_exist']) AND $_SESSION['alerts']['film_doesnt_exist'] == true)
  {
    $alerte = 'Ce film n\'existe pas !';
    $_SESSION['alerts']['film_doesnt_exist'] = NULL;
  }
  // Film ajouté
  elseif (isset($_SESSION['alerts']['film_added']) AND $_SESSION['alerts']['film_added'] == true)
  {
    $alerte = 'Le film a bien été ajouté.';
    $_SESSION['alerts']['film_added'] = NULL;
  }
  // Film modifié
  elseif (isset($_SESSION['alerts']['film_modified']) AND $_SESSION['alerts']['film_modified'] == true)
  {
    $alerte = 'La fiche du film a bien été modifiée.';
    $_SESSION['alerts']['film_modified'] = NULL;
  }
  // Film supprimé
  elseif (isset($_SESSION['alerts']['film_removed']) AND $_SESSION['alerts']['film_removed'] == true)
  {
    $alerte = 'La demande de suppression a bien été prise en compte.';
    $_SESSION['alerts']['film_removed'] = NULL;
  }
  // Alertes #TheBox (Utilisateurs)
  elseif (isset($_SESSION['alerts']['idea_submitted']))
  {
    // Idée soumise
    if (isset($_SESSION['alerts']['idea_submitted']) AND $_SESSION['alerts']['idea_submitted'] == false)
    {
      $alerte = 'Problème lors de l\'envoi de l\'idée.';
      $_SESSION['alerts']['idea_submitted'] = NULL;
    }
    elseif (isset($_SESSION['alerts']['idea_submitted']) AND $_SESSION['alerts']['idea_submitted'] == true)
    {
      $alerte = 'L\'idée a été soumise avec succès.';
      $_SESSION['alerts']['idea_submitted'] = NULL;
    }
    else
    {
      $_SESSION['alerts']['idea_submitted'] = NULL;
    }
  }
  // Alerte soumission bug (Utilisateurs)
  elseif (isset($_SESSION['alerts']['bug_submitted']) AND $_SESSION['alerts']['bug_submitted'] == true)
  {
    $alerte = 'Votre message a été envoyé à l\'administrateur.';
    $_SESSION['alerts']['bug_submitted'] = NULL;
  }
  // Alertes suppression bug (Admin)
  elseif (isset($_SESSION['alerts']['bug_deleted']) AND $_SESSION['alerts']['bug_deleted'] == true)
  {
    $alerte = 'Le rapport a été supprimé.';
    $_SESSION['alerts']['bug_deleted'] = NULL;
  }
  // Alertes profil (Utilisateurs)
  elseif (isset($_SESSION['alerts']['pseudo_updated'])
  OR      isset($_SESSION['alerts']['avatar_updated'])
  OR      isset($_SESSION['alerts']['avatar_deleted'])
  OR      isset($_SESSION['alerts']['duration_not_correct'])
  OR      isset($_SESSION['alerts']['duration_too_long'])
  OR      isset($_SESSION['alerts']['wrong_password'])
  OR      isset($_SESSION['alerts']['preferences_updated'])
  OR      isset($_SESSION['alerts']['mail_updated'])
  OR      isset($_SESSION['alerts']['ask_desinscription'])
  OR      isset($_SESSION['alerts']['cancel_status']))
  {
    // Changement pseudo
    if (isset($_SESSION['alerts']['pseudo_updated']) AND $_SESSION['alerts']['pseudo_updated'] == true)
    {
      $alerte = 'Le pseudo a bien été modifié.';
      $_SESSION['alerts']['pseudo_updated'] = NULL;
    }

    // Changement avatar
    if (isset($_SESSION['alerts']['avatar_updated']) AND $_SESSION['alerts']['avatar_updated'] == true)
    {
      $alerte = 'L\'avatar a bien été modifié.';
      $_SESSION['alerts']['avatar_updated'] = NULL;
    }
    elseif (isset($_SESSION['alerts']['avatar_updated']) AND $_SESSION['alerts']['avatar_updated'] == false)
    {
      $alerte = 'Un problème a eu lieu lors de la modification de l\'avatar.';
      $_SESSION['alerts']['avatar_updated'] = NULL;
    }
    else
    {
      $_SESSION['alerts']['avatar_updated'] = NULL;
    }

    // Suppression avatar
    if (isset($_SESSION['alerts']['avatar_deleted']) AND $_SESSION['alerts']['avatar_deleted'] == true)
    {
      $alerte = 'L\'avatar a bien été supprimé.';
      $_SESSION['alerts']['avatar_deleted'] = NULL;
    }
    elseif (isset($_SESSION['alerts']['avatar_deleted']) AND $_SESSION['alerts']['avatar_deleted'] == false)
    {
      $alerte = 'Un problème a eu lieu lors de la suppression de l\'avatar.';
      $_SESSION['alerts']['avatar_deleted'] = NULL;
    }
    else
    {
      $_SESSION['alerts']['avatar_deleted'] = NULL;
    }

    // Durée affichage film incorrecte
    if (isset($_SESSION['alerts']['duration_not_correct']) AND $_SESSION['alerts']['duration_not_correct'] == true)
    {
      $alerte = 'La durée correspondant à l\'affichage des films doit être un entier numérique positif.';
      $_SESSION['alerts']['duration_not_correct'] = NULL;
    }

    // Durée trop longue incorrecte
    if (isset($_SESSION['alerts']['duration_too_long']) AND $_SESSION['alerts']['duration_too_long'] == true)
    {
      $alerte = 'La durée saisie correspondant à l\'affichage des films est trop grande.';
      $_SESSION['alerts']['duration_too_long'] = NULL;
    }

    // Email mis à jour
    if (isset($_SESSION['alerts']['mail_updated']) AND $_SESSION['alerts']['mail_updated'] == true)
    {
      $alerte = 'L\'adresse mail a été mise à jour.';
      $_SESSION['alerts']['mail_updated'] = NULL;
    }

    // Changement mot de passe
    if (isset($_SESSION['alerts']['wrong_password']) AND $_SESSION['alerts']['wrong_password'] == true)
    {
      $alerte = 'Mauvais mot de passe d\'origine ou mauvaise confirmation du nouveau mot de passe.';
      $_SESSION['alerts']['wrong_password'] = NULL;
    }
    elseif (isset($_SESSION['alerts']['wrong_password']) AND $_SESSION['alerts']['wrong_password'] == false)
    {
      $alerte = 'Le mot de passe a été modifié avec succès.';
      $_SESSION['alerts']['wrong_password'] = NULL;
    }
    else
    {
      $_SESSION['alerts']['wrong_password'] = NULL;
    }

    // Mise à jour préférences
    if (isset($_SESSION['alerts']['preferences_updated']) AND $_SESSION['alerts']['preferences_updated'] == true)
    {
      $alerte = 'Les préférences ont été mises à jour avec succès.';
      $_SESSION['alerts']['preferences_updated'] = NULL;
    }

    // Demande de désinscription
    if (isset($_SESSION['alerts']['ask_desinscription']) AND $_SESSION['alerts']['ask_desinscription'] == true)
    {
      $alerte = 'La demande de désinscription a bien été soumise.';
      $_SESSION['alerts']['ask_desinscription'] = NULL;
    }

    // Annulation désinscription
    if (isset($_SESSION['alerts']['cancel_status']) AND $_SESSION['alerts']['cancel_status'] == true)
    {
      $alerte = 'La demande a bien été annulée.';
      $_SESSION['alerts']['cancel_status'] = NULL;
    }
  }
  // Alerte dépenses : prix non numérique ou > 0
  elseif (isset($_SESSION['alerts']['not_numeric']) AND $_SESSION['alerts']['not_numeric'] == true)
  {
    $alerte = 'Le prix doit être numérique.';
    $_SESSION['alerts']['not_numeric'] = NULL;
  }
  // Alerte dépense ajoutée
  elseif (isset($_SESSION['alerts']['depense_added']) AND $_SESSION['alerts']['depense_added'] == true)
  {
    $alerte = 'La dépense a bien été ajoutée.';
    $_SESSION['alerts']['depense_added'] = NULL;
  }
  // Alerte dépense modifiée
  elseif (isset($_SESSION['alerts']['depense_modified']) AND $_SESSION['alerts']['depense_modified'] == true)
  {
    $alerte = 'La dépense a bien été modifiée.';
    $_SESSION['alerts']['depense_modified'] = NULL;
  }
  // Alerte dépense supprimée
  elseif (isset($_SESSION['alerts']['depense_deleted']) AND $_SESSION['alerts']['depense_deleted'] == true)
  {
    $alerte = 'La dépense a bien été supprimée.';
    $_SESSION['alerts']['depense_deleted'] = NULL;
  }
  // Alerte parcours : distance non numérique
  elseif (isset($_SESSION['alerts']['erreur_distance']))
  {
    $alerte = 'La distance doit être un nombre ;)';
    $_SESSION['alerts']['erreur_distance'] = NULL;
  }
  // Alerte parcours ajouté
  elseif (isset($_SESSION['alerts']['parcours_added']))
  {
    $alerte = 'Le parcours a bien été ajouté.';
    $_SESSION['alerts']['parcours_added'] = NULL;
  }
  // Alerte parcours modifié
  elseif (isset($_SESSION['alerts']['parcours_modified']))
  {
    $alerte = 'Le parcours a bien été modifié.';
    $_SESSION['alerts']['parcours_modified'] = NULL;
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
  // Alerte annexe ajoutée
  elseif (isset($_SESSION['annexe_added']) AND $_SESSION['annexe_added'] == true)
  {
    $alerte = 'L\'annexe a bien été ajoutée.';
    $_SESSION['annexe_added'] = NULL;
  }
  // Alerte annexe supprimé
  elseif (isset($_SESSION['annexe_removed']) AND $_SESSION['annexe_removed'] == true)
  {
    $alerte = 'La demande de suppression a bien été prise en compte.';
    $_SESSION['annexe_removed'] = NULL;
  }
  // Alertes gestion des calendriers (Administrateur)
  elseif (isset($_SESSION['alerts']['calendar_deleted'])
  OR      isset($_SESSION['alerts']['calendar_reseted'])
  OR      isset($_SESSION['alerts']['annexe_deleted'])
  OR      isset($_SESSION['alerts']['annexe_reseted']))
  {
    // Calendrier supprimé
    if (isset($_SESSION['alerts']['calendar_deleted']) AND $_SESSION['alerts']['calendar_deleted'] == true)
    {
      $alerte = 'Le calendrier a bien été supprimé de la base de données.';
      $_SESSION['alerts']['calendar_deleted'] = NULL;
    }

    // Calendrier réinitialisé
    if (isset($_SESSION['alerts']['calendar_reseted']) AND $_SESSION['alerts']['calendar_reseted'] == true)
    {
      $alerte = 'Le calendrier a bien été remis dans la liste.';
      $_SESSION['alerts']['calendar_reseted'] = NULL;
    }

    // Annexe supprimée
    if (isset($_SESSION['alerts']['annexe_deleted']) AND $_SESSION['alerts']['annexe_deleted'] == true)
    {
      $alerte = 'L\'annexe a bien été supprimée de la base de données.';
      $_SESSION['alerts']['annexe_deleted'] = NULL;
    }

    // Annexe réinitialisée
    if (isset($_SESSION['alerts']['annexe_reseted']) AND $_SESSION['alerts']['annexe_reseted'] == true)
    {
      $alerte = 'L\'annexe a bien été remise dans la liste.';
      $_SESSION['alerts']['annexe_reseted'] = NULL;
    }
  }
  // Alerte autorisations mises à jour (Administrateur)
  elseif (isset($_SESSION['alerts']['autorizations_updated']) AND $_SESSION['alerts']['autorizations_updated'] == true)
  {
    $alerte = 'Les autorisations ont été mises à jour.';
    $_SESSION['alerts']['autorizations_updated'] = NULL;
  }
  // ALerte phrase culte ajoutée
  elseif (isset($_SESSION['alerts']['collector_added']) AND $_SESSION['alerts']['collector_added'] == true)
  {
    $alerte = 'La phrase culte a été ajoutée.';
    $_SESSION['alerts']['collector_added'] = NULL;
  }
  // ALerte image culte ajoutée
  elseif (isset($_SESSION['alerts']['image_collector_added']) AND $_SESSION['alerts']['image_collector_added'] == true)
  {
    $alerte = 'L\'image a été ajoutée.';
    $_SESSION['alerts']['image_collector_added'] = NULL;
  }
  // Alerte phrase culte supprimée
  elseif (isset($_SESSION['alerts']['collector_deleted']) AND $_SESSION['alerts']['collector_deleted'] == true)
  {
    $alerte = 'La phrase culte a été supprimée.';
    $_SESSION['alerts']['collector_deleted'] = NULL;
  }
  // Alerte image culte supprimée
  elseif (isset($_SESSION['alerts']['image_collector_deleted']) AND $_SESSION['alerts']['image_collector_deleted'] == true)
  {
    $alerte = 'L\'image a été supprimée.';
    $_SESSION['alerts']['image_collector_deleted'] = NULL;
  }
  // Alerte phrase culte modifiée
  elseif (isset($_SESSION['alerts']['collector_modified']) AND $_SESSION['alerts']['collector_modified'] == true)
  {
    $alerte = 'La phrase culte a été modifiée.';
    $_SESSION['alerts']['collector_modified'] = NULL;
  }
  // Alerte image culte modifiée
  elseif (isset($_SESSION['alerts']['image_collector_modified']) AND $_SESSION['alerts']['image_collector_modified'] == true)
  {
    $alerte = 'L\'image a été modifiée.';
    $_SESSION['alerts']['image_collector_modified'] = NULL;
  }
  // Alerte email film
  elseif (isset($_SESSION['alerts']['mail_film_send']) AND $_SESSION['alerts']['mail_film_send'] == true)
  {
    $alerte = 'L\'email a bien été envoyé.';
    $_SESSION['alerts']['mail_film_send'] = NULL;
  }
  // Alerte erreur mail film
  elseif (isset($_SESSION['alerts']['mail_film_error']) AND $_SESSION['alerts']['mail_film_error'] == true)
  {
    $alerte = 'Une erreur est survenue lors de l\'envoi. Contactez l\'administrateur.';
    $_SESSION['alerts']['mail_film_error'] = NULL;
  }
  // Alerte succès : référence déjà existante
  elseif (isset($_SESSION['alerts']['already_referenced']) AND $_SESSION['alerts']['already_referenced'] == true)
  {
    $alerte = 'Cette référence existe déjà.';
    $_SESSION['alerts']['already_referenced'] = NULL;
  }
  // Alerte succès : niveau non numérique ou <= 0
  elseif (isset($_SESSION['alerts']['level_not_numeric']) AND $_SESSION['alerts']['level_not_numeric'] == true)
  {
    $alerte = 'Le niveau doit être numérique et supérieur à 0.';
    $_SESSION['alerts']['level_not_numeric'] = NULL;
  }
  // Alerte succès : ordonnancement non numérique
  elseif (isset($_SESSION['alerts']['order_not_numeric']) AND $_SESSION['alerts']['order_not_numeric'] == true)
  {
    $alerte = 'L\'ordonnancement doit être numérique.';
    $_SESSION['alerts']['order_not_numeric'] = NULL;
  }
  // ALerte succès : ordonnancement déjà pris
  elseif (isset($_SESSION['alerts']['already_ordered']) AND $_SESSION['alerts']['already_ordered'] == true)
  {
    $alerte = 'Cette ordonnancement est déjà pris pour ce niveau.';
    $_SESSION['alerts']['already_ordered'] = NULL;
  }
  // Alerte succès : condition non numérique
  elseif (isset($_SESSION['alerts']['limit_not_numeric']) AND $_SESSION['alerts']['limit_not_numeric'] == true)
  {
    $alerte = 'La condition doit être numérique.';
    $_SESSION['alerts']['limit_not_numeric'] = NULL;
  }
  // Alerte succès ajouté
  elseif (isset($_SESSION['alerts']['success_added']) AND $_SESSION['alerts']['success_added'] == true)
  {
    $alerte = 'Succès ajouté, ne pas oublier d\'ajouter le code de la fonction getSuccess() dans metier_profil.php.';
    $_SESSION['alerts']['success_added'] = NULL;
  }
  // Alerte succès supprimé
  elseif (isset($_SESSION['alerts']['success_deleted']) AND $_SESSION['alerts']['success_deleted'] == true)
  {
    $alerte = 'Succès supprimé, ne pas oublier de supprimer le code de la fonction getSuccess() dans metier_profil.php.';
    $_SESSION['alerts']['success_deleted'] = NULL;
  }
  // Alerte succès mis à jour
  elseif (isset($_SESSION['alerts']['success_updated']) AND $_SESSION['alerts']['success_updated'] == true)
  {
    $alerte = 'Succès mis à jour.';
    $_SESSION['alerts']['success_updated'] = NULL;
  }
  // Alerte CRON journalier exécuté
  elseif (isset($_SESSION['alerts']['daily_cron']) AND $_SESSION['alerts']['daily_cron'] == true)
  {
    $alerte = 'CRON journalier exécuté.';
    $_SESSION['alerts']['daily_cron'] = NULL;
  }
  // Alerte CRON hebdomadaire exécuté
  elseif (isset($_SESSION['alerts']['weekly_cron']) AND $_SESSION['alerts']['weekly_cron'] == true)
  {
    $alerte = 'CRON hebdomadaire exécuté.';
    $_SESSION['alerts']['weekly_cron'] = NULL;
  }
  elseif (isset($_SESSION['alerts']['mission_achieved']) AND $_SESSION['alerts']['mission_achieved'] == true)
  {
    $alerte = 'Bien joué ! Cette mission est terminée pour aujourd\'hui !';
    $_SESSION['alerts']['mission_achieved'] = NULL;
  }
  // Mission inexistante
  elseif (isset($_SESSION['alerts']['mission_doesnt_exist']) AND $_SESSION['alerts']['mission_doesnt_exist'] == true)
  {
    $alerte = 'Cette mission n\'existe pas ou n\'est pas encore accessible !';
    $_SESSION['alerts']['mission_doesnt_exist'] = NULL;
  }
  // Référence mission déjà existante
  elseif (isset($_SESSION['alerts']['already_ref_mission']) AND $_SESSION['alerts']['already_ref_mission'] == true)
  {
    $alerte = 'La référence de cette mission existe déjà.';
    $_SESSION['alerts']['already_ref_mission'] = NULL;
  }
  // Objectif non numérique ou < 0
  elseif (isset($_SESSION['alerts']['objective_not_numeric']) AND $_SESSION['alerts']['objective_not_numeric'] == true)
  {
    $alerte = 'L\'objectif doit être numérique et positif.';
    $_SESSION['alerts']['objective_not_numeric'] = NULL;
  }
  // Dates incohérentes
  elseif (isset($_SESSION['alerts']['date_less']) AND $_SESSION['alerts']['date_less'] == true)
  {
    $alerte = 'La date de fin doit être supérieure ou égale à la date de début.';
    $_SESSION['alerts']['date_less'] = NULL;
  }
  // Fichier mission manquant
  elseif (isset($_SESSION['alerts']['missing_mission_file']) AND $_SESSION['alerts']['missing_mission_file'] == true)
  {
    $alerte = 'Un fichier est manquant pour la mission.';
    $_SESSION['alerts']['missing_mission_file'] = NULL;
  }
  // Problème fichier mission
  elseif (isset($_SESSION['alerts']['wrong_file']) AND $_SESSION['alerts']['wrong_file'] == true)
  {
    $alerte = 'Un problème a eu lieu avec l\'un des fichiers.';
    $_SESSION['alerts']['wrong_file'] = NULL;
  }
  // Mission ajoutée
  elseif (isset($_SESSION['alerts']['mission_added']) AND $_SESSION['alerts']['mission_added'] == true)
  {
    $alerte = 'La mission a bien été créée.';
    $_SESSION['alerts']['mission_added'] = NULL;
  }
  // Mission modifiée
  elseif (isset($_SESSION['alerts']['mission_updated']) AND $_SESSION['alerts']['mission_updated'] == true)
  {
    $alerte = 'La mission a bien été modifiée.';
    $_SESSION['alerts']['mission_updated'] = NULL;
  }
  // Mission supprimée
  elseif (isset($_SESSION['alerts']['mission_deleted']) AND $_SESSION['alerts']['mission_deleted'] == true)
  {
    $alerte = 'La mission a bien été supprimée.';
    $_SESSION['alerts']['mission_deleted'] = NULL;
  }
  // Référence mission déjà existante
  elseif (isset($_SESSION['alerts']['already_ref_theme']) AND $_SESSION['alerts']['already_ref_theme'] == true)
  {
    $alerte = 'La référence de ce thème existe déjà.';
    $_SESSION['alerts']['already_ref_theme'] = NULL;
  }
  // Fichier thème manquant
  elseif (isset($_SESSION['alerts']['missing_theme_file']) AND $_SESSION['alerts']['missing_theme_file'] == true)
  {
    $alerte = 'Un fichier est manquant pour le thème.';
    $_SESSION['alerts']['missing_theme_file'] = NULL;
  }
  // Dates incohérentes
  elseif (isset($_SESSION['alerts']['date_conflict']) AND $_SESSION['alerts']['date_conflict'] == true)
  {
    $alerte = 'Les dates de thèmes ne peuvent pas se superposer.';
    $_SESSION['alerts']['date_conflict'] = NULL;
  }
  // Thème ajouté
  elseif (isset($_SESSION['alerts']['theme_added']) AND $_SESSION['alerts']['theme_added'] == true)
  {
    $alerte = 'Le thème a bien été ajouté.';
    $_SESSION['alerts']['theme_added'] = NULL;
  }
  // Thème modifié
  elseif (isset($_SESSION['alerts']['theme_modified']) AND $_SESSION['alerts']['theme_modified'] == true)
  {
    $alerte = 'Le thème a été modifié.';
    $_SESSION['alerts']['theme_modified'] = NULL;
  }
  // Thème supprimé
  elseif (isset($_SESSION['alerts']['theme_deleted']) AND $_SESSION['alerts']['theme_deleted'] == true)
  {
    $alerte = 'Le thème a été supprimé.';
    $_SESSION['alerts']['theme_deleted'] = NULL;
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
