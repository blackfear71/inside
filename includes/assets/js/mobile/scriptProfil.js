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

  /*** Actions au clic ***/
  // Change la couleur des boutons préférences (radio boutons)
  $('.radioPreference').click(function()
  {
    var bouton     = $(this);
    var zoneParent = bouton.parents('.zone_preference');

    changeRadioColor(bouton, zoneParent);
  });

  // Change la couleur des boutons préférences (checkboxes)
  $('.checkPreference').click(function()
  {
    var zoneParent = $(this).parents('.switch_preference_2');

    changeCheckedColor(zoneParent);
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Change la couleur d'une préférence (radio boutons)
function changeRadioColor(bouton, zoneParent)
{
  // Réinitialisation des autres boutons
  zoneParent.find('.switch_preference').each(function()
  {
    $(this).removeClass('bouton_checked');
    $(this).find('input').prop('checked', false);
  })

  // On coche le bouton concerné
  bouton.parent().addClass('bouton_checked');
  bouton.parent().find('input').prop('checked', true);
}

// Change la couleur d'une préférence (checkboxes)
function changeCheckedColor(zoneParent)
{
  if (zoneParent.find('input').prop('checked'))
    zoneParent.removeClass('bouton_checked');
  else
    zoneParent.addClass('bouton_checked');
}
