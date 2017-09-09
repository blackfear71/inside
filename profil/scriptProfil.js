// Insère une prévisualisation de l'image sur la page
var loadFile = function(event)
{
  var output = document.getElementById('output');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.src.SizeHeight = "120px";
};
