// Au chargement du document complet
$(window).load(function()
{
  // On lance Masonry et le scroll après avoir chargé les images
  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 400,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_fiches_restaurants').addClass('masonry');

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 60;

  // Scroll vers l'id
  scrollToId(id, offset);

  // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
  if (id != null)
  {
    $('#zone_shadow_' + id).css('box-shadow', '0 3px 10px #262626');

    setTimeout(function()
    {
      $('#zone_shadow_' + id).css('box-shadow', 'none');
      $('#zone_shadow_' + id).css({transition : "box-shadow ease 0.2s"});
    }, 5000);
  }
});

// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // On lance Masonry
  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 400,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
    /*transitionDuration: 0*/
  });

  // Découpe le texte si besoin
  $('.description_restaurant').wrapInner();
}

// Affiche ou masque un élément
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
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

// Fixe la couleur de fond lors de la sélection
function changeCheckedColor(id_checkbox, id_label)
{
  if (document.getElementById(id_checkbox).checked == true)
    document.getElementById(id_label).className = "label_type_checked";
  else
    document.getElementById(id_label).className = "label_type";
}

// Fixe la couleur de fond lors de la saisie de texte
function changeTypeColor(id)
{
  if (document.getElementById(id).value != "")
  {
    document.getElementById(id).style.background = "#70d55d";
    document.getElementById(id).style.color = "white";
  }
  else
  {
    document.getElementById(id).style.background = "#e3e3e3";
    document.getElementById(id).style.color = "#262626";
  }
}

// Génère une nouvelle zone pour saisir un type
function addOtherType(id)
{
  var html;
  var length = $("#" + id + " input").length;
  var new_length = length + 1;
  var id_type = id + '_' + new_length;

  html = '<input type="text" placeholder="Type" value="" id="' + id_type + '" name="' + id + '[' + new_length + ']" oninput="changeTypeColor(\'' + id_type + '\')" class="type_other" />';

  $("#" + id).append(html);
}

// Affiche ou masque la zone de saisie "Autre" (insertion)
function afficherOther(select, id, name)
{
  if (document.getElementById(select).value == "other_location")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(select).style.width = "calc(33% - 100px)";
      document.getElementById(id).style.width     = "calc(33% - 100px)";
      document.getElementById(name).style.width   = "calc(34% - 77px)";
      document.getElementById(id).style.display   = "inline-block";
      document.getElementById(id).required = true;
    }
  }
  else
  {
    document.getElementById(select).style.width = "calc(50% - 225px)";
    document.getElementById(name).style.width = "calc(50% - 20px)";
    document.getElementById(id).style.display = "none";
    document.getElementById(id).required = false;
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if (document.getElementById(select).value == "other_location")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(id).style.display = "block";
      document.getElementById(id).required = true;
    }
  }
  else
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(id).required = false;
  }
}
