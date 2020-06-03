/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
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
