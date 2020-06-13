/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie d'année
  $('#afficherSaisieAnnee, #fermerSaisieAnnee').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisieAnnee');
  });
});
