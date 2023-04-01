/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Affiche la zone de saisie d'un parcours
    $('#ajouterParcours, #annulerParcours').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_parcours');
    });

    // Ferme au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme la saisie d'un parcours
        if ($(event.target).attr('class') == 'fond_saisie_parcours')
            closeInput('zone_saisie_parcours');

        // Ferme la saisie participation
        if ($(event.target).attr('class') == 'fond_saisie_participation')
            masquerSupprimerIdWithDelay('zone_saisie_participation');
    });

    // Bloque le bouton de soumission si besoin
    $('#bouton_saisie_parcours').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = null;

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au changement ***/
    // Charge l'image (saisie)
    $('.loadSaisieParcours').on('change', function (event)
    {
        loadFile(event, 'image_parcours', true);
    });

    // Charge le document (saisie)
    $('.loadDocumentParcours').on('change', function (event)
    {
        loadDocument(event, 'document_parcours');
    });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ferme la saisie d'un parcours
function closeInput(id)
{
    if ($('#' + id).css('display') != 'none')
        afficherMasquerIdWithDelay(id);
}