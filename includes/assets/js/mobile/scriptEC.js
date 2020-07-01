/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie d'année
  $('#afficherSaisieAnnee, #fermerSaisieAnnee').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisieAnnee');
  });

  // Ouvre ou ferme la zone de saisie d'une dépense
  $('#afficherSaisieDepense, #fermerSaisieDepense').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisieDepense');
  });

  // Ajoute une part
  $('.ajouterPart').click(function()
  {
    var id_user = $(this).attr('id').replace('ajouter_part_', '');

    ajouterPart('zone_user_' + id_user, 'quantite_user_' + id_user, 1);
  });

  // Retire une part
  $('.retirerPart').click(function()
  {
    var id_user = $(this).attr('id').replace('retirer_part_', '');

    ajouterPart('zone_user_' + id_user, 'quantite_user_' + id_user, -1);
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ajoute une part à un utilisateur
function ajouterPart(zone, quantite, value)
{
  var currentValue = parseInt($('#' + quantite).val());
  var newValue     = currentValue + value;

  // Colorise en fonction de la valeur
  if (newValue > 0)
  {
    $('#' + zone).css('background-color', '#ff1937');

    $('#' + quantite).css('background-color', '#ff1937');
    $('#' + quantite).css('color', 'white');
  }
  else
  {
    $('#' + zone).css('background-color', '#e3e3e3');

    $('#' + quantite).css('background-color', '#e3e3e3');
    $('#' + quantite).css('color', '#262626');
  }

  // Incrit la valeur dans la zone
  if (newValue >= 0 && newValue <= 5)
    $('#' + quantite).val(newValue);
}
