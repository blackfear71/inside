/**************/
/*** Action ***/
/**************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie de la vue
    $('#afficherSaisieVue, #fermerSaisieVue').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_vue');
    });

    /*** Actions au changement ***/
    // Charge le calendrier à générer
    $('.loadCalendrierGenere').on('change', function (event)
    {
        loadFile(event, 'image_calendars_generated', false);
    });

    // Charge l'annexe à générer
    $('.loadAnnexeGeneree').on('change', function (event)
    {
        loadFile(event, 'image_annexe_generated', false);
    });

    // Charge le calendrier
    $('.loadCalendrier').on('change', function (event)
    {
        loadFile(event, 'image_calendars', false);
    });

    // Charge l'annexe
    $('.loadAnnexe').on('change', function (event)
    {
        loadFile(event, 'image_annexes', false);
    });

    // Change la couleur de la checkbox options générateur de calendriers
    $('#checkbox_jours_feries').click(function ()
    {
        var idBouton = $(this).closest('div').attr('id');
        
        switchCheckedColor(idBouton);
    });

    // Change la couleur des switch options générateur de calendriers (sauf les couleurs)
    $('.zone_bouton_option').not('#checkbox_jours_feries').click(function ()
    {
        var idBouton = $(this).closest('div').attr('id');
        var idParent = $(this).closest('div').parent().attr('id');

        if (idBouton != undefined)
            switchRadioColor('zone_bouton_option', idBouton, idParent);
    });

    // Bloque la saisie en cas de soumission (Génération de calendrier)
    $('#bouton_saisie_generator').click(function ()
    {
        var idForm = $('#form_saisie_generator');

        blockValidationSubmissionPage(idForm);
    });

    // Bloque la saisie en cas de soumission (Sauvegarde de calendrier)
    $('#bouton_saisie_generated').click(function ()
    {
        var idForm = $('#form_saisie_generated');

        blockValidationSubmissionPage(idForm);
    });

    // Bloque la saisie en cas de soumission (Génération d'annexe)
    $('#bouton_saisie_annexe_generator').click(function ()
    {
        var idForm = $('#form_saisie_annexe_generator');

        blockValidationSubmissionPage(idForm);
    });

    // Bloque la saisie en cas de soumission (Sauvegarde d'annexe)
    $('#bouton_saisie_annexe_generated').click(function ()
    {
        var idForm = $('#form_saisie_annexe_generated');

        blockValidationSubmissionPage(idForm);
    });

    // Bloque la saisie en cas de soumission (Saisie de calendrier)
    $('#bouton_saisie_calendrier').click(function ()
    {
        var idForm = $('#form_saisie_calendrier');

        blockValidationSubmissionPage(idForm);
    });

    // Bloque la saisie en cas de soumission (Saisie d'annexe)
    $('#bouton_saisie_annexe').click(function ()
    {
        var idForm = $('#form_saisie_annexe');

        blockValidationSubmissionPage(idForm);
    });
});

// Au chargement du document complet
$(window).on('load', function ()
{
    // Génération du calendrier sous forme d'image
    if ($('.zone_calendrier_generator').length)
        afficherCalendrierJpeg();

    // Génération de l'annexe sous forme d'image
    if ($('.zone_annexe_generator').length)
        afficherAnnexeJpeg();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Change la couleur des switch
function switchCheckedColor(idBouton)
{
    if ($('#' + idBouton).children('input').prop('checked'))
    {
        $('#' + idBouton).removeClass('bouton_checked');
        $('#' + idBouton).children('input').prop('checked', false);
    }
    else
    {
        $('#' + idBouton).addClass('bouton_checked');
        $('#' + idBouton).children('input').prop('checked', true);
    }
}

// Change la couleur des radio boutons
function switchRadioColor(zone, idBouton, idParent)
{
    $('#' + idParent + ' .' + zone).each(function ()
    {
        $(this).removeClass('bouton_checked');
        $(this).children('input').prop('checked', false);
    });

    $('#' + idBouton).addClass('bouton_checked');
    $('#' + idBouton).children('input').prop('checked', true);
}

// Affiche le calendrier généré au format JPEG
function afficherCalendrierJpeg()
{
    // Affichage de la zone de génération
    var idZone = $('#titre_generateur_calendrier').attr('id').replace('titre_', 'afficher_');
    openSection($('#titre_generateur_calendrier'), idZone, '');

    // Génération du calendrier après ouverture de la zone d'affichage
    setTimeout(function ()
    {
        // Réglage de la hauteur des jours fériés (dépend du nombre de lignes affichées)
        var hauteurJourFerie = $('.ligne_calendrier_generator').height() - 105;

        $('.zone_jour_ferie_calendrier_generator').css('line-height', hauteurJourFerie + 'px');
        $('.zone_jour_ferie_calendrier_generator').css('height', hauteurJourFerie + 'px');

        // Conversion du calendrier généré en image
        html2canvas($('.zone_calendrier_generator')[0],
        {
            // Options
            scale: 1
        }).then(function (canvas)
        {
            // Conversion du calendrier généré
            var data = canvas.toDataURL('image/jpeg', 1);

            // Affichage de la zone et du formulaire
            $('.form_sauvegarde_calendrier').css('display', 'block');
            $('#generated_calendar').attr('src', data);
            $('#calendar_generator').val(data);
        });

        // Masquage du calendrier généré (format HTML)
        $('.zone_calendrier_generator_hidden').remove();

        // Scroll vers l'id
        var id     = 'generated_calendar';
        var offset = 0.05;
        var shadow = false;

        scrollToId(id, offset, shadow);
    }, 200);
}

// Affiche l'annexe générée au format JPEG
function afficherAnnexeJpeg()
{
    // Affichage de la zone de génération
    var idZone = $('#titre_generateur_annexe').attr('id').replace('titre_', 'afficher_');
    openSection($('#titre_generateur_annexe'), idZone, '');

    // Génération de l'annexe après ouverture de la zone d'affichage
    setTimeout(function ()
    {
        // Conversion de l'annexe généré en image
        html2canvas($('.zone_annexe_generator')[0],
        {
            // Options
            scale: 1
        }).then(function (canvas)
        {
            // Conversion de l'annexe générée
            var data = canvas.toDataURL('image/jpeg', 1);

            // Affichage de la zone et du formulaire
            $('.form_sauvegarde_annexe').css('display', 'block');
            $('#generated_annexe').attr('src', data);
            $('#annexe_generator').val(data);
        });

        // Masquage de l'annexe généré (format HTML)
        $('.zone_annexe_generator_hidden').remove();

        // Redimenssionnement des zones
        $('.zone_annexe_generator_right').css('width', 'calc(100% - 513px)');

        // Scroll vers l'id
        var id     = 'generated_annexe';
        var offset = 0.05;
        var shadow = false;

        scrollToId(id, offset, shadow);
    }, 200);
}