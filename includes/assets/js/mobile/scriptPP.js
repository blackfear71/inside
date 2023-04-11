/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie d'un parcours
    $('#afficherSaisieParcours, #fermerSaisieParcours').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_parcours');
    });

    // Affiche la zone de modification d'un parcours
    $('#afficherModificationParcours').click(function ()
    {
        initialisationModificationParcours('zone_saisie_parcours');
    });

    // Affiche la zone de saisie de participation
    $('.afficherSaisieParticipation').click(function ()
    {
        var idParcours = $(this).attr('id').replace('ajouter_participation_', '');

        initialisationSaisieParticipation('zone_saisie_participation', idParcours);
    });

    // Affiche la zone de modification de participation
    $('.afficherModificationParticipation').click(function ()
    {
        var idParticipation = $(this).attr('id').replace('modifier_participation_', '');

        initialisationModificationParticipation('zone_saisie_participation', idParticipation);
    });

    // Masque la zone de saisie de participation
    $('#fermerSaisieParticipation').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_participation');
    });

    /*** Actions au changement ***/
    // Charge l'image dans la zone de saisie
    $('.loadSaisieParcours').on('change', function (event)
    {
        loadFile(event, 'image_parcours', true);
    });

    // Charge le document dans la zone de saisie
    $('.loadDocumentParcours').on('change', function (event)
    {
        loadDocument(event, 'document_parcours');
    });
});

// Au chargement du document complet
$(window).on('load', function ()
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
// Initialisation modification d'un parcours
function initialisationModificationParcours(zone)
{
    var titre  = 'Modifier le parcours';
    var action = 'details.php?action=doModifierParcours';

    // Modification des données
    $('#' + zone).find('.zone_titre_saisie').html(titre);
    $('#' + zone).find('.form_saisie').attr('action', action);
    
    $('#' + zone).find('input[name=id_parcours]').val(detailsParcours['id']);
    $('#' + zone).find('input[name=nom_parcours]').val(detailsParcours['name']);
    $('#' + zone).find('input[name=distance_parcours]').val(formatNumericForDisplay(detailsParcours['distance']));
    $('#' + zone).find('input[name=lieu_parcours]').val(detailsParcours['location']);
    $('#' + zone).find('textarea[name=description_parcours]').val(detailsParcours['description']);
    $('#' + zone).find('input[name=document_parcours]').prop('required', false);
    $('#' + zone).find('#document_parcours').html(detailsParcours['document']);

    if (detailsParcours['picture'] != '')
        $('#' + zone).find('#image_parcours').attr('src', '../../includes/images/petitspedestres/pictures/' + detailsParcours['picture']);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}

// Initialisation saisie participation
function initialisationSaisieParticipation(zone, idParcours)
{
    var titre = 'Ajouter une participation';
    var action;

    if (pageAppelante == 'petitspedestres')
        action = 'petitspedestres.php?action=doAjouterParticipationMobile';
    else
        action = 'details.php?id_parcours=' + idParcours + '&action=doAjouterParticipationMobile';

    // Modification des données
    $('#' + zone).find('.zone_titre_saisie').html(titre);
    $('#' + zone).find('.form_saisie').attr('action', action);

    $('#' + zone).find('input[name=id_parcours]').val(idParcours);
    $('#' + zone).find('input[name=id_participation]').val('');
    $('#' + zone).find('input[name=date_participation]').val('');
    $('#' + zone).find('input[name=competition_participation]').first().prop('checked', true);
    $('#' + zone).find('input[name=distance_participation]').val('');
    $('#' + zone).find('input[name=vitesse_participation]').val('');
    $('#' + zone).find('input[name=heures_participation]').val('');
    $('#' + zone).find('input[name=minutes_participation]').val('');
    $('#' + zone).find('input[name=secondes_participation]').val('');
    $('#' + zone).find('input[name=cardio_participation]').val('');

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}

// Initialisation modification participation
function initialisationModificationParticipation(zone, idParticipation)
{
    var titre  = 'Modifier la participation';
    var action = 'details.php?id_parcours=' + detailsParcours['id'] + '&action=doModifierParticipationMobile';

    // Récupération des données
    var participation = listeParticipations[idParticipation];

    // Modification des données
    $('#' + zone).find('.zone_titre_saisie').html(titre);
    $('#' + zone).find('.form_saisie').attr('action', action);

    $('#' + zone).find('input[name=id_parcours]').val(detailsParcours['id']);
    $('#' + zone).find('input[name=id_participation]').val(idParticipation);
    $('#' + zone).find('input[name=date_participation]').val(formatDateForDisplayMobile(participation['date']));

    if (participation['competition'] == 'Y')
    {
        $('#' + zone).find('#competition_non').prop('checked', false);
        $('#' + zone).find('#competition_oui').prop('checked', true);
    }
    else
    {
        $('#' + zone).find('#competition_non').prop('checked', true);
        $('#' + zone).find('#competition_oui').prop('checked', false);
    }

    $('#' + zone).find('input[name=distance_participation]').val(formatNumericForDisplay(participation['distance']));
    $('#' + zone).find('input[name=vitesse_participation]').val(formatNumericForDisplay(participation['vitesse']));
    $('#' + zone).find('input[name=heures_participation]').val(participation['heures']);
    $('#' + zone).find('input[name=minutes_participation]').val(participation['minutes']);
    $('#' + zone).find('input[name=secondes_participation]').val(participation['secondes']);
    $('#' + zone).find('input[name=cardio_participation]').val(formatNumericForDisplay(participation['cardio']));

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}