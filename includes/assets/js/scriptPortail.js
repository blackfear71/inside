/***************/
/*** Actions ***/
/***************/
// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  adaptPortail();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation mobile
  adaptPortail();

  // Masonry (Portail)
  if ($('.menu_portail').length)
  {
    $('.menu_portail').masonry().masonry('destroy');

    $('.menu_portail').masonry({
      // Options
      itemSelector: '.lien_portail',
      //columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.menu_portail').addClass('masonry');
  }
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations du portail sur mobiles
function adaptPortail()
{
  if ($(window).width() < 1080)
  {
    $('.zone_portail_left').css('display', 'block');
    $('.zone_portail_left').css('width', '100%');

    $('.zone_portail_right').css('display', 'block');
    $('.zone_portail_right').css('width', '100%');
    $('.zone_portail_right').css('margin-left', '0');
    $('.zone_portail_right').css('margin-top', '10px');
  }
  else
  {
    $('.zone_portail_left').css('display', 'inline-block');
    $('.zone_portail_left').css('width', '400px');

    $('.zone_portail_right').css('display', 'inline-block');
    $('.zone_portail_right').css('width', 'calc(100% - 420px');
    $('.zone_portail_right').css('margin-left', '20px');
    $('.zone_portail_right').css('margin-top', '0');
  }
}
