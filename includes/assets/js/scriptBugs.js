/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
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

    html += '<div id="zoom_image" class="fond_zoom">';
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

/****************/
/*** Fonction ***/
/****************/
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
