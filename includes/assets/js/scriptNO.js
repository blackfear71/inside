/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Redirige vers le détail des films au clic Doodle des fiches
  $('.lienDetails').click(function()
  {
    var id_film = $(this).attr('id').replace('lien_details_', '');

    document.location.href = '/inside/portail/moviehouse/details.php?id_film=' + id_film + '&action=goConsulter';
  });
});
