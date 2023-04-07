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
        initialisationModificationParcours('zone_saisie_parcours');
    });

    // Affiche la zone de saisie de participation
    $('.ajouterParticipation').click(function ()
    {
        var idParcours = $(this).attr('id').replace('ajouter_participation_', '');

        initialisationSaisieParticipation('zone_saisie_participation', idParcours);
    });

    // Affiche la zone de modification de participation
    $('.modifierParticipation').click(function ()
    {
        var idParticipation = $(this).attr('id').replace('modifier_participation_', '');

        initialisationModificationParticipation('zone_saisie_participation', idParticipation);
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

    // Bloque le bouton de soumission si besoin (parcours)
    $('#bouton_saisie_parcours').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie_parcours');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (liens actions)
        tabBlock.push({ element: '.icone_modifier_participation', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_supprimer_participation', property: 'display', value: 'none' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Bloque le bouton de soumission si besoin (participation)
    $('#bouton_saisie_participation').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie_participation');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (liens actions)
        tabBlock.push({ element: '.icone_modifier_participation', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_supprimer_participation', property: 'display', value: 'none' });

        // Blocage spécifique (saisie parcours)
        tabBlock.push({ element: '.zone_saisie_parcours input', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_saisie_parcours input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_saisie_parcours input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_saisie_parcours label', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_saisie_parcours label', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_saisie_parcours label', property: 'color', value: '#a3a3a3' });

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

// Au chargement du document complet
$(window).on('load', function ()
{
    // Adaptation mobile
    adaptParcours();

    // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
    var id     = $_GET('anchor');
    var offset = 70;
    var shadow = true;

    // Scroll vers l'id
    scrollToId(id, offset, shadow);
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation mobile
    adaptParcours();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des parcours sur mobile
function adaptParcours()
{
    if ($(window).width() < 1080)
    {
        // Liste des parcours
        if ($('.zone_tableau_de_bord').length && $('.zone_dernieres_courses').length)
        {
            $('.zone_tableau_de_bord').css('display', 'block');
            $('.zone_tableau_de_bord').css('width', '100%');
    
            $('.zone_dernieres_courses').css('display', 'block');
            $('.zone_dernieres_courses').css('width', '100%');
            $('.zone_dernieres_courses').css('margin-left', '0');
        }

        // Détails parcours
        if ($('.zone_details_parcours_left').length && $('.zone_details_parcours_right').length)
        {
            $('.zone_details_parcours_left').css('display', 'block');
            $('.zone_details_parcours_left').css('width', '100%');
    
            $('.zone_details_parcours_right').css('display', 'block');
            $('.zone_details_parcours_right').css('width', '100%');
            $('.zone_details_parcours_right').css('margin-left', '0');
            $('.zone_details_parcours_right').css('margin-top', '20px');
        }
    }
    else
    {
        // Liste des parcours
        if ($('.zone_tableau_de_bord').length && $('.zone_dernieres_courses').length)
        {
            $('.zone_tableau_de_bord').css('display', 'inline-block');
            $('.zone_tableau_de_bord').css('width', 'calc(40% - 10px)');
    
            $('.zone_dernieres_courses').css('display', 'inline-block');
            $('.zone_dernieres_courses').css('width', 'calc(60% - 10px)');
            $('.zone_dernieres_courses').css('margin-left', '20px');
        }

        // Détails parcours
        if ($('.zone_details_parcours_left').length && $('.zone_details_parcours_right').length)
        {
            $('.zone_details_parcours_left').css('display', 'inline-block');
            $('.zone_details_parcours_left').css('width', 'calc(60% - 10px)');
    
            $('.zone_details_parcours_right').css('display', 'inline-block');
            $('.zone_details_parcours_right').css('width', 'calc(40% - 10px)');
            $('.zone_details_parcours_right').css('margin-left', '20px');
            $('.zone_details_parcours_right').css('margin-top', '0');
        }
    }
}

// Ferme la saisie d'un parcours
function closeInput(id)
{
    if ($('#' + id).css('display') != 'none')
        afficherMasquerIdWithDelay(id);
}

// Initialisation modification d'un parcours
function initialisationModificationParcours(zone)
{
    var titre  = 'Modifier un parcours';
    var bouton = 'Modifier le parcours';
    var action = 'details.php?action=doModifierParcours';

    // Modification des données
    $('#' + zone).find('.titre_saisie_parcours').html(titre);
    $('#' + zone).find('.form_saisie_parcours').attr('action', action);
    
    $('#' + zone).find('input[name=id_parcours]').val(detailsParcours['id']);
    $('#' + zone).find('input[name=nom_parcours]').val(detailsParcours['name']);
    $('#' + zone).find('input[name=distance_parcours]').val(formatNumericForDisplay(detailsParcours['distance']));
    $('#' + zone).find('input[name=lieu_parcours]').val(detailsParcours['location']);
    $('#' + zone).find('input[name=document_parcours]').prop('required', false);
    $('#' + zone).find('#document_parcours').html(detailsParcours['document']);
    $('#' + zone).find('#bouton_saisie_parcours').val(bouton);

    if (detailsParcours['picture'] != '')
        $('#' + zone).find('#image_parcours').attr('src', '../../includes/images/petitspedestres/pictures/' + detailsParcours['picture']);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}

// Initialisation saisie participation
function initialisationSaisieParticipation(zone, idParcours)
{
    var titre;
    var bouton = 'Ajouter la participation';
    var action;

    if (pageAppelante == 'petitspedestres')
    {
        titre  = 'Ajouter une participation au parcours "' + listeParcours[idParcours] + '"';
        action = 'petitspedestres.php?action=doAjouterParticipation';
    }
    else
    {
        titre  = 'Ajouter une participation au parcours "' + detailsParcours['name'] + '"';
        action = 'details.php?id_parcours=' + idParcours + '&action=doAjouterParticipation';
    }

    // Modification des données
    $('#' + zone).find('.titre_saisie_participation').html(titre);
    $('#' + zone).find('.form_saisie_participation').attr('action', action);

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
    $('#' + zone).find('#bouton_saisie_participation').val(bouton);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}

// Initialisation modification participation
function initialisationModificationParticipation(zone, idParticipation)
{
    var titre  = 'Modifier la participation au parcours "' + detailsParcours['name'] + '"';
    var bouton = 'Modifier la participation';
    var action = 'details.php?id_parcours=' + detailsParcours['id'] + '&action=doModifierParticipation';

    // Récupération des données
    var participation = listeParticipations[idParticipation];

    // Modification des données
    $('#' + zone).find('.titre_saisie_participation').html(titre);
    $('#' + zone).find('.form_saisie_participation').attr('action', action);

    $('#' + zone).find('input[name=id_parcours]').val(detailsParcours['id']);
    $('#' + zone).find('input[name=id_participation]').val(idParticipation);
    $('#' + zone).find('input[name=date_participation]').val(formatDateForDisplay(participation['date']));

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
    $('#' + zone).find('#bouton_saisie_participation').val(bouton);

    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);
}