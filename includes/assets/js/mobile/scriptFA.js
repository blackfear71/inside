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
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone);
  });

  // Ouvre ou ferme la zone de saisie de propositions
  $('#afficherSaisiePropositions, #fermerSaisiePropositions').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisiePropositions');
  });

  // Change la couleur d'une case à cocher à la sélection
  $('label').click(function()
  {
    changeCheckedColor($(this));
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ouvre une zone sous un titre
function openSection(titre, zone)
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

  // Affichage ou masquage de la zone
  afficherMasquerIdWithDelay(zone);
}

// Change la couleur d'un proposition
function changeCheckedColor(label)
{
  if (label.find('input').prop('checked'))
  {
    label.find('.image_normal').css('background-color', '#70d55d');
    label.find('.proposition_normal').css('background-color', '#96e687');
    label.find('.nom_normal').css('color', 'white');
    label.find('.zone_checkbox_proposition').css('background-color', '#70d55d');
  }
  else
  {
    label.find('.image_normal').css('background-color', '#d3d3d3');
    label.find('.proposition_normal').css('background-color', '#e3e3e3');
    label.find('.nom_normal').css('color', '#262626');
    label.find('.zone_checkbox_proposition').css('background-color', '#d3d3d3');
  }
}
