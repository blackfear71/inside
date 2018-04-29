<?php
  if (!isset($listeBugs) OR empty($listeBugs))
  {
    if ($_GET['view'] == "resolved")
      echo '<p class="no_bugs">Aucun(e) bug/évolution résolu(e)</p>';
    else
      echo '<p class="no_bugs">Aucun(e) bug/évolution non résolu(e)</p>';
  }
  else
  {
    foreach ($listeBugs as $ligne)
    {
      // Libellés type
      switch ($ligne->getType())
      {
        // Bug
        case "B":
          $type_bug = 'Bug';
          $lib_author = 'Remonté par';
          break;

        // Evolution
        case "E":
          $type_bug = 'Evolution';
          $lib_author = 'Proposée par';
          break;

        default:
          break;
      }

      // Libellés états
      switch ($ligne->getResolved())
      {
        // Résolu
        case "Y":
          $etat_bug = '<span style="color: green;">Résolu</span>';
          break;

        // En cours
        case "N":
          $etat_bug = '<span style="color: red;">En cours</span>';
          break;

        default:
          break;
      }

      // Formatage date
      $date_bug = formatDateForDisplay($ligne->getDate());

      // Affichage des idées
      echo '<table class="table_bugs">';
        echo '<tr>';
          // Titre idée
          echo '<td class="td_bugs_title">';
            echo $type_bug;
          echo '</td>';
          echo '<td class="td_bugs_content">';
            echo $ligne->getSubject();
          echo '</td>';

          // Date
          echo '<td class="td_bugs_title">';
            echo 'Date';
          echo '</td>';
          echo '<td class="td_bugs_content">';
            echo $date_bug;
          echo '</td>';
        echo '</tr>';

        // Proposé par
        echo '<tr>';
          echo '<td class="td_bugs_title">';
            echo $lib_author;
          echo '</td>';
          echo '<td class="td_bugs_content">';
            echo $ligne->getName_a();
          echo '</td>';

          // Statut & développeur
          echo '<td class="td_bugs_title">';
            echo 'Statut';
          echo '</td>';
          echo '<td class="td_bugs_content">';
            // Statut
            echo $etat_bug;
          echo '</td>';
        echo '</tr>';

        // Description idée
        echo '<tr class="tr_bugs_bug">';
          echo '<td colspan="4">';
            echo '<p>' . nl2br($ligne->getContent()) . '</p>';
          echo '</td>';
        echo '</tr>';
      echo '</table>';
    }
  }
?>
