/***************/
/*** Actions ***/
/***************/
$(document).ready(function()
{
  /*** Actions au clic ***/
  // Affiche les détails d'un log et applique une rotation à la flèche
  $('.detailsLogs').click(function()
  {
    var time;
    var num;
    var id_image = $(this).children('img').attr('id');

    if (id_image.search('daily') != -1)
      time = 'daily';
    else
      time = 'weekly';

    num = id_image.replace(time + '_arrow_', '');

    afficherMasquer(time + '_log_' + num);
    rotateIcon(time + '_arrow_' + num);
  });

  // Affiche la ligne de modification d'une alerte
  $('.modifierAlerte').click(function()
  {
    var id_alerte = $(this).attr('id').replace('alerte_', '');

    afficherMasquerRow('modifier_alerte_' + id_alerte);
    afficherMasquerRow('modifier_alerte_2_' + id_alerte);
  });

  // Masque la ligne de modification d'une alerte
  $('.annulerAlerte').click(function()
  {
    var id_alerte = $(this).attr('id').replace('annuler_', '');

    afficherMasquerRow('modifier_alerte_' + id_alerte);
    afficherMasquerRow('modifier_alerte_2_' + id_alerte);
  });

  // Affiche la zone de modification d'un thème
  $('.modifierTheme').click(function()
  {
    var id_theme = $(this).attr('id').replace('theme_', '');

    afficherMasquer('modifier_theme_' + id_theme);
    afficherMasquer('modifier_theme_2_' + id_theme);
    initMasonry();
  });

  // Masque la zone de modification d'un thème
  $('.annulerTheme').click(function()
  {
    var id_theme = $(this).attr('id').replace('annuler_', '');

    afficherMasquer('modifier_theme_' + id_theme);
    afficherMasquer('modifier_theme_2_' + id_theme);
    initMasonry();
  });

  // Change la couleur des boutons radio autorisations calendriers
  $('.switch_autorisation').click(function()
  {
    var id_bouton = $(this).attr('id');

    changeCheckedColor(id_bouton);
  });
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet (on lance Masonry et le scroll après avoir chargé les images)
$(window).on('load', function()
{
  // Masonry (Thèmes)
  if ($('.zone_themes').length)
  {
    $('.zone_themes').masonry('destroy');

    $('.zone_themes').masonry({
      // Options
      itemSelector: '.zone_theme',
      columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_themes').addClass('masonry');
  }

  // Masonry (Portail)
  if ($('.menu_admin').length)
  {
    $('.menu_admin').masonry('destroy');

    $('.menu_admin').masonry({
      // Options
      itemSelector: '.menu_link_admin',
      columnWidth: 300,
      fitWidth: true,
      gutter: 15,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.menu_admin').addClass('masonry');
  }

  // Masonry (Infos utilisateurs)
  if ($('.zone_infos').length)
  {
    $('.zone_infos').masonry('destroy');

    $('.zone_infos').masonry({
      // Options
      itemSelector: '.zone_infos_user',
      columnWidth: 300,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_infos').addClass('masonry');
  }

  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_admin').css('display', 'block');

  // Masonry (Succès)
  if ($('.zone_niveau_succes_admin').length)
  {
    $('.zone_niveau_succes_admin').masonry('destroy');

    $('.zone_niveau_succes_admin').masonry({
      // Options
      itemSelector: '.ensemble_succes',
      columnWidth: 180,
      fitWidth: true,
      gutter: 10,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_niveau_succes_admin').addClass('masonry');
  }

  // Masonry (Modification succès)
  if ($('.zone_niveau_mod_succes_admin').length)
  {
    $('.zone_niveau_mod_succes_admin').masonry('destroy');

    $('.zone_niveau_mod_succes_admin').masonry({
      // Options
      itemSelector: '.succes_liste_mod',
      columnWidth: 320,
      fitWidth: true,
      gutter: 25,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_niveau_mod_succes_admin').addClass('masonry');
  }

  // Déclenchement du scroll pour "anchor" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 30;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);

  // Déclenchement du scroll pour "anchorAlerts" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id_alerts     = $_GET('anchorAlerts');
  var offset_alerts = 30;
  var shadow_alerts = false;

  // Scroll vers l'id
  scrollToId(id_alerts, offset_alerts, shadow_alerts);

  // Déclenchement du scroll pour "anchorTheme" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id_theme     = $_GET('anchorTheme');
  var offset_theme = 30;
  var shadow_theme = false;

  // Scroll vers l'id
  scrollToId(id_theme, offset_theme, shadow_theme);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // On lance Masonry
  $('.zone_themes').masonry({
    // Options
    itemSelector: '.zone_theme',
    columnWidth: 500,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });
}

// Affiche ou masque une zone
function afficherMasquer(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).css('display', 'block');
  else
    $('#' + id).css('display', 'none');
}

// Affiche ou masque les lignes de visualisation/modification du tableau
function afficherMasquerRow(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).css('display', 'table-row');
  else
    $('#' + id).css('display', 'none');
}

// Rotation icône affichage log
function rotateIcon(id)
{
  // Calcul de l'angle
  var matrix = $('#' + id).css('transform');

  if (matrix !== 'none')
  {
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var a      = values[0];
    var b      = values[1];
    var angle  = Math.round(Math.atan2(b, a) * (180 / Math.PI));

    if (angle < 0)
      angle = angle + 360;
  }
  else
    var angle = 0;

  // Application style
  $('#' + id).css('transition', 'all ease 0.4s');

  if (angle == 0)
    $('#' + id).css('transform', 'rotate(180deg)');
  else
    $('#' + id).css('transform', 'rotate(0deg)');
}

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};

// Change la couleur des checkbox (autorisations)
function changeCheckedColor(input)
{
  if ($('#' + input).children('input').prop('checked'))
    $('#' + input).addClass('bouton_checked');
  else
    $('#' + input).removeClass('bouton_checked');
}

// Génère un calendrier
$(function()
{
  if ($("#datepicker_saisie_deb").length || $("#datepicker_saisie_fin").length)
  {
    $("#datepicker_saisie_deb, #datepicker_saisie_fin").datepicker(
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

  $('.modify_date_deb_theme, .modify_date_fin_theme').each(function()
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
