/**************/
/*** Action ***/
/**************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Génération du calendrier sous forme d'image
  if ($('.zone_calendrier_generator').length)
    afficherCalendrierJpeg();

  // Génération de l'annexe sous forme d'image
  if ($('.zone_annexe_generator').length)
    afficherAnnexeJpeg();

  // Adaptation mobile
  if ($('.zone_calendrier_generator').length || $('.zone_annexe_generator').length)
    adaptCalendars();

  /*** Actions au clic ***/
  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_calendrier, #bouton_saisie_annexe, #bouton_saisie_generator, #bouton_saisie_annexe_generator, #bouton_saisie_generated, #bouton_saisie_annexe_generated').click(function()
  {
    var zoneButton   = $(this).parents('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (zones de saisie)
    tabBlock.push({element: '.titre_annexe', property: 'readonly', value: true});
    tabBlock.push({element: '.titre_annexe', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.titre_annexe', property: 'color', value: '#a3a3a3'});
    tabBlock.push({element: '.listbox', property: 'readonly', value: true});
    tabBlock.push({element: '.listbox', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.listbox', property: 'color', value: '#a3a3a3'});
    tabBlock.push({element: '.saisie_bouton', property: 'display', value: 'none'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  /*** Actions au changement ***/
  // Charge le calendrier à générer
  $('.loadCalendrierGenere').on('change', function()
  {
    loadFile(event, 'image_calendars_generated', false);
  });

  // Charge l'annexe à générer
  $('.loadAnnexeGeneree').on('change', function()
  {
    loadFile(event, 'image_annexe_generated', false);
  });

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

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des calendriers et annexes sur mobile
function adaptCalendars()
{
  if ($(window).width() < 1080)
  {
    // Affichage des calendriers ou annexes
    $('.zone_calendars_onglets').css('display', 'block');
    $('.zone_calendars_onglets').css('width', '100%');
    $('.zone_calendars_onglets').css('margin-right', '0');

    $('.zone_calendars').css('display', 'block');
    $('.zone_calendars').css('width', '100%');

    // Générateur de calendriers
    $('.zone_calendrier_generator_middle').css('width', 'calc(100% - 280px)');
    $('.zone_calendrier_generator_middle').css('margin-right', '0');

    if ($('.zone_calendrier_generator_middle').length)
    {
      $('.zone_calendrier_generator_right').css('width', '100%');
      $('.zone_calendrier_generator_right').css('margin-top', '20px');
    }
    else
    {
      $('.zone_calendrier_generator_right').css('width', 'calc(100% - 280px)');
      $('.zone_calendrier_generator_right').css('margin-top', '0');
    }

    // Générateur d'annexes
    $('.zone_annexe_generator_middle').css('width', 'calc(100% - 280px)');
    $('.zone_annexe_generator_middle').css('margin-right', '0');

    if ($('.zone_annexe_generator_middle').length)
    {
      $('.zone_annexe_generator_right').css('width', '100%');
      $('.zone_annexe_generator_right').css('margin-top', '20px');
    }
    else
    {
      $('.zone_annexe_generator_right').css('width', 'calc(100% - 280px)');
      $('.zone_annexe_generator_right').css('margin-top', '0');
    }

    // Ajout de calendriers et annexes
    $('.zone_calendars_left').css('display', 'block');
    $('.zone_calendars_left').css('width', '100%');
    $('.zone_calendars_left').css('margin-right', '0');

    $('.zone_calendars_right').css('display', 'block');
    $('.zone_calendars_right').css('width', '100%');
  }
  else
  {
    // Affichage des calendriers ou annexes
    $('.zone_calendars_onglets').css('display', 'inline-block');
    $('.zone_calendars_onglets').css('width', '260px');
    $('.zone_calendars_onglets').css('margin-right', '20px');

    $('.zone_calendars').css('display', 'inline-block');
    $('.zone_calendars').css('width', 'calc(100% - 280px)');

    // Générateur de calendriers
    $('.zone_calendrier_generator_middle').css('width', '289px');
    $('.zone_calendrier_generator_middle').css('margin-right', '20px');

    if ($('.zone_calendrier_generator_middle').length)
      $('.zone_calendrier_generator_right').css('width', 'calc(100% - 589px)');
    else
      $('.zone_calendrier_generator_right').css('width', 'calc(100% - 280px)');

    $('.zone_calendrier_generator_right').css('margin-top', '0');

    // Générateur d'annexes
    $('.zone_annexe_generator_middle').css('width', '213px');
    $('.zone_annexe_generator_middle').css('margin-right', '20px');

    if ($('.zone_annexe_generator_middle').length)
      $('.zone_annexe_generator_right').css('width', 'calc(100% - 513px)');
    else
      $('.zone_annexe_generator_right').css('width', 'calc(100% - 280px)');

    $('.zone_annexe_generator_right').css('margin-top', '0');

    // Ajout de calendriers et annexes
    $('.zone_calendars_left').css('display', 'inline-block');
    $('.zone_calendars_left').css('width', 'calc(50% - 10px)');
    $('.zone_calendars_left').css('margin-right', '20px');

    $('.zone_calendars_right').css('display', 'inline-block');
    $('.zone_calendars_right').css('width', 'calc(50% - 10px)');
  }
}

// Affiche le calendrier généré au format JPEG
function afficherCalendrierJpeg()
{
  // Réglage de la hauteur des jours fériés (dépend du nombre de lignes affichées)
  var hauteurJourFerie = $('.ligne_calendrier_generator').height() - 105;
  $('.zone_jour_ferie_calendrier_generator').css('line-height', hauteurJourFerie + 'px');
  $('.zone_jour_ferie_calendrier_generator').css('height', hauteurJourFerie + 'px');

  // Conversion du calendrier généré en image
  html2canvas($('.zone_calendrier_generator')[0],
  {
    // Options
    scale:1
  }).then(function(canvas)
  {
    // Conversion du calendrier généré
    var data = canvas.toDataURL('image/jpg', 1);

    // Affichage de la zone et du formulaire
    $('.form_sauvegarde_calendrier').css('display', 'block');
    $('#generated_calendar').attr('src', data);
    $('#calendar_generator').val(data);
  });

  // Masquage du calendrier généré (format HTML)
  $('.zone_calendrier_generator_hidden').remove();

  // Redimenssionnement des zones
  $('.zone_calendrier_generator_right').css('width', 'calc(100% - 589px)');
}

// Affiche l'annexe générée au format JPEG
function afficherAnnexeJpeg()
{
  // Conversion de l'annexe généré en image
  html2canvas($('.zone_annexe_generator')[0],
  {
    // Options
    scale:1
  }).then(function(canvas)
  {
    // Conversion de l'annexe générée
    var data = canvas.toDataURL('image/jpg', 1);

    // Affichage de la zone et du formulaire
    $('.form_sauvegarde_annexe').css('display', 'block');
    $('#generated_annexe').attr('src', data);
    $('#annexe_generator').val(data);
  });

  // Masquage de l'annexe généré (format HTML)
  $('.zone_annexe_generator_hidden').remove();

  // Redimenssionnement des zones
  $('.zone_calendrier_generator_right').css('width', 'calc(100% - 589px)');
}
