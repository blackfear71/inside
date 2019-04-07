// Au chargement du document complet
$(window).on('load', function()
{
  // On lance Masonry après avoir chargé les images
  $('.zone_annexes').masonry({
    // Options
    itemSelector: '.zone_annexe',
    columnWidth: 216,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_annexes').addClass('masonry');
});

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};
