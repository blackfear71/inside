/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /** Actions au chargement ***/
  afficherMasquerIdWithDelay('alerte');

  /*** Actions au clic ***/
  // Bouton fermer alerte
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });

  /*** Actions au changement ***/
  // Transforme en majuscule les caractères saisis dans l'identifiant
  $('#focus_identifiant').change(function()
  {
    identifiantMajuscule($(this));

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

// Transforme le contenu d'un champ en majuscules
function identifiantMajuscule(champ)
{
  var value = champ.val();

  if (value != "admin")
    value = value.toUpperCase();

  champ.val(value);
}
