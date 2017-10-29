<!-- Bloc utilisateur -->
<div class="zone_profil_utilisateur">
  <!-- Affichage pseudo -->
  <div class="zone_profil_utilisateur_titre">
    <?php
      echo '<img src="icons/profile.png" alt="profile" class="icone_profil" />' . $profil->getPseudo();
    ?>
  </div>

  <!-- Tableau modification pseudo & avatar -->
  <table class="zone_profil_utilisateur_table">
    <tr>
      <!-- Saisie pseudo -->
      <td class="zone_profil_utilisateur_pseudo">
        <?php
          echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangePseudo" class="zone_profil_utilisateur_pseudo_form">';
            echo '<input type="text" name="new_pseudo" placeholder="Nouveau pseudo" maxlength="255" class="monoligne_profil" required />';
            echo '<input type="submit" name="saisie_pseudo" value="Valider" class="bouton_profil" />';
          echo '</form>';
        ?>
      </td>

      <!-- Saisie avatar -->
      <td class="zone_profil_utilisateur_avatar">
        <div class="zone_avatar">
          <?php
            echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangeAvatar" enctype="multipart/form-data" runat="server">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

              echo '<span class="zone_parcourir_avatar">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_avatar" onchange="loadFile(event)" required /></span>';

              echo '<div class="mask_avatar">';
                echo '<img id="output" class="avatar_profil" />';
              echo '</div>';

              echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" class="bouton_profil" />';
            echo '</form>';
          ?>
        </div>
      </td>

      <!-- Suppression avatar -->
      <td class="zone_profil_utilisateur_suppr">
        <?php
          // Affichage avatar
          if (!empty($profil->getAvatar()))
          {
            echo '<div class="zone_profil_utilisateur_suppr_mask">';
              echo '<img src="avatars/' . $profil->getAvatar() . '" alt="avatar" title="' . $profil->getPseudo() . '" class="zone_profil_utilisateur_suppr_avatar" />';
            echo '</div>';

            echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doSupprimerAvatar" enctype="multipart/form-data" runat="server">';
              echo '<input type="submit" name="delete_avatar" value="Supprimer l\'avatar" class="bouton_profil" />';
            echo '</form>';
          }
          else
          {
            echo '<div class="zone_profil_utilisateur_suppr_mask">';
              echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $profil->getPseudo() . '" class="zone_profil_utilisateur_suppr_avatar" />';
            echo '</div>';
          }
        ?>
      </td>
    </tr>
  </table>
</div>

