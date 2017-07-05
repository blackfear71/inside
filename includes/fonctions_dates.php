<?php
/* isLastDayOfYearWednesday
   Fonction pour déterminer si le 31 décembre de l'année en cours est un mercredi
   afin de gérer le cas où on afficherait "N.C." pour 31/12 qui est effectivement une date de sortie 
*/ 
function isLastDayOfYearWednesday($year){
    $lastDay = strtotime($year . '-12-31');
    
    if(date('D', $lastDay) === 'Wed') 
        return true;
    else
        return false;

}

/* formatDateForDisplay
   Les dates sont stockées au format MMJJAAAA. Cette fonction renvoie la date au format
   JJ/MM/AAAA pour l'affichage. Si elle ne comporte pas 8 caractères, on renvoie l'argument
*/
function formatDateForDisplay($date){
    if (strlen($date) == 8)
        return substr($date, 2, 2) . '/' . substr($date, 0, 2) . '/' . substr($date, 4, 4);
    else
        return $date;
}

/* isBlankDate
    <=> si on affiche "N.C." dans le tableau pour une date inconnue (laissée à blanc)
   Retourne vrai si la date est 31/12 et que la date n'est pas un mercredi
   ou si la date est 30/12 et que le dernier jour de l'année est un mercredi
   Retourne faux sinon
*/
function isBlankDate($date){
    $isLastDayWednesday = isLastDayOfYearWednesday(date('Y'));
    $thirtiethOfDecember = '1230' . date ('Y');
    $thirtyFirstOfDecember = '1231' . date ('Y');

    if (($date == $thirtyFirstOfDecember && !$isLastDayWednesday)
       || ($date == $thirtiethOfDecember && $isLastDayWednesday))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>