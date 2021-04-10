<?php
  include_once('../../includes/classes/changelog.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    // Initialisations
    $anneeExistante = false;

    // Vérification année présente en base
    if (isset($year) AND is_numeric($year))
      $anneeExistante = physiqueAnneeExistante($year);

    // Retour
    return $anneeExistante;
  }

  // METIER : Lecture années distinctes pour les onglets
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    // Récupération de la liste des années existantes
    $onglets = physiqueOnglets();

    // Retour
    return $onglets;
  }

  // METIER : Récupération des catégories pour les logs
  // RETOUR : Liste des catégories
  function getCategories()
  {
    // Tableau des catégories
    $listCategories = array('general'          => 'GÉNÉRAL',
                            'portail'          => 'PORTAIL',
                            'movie_house'      => 'MOVIE HOUSE',
                            'food_advisor'     => 'LES ENFANTS ! À TABLE !',
                            'cooking_box'      => 'COOKING BOX',
                            'expense_center'   => 'EXPENSE CENTER',
                            'collector'        => 'COLLECTOR ROOM',
                            'calendars'        => 'CALENDARS',
                            'petits_pedestres' => 'LES PETITS PÉDESTRES',
                            'missions'         => 'MISSIONS : INSIDER',
                            'notifications'    => 'NOTIFICATIONS',
                            'search'           => 'RECHERCHE',
                            'profile'          => 'PROFIL',
                            'chat'             => 'INSIDE ROOM',
                            'change_log'       => 'JOURNAL DES MODIFICATIONS',
                            'bugs'             => 'DEMANDES D\'ÉVOLUTION',
                            'ideas'            => '#THEBOX',
                            'admin'            => 'ADMINISTRATION',
                            'cron'             => 'TÂCHES CRON',
                            'technical'        => 'TECHNIQUE',
                            'other'            => 'AUTRE'
                           );

    // Retour
    return $listCategories;
  }

  // METIER : Lecture de la liste des logs
  // RETOUR : Liste des logs
  function getLogs($year, $categories)
  {
    // Récupération de la liste de logs de l'année
    $listeLogs = physiqueChangelog($year);

    // Traitement des logs
    if (!empty($listeLogs))
    {
      foreach ($listeLogs as $log)
      {
        // Extraction des logs
        $extractLogs = explode(';', $log->getLogs());

        // Tri des logs par catégories
        $sortedLogs = array();

        foreach ($categories as $categorie => $labelCategorie)
        {
          foreach ($extractLogs as $keyExtract => $extractedLog)
          {
            if (!empty($extractedLog))
            {
              list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);

              if ($categoryExtracted == $categorie)
              {
                if (!isset($sortedLogs[$categorie]))
                  $sortedLogs[$categorie] = array();

                array_push($sortedLogs[$categorie], $entryExtracted);
                unset($extractLogs[$keyExtract]);
              }
            }
            else
              unset($extractLogs[$keyExtract]);
          }
        }

        // Sécurité si besoin (logs restants sans catégorie ajoutés à une catégorie "Autre")
        if (!empty($extractLogs))
        {
          if (!isset($sortedLogs['other']))
            $sortedLogs['other'] = array();

          foreach ($extractLogs as $keyExtract => $extractedLog)
          {
            if (!empty($extractedLog))
            {
              list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);
              array_push($sortedLogs['other'], $entryExtracted);
            }
          }
        }

        // Remplacement des logs récupérés par les logs triés
        $log->setLogs($sortedLogs);
      }
    }

    // Retour
    return $listeLogs;
  }
?>
