<?php
  if (!isset($listeIdeas) OR empty($listeIdeas))
  {
    if ($_GET['view'] == "done")
      echo '<p class="no_ideas">Aucune idée terminée</p>';
    elseif ($_GET['view'] == "inprogress")
      echo '<p class="no_ideas">Aucune idée en cours</p>';
    elseif ($_GET['view'] == "mine")
      echo '<p class="no_ideas">Aucune idée en charge</p>';
    else
      echo '<p class="no_ideas">Aucune idée proposée</p>';
  }
  else
  {
    foreach ($listeIdeas as $ligne)
    {
      // Libellés états
      switch ($ligne['status'])
      {
        // Ouverte
        case "O":
          $etat_idee = '<span style="color: red;">Ouverte</span>';
          break;

        // Prise en charge
        case "C":
          $etat_idee = '<span style="color: red;">Prise en charge</span>';
          break;

        // En progrès
        case "P":
          $etat_idee = '<span style="color: red;">En cours de développement</span>';
          break;

        // Terminée
        case "D":
          $etat_idee = '<span style="color: green;">Terminée</span>';
          break;

        // Rejetée
        case "R":
          $etat_idee = '<span style="color: red;">Rejetée</span>';
          break;

        default:
          break;
      }

      // Formatage date
      $date_idee = formatDateForDisplay($ligne['date']);

      // Affichage des idées
      echo '<table class="table_ideas">';
        echo '<tr id="' . $ligne['id'] . '">';
          // Titre idée
          echo '<td class="td_ideas_title">';
            echo 'Idée';
          echo '</td>';
          echo '<td class="td_ideas_content">';
            echo $ligne['subject'];
          echo '</td>';

          // Date
          echo '<td class="td_ideas_title">';
            echo 'Date';
          echo '</td>';
          echo '<td class="td_ideas_content">';
            echo $date_idee;
          echo '</td>';

          // Boutons de prise en charge (disponibles si personne n'a pris en charge OU si le développeur est sur la page OU si l'idée est terminée / rejetée)
          if ( empty($ligne['developper'])
          OR (!empty($ligne['developper']) AND $_SESSION['identifiant'] == $ligne['developper'])
          OR  $ligne['status'] == "D"
          OR  $ligne['status'] == "R")
          {
            echo '<td rowspan="100%" class="td_ideas_actions">';
              echo '<form method="post" action="../controleur/controleur_ideas.php?view=' . $_GET['view'] . '&action=doChangerStatut&id=' . $ligne['id'] . '">';
                switch ($ligne['status'])
                {
                  // Ouverte
                  case "O":
                    echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="button_idea" />';
                    break;

                  // Prise en charge
                  case "C":
                    echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
                    echo '<input type="submit" name="developp" value="Développer" title="Commencer les développements" class="button_idea" />';
                    echo '<input type="submit" name="reject" value="Rejeter" title="Annuler l\'idée" class="button_idea" />';
                    break;

                  // En progrès
                  case "P":
                    echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
                    echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="button_idea" />';
                    echo '<input type="submit" name="end" value="Terminer" title="Finaliser l\'idée" class="button_idea" />';
                    break;

                  // Terminée
                  case "D":
                    echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
                    break;

                  // Rejetée
                  case "R":
                    echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
                    break;

                  default:
                    break;
                }
              echo '</form>';
            echo '</td>';
          }
        echo '</tr>';

        // Proposé par
        echo '<tr>';
          echo '<td class="td_ideas_title">';
            echo 'Proposée par';
          echo '</td>';
          echo '<td class="td_ideas_content">';
            echo $ligne['name_a'];
          echo '</td>';

          // Statut & développeur
          echo '<td class="td_ideas_title">';
            echo 'Statut';
          echo '</td>';
          echo '<td class="td_ideas_content">';
            // Statut
            echo $etat_idee;

            // Développeur
            if (!empty($ligne['name_d']))
              echo ' par ' . $ligne['name_d'];
          echo '</td>';
        echo '</tr>';

        // Description idée
        echo '<tr class="tr_ideas_idea">';
          echo '<td colspan="4">';
            echo '<p>' . $ligne['content'] . '</p>';
          echo '</td>';
        echo '</tr>';
      echo '</table>';
    }
  }
?>
