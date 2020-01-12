/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptBugs();

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);

  /*** Actions au clic ***/
  // Ajouter un rapport
  $('#ajouterRapport, #fermerRapport').click(function()
  {
    afficherMasquer('zone_add_report');
  });

  // Affiche une image de bug / évolution en grand
  $('.agrandirImage').click(function()
  {
    var html;
    var path   = $(this).children().attr('src');
    var split  = path.split('/');
    var report = split[split.length - 1];

    html = '<div id="zoom_image" class="fond_zoom">';
      html += '<div class="zone_image_zoom">';
        html += '<a id="fermerImage" class="lien_zoom"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';
        html += '<img src="' + path + '" alt="' + report + '" class="image_zoom" />';
      html += '</div>';
    html += '</div>';

    $('body').append(html);

    $('#zoom_image').fadeIn(200);
  });

  // Ferme le zoom d'une image de bug / évolution
  $(document).on('click', '#fermerImage', function()
  {
    $('#zoom_image').fadeOut(200, function()
    {
      $('#zoom_image').remove();
    });
  });

  /*** Actions au changement ***/
  // Charge l'image (saisie)
  $('.loadSaisieReport').on('change', function()
  {
    loadFile(event, 'image_report');
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptBugs();
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

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output   = document.getElementById(id);
  output.src   = URL.createObjectURL(event.target.files[0]);

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

// Adaptations des bugs/évolutions sur mobiles
function adaptBugs()
{
  if ($(window).width() < 1080)
  {
    $('.zone_vues').css('display', 'block');
    $('.zone_vues').css('width', '100%');

    $('.zone_bugs').css('display', 'block');
    $('.zone_bugs').css('width', '100%');
    $('.zone_bugs').css('margin-left', '0');

    $('.zone_evolutions').css('display', 'block');
    $('.zone_evolutions').css('width', '100%');
    $('.zone_evolutions').css('margin-left', '0');

    $('.view').css('display', 'inline-block');
    $('.view').css('width', 'calc(50% - 10px)');
    $('.view').first().css('margin-right', '20px');
  }
  else
  {
    $('.zone_vues').css('display', 'inline-block');
    $('.zone_vues').css('width', '120px');

    $('.zone_bugs').css('display', 'inline-block');
    $('.zone_bugs').css('width', 'calc(50% - 80px)');
    $('.zone_bugs').css('margin-left', '20px');

    $('.zone_evolutions').css('display', 'inline-block');
    $('.zone_evolutions').css('width', 'calc(50% - 80px)');
    $('.zone_evolutions').css('margin-left', '20px');

    $('.view').css('display', 'block');
    $('.view').css('width', '100%');
    $('.view').first().css('margin-right', '0');
  }
}