<!-- Bloc contributions -->
<div class="zone_profil_generique">
  <!-- Titre -->
  <div class="zone_profil_utilisateur_titre">
    <img src="icons/stats.png" alt="stats" class="icone_profil" />Mes contributions
  </div>

  <!-- Tableau contributions -->
  <div class="zone_profil_preferences_table">
    <!-- Contributions Movie House -->
    <div class="zone_profil_contribution">
      <div class="titre_preference">
        MOVIE HOUSE
      </div>

      <!-- Nombre de films ajoutés Movie House -->
      <div class="sous_titre_preference">
        Nombre de films ajoutés
      </div>

      <div class="contenu_contribution">
        <?php
          echo $statistiques->getNb_films_ajoutes();
        ?>
      </div>

      <!-- Nombre de commentaires Movie House -->
      <div class="sous_titre_preference">
        Nombre de commentaires
      </div>

      <div class="contenu_contribution_fin">
        <?php
          echo $statistiques->getNb_comments();
        ?>
      </div>
    </div>

    <!-- Contributions Expense Center -->
    <div class="zone_profil_contribution">
      <div class="titre_preference">
        EXPENSE CENTER
      </div>

      <!-- Solde des dépenses -->
      <div class="sous_titre_preference">
        Solde
      </div>

      <div class="contenu_contribution_fin">
        <?php
          echo formatBilanForDisplay($profil->getExpenses());
        ?>
      </div>
    </div>

    <!-- Contributions Collector Room -->
    <div class="zone_profil_contribution">
      <div class="titre_preference">
        COLLECTOR ROOM
      </div>

      <!-- Nombre d'idées soumises -->
      <div class="sous_titre_preference">
        Nombre de phrases cultes soumises
      </div>

      <div class="contenu_contribution_fin">
        <?php
          echo $statistiques->getNb_collectors();
        ?>
      </div>
    </div>

    <!-- Contributions #TheBox -->
    <div class="zone_profil_contribution">
      <div class="titre_preference">
        #THEBOX
      </div>

      <!-- Nombre d'idées soumises -->
      <div class="sous_titre_preference">
        Nombre d'idées soumises
      </div>

      <div class="contenu_contribution_fin">
        <?php
          echo $statistiques->getNb_ideas();
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Bloc préférences -->
<div class="zone_profil_generique">
  <!-- Titre -->
  <div class="zone_profil_utilisateur_titre">
    <img src="icons/settings.png" alt="settings" class="icone_profil" />Préférences
  </div>

  <!-- Tableau modification préférences -->
  <div class="zone_profil_preferences_table">
    <?php
      echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&view=settings&action=doModifierPreferences">';
    ?>
      <!-- Préférences Movie House -->
      <div class="zone_profil_contribution">
        <div class="titre_preference">
          MOVIE HOUSE
        </div>

        <div class="sous_titre_preference">
          Choix de la vue par défaut
        </div>

        <div class="contenu_preference">
          <?php
            $checked_s = false;
            $checked_d = false;
            $checked_h = false;

            switch ($preferences->getView_movie_house())
            {
              case "S":
                $checked_s = true;
                break;

              case "D":
                $checked_d = true;
                break;

              case "H":
              default:
                $checked_h = true;
                break;
            }
          ?>

          <input id="accueil" type="radio" name="movie_house_view" value="H" class="bouton_preference" <?php if($checked_h){echo 'checked';} ?> required />
          <label for="accueil" class="label_preference">Accueil</label>
          <br />
          <input id="synthese" type="radio" name="movie_house_view" value="S" class="bouton_preference" <?php if($checked_s){echo 'checked';} ?> required />
          <label for="synthese" class="label_preference">Synthèse</label>
          <br />
          <input id="detail" type="radio" name="movie_house_view" value="D" class="bouton_preference" <?php if($checked_d){echo 'checked';} ?> required />
          <label for="detail" class="label_preference">Détails</label>
        </div>

        <div class="sous_titre_preference">
          Catégories à afficher sur la page d'accueil
        </div>

        <div class="contenu_preference">
          <?php
            $categories_home = $preferences->getCategories_home();
            $films_waited    = $categories_home[0];
            $films_way_out   = $categories_home[1];
          ?>

          <input id="films_waited" type="checkbox" name="films_waited" class="bouton_preference" <?php if($films_waited == "Y"){echo 'checked';} ?> />
          <label for="films_waited" class="label_preference_3">Les plus attendus</label>
          <br />
          <input id="films_way_out" type="checkbox" name="films_way_out" class="bouton_preference" <?php if($films_way_out == "Y"){echo 'checked';} ?> />
          <label for="films_way_out" class="label_preference_3">Les prochaines sorties</label>
        </div>

        <div class="sous_titre_preference">
          Affichage de la date du jour dans la liste des films
        </div>

        <div class="contenu_preference" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
          <?php
            switch ($preferences->getToday_movie_house())
            {
              case "Y":
                echo '<input id="afficher" type="checkbox" name="affiche_date" class="bouton_preference" checked />';
                echo '<label for="afficher" class="label_preference">Afficher</label>';
                break;

              case "N":
              default:
                echo '<input id="afficher" type="checkbox" name="affiche_date" class="bouton_preference" />';
                echo '<label for="afficher" class="label_preference">Afficher</label>';
                break;
            }
          ?>
        </div>
      </div>

      <!-- Préférences #TheBox -->
      <div class="zone_profil_contribution">
        <div class="titre_preference">
          #THEBOX
        </div>

        <div class="sous_titre_preference">
          Choix de la vue par défaut
        </div>

        <div class="contenu_preference" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
          <?php
            $checked_p = false;
            $checked_m = false;
            $checked_d = false;
            $checked_a = false;

            switch ($preferences->getView_the_box())
            {
              case "P":
                $checked_p = true;
                break;

              case "M":
                $checked_m = true;
                break;

              case "D":
                $checked_d = true;
                break;

              case "A":
              default:
                $checked_a = true;
                break;
            }
          ?>

          <input id="all" type= "radio" name="the_box_view" value="A" class="bouton_preference" <?php if($checked_a){echo 'checked';} ?> required />
          <label for="all" class="label_preference_2">Toutes</label>
          <br />
          <input id="inprogress" type= "radio" name="the_box_view" value="P" class="bouton_preference" <?php if($checked_p){echo 'checked';} ?> required />
          <label for="inprogress" class="label_preference_2">En cours</label>
          <br />
          <input id="mine" type= "radio" name="the_box_view" value="M" class="bouton_preference" <?php if($checked_m){echo 'checked';} ?> required />
          <label for="mine" class="label_preference_2">En charge</label>
          <br />
          <input id="done" type= "radio" name="the_box_view" value="D" class="bouton_preference" <?php if($checked_d){echo 'checked';} ?> required />
          <label for="done" class="label_preference_2">Terminées<br />& rejetées</label>
        </div>
      </div>

      <!-- Préférences Notifications -->
      <div class="zone_profil_contribution">
        <div class="titre_preference">
          NOTIFICATIONS
        </div>

        <div class="sous_titre_preference">
          Choix de la vue par défaut
        </div>

        <div class="contenu_preference" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
          <?php
            $checked_m = false;
            $checked_t = false;
            $checked_w = false;
            $checked_a = false;

            switch ($preferences->getView_notifications())
            {
              case "M":
                $checked_m = true;
                break;

              case "T":
                $checked_t = true;
                break;

              case "W":
                $checked_w = true;
                break;

              case "A":
              default:
                $checked_a = true;
                break;
            }
          ?>

          <input id="me" type= "radio" name="notifications_view" value="M" class="bouton_preference" <?php if($checked_m){echo 'checked';} ?> required />
          <label for="me" class="label_preference_2">Moi</label>
          <br />
          <input id="today" type= "radio" name="notifications_view" value="T" class="bouton_preference" <?php if($checked_t){echo 'checked';} ?> required />
          <label for="today" class="label_preference_2">Aujourd'hui</label>
          <br />
          <input id="week" type= "radio" name="notifications_view" value="W" class="bouton_preference" <?php if($checked_w){echo 'checked';} ?> required />
          <label for="week" class="label_preference_2">7 jours</label>
          <br />
          <input id="all_n" type= "radio" name="notifications_view" value="A" class="bouton_preference" <?php if($checked_a){echo 'checked';} ?> required />
          <label for="all_n" class="label_preference_2">Toutes</label>
        </div>
      </div>

      <input type="submit" name="saisie_preferences" value="Mettre à jour" class="bouton_profil" />
    </form>
  </div>
