/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre la zone de saisie de vote
  $('.afficherSaisieVote').click(function()
  {
    var idCollector = $(this).attr('id').replace('link_form_vote_', '');

    // Initialisation du vote
    initialisationVote(idCollector);
  });

  // Ferme la zone de saisie de vote
  $('#fermerSaisieVote').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_vote');
  });

  /*** Actions au changement ***/
  // Applique les filtres
  $('#applySort, #applyFilter').on('change', function()
  {
    if ($(this).val() == 'dateDesc' || $(this).val() == 'dateAsc')
      applySortOrFilter($(this).val(), $_GET('filter'));
    else
      applySortOrFilter($_GET('sort'), $(this).val());
  });
});

/**************/
/*** Scroll ***/
/**************/
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

/*****************/
/*** Fonctions ***/
/*****************/
// Redirige pour appliquer le tri ou le filtre
function applySortOrFilter(sort, filter)
{
  document.location.href = 'collector.php?action=goConsulter&page=1&sort=' + sort + '&filter=' + filter;
}

// Initialisation du vote d'une phrase / image culte
function initialisationVote(idCollector)
{
  // Récupération des données
  var collector     = listeCollectors[idCollector];
  var voteUser      = collector['vote_user'];
  var typeCollector = collector['type_collector'];
  var titre;
  var idCol;

  // Titre
  if (typeCollector == 'I')
    titre = 'Voter pour une image culte';
  else
    titre = 'Voter pour une phrase culte';

  // Modification des données
  $('.zone_titre_saisie').html(titre);
  $('input[name=id_col]').val(idCollector);

  $('.smiley').each(function()
  {
    var numeroSmiley = $(this).attr('name').replace('smiley_', '');

    if (numeroSmiley == voteUser)
      $(this).addClass('smiley_selected');
    else
      $(this).removeClass('smiley_selected');
  });

  // Affiche la zone de saisie
  afficherMasquerIdWithDelay('zone_saisie_vote');
}
