/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Affiche ou masque la zone de saisie d'un parcours
    $('#ajouterParcours, #annulerParcours').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_parcours');
    });

    // Affiche la zone de modification d'un parcours
    $('#modifierParcours').click(function ()
    {
        initialisationModification('zone_saisie_parcours');
    });

    // Affiche la zone de saisie de participation
    $('.ajouterParticipation').click(function ()
    {
        var idParcours = $(this).attr('id').replace('ajouter_participation_', '');

        initialisationParticipation('zone_saisie_participation', idParcours);
    });

    // Masque la zone de saisie de participation
    $('#annulerParticipation').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_participation');
    });

    // Ferme au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme la saisie d'un parcours
        if ($(event.target).attr('class') == 'fond_saisie_parcours')
            closeInput('zone_saisie_parcours');

        // Ferme la saisie participation
        if ($(event.target).attr('class') == 'fond_saisie_participation')
            closeInput('zone_saisie_participation');
    });

    // Bloque le bouton de soumission si besoin
    $('#bouton_saisie_parcours, #bouton_saisie_participation').click(function ()
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

    /*** Calendriers ***/
    if ($('#datepicker_parcours').length)
    {
        $('#datepicker_parcours').datepicker(
        {
            autoHide: true,
            language: 'fr-FR',
            format: 'dd/mm/yyyy',
            weekStart: 1,
            days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            daysShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            daysMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']
        });
    }
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

// Initialisation modification d'un parcours
function initialisationModification(zone)
{
    var titre  = 'Modifier un parcours';
    var bouton = 'Modifier le parcours';
    var action = 'details.php?action=doModifier';

    // Modification des données
    $('#' + zone).find('.titre_saisie_parcours').html(titre);
    $('#' + zone).find('.form_saisie_parcours').attr('action', action);
    
    $('#' + zone).find('input[name=id_parcours]').val(detailsParcours['id']);
    $('#' + zone).find('input[name=nom_parcours]').val(detailsParcours['name']);
    $('#' + zone).find('input[name=distance_parcours]').val(formatDistanceForDisplay(detailsParcours['distance']));
    $('#' + zone).find('input[name=lieu_parcours]').val(detailsParcours['location']);
    $('#' + zone).find('input[name=document_parcours]').prop('required', false);
    $('#' + zone).find('#document_parcours').text(detailsParcours['document']);
    $('#' + zone).find('#bouton_saisie_parcours').val(bouton);

    if (detailsParcours['picture'] != '')
        $('#' + zone).find('#image_parcours').attr('src', '../../includes/images/petitspedestres/pictures/' + detailsParcours['picture']);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}

// Initialisation saisie participation
function initialisationParticipation(zone, idParcours)
{
    var titre  = 'Ajouter une participation au parcours "' + listeParcours[idParcours] + '"';
    var bouton = 'Ajouter la participation';
    var action = 'petitspedestres.php?action=doAjouterParticipation';

    // Modification des données
    $('#' + zone).find('.titre_saisie_participation').html(titre);
    $('#' + zone).find('.form_saisie_participation').attr('action', action);

    $('#' + zone).find('input[name=id_parcours]').val(idParcours);
    $('#' + zone).find('input[name=date_participation]').val('');
    $('#' + zone).find('input[name=competition_participation]').first().prop('checked', true);
    $('#' + zone).find('input[name=distance_participation]').val('');
    $('#' + zone).find('input[name=vitesse_participation]').val('');
    $('#' + zone).find('input[name=heures_participation]').val('');
    $('#' + zone).find('input[name=minutes_participation]').val('');
    $('#' + zone).find('input[name=secondes_participation]').val('');
    $('#' + zone).find('input[name=cardio_participation]').val('');
    $('#' + zone).find('#bouton_saisie_participation').val(bouton);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}