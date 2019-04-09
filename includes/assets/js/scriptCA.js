// Au chargement du document complet
$(window).on('load', function()
{
  // Masonry (Calendriers & annexes)
  if ($('.zone_calendriers').length)
  {
    $('.zone_calendriers').masonry({
      // Options
      itemSelector: '.zone_calendrier',
      columnWidth: 300,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_calendriers').addClass('masonry');
  }
});

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};
