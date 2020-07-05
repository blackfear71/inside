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

    showDetails(idProposition);
  });

  // Ferme la zone de détails d'une proposition
  $('#fermerDetailsProposition').click(function()
  {
    afficherMasquerIdWithDelay('zone_details_proposition');
  });
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
function showDetails(idProposition)
{
  // Récupération des données
  var proposition = detailsPropositions[idProposition];
  var opened      = proposition['opened'].split(';');

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

  // Nom du restaurant
  $('.titre_details > .texte_titre_section').html(proposition['name']);

  // Jours d'ouverture
  var dateDuJour   = new Date();
  var availableDay = true;

  $.each(opened, function(key, value)
  {
    if (value != '')
    {
      if (value == 'Y')
        $('#jour_details_proposition_' + key).addClass('jour_oui_details');
      else
        $('#jour_details_proposition_' + key).addClass('jour_non_details');

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

  // Lieu
  $('.lieu_details').html(proposition['location']);

  // Nombre de participants
  var nombreParticipants;

  if (proposition['nb_participants'] == 1)
    nombreParticipants = proposition['nb_participants'] + " participant";
  else
    nombreParticipants = proposition['nb_participants'] + " participants";

  $('.nombre_participants_details').html(nombreParticipants);

  // Affichage des détails
  afficherMasquerIdWithDelay('zone_details_proposition');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, true);
  });
}
