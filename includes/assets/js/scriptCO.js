// Au chargement du document complet
$(window).on('load', function()
{
  // Masonry
  if ($('.zone_collectors').length)
  {
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

// Affiche ou masque un élément (délai 400ms)
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

// Adapte la zone "Parcourir" en fonction de la taille de l'image à son chargement
function adaptBrowse(id)
{
  var image_height = $('#image_collector_' + id).height();
  var marge        = -1 * (image_height + 6);

  $('#zone_parcourir_' + id).height(image_height);
  $('#mask_collector_' + id).css('margin-top', marge);
}

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  EXIF.getData(event.target.files[0], function()
  {
    var orientation = EXIF.getTag(this, "Orientation");
    var degrees     = 0;

    // Les valeurs sont inversées par rapport à la fonction rotateImage() dans fonctions_communes.php
    switch(orientation)
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

// Combiné avec afficherMasquer(), cela permet de fermer le formulaire en cliquant n'importe où sur le body
$(function()
{
  $("body").click(function()
  {
    $(".zone_smileys").hide();
    $(".link_current_vote").show();
  });

  $(".link_current_vote").click(function(event)
  {
    $("#modifier_vote").show();
    event.stopPropagation();
  });
})

// Génère un ou plusieurs calendrier
$(function()
{
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
