/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ferme le formulaire de vote en cliquant n'importe où sur le body
  $('body').click(function()
  {
    $('.zone_smileys').each(function()
    {
      $(this).css('display', 'none');
    });

    $('.link_current_vote').each(function()
    {
      if ($(this).css('display') == 'none')
        $(this).css('display', 'block');
    });
  });

  // Ajouter une phrase culte
  $('#ajouterCollector, #fermerCollector').click(function()
  {
    afficherMasquer('zone_add_collector');
  });

  // Ajouter une image culte
  $('#ajouterImage, #fermerImage').click(function()
  {
    afficherMasquer('zone_add_image');
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme le zoom d'une image culte
    if ($(event.target).attr('class') == 'fond_zoom')
      closePicture();

    // Ferme la saisie d'une phrase/image culte
    if ($(event.target).attr('class') == 'fond_saisie_collector')
    {
      closeInput('zone_add_collector');
      closeInput('zone_add_image');
    }
  });

  // Affiche la zone de modification d'une phrase/image culte
  $('.modifierCollector').click(function()
  {
    var id_collector = $(this).attr('id').replace('modifier_', '');

    afficherMasquerNoDelay('modifier_collector_' + id_collector);
    afficherMasquerNoDelay('visualiser_collector_' + id_collector);
    adaptBrowse(id_collector);
    initMasonry();
  });

  // Ferme la zone de modification d'une phrase/image culte
  $('.annulerCollector').click(function()
  {
    var id_collector = $(this).attr('id').replace('annuler_', '');

    afficherMasquerNoDelay('modifier_collector_' + id_collector);
    afficherMasquerNoDelay('visualiser_collector_' + id_collector);
    initMasonry();
  });

  // Affiche la zone de modification d'un vote d'une phrase/image culte
  $('.modifierVote').click(function(event)
  {
    var id_collector = $(this).attr('id').replace('link_form_vote_', '');

    afficherMasquerNoDelay('modifier_vote_' + id_collector);
    afficherMasquerNoDelay('link_form_vote_' + id_collector);
    event.stopPropagation();
  });

  // Affiche une image culte en grand
  $('.agrandirImage').click(function()
  {
    var html;
    var path      = $(this).children().attr('src');
    var split     = path.split('/');
    var collector = split[split.length - 1];

    html = '<div id="zoom_image" class="fond_zoom">';
      html += '<div class="zone_image_zoom">';
        html += '<a id="fermerImage" class="lien_zoom"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';
        html += '<img src="' + path + '" alt="' + collector + '" class="image_zoom" />';
      html += '</div>';
    html += '</div>';

    $('body').append(html);

    $('#zoom_image').fadeIn(200);
  });

  // Ferme le zoom d'une image culte
  $(document).on('click', '#fermerImage', function()
  {
    $('#zoom_image').fadeOut(200, function()
    {
      $('#zoom_image').remove();
    });
  });

  // Bloque le bouton de soumission si besoin (phrase culte)
  $('#bouton_saisie_collector').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie_collector');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Bloque le bouton de soumission si besoin (image culte)
  $('#bouton_saisie_image').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie_image');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Bloque le bouton de validation si besoin
  $('.icon_validate_collector').click(function()
  {
    var submitButton = $('#' + $(this).attr('id'));
    var zoneButton   = $('#' + submitButton.parent().attr('id'));
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (smileys vote)
    tabBlock.push({element: '.link_current_vote', property: 'pointer-events', value: 'none'});

    // Blocage spécifique (liens actions)
    tabBlock.push({element: '.icone_modify_collector', property: 'display', value: 'none'});
    tabBlock.push({element: '.icon_delete_collector', property: 'display', value: 'none'});
    tabBlock.push({element: '.icon_validate_collector', property: 'display', value: 'none'});
    tabBlock.push({element: '.icone_cancel_collector', property: 'display', value: 'none'});

    // Blocage spécifique (toutes zones de saisie autres restaurants)
    tabBlock.push({element: '.zone_collectors input', property: 'readonly', value: true});
    tabBlock.push({element: '.zone_collectors input', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.zone_collectors input', property: 'color', value: '#a3a3a3'});
    tabBlock.push({element: '.zone_collectors textarea', property: 'readonly', value: true});
    tabBlock.push({element: '.zone_collectors textarea', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.zone_collectors textarea', property: 'color', value: '#a3a3a3'});
    tabBlock.push({element: '.zone_collectors select', property: 'readonly', value: true});
    tabBlock.push({element: '.zone_collectors select', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.zone_collectors select', property: 'color', value: '#a3a3a3'});
    tabBlock.push({element: '.zone_collectors label', property: 'readonly', value: true});
    tabBlock.push({element: '.zone_collectors label', property: 'pointer-events', value: 'none'});
    tabBlock.push({element: '.zone_collectors label', property: 'color', value: '#a3a3a3'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  /*** Actions au changement ***/
  // Applique les filtres
  $('#applySort, #applyFilter').on('change', function()
  {
    if ($(this).val() == 'dateDesc' || $(this).val() == 'dateAsc')
      applySortOrFilter($(this).val(), $_GET('filter'));
    else
      applySortOrFilter($_GET('sort'), $(this).val());
  });

  // Affiche la saisie "Autre" (phrase culte)
  $('#speaker').on('change', function()
  {
    afficherOther('speaker', 'other_name');
  });

  // Affiche la saisie "Autre" (image)
  $('#speaker_2').on('change', function()
  {
    afficherOther('speaker_2', 'other_name_2');
  });

  // Charge l'image (saisie)
  $('.loadSaisieCollector').on('change', function()
  {
    loadFile(event, 'image_collector');
  });

  // Affiche la saisie "Autre" (modification)
  $('.changeSpeaker').on('change', function()
  {
    id_collector = $(this).attr('id').replace('speaker_', '');

    afficherModifierOther('speaker_' + id_collector, 'other_speaker_' + id_collector);
  });

  // Charge l'image (modification)
  $('.loadModifierCollector').on('change', function()
  {
    var id_image = $(this).attr('id').replace('fichier_', '');
    loadFile(event, 'image_collector_' + id_image);
  });

  $('.loadImage').on('load', function()
  {
    var id_image = $(this).attr('id').replace('image_collector_', '');

    adaptBrowse(id_image);
    initMasonry();
  });

  /*** Calendriers ***/
  if ($("#datepicker_collector").length || $("#datepicker_image").length)
  {
    $("#datepicker_collector, #datepicker_image").datepicker(
    {
      autoHide: true,
      language: 'fr-FR',
      format: 'dd/mm/yyyy',
      weekStart: 1,
      days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      daysShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
      daysMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
      monthsShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']
    });
  }

  $('.modify_date_collector').each(function()
  {
    $(this).datepicker(
    {
      autoHide: true,
      language: 'fr-FR',
      format: 'dd/mm/yyyy',
      weekStart: 1,
      days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      daysShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
      daysMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
      monthsShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']
    });
  });
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Masonry (Phrases cultes & images)
  if ($('.zone_collectors').length)
  {
    $('.zone_collectors').masonry().masonry('destroy');

    $('.zone_collectors').masonry({
      // Options
      itemSelector: '.zone_collector',
      columnWidth: 525,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_collectors').addClass('masonry');
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
// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // On lance Masonry
  $('.zone_collectors').masonry({
    // Options
    itemSelector: '.zone_collector',
    columnWidth: 525,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
    /*transitionDuration: 0*/
  });

  // Découpe le texte si besoin
  $('.text_collector').wrapInner();
}

// Affiche ou masque un élément (délai 200ms)
function afficherMasquer(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerNoDelay(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Ferme le zoom d'une image culte
function closePicture()
{
  $('#zoom_image').fadeOut(200, function()
  {
    $('#zoom_image').remove();
  });
}

// Ferme la saisie d'une phrase/image culte
function closeInput(id)
{
  if ($('#' + id).css('display') != "none")
    afficherMasquer(id);
}

// Adapte la zone "Parcourir" en fonction de la taille de l'image à son chargement
function adaptBrowse(id)
{
  var image_height = $('#image_collector_' + id).height();
  var marge        = -1 * (image_height + 6.5);

  $('#zone_parcourir_' + id).height(image_height);
  $('#mask_collector_' + id).css('margin-top', marge);
}

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output   = document.getElementById(id);
  output.src   = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  EXIF.getData(event.target.files[0], function()
  {
    var orientation = EXIF.getTag(this, "Orientation");
    var degrees     = 0;

    // Les valeurs sont inversées par rapport à la fonction rotateImage() dans fonctions_communes.php
    switch (orientation)
    {
      case 3:
        degrees = 180;
        break;

      case 6:
        degrees = 90;
        break;

      case 8:
        degrees = -90;
        break;

      case 1:
      default:
        degrees = 0;
        break;
    }

    output.setAttribute('style','transform: rotate(' + degrees + 'deg)');
  });
};

// Affiche ou masque la zone de saisie "Autre" (insertion)
function afficherOther(select, required)
{
  if ($('#' + select).val() == "other")
  {
    $('#' + required).css('display', 'inline-block');
    $('#' + required).prop('required', true);
    $('#' + select).addClass('saisie_speaker speaker_autre');
  }
  else
  {
    $('#' + required).css('display', 'none');
    $('#' + required).prop('required', false);
    $('#' + select).removeClass('speaker_autre');
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if ($('#' + select).val() == "other")
  {
    $('#' + id).css('display', 'block');
    $('#' + id).prop('required', true);
  }
  else
  {
    $('#' + id).css('display', 'none');
    $('#' + id).prop('required', false);
  }
}

// Redirige pour appliquer le tri ou le filtre
function applySortOrFilter(sort, filter)
{
  document.location.href = "collector.php?action=goConsulter&page=1&sort=" + sort + "&filter=" + filter;
}
