<?php
  /**********************************/
  /* Zone de saisie de phrase culte */
  /**********************************/
  echo '<div id="zone_add_collector" class="fond_saisie_collector">';
    echo '<div class="zone_saisie_collector">';
      // Titre
      echo '<div class="titre_saisie_collector">Ajouter une phrase culte</div>';

      // Bouton fermeture
      echo '<a onclick="afficherMasquer(\'zone_add_collector\');" class="close_index"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie phrase culte
      echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" class="form_saisie_collector">';
        echo '<table class="table_saisie_collector">';
          // Type de saisie
          echo '<input type="hidden" name="type_collector" value="T" />';

          // Tableau de saisie
          echo '<tr>';
            // Saisie speaker
            if (!empty($_SESSION['save']['other_speaker']))
              echo '<td class="td_saisie_collector_user" id="td_other" style="width: 20%;">';
            else
              echo '<td class="td_saisie_collector_user" id="td_other">';
                echo '<select name="speaker" id="speaker" onchange="afficherOther(\'td_other\', \'speaker\', \'other_speaker\', \'other_name\');" class="saisie_speaker" required>';
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
            echo '</td>';

            // Saisie "Autre"
            if (!empty($_SESSION['save']['other_speaker']))
              echo '<td class="td_saisie_collector_name" id="other_speaker">';
            else
              echo '<td class="td_saisie_collector_name" id="other_speaker" style="display: none;">';
                echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" />';
            echo '</td>';

            // Saisie date
            echo '<td class="td_saisie_collector_date">';
              echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker1" class="saisie_date_collector" required />';
            echo '</td>';

            // Bouton
            echo '<td class="td_saisie_collector_add">';
              echo '<input type="submit" name="insert_collector" value="Ajouter" class="saisie_bouton" />';
            echo '</td>';
          echo '</tr>';

          echo '<tr>';
            // Saisie phrase
            echo '<td colspan="4" class="td_saisie_collector">';
              echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['save']['collector'] . '</textarea>';
            echo '</td>';
          echo '</tr>';

          echo '<tr>';
            // Saisie contexte
            echo '<td colspan="4" class="td_saisie_collector_cont">';
              echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
            echo '</td>';
          echo '</tr>';
        echo '</table>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
