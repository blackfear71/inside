/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche la saisie lieu restaurant
  $(document).on('click', '.afficherLieu', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_restaurant_', '');
    var id_listbox = 'zone_listbox_restaurant_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherListboxLieux(id_listbox);
  });

  // Affiche la saisie horaire restaurant
  $(document).on('click', '.afficherHoraire', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_horaire_', '');
    var id_listbox = 'zone_listbox_horaire_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherListboxHoraires(id_listbox, id_bouton, 'create', '');
  });

  // Affiche la saisie transports restaurant
  $(document).on('click', '.afficherTransports', function()
  {
    var id_bouton   = $(this).attr('id');
    var num         = $(this).attr('id').replace('choix_transports_', '');
    var id_checkbox = 'zone_checkbox_transports_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherCheckboxTransports(id_checkbox);
  });

  // Affiche la saisie menu restaurant
  $(document).on('click', '.afficherMenu', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_menu_', '');
    var id_saisie = 'zone_saisie_menu_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherSaisieMenu(id_saisie);
  });

  // Masque la saisie lieu restaurant
  $(document).on('click', '.annulerLieu', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_restaurant_', '');
    var id_zone    = 'zone_listbox_' + num;

    cacherListboxRestaurants(id_zone, id_annuler);
  });

  // Masque la saisie horaire restaurant
  $(document).on('click', '.annulerHoraire', function()
  {
    var id_annuler  = $(this).attr('id');
    var num         = $(this).attr('id').replace('annuler_horaires_', '');
    var id_zone     = 'choix_horaire_' + num;
    var id_select_h = 'select_heures_' + num;
    var id_select_m = 'select_minutes_' + num;

    cacherListboxHoraires(id_zone, id_annuler, id_select_h, id_select_m);
  });

  // Masque la saisie transports restaurant
  $(document).on('click', '.annulerTransports', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_transports_', '');
    var id_zone    = 'zone_checkbox_' + num;

    cacherCheckboxTransports(id_zone, id_annuler);
  });

  // Masque la saisie menu restaurant
  $(document).on('click', '.annulerMenu', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_menu_', '');
    var id_zone    = 'zone_menu_' + num;

    cacherSaisieMenu(id_zone, id_annuler);
  });

  // Coche / décoche le mode de transport
  $(document).on('click', '.cocherTransport', function()
  {
    var id_check = $(this).closest('div').attr('id');

    changeCheckedColor(id_check);
  });
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_propositions_determination').css('display', 'block');
  $('.zone_restaurants').css('display', 'block');

  // Calcul automatique des tailles des zones
  tailleAutoZones();

  // Masonry (Propositions)
  if ($('.zone_propositions').length)
  {
    $('.zone_propositions').masonry('destroy');

    $('.zone_propositions').masonry({
      // Options
      itemSelector: '.zone_proposition, #zone_solo_no_votes, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
      columnWidth: 200,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_propositions').addClass('masonry');
  }

  // Masonry (Restaurants)
  if ($('.zone_fiches_restaurants').length)
  {
    $('.zone_fiches_restaurants').masonry('destroy');

    $('.zone_fiches_restaurants').masonry({
      // Options
      itemSelector: '.fiche_restaurant',
      columnWidth: 500,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_fiches_restaurants').addClass('masonry');
  }

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 60;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // Masonry (Propositions)
  $('.zone_propositions').masonry({
    // Options
    itemSelector: '.zone_proposition, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
    columnWidth: 200,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // Masonry (Restaurants)
  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 500,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // Découpe le texte si besoin
  $('.description_restaurant').wrapInner();
}

// Calcul les tailles des zones automatiquement
function tailleAutoZones()
{
  // Taille de la zone bande à part / sans votes en fonction de la zone suivante si elle existe
  if ($('#zone_first').length && ($('#zone_solo').length || $('#zone_no_votes').length))
  {
    var hauteur_min = $('#zone_first').height();

    $('#zone_solo_no_votes').css('min-height', hauteur_min);

    // Les 2 zones sont présentes
    if ($('#zone_solo').length && $('#zone_no_votes').length)
      $('#zone_no_votes').css('min-height', hauteur_min - $('#zone_solo').height() - 20);

    // Il n'y a que la zone "bande à part"
    if (!$('#zone_no_votes').length)
      $('#zone_solo').css('min-height', hauteur_min);

    // Il n'y a que la zone des non votants
    if (!$('#zone_solo').length)
      $('#zone_no_votes').css('min-height', hauteur_min);
  }

  // Taille des zones de résumé en fonction de la plus grande
  var day_height     = $('.jour_semaine').height() + 10;
  var text_height    = $('.no_proposal').height();
  var max_height     = Math.max($('#zone_resume_lundi').height(), $('#zone_resume_mardi').height(), $('#zone_resume_mercredi').height(), $('#zone_resume_jeudi').height(), $('#zone_resume_vendredi').height());
  var calcul_padding = (max_height - (day_height + text_height + 10)) / 2;

  $('.no_proposal').css('padding-top', calcul_padding);
  $('.no_proposal').css('padding-bottom', calcul_padding);
  $('.zone_proposition_resume').css('min-height', max_height);
}

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

// Affiche la listbox des lieux
function afficherListboxLieux(id)
{
  var num        = id.substr(-1);
  var id_zone    = 'zone_listbox_' + num;
  var id_select  = 'select_lieu_' + num;
  var id_annuler = 'annuler_restaurant_' + num;
  var html;

  html = '<div id="' + id_zone + '" class="zone_listbox_restaurant">';
    html += '<select id="' + id_select + '" name="select_lieu[' + num + ']" class="listbox_choix" onchange="afficherListboxRestaurants(\'' + id_select + '\', \'' + id_zone + '\', \'' + id_annuler + '\')" required>';
      html += '<option value="" hidden>Choisissez...</option>';
      $.each(listeLieux, function(key, value)
      {
        html += '<option value="' + value + '">' + value + '</option>';
      });
    html += '</select>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerLieu" style="margin-top: 10px;">Annuler</a>';

  $("#" + id).append(html);
}

// Affiche la listbox des restaurants associés
function afficherListboxRestaurants(id, zone, bouton)
{
  var lieu        = $('#' + id).val();
  var num         = id.substr(-1);
  var id_select_2 = 'select_restaurant_' + num;
  var html;

  if ($('#' + id_select_2).length)
    $('#' + id_select_2).remove();

  html = '<select id="' + id_select_2 + '" name="select_restaurant[' + num + ']" class="listbox_choix" required>';
    html += '<option value="" hidden>Choisissez...</option>';
    $.each(listeRestaurants[lieu], function(key, value)
    {
      html += '<option value="' + value.id + '">' + value.name + '</option>';
    });
  html += '</select>';

  $("#" + zone).append(html);
  $("#" + bouton).css('margin-top', '31px');
}

// Cache les lisbox des restaurants
function cacherListboxRestaurants(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_restaurant_' + num).css('display', 'block');
}

// Affiche les listbox des horaires
function afficherListboxHoraires(id, zone, type, idChoix)
{
  var html;
  var num;
  var name_select_h;
  var name_select_m;
  var class_listbox;
  var class_bouton;

  if (type == "create")
  {
    num           = id.substr(-1);
    name_select_h = 'select_heures[' + num + ']';
    name_select_m = 'select_minutes[' + num + ']';
    class_listbox = "listbox_horaires";
    class_bouton  = "bouton_annuler";
  }
  else if (type == "update")
  {
    num           = idChoix;
    name_select_h = 'select_heures_' + num;
    name_select_m = 'select_minutes_' + num;
    class_listbox = "listbox_horaires_update";
    class_bouton  = "bouton_annuler_update";
  }

  var id_select_h = 'select_heures_' + num;
  var id_select_m = 'select_minutes_' + num;
  var id_annuler  = 'annuler_horaires_' + num;
  var id_zone     = zone;

  html = '<select id="' + id_select_h + '" name="' + name_select_h + '" class="' + class_listbox + '">';
    for (var i = 11; i < 14; i++)
    {
      if (i == 12)
        html += '<option value="' + i + '" selected>' + i + '</option>';
      else
        html += '<option value="' + i + '">' + i + '</option>';
    }
  html += '</select>';

  html += '<select id="' + id_select_m + '" name="' + name_select_m + '" class="' + class_listbox + '">';
    for (var j = 0; j < 4; j++)
    {
      if (j == 0)
        html += '<option value="0' + j + '" selected>0' + j + '</option>';
      else
        html += '<option value="' + j*15 + '">' + j*15 + '</option>';
    }
  html += '</select>';

  html += '<a id="' + id_annuler + '" class="' + class_bouton + ' annulerHoraire">Annuler</a>';

  $("#" + id).append(html);
}

// Cache les lisbox des horaires
function cacherListboxHoraires(zone, bouton, heures, minutes)
{
  var num = heures.substr(-1);

  $('#' + heures).remove();
  $('#' + minutes).remove();
  $('#' + bouton).remove();
  $('#' + zone).css('display', 'block');
}

// Affiche les checkbox des transports
function afficherCheckboxTransports(id)
{
  var html;
  var num        = id.substr(-1);
  var id_zone    = "zone_checkbox_" + num;
  var id_check_f = 'checkbox_feet_' + num;
  var id_check_b = 'checkbox_bike_' + num;
  var id_check_t = 'checkbox_tram_' + num;
  var id_check_c = 'checkbox_car_' + num;
  var id_annuler = 'annuler_transports_' + num;

  html = '<div id="' + id_zone + '" class="zone_checkbox">';
    html += '<div id="bouton_' + id_check_f + '" class="switch_transport">';
      html += '<input id="' + id_check_f + '" type="checkbox" value="F" name="checkbox_feet[' + num + ']" />';
      html += '<label for="' + id_check_f + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_b + '" class="switch_transport">';
      html += '<input id="' + id_check_b + '" type="checkbox" value="B" name="checkbox_bike[' + num + ']" />';
      html += '<label for="' + id_check_b + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_t + '" class="switch_transport">';
      html += '<input id="' + id_check_t + '" type="checkbox" value="T" name="checkbox_tram[' + num + ']" />';
      html += '<label for="' + id_check_t + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_c + '" class="switch_transport">';
      html += '<input id="' + id_check_c + '" type="checkbox" value="C" name="checkbox_car[' + num + ']" />';
      html += '<label for="' + id_check_c + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
    html += '</div>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerTransports" style="margin-top: 25px;">Annuler</a>';

  $("#" + id).append(html);
}

// Cache les checkbox des transports
function cacherCheckboxTransports(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_transports_' + num).css('display', 'block');
}

// Affiche la saisie du menu
function afficherSaisieMenu(id)
{
  var html;
  var num        = id.substr(-1);
  var id_zone    = "zone_menu_" + num;
  var id_entree  = 'saisie_entree_' + num;
  var id_plat    = 'saisie_plat_' + num;
  var id_dessert = 'saisie_dessert_' + num;
  var id_annuler = 'annuler_menu_' + num;

  html = '<div id="' + id_zone + '" class="zone_saisie_menu">';
    html += '<input type="text" placeholder="Entrée" name="saisie_entree[' + num + ']" class="saisie_menu" />';
    html += '<input type="text" placeholder="Plat" name="saisie_plat[' + num + ']" class="saisie_menu" />';
    html += '<input type="text" placeholder="Dessert" name="saisie_dessert[' + num + ']" class="saisie_menu" />';
  html += '</div>';

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerMenu" style="margin-top: 30px;">Annuler</a>';

  $("#" + id).append(html);
}

// Cache la saisie du menu
function cacherSaisieMenu(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_menu_' + num).css('display', 'block');
}

// Génère une nouvelle zone pour saisir un choix
function addChoice(id, zone)
{
  var html;
  var num     = $("#" + id + " span").length / 4;
  var new_num = num + 1;
  var icon;

  // Sélection du logo
  switch (new_num)
  {
    case 2:
      icon = "location_grey";
      break;

    case 3:
      icon = "menu_grey";
      break;

    case 4:
      icon = "feet";
      break;

    case 5:
      icon = "restaurants_grey";
      break;

    default:
      icon = "propositions_grey";
      break;
  }

  // On ajoute de nouveaux champs de saisie
  html  = '<div class="titre_choix"><img src="/inside/includes/icons/foodadvisor/' + icon + '.png" alt="' + icon + '" class="logo_proposition" />Proposition ' + new_num + '</div>';

  html += '<div id="zone_listbox_restaurant_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_restaurant_' + new_num + '" class="bouton_choix afficherLieu"><span class="fond_plus">+</span>Restaurant</a>';
  html += '</div>';

  html += '<div id="zone_listbox_horaire_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_horaire_' + new_num + '" class="bouton_choix afficherHoraire"><span class="fond_plus">+</span>Horaire</a>';
  html += '</div>';

  html += '<div id="zone_checkbox_transports_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_transports_' + new_num + '" class="bouton_choix afficherTransports"><span class="fond_plus">+</span>Transport</a>';
  html += '</div>';

  html += '<div id="zone_saisie_menu_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_menu_' + new_num + '" class="bouton_choix afficherMenu"><span class="fond_plus">+</span>Menu</a>';
  html += '</div>';

  html += '<div class="separation_choix"></div>';

  $("#" + id).append(html);

  // On remonte la zone de saisie sur l'écran
  var marge = 200 - num * 25 + 'px';
  $('#' + zone).animate({marginTop: marge}, 400);

  // On supprime le bouton d'ajout si on a 5 propositions
  if (new_num == 5)
    $('#saisie_autre_choix').css('display', 'none');
}

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  EXIF.getData(event.target.files[0], function()
  {
    var orientation = EXIF.getTag(this, "Orientation");
    var degrees     = 0;

    // Les valeurs sont inversées par rapport à la fonction rotateImage() dans fonctions_communes.php
    switch(orientation)
    {
      case 3:
        degrees = 180;
        break;

      case 6:
        degrees = 90;
        break;

      case 8:
        degrees = -90;
        break;

      case 1:
      default:
        degrees = 0;
        break;
    }

    output.setAttribute('style','transform: rotate(' + degrees + 'deg)');
  });
};

// Change la couleur des checkbox (saisie restaurant)
function changeCheckedColor(input)
{
  if ($('#' + input).children('input').prop('checked'))
    $('#' + input).removeClass('bouton_checked');
  else
    $('#' + input).addClass('bouton_checked');
}

// Change la couleur de fond lors de la saisie de texte
function changeTypeColor(id)
{
  if ($('#' + id).val() != "")
  {
    $('#' + id).css('background-color', '#70d55d');
    $('#' + id).css('color', 'white');
  }
  else
  {
    $('#' + id).css('background-color', '#e3e3e3');
    $('#' + id).css('color', '262626');
  }
}

// Génère une nouvelle zone pour saisir un type
function addOtherType(id)
{
  var html;
  var length     = $("#" + id + " input").length;
  var new_length = length + 1;
  var id_type    = id + '_' + new_length;

  html = '<input type="text" placeholder="Type" value="" id="' + id_type + '" name="' + id + '[' + new_length + ']" oninput="changeTypeColor(\'' + id_type + '\')" class="type_other" />';

  $("#" + id).append(html);
}

// Affiche ou masque la zone de saisie lieu "Autre" (insertion)
function afficherOther(select, id, name)
{
  if ($('#' + select).val() == 'other_location')
  {
    if ($('#' + id).css('display') == 'none')
    {
      $('#' + select).css('width', '200px');
      $('#' + name).css('width', 'calc(100% - 670px)');
      $('#' + id).css('width', '200px');
      $('#' + id).css('display', 'inline-block');
      $('#' + id).prop('required', true);
    }
  }
  else
  {
    $('#' + select).css('width', '180px');
    $('#' + name).css('width', 'calc(100% - 420px)');
    $('#' + id).css('display', 'none');
    $('#' + id).prop('required', false);
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if ($('#' + select).val() == 'other_location')
  {
    if ($('#' + id).css('display') == 'none')
    {
      $('#' + id).css('display', 'block');
      $('#' + id).prop('required', true);
    }
  }
  else
  {
    $('#' + id).css('display', 'none');
    $('#' + id).prop('required', false);
  }
}

// Fixe la couleur de fond lors du changement de statut
function changeCheckedDay(id_checkbox, id_label, class_checked, class_no_check)
{
  if ($('#' + id_checkbox).prop('checked') == true)
  {
    $('#' + id_label).removeClass(class_checked);
    $('#' + id_label).addClass(class_no_check);
  }
  else
  {
    $('#' + id_label).addClass(class_checked);
    $('#' + id_label).removeClass(class_no_check);
  }
}
