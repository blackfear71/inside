/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ajouter une idée
  $('#ajouterIdee, #fermerIdee').click(function()
  {
    afficherMasquer('zone_add_idea');
  });
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

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Masonry (Idées)
  if ($('.zone_ideas').length)
  {
    $('.zone_ideas').masonry().masonry('destroy');

    $('.zone_ideas').masonry({
      // zone_ideas
      itemSelector: '.zone_idea',
      columnWidth: 480,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_ideas').addClass('masonry');
  }

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});