</div>

<!-- Bloc mailing -->
<div class="zone_profil_generique">
  <!-- Titre -->
  <div class="zone_profil_utilisateur_titre">
    <img src="icons/mailing.png" alt="mailing" class="icone_profil" />Mailing
  </div>

  <!-- Tableau modification mailing -->
  <table class="zone_profil_utilisateur_table">
    <tr>
      <td class="zone_profil_utilisateur_mdp">
        <!-- Affichage adresse mail courante -->
        <div class="message_profil">
          <?php
            if (!empty($profil->getEmail()))
              echo 'L\'adresse mail actuellement utilisée est : <strong>' . $profil->getEmail() . '</strong>';
            else
              echo 'Pas d\'adresse mail actuellement renseignée.';
          ?>
        </div>

        <?php
          // Saisie adresse mail
          echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doUpdateMail" class="zone_profil_utilisateur_pseudo_form">';
            echo '<input type="email" name="mail" placeholder="Adresse mail" maxlength="255" class="monoligne_profil" required />';
            echo '<input type="submit" name="saisie_mail" value="Mettre à jour" class="bouton_profil" />';
          echo '</form>';

          // Suppression adresse mail
          echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doUpdateMail" class="zone_profil_utilisateur_pseudo_form">';
            echo '<input type="submit" name="suppression_mail" value="Supprimer" class="bouton_profil" />';
          echo '</form>';
        ?>
      </td>
    </tr>
  </table>
</div>

<!-- Bloc utilisateur -->
<div class="zone_profil_generique">
  <!-- Titre -->
  <div class="zone_profil_utilisateur_titre">
    <img src="icons/connexion.png" alt="connexion" class="icone_profil" />Utilisateur
  </div>

  <!-- Tableau modification mot de passe & désinscription -->
  <table class="zone_profil_utilisateur_table">
    <tr>
      <!-- Saisie mot de passe -->
      <td class="zone_profil_utilisateur_mdp">
        <?php
          echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doChangeMdp" class="zone_profil_utilisateur_pseudo_form">';
            echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_profil" required />';
            echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_profil" required />';
            echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_profil" required />';
            echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_profil" />';
          echo '</form>';
        ?>
      </td>

      <!-- Demande désinscription -->
      <td class="zone_profil_utilisateur_desinscription">
        <div class="message_profil">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l'administrateur à l'aide de ce bouton. Il validera votre choix après vérification.</div>

        <?php
          echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=askDesinscription" class="form_desinscription">';
            echo '<input type="submit" name="ask_desinscription" value="Demander la désinscription" class="bouton_profil" />';
          echo '</form>';

          if ($profil->getReset() == "D")
            echo '<div class="message_profil_2">Une demande est déjà en cours.</div>';
          else
            echo '<div class="message_profil_2">Aucune demande en cours.</div>';
        ?>
      </td>
    </tr>
  </table>
</div>
