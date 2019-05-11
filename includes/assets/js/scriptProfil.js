// Au chargement du document
$(document).ready(function()
{
  // Adaptation mobile
  adaptProfil();

  $('#progress_circle').circlize(
  {
		radius: 60,
    percentage: $('#progress_circle').attr('data-perc'),
		text: $('#progress_circle').attr('data-text'),
    min: $('#progress_circle').attr('data-perc'),
    max: 100,
    typeUse: "useText",
		useAnimations: true,
		useGradient: false,
		background: "#d3d3d3",
		foreground: "#a3a3a3",
		stroke: 5,
		duration: 1000
	});
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Décalage pour mobile
  adaptProfil();
});

// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_profil').css('display', 'block');

  // Masonry
  if ($('.zone_profil_contributions').length)
  {
    $('.zone_profil_contributions').masonry('destroy');

    $('.zone_profil_contributions').masonry({
      // Options
      itemSelector: '.zone_contributions',
      columnWidth: 360,
      fitWidth: true,
      gutter: 40,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_profil_contributions').addClass('masonry');
  }

  if ($('.succes_liste').length)
  {
    $('.zone_niveau_succes').masonry('destroy');

    $('.zone_niveau_succes').masonry({
      // Options
      itemSelector: '.succes_liste',
      columnWidth: 160,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_niveau_succes').addClass('masonry');
  }

  if ($('.classement_liste').length)
  {
    $('.zone_niveau_succes').masonry('destroy');

    $('.zone_niveau_succes').masonry({
      // Options
      itemSelector: '.classement_liste',
      columnWidth: 195,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_niveau_succes').addClass('masonry');
  }
});

// Initialisation manuelle de "Masonry"
function initMasonry()
{
  $('.zone_profil_contributions').masonry({
    // Options
    itemSelector: '.zone_contributions',
    columnWidth: 360,
    fitWidth: true,
    gutter: 40,
    horizontalOrder: true
  });
}

// Adaptations de la section sur mobiles
function adaptProfil()
{
  if ($(window).width() < 1200)
  {
    $('.form_update_infos').css('display', 'block');
    $('.form_update_infos').css('width', '100%');
    $('.form_update_infos').css('margin-left', '0');
    $('.form_update_infos').css('margin-top', '20px');

    $('.form_update_avatar').css('border', '0');
  }
  else
  {
    $('.form_update_infos').css('display', 'inline-block');
    $('.form_update_infos').css('width', 'calc(100% - 442px)');
    $('.form_update_infos').css('margin-left', '20px');
    $('.form_update_infos').css('margin-top', '0');

    $('.form_update_avatar').css('border-right', 'solid 1px #b3b3b3');
  }
}

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};

// Change la couleur des radio boutons (préférences)
function switchCheckedColor(zone, input)
{
  $('.' + zone).each(function()
  {
    $(this).removeClass('bouton_checked');
    $(this).children('input').prop('checked', false);
  })

  $('#' + input).addClass('bouton_checked');
  $('#' + input).children('input').prop('checked', true);
}

// Change la couleur des checkbox (préférences)
function changeCheckedColor(input)
{
  if ($('#' + input).children('input').prop('checked'))
    $('#' + input).addClass('bouton_checked');
  else
    $('#' + input).removeClass('bouton_checked');
}

// Affiche ou masque la saisie vue anciens films
function afficherMasquerOldMovies(old, id, required)
{
  if (old == "all")
  {
    $('#' + id).css('display', 'none');
    $('#' + required).prop('required', false);
  }
  else
  {
    $('#' + id).css('display', 'block');
    $('#' + required).prop('required', true);
  }

  initMasonry();
}

// Génère un calendrier
$(function()
{
  if ($("#datepicker_anniversary").length)
  {
    $("#datepicker_anniversary").datepicker(
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
});
