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

    return $formatMonth;
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

  /* calcDureeTrt
    <=> calcule la durée en heures/minutes/secondes pour un traitement
    Retourne le temps sous forme d'un tableau
  */
  function calcDureeTrt($hdeb, $hfin)
  {
    if (strlen($hdeb) == 6 AND strlen($hfin) == 6)
    {
      // Calcul (en secondes)
      $hfin_sec  = substr($hfin, 0, 2)*60*60 + substr($hfin, 2, 2)*60 + substr($hfin, 4, 2);
      $hdeb_sec  = substr($hdeb, 0, 2)*60*60 + substr($hdeb, 2, 2)*60 + substr($hdeb, 4, 2);
      $duree_sec = $hfin - $hdeb;

      // Conversion
      $total      = $duree_sec;
      $heures     = intval(abs($total / 3600));
      $total      = $total - ($heures * 3600);
      $minutes    = intval(abs($total / 60));
      $total      = $total - ($minutes * 60);
      $secondes   = $total;

      $duree_format = array('heures'   => $heures,
                            'minutes'  => $minutes,
                            'secondes' => $secondes
                          );

      return $duree_format;
    }
  }

  /* ecartDatesEvent
    <=> calcule la durée en jours entre 2 dates pour une mission
  */
  function ecartDatesEvent($date_deb, $date_fin)
  {
    $ecart = 0;

    // Calcul
    $date1 = strtotime($date_deb);
    $date2 = strtotime($date_fin);

    $calcul = abs($date2 - $date1);

    // Formatage
    $ecart = ($calcul / (60*60*24)) + 1;

    return $ecart;
  }
?>
