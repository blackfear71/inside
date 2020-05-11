/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Déplie une zone en cliquant sur le titre
  $('.titre_section').click(function()
  {
    openSection($(this));
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ouvre une zone sous un titre
function openSection(titre)
{
  // Calcul de l'angle
  var fleche = titre.children('.fleche_titre_section');
  var matrix = fleche.css('transform');

  if (matrix !== 'none')
  {
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var a      = values[0];
    var b      = values[1];
    var angle  = Math.round(Math.atan2(b, a) * (180 / Math.PI));

    if (angle < 0)
      angle = angle + 360;
  }
  else
    var angle = 0;

  // Rotation de la flèche
  fleche.css('transition', 'all ease 0.2s');

  if (angle == 0)
    fleche.css('transform', 'rotate(-90deg)');
  else
    fleche.css('transform', 'rotate(0deg)');
}
