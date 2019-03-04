// Au chargement du document complet
$(window).load(function()
{
  // On lance Masonry après avoir chargé les images
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

  // On lance Masonry après avoir chargé les images
  $('.zone_messages_missions').masonry({
    // Options
    itemSelector: '.zone_resume_mission',
    columnWidth: 500,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });
});