// Au chargement du document
$(document).ready(function()
{
  $('#progress_circle').circlize(
  {
		radius: 60,
    percentage: $('#progress_circle').attr('data-perc'),
		text: $('#progress_circle').attr('data-text'),
    min: $('#progress_circle').attr('data-perc'),
    max: 100,
    typeUse: "useText",
		useAnimations: true,
		useGradient: false,
		background: "#d3d3d3",
		foreground: "#a3a3a3",
		stroke: 5,
		duration: 1000
	});
});

// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_profil').css('display', 'block');

  // Masonry
  if ($('.zone_niveau_succes').length)
  {
    $('.zone_niveau_succes').masonry('destroy');

    $('.zone_niveau_succes').masonry({
      // Options
      itemSelector: '.succes_liste',
      columnWidth: 160,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_niveau_succes').addClass('masonry');
  }
});

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};

// Affiche la zone de saisie des anciens films si "partiel" est sélectionné
function afficherSaisieFilms(id, required)
{
  if (document.getElementById(id).style.display == "none")
  {
    document.getElementById(id).style.display = "block";
    document.getElementById(required).required = true;
  }
}

// Masque la zone de saisie des anciens films si "tous" est sélectionné
function masquerSaisieFilms(id, required)
{
  if (document.getElementById(id).style.display == "block")
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(required).required = false;
  }
}
