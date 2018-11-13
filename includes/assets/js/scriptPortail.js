// Au chargement du document
$(window).load(function()
{
  // On lance Masonry et le scroll après avoir chargé les images
  /*$('.menu_portail').imagesLoaded(function()
  {*/
    $('.menu_portail').masonry({
      // Options
      itemSelector: '.lien_portail',
      columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.menu_portail').addClass('masonry');
  //});
});
