/**************/
/*** Action ***/
/**************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_calendrier, #bouton_saisie_annexe').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  /*** Actions au changement ***/
  // Charge le calendrier
  $('.loadCalendrier').on('change', function()
  {
    loadFile(event, 'image_calendars', false);
  });

  // Charge l'annexe
  $('.loadAnnexe').on('change', function()
  {
    loadFile(event, 'image_annexes', false);
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptCalendars();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation mobile
  adaptCalendars();

  // Masonry (Calendriers & annexes)
  if ($('.zone_calendriers').length)
  {
    $('.zone_calendriers').masonry().masonry('destroy');

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
// Adaptations des calendriers et annexes sur mobile
function adaptCalendars()
{
  if ($(window).width() < 1080)
  {
    $('.zone_calendars_left').css('display', 'block');
    $('.zone_calendars_left').css('width', '100%');

    $('.zone_calendars_right').css('display', 'block');
    $('.zone_calendars_right').css('width', '100%');
    $('.zone_calendars_right').css('margin-left', '0');
  }
  else
  {
    $('.zone_calendars_left').css('display', 'inline-block');
    $('.zone_calendars_left').css('width', '260px');

    $('.zone_calendars_right').css('display', 'inline-block');
    $('.zone_calendars_right').css('width', 'calc(100% - 280px)');
    $('.zone_calendars_right').css('margin-left', '20px');
  }
}
