/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  // Charge l'image dans la zone de saisie
  $('.loadSaisieAvatar').on('change', function(event)
  {
    loadFile(event, 'image_avatar_saisie', true);
  });
});
