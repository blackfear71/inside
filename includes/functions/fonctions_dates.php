<?php
  /* validateDate
     Fonction qui contrôle qu'une date est valide
  */
  function validateDate($date, $format = 'Y-m-d H:i:s')
  {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
  }

  /* formatDateForDisplay
     Les dates sont stockées au format AAAAMMJJ. Cette fonction renvoie la date au format
     JJ/MM/AAAA pour l'affichage. Si elle ne comporte pas 8 caractères, on renvoie l'argument
  */
  function formatDateForDisplay($date)
  {
    if (strlen($date) == 8)
      return substr($date, 6, 2) . '/' . substr($date, 4, 2) . '/' . substr($date, 0, 4);
    else
      return $date;
  }

  /* formatTimeForDisplay
     Les heures sont stockées au format HHMMSS. Cette fonction renvoie l'heure au format
     HH:MM:SS pour l'affichage. Si elle ne comporte pas 6 caractères, on renvoie l'argument
  */
  function formatTimeForDisplay($time)
  {
    if (strlen($time) == 6)
      return substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2);
    else
      return $time;
  }

  /* formatTimeForDisplayLight
     Les heures sont stockées au format HHMMSS. Cette fonction renvoie l'heure au format
     HH:MM pour l'affichage. Si elle ne comporte pas 6 caractères, on renvoie l'argument
  */
  function formatTimeForDisplayLight($time)
  {
    if (strlen($time) == 6 OR strlen($time) == 4)
      return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    else
      return $time;
  }

  /* formatMonthForDisplay
     On stocke le mois sur 2 caractères et le convertit en une chaîne de caractères pour obtenir le mot correspondant
  */
  function formatMonthForDisplay($month)
  {
    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    $formatMonth = $listeMois[$month];

    // Retour
    return $formatMonth;
  }

  /* formatMonthForDisplayLight
     On stocke le mois sur 2 caractères et le convertit en une chaîne de caractères pour obtenir le mot correspondant
  */
  function formatMonthForDisplayLight($month)
  {
    $listeMois = array('01' => 'JAN',
                       '02' => 'FÉV',
                       '03' => 'MAR',
                       '04' => 'AVR',
                       '05' => 'MAI',
                       '06' => 'JUIN',
                       '07' => 'JUIL',
                       '08' => 'AOÛT',
                       '09' => 'SEP',
                       '10' => 'OCT',
                       '11' => 'NOV',
                       '12' => 'DÉC'
                      );

    $formatMonth = $listeMois[$month];

    // Retour
    return $formatMonth;
  }

  /* formatWeekForDisplay
     Formate un numéro de semaine sans le 0 initial
  */
  function formatWeekForDisplay($week)
  {
    if (intval($week) < 10)
      $formattedWeek = str_replace('0', '', $week);
    else
      $formattedWeek = $week;

    // Retour
    return $formattedWeek;
  }

  /* formatDateForInsert
     Les dates sont stockées au format AAAAMMJJ. Cette fonction renvoie la date au format
     AAAAMMJJ pour l'insertion en base. Si elle ne comporte pas 8 caractères, on renvoie l'argument
  */
  function formatDateForInsert($date)
  {
    if (strlen($date) == 10)
      return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    else
      return $date;
  }

  /* formatWeekForInsert
     Formate un numéro de semaine avec le 0 initial
  */
  function formatWeekForInsert($week)
  {
    if (intval($week) < 10)
      $formattedWeek = '0' . $week;
    else
      $formattedWeek = $week;

    // Retour
    return $formattedWeek;
  }

  /* calcDureeTrt
    <=> calcule la durée en heures/minutes/secondes pour un traitement
    Retourne le temps sous forme d'un tableau
  */
  function calcDureeTrt($heureDeb, $heureFin)
  {
    if (strlen($heureDeb) == 6 AND strlen($heureFin) == 6)
    {
      // Calcul (en secondes)
      $heureFinSecondes = substr($heureFin, 0, 2) * 60 * 60 + substr($heureFin, 2, 2) * 60 + substr($heureFin, 4, 2);
      $heureDebSecondes = substr($heureDeb, 0, 2) * 60 * 60 + substr($heureDeb, 2, 2) * 60 + substr($heureDeb, 4, 2);
      $dureeSecondes    = $heureFinSecondes - $heureDebSecondes;

      // Conversion
      $total      = $dureeSecondes;
      $heures     = intval(abs($total / 3600));
      $total      = $total - ($heures * 3600);
      $minutes    = intval(abs($total / 60));
      $total      = $total - ($minutes * 60);
      $secondes   = $total;

      $dureeFormat = array('heures'   => $heures,
                           'minutes'  => $minutes,
                           'secondes' => $secondes
                          );

      // Retour
      return $dureeFormat;
    }
  }

  /* ecartDatesEvent
    <=> calcule la durée en jours entre 2 dates pour une mission
  */
  function ecartDatesEvent($dateDeb, $dateFin)
  {
    $ecart = 0;

    // Calcul
    $date1 = strtotime($dateDeb);
    $date2 = strtotime($dateFin);

    $calcul = abs($date2 - $date1);

    // Formatage
    $ecart = ($calcul / (60 * 60 * 24)) + 1;

    // Retour
    return $ecart;
  }
?>
