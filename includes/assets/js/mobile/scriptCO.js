/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
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

/*****************/
/*** Fonctions ***/
/*****************/
// Redirige pour appliquer le tri ou le filtre
function applySortOrFilter(sort, filter)
{
  document.location.href = 'collector.php?action=goConsulter&page=1&sort=' + sort + '&filter=' + filter;
}
