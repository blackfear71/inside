/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie de la vue
  $('#afficherSaisieVue, #fermerSaisieVue').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_vue');
  });

  // Ouvre ou ferme la zone de saisie d'année
  $('#afficherSaisieAnnee, #fermerSaisieAnnee').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_annee');
  });

  // Ouvre ou ferme la zone de saisie d'une dépense
  $('#afficherSaisieFilm, #fermerSaisieFilm').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_film');
  });

  // Affiche la zone de modification d'un film
  $('#modifierFilm, #doodleFilm').click(function()
  {
    initialisationModification('zone_saisie_film');
  });

  // Réinitialise la saisie à la fermeture au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'un film
    if ($(event.target).attr('class') == 'fond_saisie')
    {
      switch (event.target.id)
      {
        case 'zone_saisie_film':
          afficherMasquerIdWithDelay('zone_saisie_film');
          break;

        case 'zone_saisie_preference':
          afficherMasquerIdWithDelay('zone_saisie_preference');
          break;

        default:
          break;
      }
    }
  });

  // Change la couleur des switch restaurant film
  $('.label_switch').click(function()
  {
    var idBouton = $(this).closest('div').attr('id');

    switchCheckedColor('switch_restaurant', idBouton);
  });

  // Affiche la saisie de préférence d'une fiche
  $('.afficherSaisiePreference').click(function()
  {
    var idFilm            = $(this).attr('id').replace('preference_fiche_', '');
    var titreFilm         = $('#titre_film_' + idFilm).val();
    var voteFilm          = $('#vote_film_' + idFilm).val();
    var participationFilm = $('#participation_film_' + idFilm).val();
    var view              = $_GET('view');
    var year              = $_GET('year');

    afficherSaisiePreference(titreFilm, voteFilm, participationFilm, view, year, idFilm);
  });

  // Ferme la saisie de préférence d'une fiche
  $('#fermerSaisiePreference').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_preference');
  });

  // Affiche la zone de modification d'un commentaire
  $('.modifierCommentaire').click(function()
  {
    var idComment = $(this).attr('id').replace('modifier_commentaire_', '');

    afficherMasquerIdNoDelay('modifier_comment_' + idComment);
    afficherMasquerIdNoDelay('visualiser_comment_' + idComment);
    afficherMasquerIdNoDelay('actions_visualisation_comment_' + idComment);
  });

  // Masque la zone de modification d'un commentaire
  $('.annulerCommentaire').click(function()
  {
    var idComment = $(this).attr('id').replace('annuler_commentaire_', '');

    afficherMasquerIdNoDelay('modifier_comment_' + idComment);
    afficherMasquerIdNoDelay('visualiser_comment_' + idComment);
    afficherMasquerIdNoDelay('actions_visualisation_comment_' + idComment);
  });

  // Insère un smiley en saisie / modification de commentaire
  $('.ajouterSmiley, .modifierSmiley').click(function()
  {
    var idSmiley  = $(this).attr('id').split('_');
    var idComment = idSmiley[idSmiley.length - 1];
    var smiley    = idSmiley[idSmiley.length - 2];

    insertSmiley(smiley, 'textarea_comment_' + idComment);
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Déclenchement du scroll
  var id     = $_GET('anchor');
  var offset = 0.1;
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
// Affiche la saisie préférence d'un film
function afficherSaisiePreference(titre, vote, participation, view, year, idFilm)
{
  // Titre
  $('#zone_saisie_preference').find('.zone_titre_saisie').html(formatString(titre, 30));

  // Modification des données (étoiles)
  $('.form_saisie_preference').attr('action', 'moviehouse.php?view=' + view + '&year=' + year + '&action=doVoterFilm');
  $('.form_saisie_preference').find('input[name=id_film]').val(idFilm);

  $.each($('.form_saisie_preference').find('input[type=submit]'), function()
  {
    preference = $(this).attr('name').replace('preference_', '');

    if (preference == vote)
      $(this).addClass('rounded');
    else
      $(this).removeClass('rounded');
  });

  // Modification des données (participation)
  if (vote == 0)
    $('.form_saisie_participation').css('display', 'none');
  else
  {
    $('.form_saisie_participation').css('display', 'block');
    $('.form_saisie_participation').attr('action', 'moviehouse.php?view=' + view + '&year=' + year + '&action=doParticiperFilm');
    $('.form_saisie_participation').find('input[name=id_film]').val(idFilm);

    if (participation == 'P')
    {
      $('.form_saisie_participation').find('input[name=participate]').addClass('input_participate_yes');
      $('.form_saisie_participation').find('input[name=participate]').removeClass('input_participate_no');
    }
    else
    {
      $('.form_saisie_participation').find('input[name=participate]').removeClass('input_participate_no');
      $('.form_saisie_participation').find('input[name=participate]').addClass('input_participate_no');
    }

    if (participation == 'S')
    {
      $('.form_saisie_participation').find('input[name=seen]').addClass('input_seen_yes');
      $('.form_saisie_participation').find('input[name=seen]').removeClass('input_seen_no');
    }
    else
    {
      $('.form_saisie_participation').find('input[name=seen]').removeClass('input_seen_yes');
      $('.form_saisie_participation').find('input[name=seen]').addClass('input_seen_no');
    }
  }

  // Affichage zone de saisie
  afficherMasquerIdWithDelay('zone_saisie_preference');
}

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

// Modification d'un film
function initialisationModification(zone)
{
  var titre  = 'Modifier un film';
  var action = 'details.php?action=doModifierMobile';

  // Conversion des dates
  var dateTheater = formatDateForDisplayMobile(detailsFilm['date_theater']);
  var dateRelease = formatDateForDisplayMobile(detailsFilm['date_release']);
  var dateDoodle  = formatDateForDisplayMobile(detailsFilm['date_doodle']);

  // Modification des données (film)
  $('.zone_titre_saisie').html(titre);
  $('.form_saisie').attr('action', action);
  $('input[name=id_film]').val(detailsFilm['id']);
  $('input[name=nom_film]').val(detailsFilm['film']);
  $('input[name=date_theater]').val(dateTheater);
  $('input[name=date_release]').val(dateRelease);
  $('input[name=trailer]').val(detailsFilm['trailer']);
  $('input[name=link]').val(detailsFilm['link']);
  $('input[name=poster]').val(detailsFilm['poster']);
  $('textarea[name=synopsis]').html(detailsFilm['synopsis']);

  // Modification des données (sortie)
  $('input[name=doodle]').val(detailsFilm['doodle']);
  $('input[name=date_doodle]').val(dateDoodle);
  $('input[name=place]').val(detailsFilm['place']);

  // Modification des données (restaurant)
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

  // Affichage zone de saisie
  afficherMasquerIdWithDelay(zone);
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
