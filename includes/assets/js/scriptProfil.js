/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
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

  /*** Actions au clic ***/
  // Change la couleur des boutons préférences
  $('.label_switch').click(function()
  {
    var id_bouton = $(this).closest('div').attr('id');

    switch (id_bouton)
    {
      // Notifications
      case 'bouton_me':
      case 'bouton_today':
      case 'bouton_week':
      case 'bouton_all_n':
        switchCheckedColor('switch_default_view_notifications', id_bouton);
        break;

      // Films
      case 'bouton_accueil':
      case 'bouton_cards':
        switchCheckedColor('switch_default_view_movies', id_bouton);
        break;

      case 'bouton_semaine':
      case 'bouton_waited':
      case 'bouton_way_out':
        changeCheckedColor(id_bouton);
        break;

      // #TheBox
      case 'bouton_all':
      case 'bouton_inprogress':
      case 'bouton_mine':
      case 'bouton_done':
        switchCheckedColor('switch_default_view_ideas', id_bouton);
        break;

      // INSIDE Room
      case 'bouton_chat_yes':
      case 'bouton_chat_no':
        switchCheckedColor('switch_default_view_chat', id_bouton);
        break;

      default:
        break;
    }
  });

  // Affiche les détails d'un succès
  $('.agrandirSucces').click(function()
  {
    var id_success = $(this).attr('id').replace('agrandir_succes_', '');

    showSuccess(id_success);
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme le zoom d'une image culte
    if ($(event.target).attr('class') == 'fond_zoom_succes')
      closeSuccess();
  });

  // Plie ou déplie les thèmes
  $('#fold_themes_user, #fold_themes_missions').click(function()
  {
    var id_fold = $(this).attr('id').replace('fold_', '');

    afficherMasquerThemes(id_fold);
  });

  // Affiche un aperçu d'un thème
  $('.apercuTheme').click(function()
  {
    var reference = '';
    var withLogo  = $(this).attr('id').split('_')[0];
    var logo      = '';

    if (withLogo == 'nologo')
      reference = $(this).attr('id').replace('nologo_', '');
    else
      reference = $(this).attr('id');

    var background = '/inside/includes/images/themes/backgrounds/' + reference + '.png';
    var header     = '/inside/includes/images/themes/headers/' + reference + '_h.png';
    var footer     = '/inside/includes/images/themes/footers/' + reference + '_f.png';

    if (withLogo != 'nologo')
      logo = '/inside/includes/images/themes/logos/' + reference + '_l.png';
    else
      logo = '/inside/includes/icons/common/inside.png';

    changeTheme(background, header, footer, logo);
  });

  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_avatar').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  /*** Actions au changement ***/
  // Charge l'avatar
  $('.loadAvatar').on('change', function()
  {
    loadFile(event, 'avatar');
  });

  /*** Calendriers ***/
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

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptProfil();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation mobile
  adaptProfil();

  // Masonry (Contributions)
  if ($('.zone_profil_contributions').length)
  {
    $('.zone_profil_contributions').masonry().masonry('destroy');

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

  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_profil').css('display', 'block');

  // Masonry (Succès)
  if ($('.succes_liste').length)
  {
    $('.zone_niveau_succes').masonry().masonry('destroy');

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

  // Masonry (Classement)
  if ($('.classement_liste').length)
  {
    $('.zone_niveau_succes').masonry().masonry('destroy');

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

  // On n'affiche la zone des thèmes qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_themes_user').css('display', 'block');

  // Masonry (Thèmes utilisateur)
  if ($('#themes_user').length)
  {
    $('#themes_user').masonry().masonry('destroy');

    $('#themes_user').masonry({
      // Options
      itemSelector: '.zone_theme',
      columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('#themes_user').addClass('masonry');
  }

  // Masonry (Thèmes missions)
  if ($('#themes_missions').length)
  {
    $('#themes_missions').masonry().masonry('destroy');

    $('#themes_missions').masonry({
      // Options
      itemSelector: '.zone_theme',
      columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('#themes_missions').addClass('masonry');
  }
});

/*****************/
/*** Fonctions ***/
/*****************/
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

// Adaptations du profil sur mobile
function adaptProfil()
{
  if ($(window).width() < 1080)
  {
    $('.form_update_infos').css('display', 'block');
    $('.form_update_infos').css('width', '100%');
    $('.form_update_infos').css('margin-left', '0');
    $('.form_update_infos').css('margin-top', '20px');

    $('.form_update_avatar').css('border', '0');

    // Adaptation succès
    if ($('.zone_succes_profil').length)
    {
      $('.zone_profil_left').css('display', 'block');
      $('.zone_profil_left').css('width', '100%');

      $('.zone_profil_right').css('display', 'block');
      $('.zone_profil_right').css('width', '100%');
      $('.zone_profil_right').css('margin-left', '0');
    }
  }
  else
  {
    $('.form_update_infos').css('display', 'inline-block');
    $('.form_update_infos').css('width', 'calc(100% - 442px)');
    $('.form_update_infos').css('margin-left', '20px');
    $('.form_update_infos').css('margin-top', '0');

    $('.form_update_avatar').css('border-right', 'solid 1px #b3b3b3');

    // Adaptation succès
    if ($('.zone_succes_profil').length)
    {
      $('.zone_profil_left').css('display', 'inline-block');
      $('.zone_profil_left').css('width', '260px');

      $('.zone_profil_right').css('display', 'inline-block');
      $('.zone_profil_right').css('width', 'calc(100% - 280px)');
      $('.zone_profil_right').css('margin-left', '20px');
    }
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
    $('#' + input).removeClass('bouton_checked');
  else
    $('#' + input).addClass('bouton_checked');
}

// Affiche le détail d'un succès débloqué
function showSuccess(id)
{
  var success = listeSuccess[id];
  var html;

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

// Affiche ou masque les thèmes
function afficherMasquerThemes(id)
{
  if ($('#' + id).css('display') == 'block')
  {
    $('#' + id).css('display', 'none');
    $('#fold_' + id).html('Déplier');
  }
  else
  {
    $('#' + id).css('display', 'block');
    $('#fold_' + id).html('Plier');
  }
}
