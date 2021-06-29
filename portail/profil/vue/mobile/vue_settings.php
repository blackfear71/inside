<?php
  /*********************/
  /* Paramètres profil */
  /*********************/
  echo '<div class="zone_preferences_profil">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Mes informations</div>';
    echo '</div>';

    // Avatar actuel & suppression
    echo '<div class="zone_avatar_parametres">';
      echo '<div class="zone_parametres_avatar">';
        $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_parametres" />';
      echo '</div>';

      echo '<form method="post" action="profil.php?action=doSupprimerAvatar" enctype="multipart/form-data">';
        echo '<input type="submit" name="delete_avatar" value="Supprimer" class="bouton_validation_image" />';
      echo '</form>';
    echo '</div>';

    // Modification avatar
    echo '<form method="post" action="profil.php?action=doModifierAvatar" enctype="multipart/form-data" class="form_update_avatar">';
      echo '<div class="zone_saisie_image">';
        echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

        echo '<div class="zone_parcourir_image">';
          echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
          echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_image loadSaisieAvatar" required />';
        echo '</div>';

        echo '<div class="mask_image">';
          echo '<img id="image_avatar_saisie" alt="" class="image" />';
        echo '</div>';
      echo '</div>';

      // Bouton d'action
      echo '<input type="submit" name="post_avatar" value="Modifier" id="bouton_saisie_avatar" class="bouton_validation_image" />';
    echo '</form>';

    // Mise à jour informations
    echo '<form method="post" action="profil.php?action=doUpdateInfosMobile" class="form_update_infos">';
      // Pseudo
      echo '<div class="zone_saisie_information">';
        echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_information" />';
        echo '<input type="text" name="pseudo" placeholder="Pseudo" value="' . $profil->getPseudo() . '" maxlength="255" class="saisie_information" />';
      echo '</div>';

      // Email
      echo '<div class="zone_saisie_information">';
        echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_information" />';
        echo '<input type="email" name="email" placeholder="Adresse mail" value="' . $profil->getEmail() . '" maxlength="255" class="saisie_information" />';
      echo '</div>';

      // Anniversaire
      echo '<div class="zone_saisie_information">';
        echo '<img src="../../includes/icons/profil/anniversary_grey.png" alt="anniversary_grey" class="logo_information" />';
        echo '<input type="date" name="anniversaire" value="' . formatDateForDisplayMobile($profil->getAnniversary()) . '" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_information" />';
      echo '</div>';

      // Bouton d'action
      echo '<input type="submit" name="saisie_pseudo" value="Mettre à jour les informations" class="bouton_validation_form" />';
    echo '</form>';
  echo '</div>';

  /***************/
  /* Préférences */
  /***************/
  echo '<div class="zone_preferences_profil">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/profil/settings_grey.png" alt="settings_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Préférences</div>';
    echo '</div>';

    // Préférences
    echo '<form method="post" action="profil.php?action=doUpdatePreferences">';
      /***************/
      /*** Celsius ***/
      /***************/
      echo '<div class="zone_preferences">';
        // Titre
        echo '<div class="titre_preference">';
          echo '<img src="../../includes/icons/profil/celsius_grey.png" alt="celsius_grey" class="logo_titre_preference" />';
          echo '<div class="texte_titre_preference">CELSIUS</div>';
        echo '</div>';

        // Affichage sur mobile
        echo '<div class="sous_titre_preference">Affichage sur mobile</div>';

        echo '<div class="zone_preference">';
          if ($preferences->getCelsius() == 'Y')
          {
            echo '<div id="bouton_celsius_yes" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="celsius_yes" type="radio" name="celsius_view" value="Y" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="celsius_yes" class="label_switch radioPreference">Oui</label>';
            echo '</div>';

            echo '<div id="bouton_celsius_no" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="celsius_no" type="radio" name="celsius_view" value="N" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="celsius_no" class="label_switch radioPreference">Non</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_celsius_yes" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="celsius_yes" type="radio" name="celsius_view" value="Y" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="celsius_yes" class="label_switch radioPreference">Oui</label>';
            echo '</div>';

            echo '<div id="bouton_celsius_no" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="celsius_no" type="radio" name="celsius_view" value="N" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="celsius_no" class="label_switch radioPreference">Non</label>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      /************/
      /*** Chat ***/
      /************/
      echo '<div class="zone_preferences">';
        // Titre
        echo '<div class="titre_preference">';
          echo '<img src="../../includes/icons/profil/chat_grey.png" alt="chat_grey" class="logo_titre_preference" />';
          echo '<div class="texte_titre_preference">INSIDE ROOM</div>';
        echo '</div>';

        // Affichage à la connexion
        echo '<div class="sous_titre_preference">Affichage à la connexion</div>';

        echo '<div class="zone_preference">';
          if ($preferences->getInit_chat() == 'Y')
          {
            echo '<div id="bouton_chat_yes" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="chat_yes" type="radio" name="inside_room_view" value="Y" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="chat_yes" class="label_switch radioPreference">Oui</label>';
            echo '</div>';

            echo '<div id="bouton_chat_no" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="chat_no" type="radio" name="inside_room_view" value="N" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="chat_no" class="label_switch radioPreference">Non</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_chat_yes" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="chat_yes" type="radio" name="inside_room_view" value="Y" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="chat_yes" class="label_switch radioPreference">Oui</label>';
            echo '</div>';

            echo '<div id="bouton_chat_no" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="chat_no" type="radio" name="inside_room_view" value="N" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="chat_no" class="label_switch radioPreference">Non</label>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      /*********************/
      /*** Notifications ***/
      /*********************/
      echo '<div class="zone_preferences">';
        // Titre
        echo '<div class="titre_preference">';
          echo '<img src="../../includes/icons/profil/notifications_grey.png" alt="notifications_grey" class="logo_titre_preference" />';
          echo '<div class="texte_titre_preference">NOTIFICATIONS</div>';
        echo '</div>';

        // Vue par défaut
        echo '<div class="sous_titre_preference">Vue par défaut</div>';

        echo '<div class="zone_preference">';
          if ($preferences->getView_notifications() == 'M')
          {
            echo '<div id="bouton_me" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="me" type="radio" name="notifications_view" value="M" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="me" class="label_switch radioPreference">Moi</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_me" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="me" type="radio" name="notifications_view" value="M" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="me" class="label_switch radioPreference">Moi</label>';
            echo '</div>';
          }

          if ($preferences->getView_notifications() == 'T')
          {
            echo '<div id="bouton_today" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="today" type="radio" name="notifications_view" value="T" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="today" class="label_switch radioPreference">Aujourd\'hui</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_today" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="today" type="radio" name="notifications_view" value="T" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="today" class="label_switch radioPreference">Aujourd\'hui</label>';
            echo '</div>';
          }

          if ($preferences->getView_notifications() == 'W')
          {
            echo '<div id="bouton_week" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="week" type="radio" name="notifications_view" value="W" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="week" class="label_switch radioPreference">7 jours</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_week" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="week" type="radio" name="notifications_view" value="W" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="week" class="label_switch radioPreference">7 jours</label>';
            echo '</div>';
          }

          if ($preferences->getView_notifications() == 'A')
          {
            echo '<div id="bouton_all_n" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="all_n" type="radio" name="notifications_view" value="A" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="all_n" class="label_switch radioPreference">Toutes</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_all_n" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="all_n" type="radio" name="notifications_view" value="A" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="all_n" class="label_switch radioPreference">Toutes</label>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      /*******************/
      /*** Movie House ***/
      /*******************/
      echo '<div class="zone_preferences">';
        // Titre
        echo '<div class="titre_preference">';
          echo '<img src="../../includes/icons/profil/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_preference" />';
          echo '<div class="texte_titre_preference">MOVIE HOUSE</div>';
        echo '</div>';

        // Vue par défaut
        echo '<div class="sous_titre_preference">Vue par défaut</div>';

        echo '<div class="zone_preference">';
          if ($preferences->getView_movie_house() == 'H')
          {
            echo '<div id="bouton_accueil" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="home" type="radio" name="movie_house_view" value="H" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="home" class="label_switch radioPreference">Accueil</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_accueil" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="home" type="radio" name="movie_house_view" value="H" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="home" class="label_switch radioPreference">Accueil</label>';
            echo '</div>';
          }

          if ($preferences->getView_movie_house() == 'C')
          {
            echo '<div id="bouton_cards" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="cards" type="radio" name="movie_house_view" value="C" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="cards" class="label_switch radioPreference">Fiches</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_cards" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="cards" type="radio" name="movie_house_view" value="C" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="cards" class="label_switch radioPreference">Fiches</label>';
            echo '</div>';
          }
        echo '</div>';

        // Catégories accueil
        echo '<div class="sous_titre_preference">Catégories accueil</div>';

        echo '<div class="zone_preference">';
          list($filmsSemaine, $filmsWaited, $filmsWayOut) = explode(';', $preferences->getCategories_movie_house());

          if ($filmsSemaine == 'Y')
          {
            echo '<div id="bouton_semaine" class="switch_preference_2 bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_semaine" type="checkbox" name="films_semaine" class="check_preference" checked />';
              echo '</div>';

              echo '<label for="films_semaine" class="label_switch checkPreference">Les films de la semaine</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_semaine" class="switch_preference_2">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_semaine" type="checkbox" name="films_semaine" class="check_preference" />';
              echo '</div>';

              echo '<label for="films_semaine" class="label_switch checkPreference">Les films de la semaine</label>';
            echo '</div>';
          }

          if ($filmsWaited == 'Y')
          {
            echo '<div id="bouton_waited" class="switch_preference_2 bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_waited" type="checkbox" name="films_waited" class="check_preference" checked />';
              echo '</div>';

              echo '<label for="films_waited" class="label_switch checkPreference">Les plus attendus</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_waited" class="switch_preference_2">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_waited" type="checkbox" name="films_waited" class="check_preference" />';
              echo '</div>';

              echo '<label for="films_waited" class="label_switch checkPreference">Les plus attendus</label>';
            echo '</div>';
          }

          if ($filmsWayOut == 'Y')
          {
            echo '<div id="bouton_way_out" class="switch_preference_2 bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_way_out" type="checkbox" name="films_way_out" class="check_preference" checked />';
              echo '</div>';

              echo '<label for="films_way_out" class="label_switch checkPreference">Les prochaines sorties</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_way_out" class="switch_preference_2">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="films_way_out" type="checkbox" name="films_way_out" class="check_preference" />';
              echo '</div>';

              echo '<label for="films_way_out" class="label_switch checkPreference">Les prochaines sorties</label>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      /***************/
      /*** #TheBox ***/
      /***************/
      echo '<div class="zone_preferences">';
        // Titre
        echo '<div class="titre_preference">';
          echo '<img src="../../includes/icons/profil/ideas_grey.png" alt="ideas_grey" class="logo_titre_preference" />';
          echo '<div class="texte_titre_preference">#THEBOX</div>';
        echo '</div>';

        // Vue par défaut
        echo '<div class="sous_titre_preference">Vue par défaut</div>';

        echo '<div class="zone_preference">';
          if ($preferences->getView_the_box() == 'A')
          {
            echo '<div id="bouton_all" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="all" type="radio" name="the_box_view" value="A" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="all" class="label_switch radioPreference">Toutes</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_all" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="all" type="radio" name="the_box_view" value="A" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="all" class="label_switch radioPreference">Toutes</label>';
            echo '</div>';
          }

          if ($preferences->getView_the_box() == 'P')
          {
            echo '<div id="bouton_inprogress" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="inprogress" type="radio" name="the_box_view" value="P" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="inprogress" class="label_switch radioPreference">En cours</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_inprogress" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="inprogress" type="radio" name="the_box_view" value="P" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="inprogress" class="label_switch radioPreference">En cours</label>';
            echo '</div>';
          }

          if ($preferences->getView_the_box() == 'M')
          {
            echo '<div id="bouton_mine" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="mine" type="radio" name="the_box_view" value="M" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="mine" class="label_switch radioPreference">En charge</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_mine" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="mine" type="radio" name="the_box_view" value="M" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="mine" class="label_switch radioPreference">En charge</label>';
            echo '</div>';
          }

          if ($preferences->getView_the_box() == 'D')
          {
            echo '<div id="bouton_done" class="switch_preference bouton_checked">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="done" type="radio" name="the_box_view" value="M" class="radio_preference" checked required />';
              echo '</div>';

              echo '<label for="done" class="label_switch radioPreference">Terminées & rejetées</label>';
            echo '</div>';
          }
          else
          {
            echo '<div id="bouton_done" class="switch_preference">';
              echo '<div class="zone_radio_preference">';
                echo '<input id="done" type="radio" name="the_box_view" value="M" class="radio_preference" required />';
              echo '</div>';

              echo '<label for="done" class="label_switch radioPreference">Terminées & rejetées</label>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      // Bouton validation
      echo '<input type="submit" name="saisie_preferences" value="Mettre à jour les préférences" class="bouton_validation_form" />';
    echo '</form>';
  echo '</div>';

  /***************/
  /* Utilisateur */
  /***************/
  echo '<div class="zone_preferences_profil preference_margin">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/profil/connexion_grey.png" alt="connexion_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Utilisateur</div>';
    echo '</div>';

    /********************/
    /*** Mot de passe ***/
    /********************/
    echo '<div class="zone_preferences">';
      // Titre
      echo '<div class="titre_preference">';
        echo '<div class="texte_titre_preference">CHANGER MOT DE PASSE</div>';
      echo '</div>';

      // Modification mot de passe
      echo '<form method="post" action="profil.php?action=doUpdatePassword" class="form_update_user">';
        echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="saisie_information_2" required />';
        echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="saisie_information_2" required />';
        echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="saisie_information_2" required />';

        echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_validation_form_2" />';
      echo '</form>';

      // Annulation demande
      if ($profil->getStatus() == 'P')
      {
        echo '<div class="message_form_preferences">Si vous avez fait la demande de réinitialisation de mot de passe mais que vous souhaitez l\'annuler car vous l\'avez retrouvé, cliquez sur ce bouton.</div>';

        echo '<form method="post" action="profil.php?action=cancelResetPassword" class="form_update_user">';
          echo '<input type="submit" name="cancel_reset" value="Annuler la demande" class="bouton_validation_form_2" />';
        echo '</form>';

        echo '<div class="message_form_preferences message_bold">Une demande est en cours.</div>';
      }
    echo '</div>';

    /***************************/
    /*** Changement d'équipe ***/
    /***************************/
    echo '<div class="zone_preferences">';
      // Titre
      echo '<div class="titre_preference">';
        echo '<div class="texte_titre_preference">CHANGER D\'ÉQUIPE</div>';
      echo '</div>';

      echo '<div class="message_form_preferences">Vous pouvez demander à changer d\'equipe à l\'administrateur ici. En cas de validation, les idées en charge non terminées seront réinitialisées et les recettes que vous n\'aurez pas encore réalisées seront supprimées.</div>';

      // Choix de l'équipe
      if ($profil->getStatus() == 'T')
        echo '<div class="message_form_preferences message_bold">Une demande est déjà en cours.</div>';
      else
      {
        echo '<form method="post" action="profil.php?action=doUpdateEquipe" class="form_update_user">';
          echo '<select name="equipe" class="select_form_update_team" required>';
            echo '<option value="" hidden>Choisir une équipe</option>';

            foreach ($listeEquipes as $equipe)
            {
              if ($equipe->getReference() == $profil->getTeam())
                echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
              else
                echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
            }

            echo '<option value="other">Créer une équipe</option>';
          echo '</select>';

          // Saisie "Autre"
          echo '<input type="text" name="autre_equipe" value="" placeholder="Nom de l\'équipe" id="autre_equipe" class="saisie_information_2" style="display: none;" />';

          // Bouton validation
          echo '<input type="submit" name="update_team" value="Changer d\'équipe" class="bouton_validation_form_2" />';
        echo '</form>';
      }
    echo '</div>';

    /**********************/
    /*** Désinscription ***/
    /**********************/
    echo '<div class="zone_preferences">';
      // Titre
      echo '<div class="titre_preference">';
        echo '<div class="texte_titre_preference">DÉSINSCRIPTION</div>';
      echo '</div>';

      echo '<div class="message_form_preferences">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l\'administrateur à l\'aide de ce bouton. Il validera votre choix après vérification.</div>';

      // Gestion désinscription
      if ($profil->getStatus() == 'D')
      {
        // Annulation
        echo '<form method="post" action="profil.php?action=cancelDesinscription" class="form_update_user">';
          echo '<input type="submit" name="cancel_desinscription" value="Annuler la demande" class="bouton_validation_form_2" />';
        echo '</form>';

        echo '<div class="message_form_preferences message_bold">Une demande est déjà en cours.</div>';
      }
      else
      {
        // Désinscription
        echo '<form method="post" action="profil.php?action=askDesinscription" class="form_update_user">';
          echo '<input type="submit" name="ask_desinscription" value="Désinscription" class="bouton_validation_form_2" />';
        echo '</form>';

        echo '<div class="message_form_preferences message_bold">Aucune demande en cours.</div>';
      }
    echo '</div>';
  echo '</div>';
?>
