/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche/masque les films cachés (tableaux)
  $('#show_hidden, #show_hidden_2').click(function()
  {
    afficherMasquerTbody('hidden_films', 'show_hidden');
  });

  // Affiche la saisie de préférence d'un film (tableaux)
  $('.afficherVote').click(function()
  {
    var id_film = $(this).attr('id').replace('preference_', '');

    afficherMasquerNoDelay('preference_' + id_film);
    afficherMasquerNoDelay('preference2_' + id_film);
  });

  // Masque la saisie de préférence d'un film (tableaux)
  $('.annulerVote').click(function()
  {
    var id_film = $(this).attr('id').replace('annuler_preference_', '');

    afficherMasquerNoDelay('preference_' + id_film);
    afficherMasquerNoDelay('preference2_' + id_film);
  });

  // Affiche la zone de saisie d'un film
  $('#ajouterFilm, #annulerFilm').click(function()
  {
    afficherMasquer('zone_saisie_film');
  });

  // Change la couleur des switch restaurant film
  $('.label_switch').click(function()
  {
    var id_bouton = $(this).closest('div').attr('id');

    switchCheckedColor('switch_restaurant', id_bouton);
  });

  // Plie ou déplie les fiches des films
  $('#fold_all, #unfold_all, .cacherFilms').click(function()
  {
    var id_fold = $(this).attr('id');

    if (id_fold != 'fold_all' && id_fold != 'unfold_all')
      id_fold = $(this).attr('id').replace('lien_hide_', '');

    afficherMasquerFilms(id_fold);
  });

  // Scroll vers le mois en cours
  $('.naviguerMois').click(function()
  {
    var full_date     = new Date();
    var current_month = full_date.getMonth() + 1;
    var id_month      = 'lien_hide_' + current_month;
    var offset        = 50;
    var shadow        = false;

    if ($('#' + id_month).length)
      scrollToId(id_month, offset, shadow);
  });

  // Affiche la saisie de préférence d'une fiche
  $('.afficherPreference').click(function()
  {
    var id_film    = $(this).attr('id').replace('fiche_', '');
    var titre_film = $('#titre_film_' + id_film).val();
    var vote_film  = $('#vote_film_' + id_film).val();
    var view       = $_GET('view');
    var year       = $_GET('year');

    afficherSaisiePreference(titre_film, vote_film, view, year, id_film);
  });

  // Masque la saisie de préférence d'une fiche
  $(document).on('click', '#masquerPreference', function()
  {
    masquerSaisiePreference();
  });

  // Redirige vers le détail des films au clic Doodle des fiches
  $('.lienDetails').click(function()
  {
    var id_film = $(this).attr('id').replace('lien_details_', '');

    document.location.href = 'details.php?id_film=' + id_film + '&doodle=true&action=goConsulter';
  });

  // Affiche la zone de modification d'un film
  $('#modifierFilm, #doodleFilm').click(function()
  {
    updateFilm('zone_saisie_film');
  });

  // Affiche la zone de modification d'un commentaire
  $('.modifierCommentaire').click(function()
  {
    var id_comment = $(this).attr('id').replace('modifier_commentaire_', '');

    afficherMasquerNoDelay('modifier_comment_' + id_comment);
    afficherMasquerNoDelay('visualiser_comment_' + id_comment);
    afficherMasquerNoDelay('actions_comment_' + id_comment);
  });

  // Masque la zone de modification d'un commentaire
  $('.annulerCommentaire').click(function()
  {
    var id_comment = $(this).attr('id').replace('annuler_commentaire_', '');

    afficherMasquerNoDelay('modifier_comment_' + id_comment);
    afficherMasquerNoDelay('visualiser_comment_' + id_comment);
    afficherMasquerNoDelay('actions_comment_' + id_comment);
  });

  // Insère un smiley en saisie/modification de commentaire
  $('.ajouterSmiley, .modifierSmiley').click(function()
  {
    var id_smiley  = $(this).attr('id').split('_');
    var id_comment = id_smiley[id_smiley.length - 1];
    var smiley     = id_smiley[id_smiley.length - 2];

    insert_smiley(smiley, 'textarea_comment_' + id_comment);
  });

  /*** Calendriers ***/
  if ($("#datepicker_sortie_1").length || $("#datepicker_sortie_2").length || $("#datepicker_doodle").length)
  {
    $("#datepicker_sortie_1, #datepicker_sortie_2, #datepicker_doodle").datepicker(
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

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet (on lance Masonry et le scroll après avoir chargé les images)
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_home').css('display', 'block');
  $('.zone_films').css('display', 'block');

  // Masonry (Accueil)
  if ($('.zone_films_accueil').length)
  {
    $('.zone_films_accueil').masonry().masonry('destroy');

    $('.zone_films_accueil').masonry({
      // Options
      itemSelector: '.zone_film_accueil',
      columnWidth: 250,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_films_accueil').addClass('masonry');
  }

  // Masonry (Fiches films)
  if ($('.zone_fiches_films').length)
  {
    $('.zone_fiches_films').masonry().masonry('destroy');

    $('.zone_fiches_films').masonry({
      // Options
      itemSelector: '.zone_fiche_film',
      columnWidth: 420,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_fiches_films').addClass('masonry');
  }

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 20;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);

  // Ouverture modification si création Doodle
  var doodle = $_GET('doodle');

  if (doodle == "true")
    updateFilm('zone_saisie_film');
});

/*****************/
/*** Fonctions ***/
/*****************/
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

// Change la couleur des radio boutons (saisie film)
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

// Affiche ou masque des films
function afficherMasquerFilms(month)
{
  switch (month)
  {
    case "fold_all":
      for (var i = 1; i <= 12; i++)
      {
        if (i < 10)
        {
          if ($('#hide_films_0' + i).length && $('#lien_hide_0' + i).length)
          {
            $('#hide_films_0' + i).css('display', 'none');
            $('#lien_hide_0' + i).html('+');
          }
        }
        else
        {
          if ($('#hide_films_' + i).length && $('#lien_hide_' + i).length)
          {
            $('#hide_films_' + i).css('display', 'none');
            $('#lien_hide_' + i).html('+');
          }
        }
      }
      break;

    case "unfold_all":
      for (var i = 1; i <= 12; i++)
      {
        if (i < 10)
        {
          if ($('#hide_films_0' + i).length && $('#lien_hide_0' + i).length)
          {
            $('#hide_films_0' + i).css('display', 'block');
            $('#lien_hide_0' + i).html('-');
          }
        }
        else
        {
          if ($('#hide_films_' + i).length && $('#lien_hide_' + i).length)
          {
            $('#hide_films_' + i).css('display', 'block');
            $('#lien_hide_' + i).html('-');
          }
        }
      }
      break;

    default:
      if ($('#hide_films_' + month).css('display') == "none")
      {
        $('#hide_films_' + month).css('display', 'block');
        $('#lien_hide_' + month).html('-');
      }
      else
      {
        $('#hide_films_' + month).css('display', 'none');
        $('#lien_hide_' + month).html('+');
      }
      break;
  }

  initPositionChat();
}

// Fonction initialisation position chat
function initPositionChat()
{
  var total_height = $('body')[0].scrollHeight - $(window).height();
  var difference   = $('footer').height() - (total_height - $(window).scrollTop());

  $("#zone_chat_position").css('display', 'block');

  if (difference > 0)
    $('#zone_chat_position').css('bottom', difference + 'px');
  else
    $('#zone_chat_position').css('bottom', '0px');
}

// Affiche la saisie préférence d'un film
function afficherSaisiePreference(titre, stars, view, year, id_film)
{
  var html;

  html  = '<div class="fond_saisie_preference">';
    html += '<div class="zone_saisie_preference">';
      // Zone titre
      html += '<div class="titre_saisie_preference">';
        // Bouton fermeture
        html += '<a id="masquerPreference" class="close_preference"><img src="/inside/includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

        // Titre
        html += '<div class="titre_preference">Votre préférence pour "' + titre + '"</div>';
      html += '</div>';

      // Etoiles
      html += '<form method="post" action="moviehouse.php?view=' + view + '&year=' + year + '&action=doVoterFilm" class="form_saisie_preference">';
        html += '<input type="hidden" name="id_film" value="' + id_film + '" />';

        for (var i = 0; i <= 5; i++)
        {
          html += '<img src="/inside/includes/icons/moviehouse/stars/star' + i + '.png" alt="star' + i + '" class="icone_preference" />';

          if (i == stars)
            html += '<input type="submit" name="preference_' + i + '" value="" class="input_preference rounded" />';
          else
            html += '<input type="submit" name="preference_' + i + '" value="" class="input_preference" />';
        }
      html += '</form>';
    html += '</div>';
  html += '</div>';

  $('body').append(html);

  $('.fond_saisie_preference').hide().fadeIn(200);
}

// Masque la saisie préférence d'un film
function masquerSaisiePreference()
{
  $('.fond_saisie_preference').fadeOut(200, function()
  {
    $(this).remove();
  });
}

// Affiche ou masque les films cachés
function afficherMasquerTbody(id, hidden)
{
  if ($('#' + hidden).html() == '<div class="symbol_hidden">+</div> Films cachés')
    $('#' + hidden).html('<div class="symbol_hidden">-</div> Films cachés');
  else
    $('#' + hidden).html('<div class="symbol_hidden">+</div> Films cachés');

  if ($('#' + id).css('display') == 'none')
    $('#' + id).css('display', 'table-row-group');
  else
    $('#' + id).css('display', 'none');
}

// Insère un smiley dans la zone de saisie
function insert_smiley(smiley, id)
{
  var chars = "";

  switch (smiley)
  {
    case '1':
      chars = ':)';
      break;

    case '2':
      chars = ';)';
      break;

    case '3':
      chars = ':(';
      break;

    case '4':
      chars = ':|';
      break;

    case '5':
      chars = ':D';
      break;

    case '6':
      chars = ':O';
      break;

    case '7':
      chars = ':P';
      break;

    case '8':
      chars = ':facepalm:';
      break;

    default:
      break;
  }

  // Texte à insérer
  var texte = chars + " ";

  // Ajout du texte au contenu déjà présent
  $('#' + id).append(texte);

  // Positionnement du curseur
  $('#' + id).focus();
}

// Modification d'un film
function updateFilm(zone)
{
  var titre  = "Modifier un film";
  var bouton = "Modifier le film";
  var action = 'details.php?action=doModifier';

  // Affichage zone de saisie
  afficherMasquer(zone);

  // Modification des données
  $('.titre_saisie_film').html(titre);
  $('.form_saisie_film').attr('action', action);

  $('input[name=id_film]').val(detailsFilm['id']);
  $('input[name=nom_film]').val(detailsFilm['film']);
  $('input[name=date_theater]').val(detailsFilm['date_theater']);
  $('input[name=date_release]').val(detailsFilm['date_release']);
  $('input[name=trailer]').val(detailsFilm['trailer']);
  $('input[name=link]').val(detailsFilm['link']);
  $('input[name=poster]').val(detailsFilm['poster']);
  $('textarea[name=synopsis]').html(detailsFilm['synopsis']);

  $('input[name=doodle]').val(detailsFilm['doodle']);
  $('input[name=date_doodle]').val(detailsFilm['date_doodle']);
  $('input[name=place]').val(detailsFilm['place']);

  switch (detailsFilm['restaurant'])
  {
    case "N":
      switchCheckedColor('switch_restaurant', 'bouton_none');
      break;

    case "B":
      switchCheckedColor('switch_restaurant', 'bouton_before');
      break;

    case "A":
      switchCheckedColor('switch_restaurant', 'bouton_after');
      break;

    default:
      break;
  }

  if (detailsFilm['hours_doodle'] != '')
    $('select[name=hours_doodle]').val(detailsFilm['hours_doodle']);
  else
    $('select[name=hours_doodle]').val('');

  if (detailsFilm['minutes_doodle'] != '')
    $('select[name=minutes_doodle]').val(detailsFilm['minutes_doodle']);
  else
    $('select[name=minutes_doodle]').val('');

  $('.saisie_bouton').val(bouton);
}
