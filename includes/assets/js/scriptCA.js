/**************/
/*** Action ***/
/**************/
// Au chargement du document
$(function()
{
  /*** Actions au changement ***/
  // Charge le calendrier
  $('.loadCalendrier').on('change', function()
  {
    loadFile(event, 'image_calendars');
  });

  // Charge l'annexe
  $('.loadAnnexe').on('change', function()
  {
    loadFile(event, 'image_annexes');
  });
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Masonry (Calendriers & annexes)
  if ($('.zone_calendriers').length)
  {
    $('.zone_calendriers').masonry('destroy');

    $('.zone_calendriers').masonry({
      // Options
      itemSelector: '.zone_calendrier',
      columnWidth: 250,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_calendriers').addClass('masonry');
  }
});

/*****************/
/*** Fonctions ***/
/*****************/
// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};
