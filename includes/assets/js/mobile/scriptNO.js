/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie de la vue
    $('#afficherSaisieVue, #fermerSaisieVue').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_vue');
    });

    // Redirige vers le détail des films au clic Doodle des fiches
    $('.lienDetails').click(function ()
    {
        var idFilm = $(this).attr('id').replace('lien_details_', '');

        document.location.href = '/inside/portail/moviehouse/details.php?id_film=' + idFilm + '&action=goConsulter';
    });
});