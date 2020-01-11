/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptTheBox();

  /*** Actions au clic ***/
  // Ajouter une idée
  $('#ajouterIdee, #fermerIdee').click(function()
  {
    afficherMasquer('zone_add_idea');
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Décalage pour mobile
  adaptTheBox();
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

// Adaptations de la section sur mobiles
function adaptTheBox()
{
  if ($(window).width() < 1080)
  {
    $('.zone_vues').css('display', 'block');
    $('.zone_vues').css('width', '100%');

    $('.zone_ideas_right').css('display', 'block');
    $('.zone_ideas_right').css('width', '100%');
    $('.zone_ideas_right').css('margin-left', '0');

    $('.view').css('display', 'inline-block');
    $('.view').css('width', 'calc(50% - 10px)');
    $('.view:eq(0)').css('margin-right', '20px');
    $('.view:eq(2)').css('margin-right', '20px');
  }
  else
  {
    $('.zone_vues').css('display', 'inline-block');
    $('.zone_vues').css('width', '120px');

    $('.zone_ideas_right').css('display', 'inline-block');
    $('.zone_ideas_right').css('width', 'calc(100% - 140px)');
    $('.zone_ideas_right').css('margin-left', '20px');

    $('.view').css('display', 'block');
    $('.view').css('width', '100%');
    $('.view:eq(0)').css('margin-right', '0');
    $('.view:eq(2)').css('margin-right', '0');
  }
}
