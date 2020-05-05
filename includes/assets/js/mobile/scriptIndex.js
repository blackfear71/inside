/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
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
// Transforme le contenu d'un champ en majuscules
function identifiantMajuscule(champ)
{
  var value = champ.val();

  if (value != "admin")
    value = value.toUpperCase();

  champ.val(value);
}
