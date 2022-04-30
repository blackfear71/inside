/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
    /*** Actions au chargement ***/
    // Description de la fonction
    fonction();

    /*** Actions au clic ***/
    // Description de la fonction au clic
    $('.class').click(function()
    {
        fonction();
    });

    /*** Actions au changement ***/
    // Description de la fonction au changement de valeur
    $('#id').on('change', function()
    {
        fonction();
    });

    /*** Actions à la saisie ***/
    // Description de la fonction à la saisie d'une touche
    $('.class').keyup(function()
    {
        fonction();
    });

    /*** Actions au passage de la souris ***/
    // Description de la fonction au passage de la souris
    $('#id').mouseover(function()
    {
        fonction();
    });

    // Description de la fonction au retrait de la souris
    $('#id').mouseover(function()
    {
        fonction();
    });

    /*** Actions sur mobile ***/
    // Description de la fonction au début du toucher
    $('.celsius').on('touchstart', function(e)
    {
        e.preventDefault();

        // Appel fonction
        fonction();
    });

    // Description de la fonction à la fin du toucher
    $('.celsius').on('touchend',function(e)
    {
        e.preventDefault();

        // Appel fonction
        fonction();
    });

    // Description de la fonction pendant le toucher
    $('.celsius').on('touchmove', function(e)
    {
        e.preventDefault();

        // Appel fonction
        fonction();
    });
});

// Au chargement du document complet
$(window).on('load', function()
{
    fonction();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Description fonction
function fonction()
{
    console.log('');
}