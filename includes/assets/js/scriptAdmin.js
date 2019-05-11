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

// Affiche ou masque le log
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Affiche ou masque les lignes de visualisation/modification du tableau
function afficherMasquerRow(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table-row";
  else
    document.getElementById(id).style.display = "none";
}

// Rotation icône affichage log
function rotateIcon(id)
{
  if (document.getElementById(id).style.transform == "rotate(0deg)")
    document.getElementById(id).style.transform = "rotate(180deg)";
  else
    document.getElementById(id).style.transform = "rotate(0deg)";
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
