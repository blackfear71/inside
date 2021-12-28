/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie de la vue
  $('#afficherSaisieVue, #fermerSaisieVue').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_vue');
  });

  // Ouvre ou ferme la zone de saisie d'une dépense
  $('#afficherSaisieIdee, #fermerSaisieIdee').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_idee');
  });

  // Bloque la saisie en cas de soumission (phrase culte)
  $('#validerSaisieIdee').click(function()
  {
    var idForm          = $('#zone_saisie_idee');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Déclenchement du scroll
  var id     = $_GET('anchor');
  var offset = 0.1;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});
