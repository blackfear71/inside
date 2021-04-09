<?php
  // CONTROLE : Saisie non vide
  // RETOUR : Booléen
  function controleCalendar($nomFichier, $type)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (empty($nomFichier))
    {
      if ($type == 'annexe')
        $_SESSION['alerts']['empty_annexe']   = true;
      else
        $_SESSION['alerts']['empty_calendar'] = true;

      $control_ok                             = false;
    }

    // Retour
    return $control_ok;
  }
?>
