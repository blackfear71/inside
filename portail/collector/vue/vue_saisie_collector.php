<?php
  /**********************************/
  /* Zone de saisie de phrase culte */
  /**********************************/
  echo '<div id="zone_add_collector" class="fond_saisie_collector">';
    echo '<div class="zone_saisie_collector">';
      // Titre
      echo '<div class="titre_saisie_collector">Ajouter une phrase culte</div>';

      // Bouton fermeture
      echo '<a id="fermerCollector" class="close_index"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie phrase culte
      echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" class="form_saisie_collector">';
        // Type de saisie
        echo '<input type="hidden" name="type_collector" value="T" />';

        // Saisie speaker
        if (!empty($_SESSION['save']['other_speaker']))
          echo '<select name="speaker" id="speaker" class="saisie_speaker speaker_autre" required>';
        else
          echo '<select name="speaker" id="speaker" class="saisie_speaker" required>';
            echo '<option value="" hidden>Choisissez...</option>';

            foreach ($listeUsers as $user)
            {
              if ($user->getIdentifiant() == $_SESSION['save']['speaker'])
                echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
              else
                echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
            }

            if (!empty($_SESSION['save']['other_speaker']))
              echo '<option value="other" selected>Autre</option>';
            else
              echo '<option value="other">Autre</option>';
          echo '</select>';

        // Saisie "Autre"
        if (!empty($_SESSION['save']['other_speaker']))
          echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" />';
        else
          echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" style="display: none;" />';

        // Date
        echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker_collector" class="saisie_date_collector" required />';

        // Bouton
        echo '<input type="submit" name="insert_collector" value="Ajouter" class="saisie_bouton" />';

        // Saisie phrase
        echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['save']['collector'] . '</textarea>';

        // Saisie contexte
        echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
