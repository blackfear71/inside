<?php
	// Extraction de la base de données
	$mysqlUserName = 'root';
	$mysqlPassword = 'root';
	$mysqlHostName = 'localhost';
	$dbName        = 'inside';
	$backupName    = 'mybackup.sql';

	// On peut ajouter un 5ème paramètre sous forme de tableau (array) pour sélectionner des tables spécifiques. Exemple : array('myTable1', 'myTable2', 'myTable3') pour plusieurs tables
	// $tables = array('myTable1', 'myTable2', 'myTable3');

	// Appel fonction exportation
  exportDatabase($mysqlHostName, $mysqlUserName, $mysqlPassword, $dbName, $tables = false, $backupName = false);

	// Fonction exportation
  function exportDatabase($host, $user, $password, $dbName, $tables = false, $backupName = false)
  {
    $mysqli = new mysqli($host, $user, $password, $dbName);
    $mysqli->select_db($dbName);
    $mysqli->query('SET NAMES "utf8"');

    $queryTables = $mysqli->query('SHOW TABLES');

    while($row = $queryTables->fetch_row())
    {
      $targetTables[] = $row[0];
    }

    if ($tables !== false)
      $targetTables = array_intersect($targetTables, $tables);

    foreach ($targetTables as $table)
    {
      $result       = $mysqli->query('SELECT * FROM ' . $table);
      $fieldsAmount = $result->field_count;
      $rowsNum      = $mysqli->affected_rows;
      $res          = $mysqli->query('SHOW CREATE TABLE ' . $table);
      $tableMLine   = $res->fetch_row();
      $content      = (!isset($content) ? '' : $content) . "\n\n" . $tableMLine[1] . ";\n\n";

      for ($i = 0, $stCounter = 0; $i < $fieldsAmount; $i++, $stCounter = 0)
      {
        while($row = $result->fetch_row())
        {
					// Quand débuté (et après chaque 100 cycles de commande)
          if ($stCounter % 100 == 0 || $stCounter == 0)
						$content .= "\nINSERT INTO " . $table . ' VALUES';

          $content .= "\n(";

					for ($j = 0; $j < $fieldsAmount; $j++)
					{
					  $row[$j] = str_replace("\n","\\n", addslashes($row[$j]));

					  if (isset($row[$j]))
					    $content .= '"' . $row[$j] . '"' ;
					  else
					    $content .= '""';

					  if ($j < ($fieldsAmount - 1))
					    $content .= ',';
					}

					$content .= ')';

          // Après chaque 100 cycles de commande (or à la dernière ligne)
          if ((($stCounter + 1) % 100 == 0 && $stCounter != 0) || $stCounter + 1 == $rowsNum)
            $content .= ';';
          else
            $content .= ',';

          $stCounter = $stCounter + 1;
        }
      }

			$content .= "\n\n\n";
    }

		// Génération nom du fichier
    $backupName = $backupName ? $backupName : $dbName . '_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.sql';

    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . $backupName . '"');

		// Retour
    echo $content;

		exit;
  }
?>
