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

  // Affiche les détails d'un succès
  $('.agrandirSucces').click(function()
  {
    var idSuccess = $(this).attr('id').replace('agrandir_succes_', '');

    showSuccess(idSuccess);
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

// Affiche le détail d'un succès débloqué
function showSuccess(id)
{
  var success = listeSuccess[id];
  var html    = '';

  html += '<div id="zoom_succes" class="fond_zoom_succes" style="display: none;">';
    // Affichage du succès
    html += '<div class="zone_success_zoom">';
      // Succès
      html += '<div class="zone_succes_zoom">';
        // Titre du succès
        html += '<div class="titre_succes_zoom">' + success['title'] + '</div>';

        // Logo du succès
        html += '<img src="/inside/includes/images/profil/success/' + success['reference'] + '.png" alt="' + success['reference'] + '" class="logo_succes_zoom" />';

        // Description du succès
        html += '<div class="description_succes_zoom">' + success['description'] + '</div>';

        // Explications du succès
        html += '<div class="explications_succes_zoom">' + success['explanation'].replace('%limit%', success['limit_success']) + '</div>';
      html += '</div>';

      // Bouton
      html += '<div class="zone_boutons_succes_zoom">';
        // Bouton fermeture
        html += '<a id="closeZoomSuccess" class="bouton_succes_zoom">Cool !</a>';
      html += '</div>';
    html += '</div>';
  html += '</div>';

  $('body').append(html);

  $('#zoom_succes').fadeIn(200);
}
