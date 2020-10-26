/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie de propositions
  $('#afficherSaisiePropositions, #fermerSaisiePropositions').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisiePropositions');
  });

  // Change la couleur d'une case à cocher à la sélection
  $('label').click(function()
  {
    changeCheckedColor($(this));
  });

  // Affiche la zone de détails d'une proposition
  $('.afficherDetailsProposition').click(function()
  {
    var idProposition = $(this).attr('id').replace('details_proposition_', '');

    showDetailsProposition(idProposition);
  });

  // Ferme la zone de détails d'une proposition
  $('#fermerDetailsProposition').click(function()
  {
    afficherMasquerIdWithDelay('zone_details_proposition');
  });

  // Scroll vers un lieu
  $('.lienLieu').click(function()
  {
    // Déclenchement du scroll
    var idLieu = $(this).attr('id').replace('link_lieu_', '');
    var offset = 0.1;
    var shadow = false;

    // Scroll vers l'id
    scrollToId(idLieu, offset, shadow);
  });

  // Affiche la zone de détails d'un restaurant
  $('.afficherDetailsRestaurant').click(function(event)
  {
    if ($(event.target).attr('class') != 'form_saisie_rapide' && $(event.target).attr('class') != 'bouton_saisie_rapide')
    {
      var idProposition = $(this).attr('id');

      showDetailsRestaurant(idProposition);
    }
  });

  // Ferme la zone de détails d'un restaurant
  $('#fermerDetailsRestaurant').click(function()
  {
    afficherMasquerIdWithDelay('zone_details_restaurant');
  });
});

