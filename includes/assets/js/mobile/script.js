/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /** Actions au chargement ***/
  // Affichage des alertes
  if ($('#alerte').length)
    afficherMasquerIdWithDelay('alerte');

  // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
  updatePing();
  setInterval(updatePing, 60000);

  /*** Actions au clic ***/
  // Bouton fermer alerte
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque un élément (délai 200ms)
function afficherMasquerIdWithDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Masque et supprime un élément (délai 200ms)
function masquerSupprimerIdWithDelay(id)
{
  $('#' + id).fadeOut(200, function()
  {
    $(this).remove();
  });
}

// Exécute le script php de mise à jour du ping
function updatePing()
{
  $.post('/inside/includes/functions/ping.php', {function: 'updatePing'});
}
