/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_missions').css('display', 'block');

  // Masonry (Calendriers & annexes)
  if ($('.zone_missions_accueil').length)
  {
    $('.zone_missions_accueil').masonry().masonry('destroy');

    $('.zone_missions_accueil').masonry({
      // Options
      itemSelector: '.zone_mission_accueil',
      columnWidth: 500,
      fitWidth: true,
      gutter: 15,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_missions_accueil').addClass('masonry');
  }
});
