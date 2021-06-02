/***************/
/*** Actions ***/
/***************/
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptCron();
  adaptGenerator();

  /*** Actions au clic ***/
  // Affiche les détails d'un log et applique une rotation à la flèche
  $('.detailsLogs').click(function()
  {
    var time;
    var num;
    var idImage = $(this).children('img').attr('id');

    if (idImage.search('daily') != -1)
      time = 'daily';
    else
      time = 'weekly';

    num = idImage.replace(time + '_arrow_', '');

    afficherMasquerIdNoDelay(time + '_log_' + num);
    rotateIcon(time + '_arrow_' + num);
  });

  // Affiche la ligne de modification d'une alerte
  $('.modifierAlerte').click(function()
  {
    var idAlerte = $(this).attr('id').replace('alerte_', '');

    afficherMasquerIdRow('modifier_alerte_' + idAlerte);
    afficherMasquerIdRow('modifier_alerte_2_' + idAlerte);
  });

  // Masque la ligne de modification d'une alerte
  $('.annulerAlerte').click(function()
  {
    var idAlerte = $(this).attr('id').replace('annuler_alerte_', '');

    afficherMasquerIdRow('modifier_alerte_' + idAlerte);
    afficherMasquerIdRow('modifier_alerte_2_' + idAlerte);
  });

  // Affiche la zone de modification d'un thème
  $('.modifierTheme').click(function()
  {
    var idTheme = $(this).attr('id').replace('theme_', '');

    afficherMasquerIdNoDelay('modifier_theme_' + idTheme);
    afficherMasquerIdNoDelay('modifier_theme_2_' + idTheme);
    initMasonry();
  });

  // Masque la zone de modification d'un thème
  $('.annulerTheme').click(function()
  {
    var idTheme = $(this).attr('id').replace('annuler_theme_', '');

    afficherMasquerIdNoDelay('modifier_theme_' + idTheme);
    afficherMasquerIdNoDelay('modifier_theme_2_' + idTheme);
    initMasonry();
  });

  // Change la couleur des switch (calendriers & générateur de code)
  $('.label_switch').click(function()
  {
    var idBouton = $(this).closest('div').attr('id');

    changeCheckedColor(idBouton);
  });

  // Change la couleur des radio boutons (journaux)
  $('.label_radio').click(function()
  {
    var idBouton = $(this).closest('div').attr('id');

    switchCheckedColor('switch_action_changelog', idBouton);
  });

  // Ajoute une nouvelle entrée (journaux)
  $('#ajouter_entree_changelog').click(function()
  {
    var lastNum = parseInt($('.saisie_entree_changelog').last().attr('name').replace('saisies_entrees[', '').replace(']', ''));
    var newNum  = lastNum + 1;

    addEntry('zone_saisie_entrees_changelog', newNum);
  });

  // Bloque le bouton de soumission si besoin (ajout / modification journal)
  $('#bouton_saisie_journal').click(function()
  {
    // Contrôle des champs requis
    $('.zone_saisie_entree_changelog').each(function()
    {
      if ($(this).children('input').val() != '')
        $(this).children('select').prop('required', true);
    });

    // Blocage si tous les champs requis sont renseignés
    var zoneButton   = $('.zone_bouton_valider_journal');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (boutons actions)
    tabBlock.push({element: '#init_changelog', property: 'display', value: 'none'});
    tabBlock.push({element: '#ajouter_entree_changelog', property: 'display', value: 'none'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Bloque le bouton de soumission si besoin (suppression journal)
  $('#bouton_suppression_journal').click(function()
  {
    var zoneButton   = $('.zone_bouton_valider_journal');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (boutons actions)
    tabBlock.push({element: '#init_changelog', property: 'display', value: 'none'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Copie le code du générateur
  $('.copyCode').click(function()
  {
    var id = $(this).attr('id');

    $('#code_' + id).select();
    document.execCommand('copy');
  });

  /*** Actions au changement ***/
  // Charge le thème (header utilisateurs)
  $('.loadHeaderUsers').on('change', function()
  {
    loadFile(event, 'theme_header_users', false);
  });

  // Charge le thème (background utilisateurs)
  $('.loadBackgroundUsers').on('change', function()
  {
    loadFile(event, 'theme_background_users', false);
  });

  // Charge le thème (footer utilisateurs)
  $('.loadFooterUsers').on('change', function()
  {
    loadFile(event, 'theme_footer_users', false);
  });

  // Charge le thème (logo utilisateurs)
  $('.loadLogoUsers').on('change', function()
  {
    loadFile(event, 'theme_logo_users', false);
  });

  // Charge le thème (header mission)
  $('.loadHeaderMission').on('change', function()
  {
    loadFile(event, 'theme_header_mission', false);
  });

  // Charge le thème (background mission)
  $('.loadBackgroundMission').on('change', function()
  {
    loadFile(event, 'theme_background_mission', false);
  });

  // Charge le thème (footer mission)
  $('.loadFooterMission').on('change', function()
  {
    loadFile(event, 'theme_footer_mission', false);
  });

  // Charge le thème (logo mission)
  $('.loadLogoMission').on('change', function()
  {
    loadFile(event, 'theme_logo_mission', false);
  });

  // Charge la bannière (mission)
  $('.loadBanner').on('change', function()
  {
    loadFile(event, 'banner', false);
  });

  // Renseigne automatiquement les cases non saisies des périodes de vacances
  $('.select_jour_periode_vacances, .select_mois_periode_vacances').change(function()
  {
    // Récupération des caractéristiques du select
    var caracteristiquesSelect = $(this).attr('name').replace('vacances', '').replaceAll('[', '').replaceAll(']', ';').split(';').slice(0, -1);

    // On renseigne automatiquement les cases correspondantes de la ligne seulement si aucune n'a été saisie
    if (caracteristiquesSelect[2] == 'zone_a')
    {
      if (($('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_b][' + caracteristiquesSelect[3] + ']"]').val() == null
      ||   $('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_b][' + caracteristiquesSelect[3] + ']"]').val() == '')
      &&  ($('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_c][' + caracteristiquesSelect[3] + ']"]').val() == null
      ||   $('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_c][' + caracteristiquesSelect[3] + ']"]').val() == ''))
      {
        $('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_b][' + caracteristiquesSelect[3] + ']"]').val($(this).val());
        $('select[name="vacances[' + caracteristiquesSelect[0] + '][' + caracteristiquesSelect[1] + '][zone_c][' + caracteristiquesSelect[3] + ']"]').val($(this).val());
      }
    }
  });

  // Charge le bouton gauche (mission)
  $('.loadLeft').on('change', function()
  {
    loadFile(event, 'button_g', false);
  });

  // Charge le bouton milieu (mission)
  $('.loadMiddle').on('change', function()
  {
    loadFile(event, 'button_m', false);
  });

  // Charge le bouton droite (mission)
  $('.loadRight').on('change', function()
  {
    loadFile(event, 'button_d', false);
  });

  /*** Calendriers ***/
  if ($('#datepicker_saisie_deb').length || $('#datepicker_saisie_fin').length)
  {
    $('#datepicker_saisie_deb, #datepicker_saisie_fin').datepicker(
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

  $('.update_date_deb_theme, .update_date_fin_theme').each(function()
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

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptCron();
  adaptGenerator();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet (on lance Masonry et le scroll après avoir chargé les images)
$(window).on('load', function()
{
  // Masonry (Portail)
  if ($('.menu_portail').length)
  {
    $('.menu_portail').masonry().masonry('destroy');

    $('.menu_portail').masonry({
      // Options
      itemSelector: '.lien_portail',
      columnWidth: 250,
      fitWidth: true,
      gutter: 10,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.menu_portail').addClass('masonry');
  }

  // Masonry (Infos utilisateurs)
  if ($('.zone_infos').length)
  {
    $('.zone_infos').masonry().masonry('destroy');

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

  // On n'affiche la zone des thèmes qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_themes_admin').css('display', 'block');

  // Masonry (Thèmes)
  if ($('.zone_themes').length)
  {
    $('.zone_themes').masonry().masonry('destroy');

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

  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_admin').css('display', 'block');

  // Masonry (Succès)
  if ($('.zone_niveau_succes_admin').length)
  {
    $('.zone_niveau_succes_admin').masonry().masonry('destroy');

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
    $('.zone_niveau_mod_succes_admin').masonry().masonry('destroy');

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

  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_missions').css('display', 'block');

  // Masonry (Calendriers & annexes)
  if ($('.zone_missions_accueil').length)
  {
    $('.zone_missions_accueil').masonry().masonry('destroy');

    $('.zone_missions_accueil').masonry({
      // Options
      itemSelector: '.zone_mission_accueil',
      columnWidth: 500,
      fitWidth: true,
      gutter: 15,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_missions_accueil').addClass('masonry');
  }

  // Masonry (Changelog)
  if ($('.zone_logs_edition').length)
  {
    $('.zone_logs_edition').masonry().masonry('destroy');

    $('.zone_logs_edition').masonry({
      // Options
      itemSelector: '.zone_logs_categorie',
      columnWidth: 450,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_logs_edition').addClass('masonry');
  }

  // Déclenchement du scroll pour "anchor" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 30;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);

  // Déclenchement du scroll pour "anchorAlerts" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var idAlerts     = $_GET('anchorAlerts');
  var offsetAlerts = 30;
  var shadowAlerts = false;

  // Scroll vers l'id
  scrollToId(idAlerts, offsetAlerts, shadowAlerts);

  // Déclenchement du scroll pour "anchorTheme" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var idTheme     = $_GET('anchorTheme');
  var offsetTheme = 30;
  var shadowTheme = false;

  // Scroll vers l'id
  scrollToId(idTheme, offsetTheme, shadowTheme);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptation du générateur de code sur mobile
function adaptGenerator()
{
  if ($(window).width() < 1080)
  {
    $('.zone_generator_left').css('display', 'block');
    $('.zone_generator_left').css('width', '100%');
    $('.zone_generator_left').css('margin-right', '0');

    $('.zone_generator_right').css('display', 'block');
    $('.zone_generator_right').css('width', '100%');
    $('.zone_generator_right').css('margin-bottom', '10px');

    $('.zone_generated_left').css('display', 'block');
    $('.zone_generated_left').css('width', '100%');
    $('.zone_generated_left').css('margin-right', '0');

    $('.zone_generated_middle').css('display', 'block');
    $('.zone_generated_middle').css('width', '100%');
    $('.zone_generated_middle').css('margin-right', '0');

    $('.zone_generated_right').css('display', 'block');
    $('.zone_generated_right').css('width', '100%');
  }
  else
  {
    var margin = 40 / 3;

    $('.zone_generator_left').css('display', 'inline-block');
    $('.zone_generator_left').css('width', 'calc(50% - 10px)');
    $('.zone_generator_left').css('margin-right', '20px');

    $('.zone_generator_right').css('display', 'inline-block');
    $('.zone_generator_right').css('width', 'calc(50% - 10px)');
    $('.zone_generator_right').css('margin-bottom', '0');

    $('.zone_generated_left').css('display', 'inline-block');
    $('.zone_generated_left').css('width', 'calc(100% / 3 - ' + margin + 'px)');
    $('.zone_generated_left').css('margin-right', '20px');

    $('.zone_generated_middle').css('display', 'inline-block');
    $('.zone_generated_middle').css('width', 'calc(100% / 3 - ' + margin + 'px)');
    $('.zone_generated_middle').css('margin-right', '20px');

    $('.zone_generated_right').css('display', 'inline-block');
    $('.zone_generated_right').css('width', 'calc(100% / 3 - ' + margin + 'px)');
  }
}

// Adaptations des logs sur mobile
function adaptCron()
{
  if ($(window).width() < 1080)
  {
    $('.zone_cron').css('display', 'block');
    $('.zone_cron').css('width', '100%');
    $('.zone_cron').css('margin-bottom', '20px');
    $('.zone_cron').first().css('margin-right', '0');

    $('.zone_jlog').css('display', 'block');
    $('.zone_jlog').css('width', '100%');
    $('.zone_jlog').css('margin-right', '0');

    $('.zone_hlog').css('display', 'block');
    $('.zone_hlog').css('width', '100%');
  }
  else
  {
    $('.zone_cron').css('display', 'inline-block');
    $('.zone_cron').css('width', 'calc(50% - 10px)');
    $('.zone_cron').css('margin-bottom', '0');
    $('.zone_cron').first().css('margin-right', '20px');

    $('.zone_jlog').css('display', 'inline-block');
    $('.zone_jlog').css('width', 'calc(50% - 10px)');
    $('.zone_jlog').css('margin-right', '20px');

    $('.zone_hlog').css('display', 'inline-block');
    $('.zone_hlog').css('width', 'calc(50% - 10px)');
  }
}

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

// Rotation icône affichage log
function rotateIcon(id)
{
  var angle;

  // Calcul de l'angle
  var matrix = $('#' + id).css('transform');

  if (matrix !== 'none')
  {
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var a      = values[0];
    var b      = values[1];
    angle      = Math.round(Math.atan2(b, a) * (180 / Math.PI));

    if (angle < 0)
      angle += 360;
  }
  else
    angle = 0;

  // Application style
  $('#' + id).css('transition', 'all ease 0.4s');

  if (angle == 0)
    $('#' + id).css('transform', 'rotate(180deg)');
  else
    $('#' + id).css('transform', 'rotate(0deg)');
}

// Change la couleur des checkbox
function changeCheckedColor(input)
{
  if ($('#' + input).children('input').prop('checked'))
    $('#' + input).removeClass('switch_checked');
  else
    $('#' + input).addClass('switch_checked');
}

// Change la couleur des radio boutons
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

// Ajoute une entrée à la saisie d'un journal
function addEntry(zone, num)
{
  var html = '';

  html += '<div class="zone_saisie_entree_changelog">';
    // Saisie entrée
    html += '<input type="text" name="saisies_entrees[' + num + ']" placeholder="Entrée n°' + num + '" value="" class="saisie_entree_changelog" />';

    // Choix catégorie
    html += '<select name="categories_entrees[' + num + ']" class="categorie_entree_changelog">';
      html += '<option value="" hidden>Choisir une catégorie</option>';

      $.each(categoriesChangeLog, function(key, value)
      {
        html += '<option value="' + key + '">' + value + '</option>';
      });
    html += '</select>';
  html += '</div>';

  $('.' + zone).append(html);
}
