/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche la zone de saisie d'un film
  $('#ajouterFilm, #annulerFilm').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_film');
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'un film
    if ($(event.target).attr('class') == 'fond_saisie_film')
      closeInput('zone_saisie_film');

    // Ferme la saisie préférence (vue fiches)
    if ($(event.target).attr('class') == 'fond_saisie_preference')
      masquerSupprimerIdWithDelay('fond_preference');
  });

  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_film').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Change la couleur des switch restaurant film
  $('.label_switch').click(function()
  {
    var idBouton = $(this).closest('div').attr('id');

    switchCheckedColor('switch_restaurant', idBouton);
  });

  // Plie ou déplie les fiches des films
  $('#fold_all, #unfold_all, .cacherFilms').click(function()
  {
    var idFold = $(this).attr('id');

    if (idFold != 'fold_all' && idFold != 'unfold_all')
      idFold = $(this).attr('id').replace('lien_hide_', '');

    afficherMasquerFilms(idFold);
  });

  // Scroll vers le mois en cours
  $('.naviguerMois').click(function()
  {
    var fullDate     = new Date();
    var currentMonth = fullDate.getMonth() + 1;
    var offset       = 50;
    var shadow       = false;
    var idMonth;

    if (currentMonth < 10)
      idMonth = 'lien_hide_0' + currentMonth;
    else
      idMonth = 'lien_hide_' + currentMonth;

    if ($('#' + idMonth).length)
      scrollToId(idMonth, offset, shadow);
  });

  // Affiche la saisie de préférence d'une fiche
  $('.afficherPreference').click(function()
  {
    var idFilm    = $(this).attr('id').replace('fiche_', '');
    var titreFilm = $('#titre_film_' + idFilm).val();
    var voteFilm  = $('#vote_film_' + idFilm).val();
    var view      = $_GET('view');
    var year      = $_GET('year');

    afficherSaisiePreference(titreFilm, voteFilm, view, year, idFilm);
  });

  // Masque la saisie de préférence d'une fiche
  $(document).on('click', '#masquerPreference', function()
  {
    masquerSupprimerIdWithDelay('fond_preference');
  });

  // Redirige vers le détail des films au clic Doodle des fiches
  $('.lienDetails').click(function()
  {
    var idFilm = $(this).attr('id').replace('lien_details_', '');

    document.location.href = 'details.php?id_film=' + idFilm + '&doodle=true&action=goConsulter';
  });

  // Affiche la zone de modification d'un film
  $('#modifierFilm, #doodleFilm').click(function()
  {
    initialisationModification('zone_saisie_film');
  });

  // Affiche la zone de modification d'un commentaire
  $('.modifierCommentaire').click(function()
  {
    var idComment = $(this).attr('id').replace('modifier_commentaire_', '');

    afficherMasquerIdNoDelay('modifier_comment_' + idComment);
    afficherMasquerIdNoDelay('visualiser_comment_' + idComment);
    afficherMasquerIdNoDelay('actions_comment_' + idComment);
  });

  // Masque la zone de modification d'un commentaire
  $('.annulerCommentaire').click(function()
  {
    var idComment = $(this).attr('id').replace('annuler_commentaire_', '');

    afficherMasquerIdNoDelay('modifier_comment_' + idComment);
    afficherMasquerIdNoDelay('visualiser_comment_' + idComment);
    afficherMasquerIdNoDelay('actions_comment_' + idComment);
  });

  // Insère un smiley en saisie/modification de commentaire
  $('.ajouterSmiley, .modifierSmiley').click(function()
  {
    var idSmiley  = $(this).attr('id').split('_');
    var idComment = idSmiley[idSmiley.length - 1];
    var smiley    = idSmiley[idSmiley.length - 2];

    insertSmiley(smiley, 'textarea_comment_' + idComment);
  });

  /*** Calendriers ***/
  if ($('#datepicker_sortie_1').length || $('#datepicker_sortie_2').length || $('#datepicker_doodle').length)
  {
    $('#datepicker_sortie_1, #datepicker_sortie_2, #datepicker_doodle').datepicker(
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
  adaptMovies();
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet (on lance Masonry et le scroll après avoir chargé les images)
$(window).on('load', function()
{
  // Adaptation mobile
  adaptMovies();

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

  if (doodle == 'true')
    initialisationModification('zone_saisie_film');
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ferme la saisie d'un film
function closeInput(id)
{
  if ($('#' + id).css('display') != 'none')
    afficherMasquerIdWithDelay(id);
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
    case 'fold_all':
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

    case 'unfold_all':
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
      if ($('#hide_films_' + month).css('display') == 'none')
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

// Affiche la saisie préférence d'un film
function afficherSaisiePreference(titre, stars, view, year, idFilm)
{
  var html = '';

  html += '<div id="fond_preference" class="fond_saisie_preference">';
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
        html += '<input type="hidden" name="id_film" value="' + idFilm + '" />';

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

// Insère un smiley dans la zone de saisie
function insertSmiley(smiley, id)
{
  var chars = '';

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
  var texte = chars + ' ';

  // Ajout du texte au contenu déjà présent
  $('#' + id).val($('#' + id).val() + texte);

  // Positionnement du curseur
  $('#' + id).focus();
}

// Modification d'un film
function initialisationModification(zone)
{
  var titre  = 'Modifier un film';
  var bouton = 'Modifier le film';
  var action = 'details.php?action=doModifier';

  // Conversion des dates
  var dateTheater = formatDateForDisplay(detailsFilm['date_theater']);
  var dateRelease = formatDateForDisplay(detailsFilm['date_release']);
  var dateDoodle  = formatDateForDisplay(detailsFilm['date_doodle']);

  // Modification des données
  $('.titre_saisie_film').html(titre);
  $('.form_saisie_film').attr('action', action);

  $('input[name=id_film]').val(detailsFilm['id']);
  $('input[name=nom_film]').val(detailsFilm['film']);
  $('input[name=date_theater]').val(dateTheater);
  $('input[name=date_release]').val(dateRelease);
  $('input[name=trailer]').val(detailsFilm['trailer']);
  $('input[name=link]').val(detailsFilm['link']);
  $('input[name=poster]').val(detailsFilm['poster']);
  $('textarea[name=synopsis]').html(detailsFilm['synopsis']);

  $('input[name=doodle]').val(detailsFilm['doodle']);
  $('input[name=date_doodle]').val(dateDoodle);
  $('input[name=place]').val(detailsFilm['place']);

  switch (detailsFilm['restaurant'])
  {
    case 'N':
      switchCheckedColor('switch_restaurant', 'bouton_none');
      break;

    case 'B':
      switchCheckedColor('switch_restaurant', 'bouton_before');
      break;

    case 'A':
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

  // Affichage zone de saisie
  afficherMasquerIdWithDelay(zone);
}

// Adaptations des films sur mobile
function adaptMovies()
{
  if ($(window).width() < 1080)
  {
    // Accueil et fiches
    if ($('.zone_movies_left').length && $('.zone_movies_right').length)
    {
      $('.zone_movies_left').css('display', 'block');
      $('.zone_movies_left').css('width', '100%');

      $('.zone_movies_right').css('display', 'block');
      $('.zone_movies_right').css('width', '100%');
      $('.zone_movies_right').css('margin-left', '0');
    }

    // Détails
    if ($('.zone_details_left').length && $('.zone_details_right').length)
    {
      $('.zone_details_left').css('display', 'block');
      $('.zone_details_left').css('width', '100%');

      $('.zone_details_right').css('display', 'block');
      $('.zone_details_right').css('width', '100%');
      $('.zone_details_right').css('margin-left', '0');

      $('.zone_details_poster').css('width', '100%');

      $('.zone_details_actions').css('float', 'right');
      $('.zone_details_actions').css('margin-right', '-50px');

      $('.form_vote_right').css('width', '30%');
      $('.form_vote_right').css('margin-right', '0');

      $('.zone_details_votes').css('width', '100%');

      $('.video_container').css('margin-top', '20px');
    }

    // Saisie film
    if ($('.zone_saisie_film').length)
    {
      $('.zone_saisie_left').css('display', 'block');
      $('.zone_saisie_left').css('width', 'calc(100% - 20px)');

      $('.zone_saisie_right').css('display', 'block');
      $('.zone_saisie_right').css('width', 'calc(100% - 20px)');
      $('.zone_saisie_right').css('margin-top', '-12px');
    }
  }
  else
  {
    // Accueil et fiches
    if ($('.zone_movies_left').length && $('.zone_movies_right').length)
    {
      $('.zone_movies_left').css('display', 'inline-block');
      $('.zone_movies_left').css('width', '200px');

      $('.zone_movies_right').css('display', 'inline-block');
      $('.zone_movies_right').css('width', 'calc(100% - 220px)');
      $('.zone_movies_right').css('margin-left', '20px');
    }

    // Détails
    if ($('.zone_details_left').length && $('.zone_details_right').length)
    {
      $('.zone_details_left').css('display', 'inline-block');
      $('.zone_details_left').css('width', '40%');

      $('.zone_details_right').css('display', 'inline-block');
      $('.zone_details_right').css('width', 'calc(60% - 20px)');
      $('.zone_details_right').css('margin-left', '20px');

      $('.zone_details_poster').css('width', 'calc(100% - 50px)');

      $('.zone_details_actions').css('display', 'inline-block');
      $('.zone_details_actions').css('margin-right', '0');

      $('.form_vote_right').css('width', 'calc(30% - 50px)');
      $('.form_vote_right').css('margin-right', '50px');

      $('.zone_details_votes').css('width', 'calc(100% - 50px)');

      $('.video_container').css('margin-top', '-20px');
    }

    // Saisie film
    if ($('.zone_saisie_film').length)
    {
      $('.zone_saisie_left').css('display', 'inline-block');
      $('.zone_saisie_left').css('width', 'calc(50% - 20px)');

      $('.zone_saisie_right').css('display', 'inline-block');
      $('.zone_saisie_right').css('width', 'calc(50% - 20px)');
      $('.zone_saisie_right').css('margin-top', '0');
    }
  }
}