/**************/
/*** Scroll ***/
/**************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Déclenchement du scroll
  var id     = $_GET('anchor');
  var offset = 0.1;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Change la couleur d'un proposition
function changeCheckedColor(label)
{
  if (label.find('input').prop('checked'))
  {
    label.find('.image_normal').css('background-color', '#70d55d');
    label.find('.proposition_normal').css('background-color', '#96e687');
    label.find('.nom_normal').css('color', 'white');
    label.find('.zone_checkbox_proposition').css('background-color', '#70d55d');
  }
  else
  {
    label.find('.image_normal').css('background-color', '#d3d3d3');
    label.find('.proposition_normal').css('background-color', '#e3e3e3');
    label.find('.nom_normal').css('color', '#262626');
    label.find('.zone_checkbox_proposition').css('background-color', '#d3d3d3');
  }
}

// Affiche la zone de détails d'une proposition
function showDetailsProposition(idProposition)
{
  /***********/
  /* Données */
  /***********/
  // Récupération des données
  var proposition = detailsPropositions[idProposition];
  var opened      = proposition['opened'].split(';');

  // Nom du restaurant
  $('.titre_details > .texte_titre_section').html(proposition['name']);

  // Lieu
  $('.lieu_details').html(proposition['location']);

  // Image restaurant
  if (proposition['picture'] != '')
  {
    $('.image_details').attr('src', '../../includes/images/foodadvisor/' + proposition['picture']);
    $('.image_details').attr('alt', proposition['picture']);
    $('.image_details').attr('title', proposition['name']);
  }
  else
  {
    $('.image_details').attr('src', '../../includes/icons/foodadvisor/restaurants.png');
    $('.image_details').attr('alt', 'restaurants');
    $('.image_details').attr('title', proposition['name']);
  }

  // Indicateur réservation
  if (proposition['reserved'] == 'Y')
    $('#reserved_details').css('display', 'block');
  else
    $('#reserved_details').css('display', 'none');

  // Nombre de participants
  var nombreParticipants;

  if (proposition['nb_participants'] == 1)
    nombreParticipants = proposition['nb_participants'] + ' participant';
  else
    nombreParticipants = proposition['nb_participants'] + ' participants';

  $('.nombre_participants_details').html(nombreParticipants);

  // Jours d'ouverture
  var dateDuJour   = new Date();
  var availableDay = true;

  $.each(opened, function(key, value)
  {
    if (value != '')
    {
      if (value == 'Y')
      {
        $('#jour_details_' + key).addClass('jour_oui_details');
        $('#jour_details_' + key).removeClass('jour_non_details');
      }
      else
      {
        $('#jour_details_' + key).addClass('jour_non_details');
        $('#jour_details_' + key).removeClass('jour_oui_details');
      }

      if (dateDuJour.getDay() == key + 1 && value == 'N')
        availableDay = false;
    }
  });

  // Prix
  if (proposition['min_price'] != '' && proposition['max_price'] != '')
  {
    var prix;

    if (proposition['min_price'] == proposition['max_price'])
      prix = 'Prix ~ ' + formatAmountForDisplay(proposition['min_price'], true);
    else
      prix = 'Prix ' + formatAmountForDisplay(proposition['min_price'], false) + ' - ' + formatAmountForDisplay(proposition['max_price'], true);

    $('.prix_details').css('display', 'block');
    $('.prix_details').html(prix);
  }
  else
  {
    $('.prix_details').css('display', 'none');
    $('.prix_details').html('');
  }

  // Types du restaurant
  $('.zone_types_details').empty();

  if (proposition['types'] != '')
  {
    var types = proposition['types'].split(';');

    $.each(types, function()
    {
      if (this != '')
      {
        var type = '<div class="type_details">' + this + '</div>';
        $('.zone_types_details').append(type);
      }
    });
  }

  // Appelant & numéro de téléphone
  if (proposition['caller'] != '' || proposition['phone'] != '' || proposition['determined'] == 'Y')
  {
    if (proposition['caller'] != '' || proposition['phone'] != '')
    {
      var avatarAppelant = formatAvatar(proposition['avatar'], proposition['pseudo'], 2, 'avatar');

      $('.zone_appelant_details').css('display', 'block');

      if (proposition['phone'] != '')
        $('.telephone_details').html(proposition['phone']);
      else
        $('.telephone_details').empty();

      if (proposition['determined'] == 'Y' && proposition['caller'] != '')
      {
        $('.avatar_appelant_details').css('display', 'inline-block');
        $('.avatar_appelant_details').attr('src', avatarAppelant.path);
        $('.avatar_appelant_details').attr('alt', avatarAppelant.alt);
        $('.avatar_appelant_details').attr('title', avatarAppelant.title);
      }
      else
      {
        $('.avatar_appelant_details').css('display', 'none');
        $('.avatar_appelant_details').attr('src', '../../includes/icons/common/default.png');
        $('.avatar_appelant_details').attr('alt', 'avatar');
        $('.avatar_appelant_details').attr('title', 'avatar');
      }
    }
    else
      $('.zone_appelant_details').css('display', 'none');
  }
  else
  {
    $('.zone_appelant_details').css('display', 'none');
    $('.avatar_appelant_details').css('display', 'none');
    $('.avatar_appelant_details').attr('src', '../../includes/icons/common/default.png');
    $('.avatar_appelant_details').attr('title', 'avatar');
  }

  // Liens
  if (proposition['website'] != '' || proposition['plan'] != '' || proposition['lafourchette'] != '')
  {
    $('.zone_liens_details').css('display', 'block');

    if (proposition['website'] == '')
    {
      $('#website_details').css('display', 'none');
      $('#website_details').attr('href', '');
    }
    else
    {
      $('#website_details').css('display', 'inline-block');
      $('#website_details').attr('href', proposition['website']);
    }

    if (proposition['plan'] == '')
    {
      $('#plan_details').css('display', 'none');
      $('#plan_details').attr('href', '');
    }
    else
    {
      $('#plan_details').css('display', 'inline-block');
      $('#plan_details').attr('href', proposition['plan']);
    }

    if (proposition['lafourchette'] == '')
    {
      $('#lafourchette_details').css('display', 'none');
      $('#lafourchette_details').attr('href', '');
    }
    else
    {
      $('#lafourchette_details').css('display', 'inline-block');
      $('#lafourchette_details').attr('href', proposition['lafourchette']);
    }
  }
  else
  {
    $('.zone_liens_details').css('display', 'none');
    $('#website_details').attr('href', '');
    $('#plan_details').attr('href', '');
    $('#lafourchette_details').attr('href', '');
  }

  // Participants
  $('.zone_details_participants').empty();

  $.each(proposition['details'], function()
  {
    var ligne  = '';

    ligne += '<div class="zone_user_details">';
      // Avatar
      var avatarFormatted = formatAvatar(this['avatar'], this['pseudo'], 2, 'avatar');

      ligne += '<div class="zone_avatar_user_details">';
        ligne += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_user_details" />';
      ligne += '</div>';

      // Pseudo
      ligne += '<div class="pseudo_user_details">' + formatString(this['pseudo'], 30) + '</div>';
    ligne += '</div>';

    $('.zone_details_participants').append(ligne);
  });

  // Description
  $('.zone_details_texte').empty();

  if (proposition['description'] != '')
  {
    var description = '';

    description += '<div class="description_details">';
      description += nl2br(proposition['description']);
    description += '</div>';

    $('.zone_details_description').css('display', 'block');
    $('.zone_details_texte').append(description);
  }
  else
  {
    $('.zone_details_description').css('display', 'none');
    $('.zone_details_texte').empty();
  }

  /***********/
  /* Actions */
  /***********/
  // Vérification si l'utilisateur participe
  var participe = false;

  $.each(proposition['details'], function()
  {
    if (userSession == this['identifiant'])
    {
      participe = true;
      return false;
    }
  });

  // Vérification si l'utilisateur a réservé
  var reserved = false;

  if (proposition['reserved'] == 'Y' && userSession == proposition['caller'])
    reserved = true;

  // Bouton réservation (si on a participé)
  if (participe == true && proposition['reserved'] != 'Y')
  {
    $('#reserver_details').css('display', 'block');
    $('#reserver_details').attr('action', 'foodadvisor.php?action=doReserver');
  }
  else
  {
    $('#reserver_details').css('display', 'none');
    $('#reserver_details').attr('action', '');
  }

  // Bouton complet (si appelant sur choix déterminé)
  if (participe == true && proposition['reserved'] != 'Y' && proposition['determined'] == 'Y' && userSession == proposition['caller'])
  {
    $('#choice_complete_details').css('display', 'block');
    $('#choice_complete_details').attr('action', 'foodadvisor.php?action=doComplet');
  }
  else
  {
    $('#choice_complete_details').css('display', 'none');
    $('#choice_complete_details').attr('action', '');
  }

  // Bouton annulation réservation (si on a participé)
  if (reserved == true)
  {
    $('#annuler_details').css('display', 'block');
    $('#annuler_details').attr('action', 'foodadvisor.php?action=doAnnulerReserver');
  }
  else
  {
    $('#annuler_details').css('display', 'none');
    $('#annuler_details').attr('action', '');
  }

  // Id restaurant des boutons
  if (participe == true && proposition['reserved'] != 'Y')
    $('#reserver_details > input[name=id_restaurant]').val(idProposition);
  else
    $('#reserver_details > input[name=id_restaurant]').val('');

  if (participe == true && proposition['reserved'] != 'Y' && proposition['determined'] == 'Y' && userSession == proposition['caller'])
    $('#choice_complete_details > input[name=id_restaurant]').val(idProposition);
  else
    $('#choice_complete_details > input[name=id_restaurant]').val('');

  if (reserved == true)
    $('#annuler_details > input[name=id_restaurant]').val(idProposition);
  else
    $('#annuler_details > input[name=id_restaurant]').val('');

  // On cache la zone si tout est vide
  if ((!$('#reserver_details').length        || $('#reserver_details').css('display')        == 'none')
  &&  (!$('#choice_complete_details').length || $('#choice_complete_details').css('display') == 'none')
  &&  (!$('#annuler_details').length         || $('#annuler_details').css('display')         == 'none')
  &&  (!$('#reserved_details').length        || $('#reserved_details').css('display')        == 'none'))
    $('#indicateurs_details').css('display', 'none');
  else
    $('#indicateurs_details').css('display', 'block');

  // Bouton choix rapide
  if (participe == true || availableDay == false)
  {
    $('#choix_rapide_details').css('display', 'none');
    $('#choix_rapide_details').attr('action', '');
    $('#choix_rapide_details > input[name=id_restaurant]').val('');
  }
  else
  {
    $('#choix_rapide_details').css('display', 'block');
    $('#choix_rapide_details').attr('action', 'foodadvisor.php?action=doChoixRapide');
    $('#choix_rapide_details > input[name=id_restaurant]').val(idProposition);
  }

  /*************/
  /* Affichage */
  /*************/
  // Affichage des détails
  afficherMasquerIdWithDelay('zone_details_proposition');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, 'open');
  });
}

