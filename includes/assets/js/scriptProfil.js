// Insère une prévisualisation de l'image sur la page
var loadFile = function(event)
{
  var output = document.getElementById('output');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.src.SizeHeight = "120px";
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
