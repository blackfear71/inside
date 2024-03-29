/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ferme le formulaire de vote en cliquant n'importe où sur le body
    $('body').click(function ()
    {
        $('.zone_smileys').each(function ()
        {
            $(this).css('display', 'none');
        });

        $('.link_current_vote').each(function ()
        {
            if ($(this).css('display') == 'none')
                $(this).css('display', 'block');
        });
    });

    // Ajouter une phrase culte
    $('#ajouterCollector, #fermerCollector').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_phrase_culte');
    });

    // Ajouter une image culte
    $('#ajouterImage, #fermerImage').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_image_culte');
    });

    // Affiche la zone de modification d'une phrase / image culte
    $('.modifierCollector').click(function ()
    {
        var idCollector = $(this).attr('id').replace('modifier_', '');

        afficherMasquerIdNoDelay('modifier_collector_' + idCollector);
        afficherMasquerIdNoDelay('visualiser_collector_' + idCollector);
        adaptBrowse(idCollector);

        // Réinitialisation Masonry
        initMasonry();
    });

    // Ferme la zone de modification d'une phrase / image culte
    $('.annulerCollector').click(function ()
    {
        var idCollector = $(this).attr('id').replace('annuler_update_collector_', '');

        afficherMasquerIdNoDelay('modifier_collector_' + idCollector);
        afficherMasquerIdNoDelay('visualiser_collector_' + idCollector);

        // Réinitialisation Masonry
        initMasonry();
    });

    // Affiche la zone de modification d'un vote d'une phrase / image culte
    $('.modifierVote').click(function (event)
    {
        var idCollector = $(this).attr('id').replace('link_form_vote_', '');

        afficherMasquerIdNoDelay('modifier_vote_' + idCollector);
        afficherMasquerIdNoDelay('link_form_vote_' + idCollector);
        event.stopPropagation();
    });

    // Affiche une image culte en grand
    $('.agrandirImage').click(function ()
    {
        afficherDetailsCollector($(this));
    });

    // Bloque le bouton de soumission si besoin (phrase culte)
    $('#bouton_saisie_collector').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie_collector');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (saisie image culte)
        tabBlock.push({ element: '#zone_saisie_image_culte input', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_image_culte input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_image_culte input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '#zone_saisie_image_culte textarea', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_image_culte textarea', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_image_culte textarea', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '#zone_saisie_image_culte select', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_image_culte select', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_image_culte select', property: 'color', value: '#a3a3a3' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Bloque le bouton de soumission si besoin (image culte)
    $('#bouton_saisie_image').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie_image');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (saisie phrase culte)
        tabBlock.push({ element: '#zone_saisie_phrase_culte input', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_phrase_culte input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_phrase_culte input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '#zone_saisie_phrase_culte textarea', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_phrase_culte textarea', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_phrase_culte textarea', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '#zone_saisie_phrase_culte select', property: 'readonly', value: true });
        tabBlock.push({ element: '#zone_saisie_phrase_culte select', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '#zone_saisie_phrase_culte select', property: 'color', value: '#a3a3a3' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Bloque le bouton de validation si besoin
    $('.icone_valider_collector').click(function ()
    {
        var submitButton = $('#' + $(this).attr('id'));
        var zoneButton   = $('#' + submitButton.parent().attr('id'));
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (smileys vote)
        tabBlock.push({ element: '.link_current_vote', property: 'pointer-events', value: 'none' });

        // Blocage spécifique (liens actions)
        tabBlock.push({ element: '.icone_modifier_collector', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_supprimer_collector', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_valider_collector', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_annuler_collector', property: 'display', value: 'none' });

        // Blocage spécifique (toutes zones de saisie autres phrases cultes)
        tabBlock.push({ element: '.zone_collectors input', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_collectors input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_collectors input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_collectors textarea', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_collectors textarea', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_collectors textarea', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_collectors select', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_collectors select', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_collectors select', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_collectors label', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_collectors label', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_collectors label', property: 'color', value: '#a3a3a3' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au changement ***/
    // Applique les filtres
    $('#applySort, #applyFilter').on('change', function ()
    {
        if ($(this).val() == 'dateDesc' || $(this).val() == 'dateAsc')
            applySortOrFilter($(this).val(), $_GET('filter'));
        else
            applySortOrFilter($_GET('sort'), $(this).val());
    });

    // Affiche la saisie "Autre" (phrase culte)
    $('#speaker').on('change', function ()
    {
        afficherOther('speaker', 'other_name');
    });

    // Affiche la saisie "Autre" (image culte)
    $('#speaker_2').on('change', function ()
    {
        afficherOther('speaker_2', 'other_name_2');
    });

    // Charge l'image (saisie)
    $('.loadSaisieCollector').on('change', function (event)
    {
        loadFile(event, 'image_collector', true);
    });

    // Affiche la saisie "Autre" (modification)
    $('.changeSpeaker').on('change', function ()
    {
        var idCollector = $(this).attr('id').replace('speaker_', '');

        afficherModifierOther('speaker_' + idCollector, 'other_speaker_' + idCollector);
    });

    // Charge l'image (modification)
    $('.loadModifierCollector').on('change', function (event)
    {
        var idImage = $(this).attr('id').replace('fichier_', '');

        loadFile(event, 'image_collector_' + idImage, true);
    });

    $('.loadImage').on('load', function ()
    {
        var idImage = $(this).attr('id').replace('image_collector_', '');

        adaptBrowse(idImage);

        // Réinitialisation Masonry
        initMasonry();
    });

    /*** Actions au redimensionnement */
    // Adaptation de la masonry
    $('.update_text_collector, .update_context_collector').mouseup(function ()
    {
        if (this.style.width != this.outerWidth || this.style.height != this.outerHeight)
            initMasonry();
    });

    /*** Calendriers ***/
    if ($('#datepicker_collector').length || $('#datepicker_image').length)
    {
        $('#datepicker_collector, #datepicker_image').datepicker(
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

    $('.update_date_collector').each(function ()
    {
        $(this).datepicker(
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
    });
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function ()
{
    // Masonry (Phrases cultes & images)
    if ($('.zone_collectors').length)
    {
        $('.zone_collectors').masonry().masonry('destroy');

        $('.zone_collectors').masonry(
        {
            // Options
            itemSelector: '.zone_collector',
            columnWidth: 525,
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_collectors').addClass('masonry');
    }

    // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
    var id     = $_GET('anchor');
    var offset = 70;
    var shadow = true;

    // Scroll vers l'id
    scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Initialisation manuelle de "Masonry"
function initMasonry()
{
    // On lance Masonry
    $('.zone_collectors').masonry(
    {
        // Options
        itemSelector: '.zone_collector',
        columnWidth: 525,
        fitWidth: true,
        gutter: 20,
        horizontalOrder: true
        /*transitionDuration: 0*/
    });

    // Découpe le texte si besoin
    $('.text_collector').wrapInner();
}

// Adapte la zone "Parcourir" en fonction de la taille de l'image à son chargement
function adaptBrowse(id)
{
    var image_height = $('#image_collector_' + id).height();
    var marge        = -1 * (image_height + 3);

    $('#zone_parcourir_' + id).height(image_height + 'px');
    $('#mask_collector_' + id).height(image_height + 'px');
    $('#mask_collector_' + id).css('margin-top', marge + 'px');
}

// Affiche ou masque la zone de saisie "Autre" (insertion)
function afficherOther(select, required)
{
    if ($('#' + select).val() == 'other')
    {
        $('#' + required).css('display', 'inline-block');
        $('#' + required).prop('required', true);
        $('#' + select).addClass('speaker_autre');
    }
    else
    {
        $('#' + required).css('display', 'none');
        $('#' + required).prop('required', false);
        $('#' + select).removeClass('speaker_autre');
    }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
    if ($('#' + select).val() == 'other')
    {
        $('#' + id).css('display', 'block');
        $('#' + id).prop('required', true);
    }
    else
    {
        $('#' + id).css('display', 'none');
        $('#' + id).prop('required', false);
    }
}

// Redirige pour appliquer le tri ou le filtre
function applySortOrFilter(sort, filter)
{
    document.location.href = 'collector.php?sort=' + sort + '&filter=' + filter + '&action=goConsulter&page=1';
}

// Affiche l'image d'une phrase culte
function afficherDetailsCollector(element)
{
    var html      = '';
    var path      = element.children().attr('src');
    var split     = path.split('/');
    var collector = split[split.length - 1];

    html += '<div id="zoom_image" class="fond_zoom_image">';
        html += '<div class="zone_image_zoom">';
            html += '<a id="fermerZoomImage" class="lien_zoom"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';
            html += '<img src="' + path + '" alt="' + collector + '" class="image_zoom" />';
        html += '</div>';
    html += '</div>';

    $('body').append(html);

    // Affichage de l'image
    afficherMasquerIdWithDelay('zoom_image');
}