// Affiche la zone de détails d'un restaurant
function showDetailsRestaurant(idRestaurant)
{
  /***********/
  /* Données */
  /***********/
  // Récupération des données
  var restaurant = listeRestaurantsJson[idRestaurant];
  var opened     = restaurant['opened'].split(';');

  // Nom du restaurant
  $('.titre_details > .texte_titre_section').html(restaurant['name']);

  // Lieu
  $('.lieu_details').html(restaurant['location']);

  // Image restaurant
  if (restaurant['picture'] != '')
  {
    $('.image_details').attr('src', '../../includes/images/foodadvisor/' + restaurant['picture']);
    $('.image_details').attr('alt', restaurant['picture']);
    $('.image_details').attr('title', restaurant['name']);
  }
  else
  {
    $('.image_details').attr('src', '../../includes/icons/foodadvisor/restaurants.png');
    $('.image_details').attr('alt', 'restaurants');
    $('.image_details').attr('title', restaurant['name']);
  }

  // Jours d'ouverture
  var dateDuJour   = new Date();
  var availableDay = true;

  $.each(opened, function(key, value)
  {
    if (value != '')
    {
      if (value == 'Y')
      {
        $('#jour_details_' + key).addClass('jour_oui_details');
        $('#jour_details_' + key).removeClass('jour_non_details');
      }
      else
      {
        $('#jour_details_' + key).addClass('jour_non_details');
        $('#jour_details_' + key).removeClass('jour_oui_details');
      }

      if (dateDuJour.getDay() == key + 1 && value == 'N')
        availableDay = false;
    }
  });

  // Prix
  if (restaurant['min_price'] != '' && restaurant['max_price'] != '')
  {
    var prix;

    if (restaurant['min_price'] == restaurant['max_price'])
      prix = 'Prix ~ ' + formatAmountForDisplay(restaurant['min_price'], true);
    else
      prix = 'Prix ' + formatAmountForDisplay(restaurant['min_price'], false) + ' - ' + formatAmountForDisplay(restaurant['max_price'], true);

    $('.prix_details').css('display', 'block');
    $('.prix_details').html(prix);
  }
  else
  {
    $('.prix_details').css('display', 'none');
    $('.prix_details').html('');
  }

  // Types du restaurant
  $('.zone_types_details').empty();

  if (restaurant['types'] != '')
  {
    var types = restaurant['types'].split(';');

    $.each(types, function()
    {
      if (this != '')
      {
        var type = '<div class="type_details">' + this + '</div>';
        $('.zone_types_details').append(type);
      }
    });
  }

  // Numéro de téléphone
  if (restaurant['phone'] != '')
  {
    $('.zone_appelant_details').css('display', 'block');

    if (restaurant['phone'] != '')
      $('.telephone_details').html(restaurant['phone']);
    else
      $('.telephone_details').empty();
  }
  else
    $('.zone_appelant_details').css('display', 'none');

  // Liens
  if (restaurant['website'] != '' || restaurant['plan'] != '' || restaurant['lafourchette'] != '')
  {
    $('.zone_liens_details').css('display', 'block');

    if (restaurant['website'] == '')
    {
      $('#website_details').css('display', 'none');
      $('#website_details').attr('href', '');
    }
    else
    {
      $('#website_details').css('display', 'inline-block');
      $('#website_details').attr('href', restaurant['website']);
    }

    if (restaurant['plan'] == '')
    {
      $('#plan_details').css('display', 'none');
      $('#plan_details').attr('href', '');
    }
    else
    {
      $('#plan_details').css('display', 'inline-block');
      $('#plan_details').attr('href', restaurant['plan']);
    }

    if (restaurant['lafourchette'] == '')
    {
      $('#lafourchette_details').css('display', 'none');
      $('#lafourchette_details').attr('href', '');
    }
    else
    {
      $('#lafourchette_details').css('display', 'inline-block');
      $('#lafourchette_details').attr('href', restaurant['lafourchette']);
    }
  }
  else
  {
    $('.zone_liens_details').css('display', 'none');
    $('#website_details').attr('href', '');
    $('#plan_details').attr('href', '');
    $('#lafourchette_details').attr('href', '');
  }

  // Description
  $('.zone_details_texte').empty();

  if (restaurant['description'] != '')
  {
    var description = '';

    description += '<div class="description_details">';
      description += nl2br(restaurant['description']);
    description += '</div>';

    $('.zone_details_description').css('display', 'block');
    $('.zone_details_texte').append(description);
  }
  else
  {
    $('.zone_details_description').css('display', 'none');
    $('.zone_details_texte').empty();
  }

  /***********/
  /* Actions */
  /***********/
  // Vérification si l'utilisateur participe
  var participe = false;

  $.each(mesChoixJson, function()
  {
    if (idRestaurant == this['id_restaurant'])
    {
      participe = true;
      return false;
    }
  });

  // Bouton choix rapide
  if (participe == true || availableDay == false)
  {
    $('#choix_rapide_details').css('display', 'none');
    $('#choix_rapide_details').attr('action', '');
    $('#choix_rapide_details > input[name=id_restaurant]').val('');
  }
  else
  {
    $('#choix_rapide_details').css('display', 'block');
    $('#choix_rapide_details').attr('action', 'restaurants.php?action=doChoixRapide');
    $('#choix_rapide_details > input[name=id_restaurant]').val(idRestaurant);
  }

  /*************/
  /* Affichage */
  /*************/
  // Affichage des détails
  afficherMasquerIdWithDelay('zone_details_restaurant');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, 'open');
  });
}
