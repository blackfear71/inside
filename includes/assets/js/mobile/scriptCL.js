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
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation des traits de l'histoire du site
  adaptHistory();

  // Déclenchement du scroll
  if ($_GET('anchor') != null)
  {
    var id;

    if ($_GET('anchor').length < 2 && $_GET('anchor') < 10)
      id = 'afficher_journal_semaine_0' + $_GET('anchor');
    else
      id = 'afficher_journal_semaine_' + $_GET('anchor');

    var offset = 0.1;
    var shadow = false;

    // Scroll vers l'id
    scrollToId(id, offset, shadow);
  }
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptation des traits de l'histoire du site
function adaptHistory()
{
  var taille_totale = $('.zone_history').width();

  // Calcul de la taille de chaque trait
  $('.event_history').each(function()
  {
    var taille_date  = $(this).children('.date_history').width();
    var taille_trait = taille_totale - taille_date - 15;

    $(this).children('.trait_history').css('width', taille_trait + 'px');
  });
}
