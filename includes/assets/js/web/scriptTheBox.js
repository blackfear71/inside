/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ajouter une idée
    $('#ajouterIdee, #fermerIdee').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_idee');
    });

    // Bloque le bouton de soumission si besoin
    $('#bouton_saisie_idee').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = null;

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Affiche la zone de modification d'une idée
    $('.modifierIdee').click(function ()
    {
        var idIdee = $(this).attr('id').replace('modifier_', '');

        afficherMasquerIdNoDelay('modifier_idee_' + idIdee);
        afficherMasquerIdNoDelay('visualiser_idee_' + idIdee);
        initMasonry();
    });

    // Ferme la zone de modification d'une idée
    $('.annulerIdee').click(function ()
    {
        var idIdee = $(this).attr('id').replace('annuler_update_idee_', '');

        afficherMasquerIdNoDelay('modifier_idee_' + idIdee);
        afficherMasquerIdNoDelay('visualiser_idee_' + idIdee);
        initMasonry();
    });

    // Bloque le bouton de validation si besoin
    $('.icone_valider_idee').click(function ()
    {
        var submitButton = $('#' + $(this).attr('id'));
        var zoneButton   = $('#' + submitButton.parent().attr('id'));
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (liens actions)
        tabBlock.push({ element: '.icone_modifier_idee', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_valider_idee', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_annuler_idee', property: 'display', value: 'none' });

        // Blocage spécifique (toutes zones de saisie autres restaurants)
        tabBlock.push({ element: '.zone_ideas input', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_ideas input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_ideas input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_ideas textarea', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_ideas textarea', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_ideas textarea', property: 'color', value: '#a3a3a3' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au redimensionnement */
    // Adaptation de la masonry
    $('.update_saisie_contenu').mouseup(function ()
    {
        if (this.style.width != this.outerWidth || this.style.height != this.outerHeight)
            initMasonry();
    });
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation mobile
    adaptTheBox();
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function ()
{
    // Adaptation mobile
    adaptTheBox();

    // Masonry (Idées)
    if ($('.zone_ideas').length)
    {
        $('.zone_ideas').masonry().masonry('destroy');

        $('.zone_ideas').masonry(
        {
            // zone_ideas
            itemSelector: '.zone_idea',
            columnWidth: 480,
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_ideas').addClass('masonry');
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
    $('.zone_ideas').masonry(
    {
        // zone_ideas
        itemSelector: '.zone_idea',
        columnWidth: 480,
        fitWidth: true,
        gutter: 20,
        horizontalOrder: true
    });
}

// Adaptations des idées sur mobile
function adaptTheBox()
{
    if ($(window).width() < 1080)
    {
        $('.zone_vues').css('display', 'block');
        $('.zone_vues').css('width', '100%');

        $('.zone_ideas_right').css('display', 'block');
        $('.zone_ideas_right').css('width', '100%');
        $('.zone_ideas_right').css('margin-left', '0');

        $('.view').css('display', 'inline-block');
        $('.view').css('width', 'calc(50% - 10px)');
        $('.view:eq(0)').css('margin-right', '20px');
        $('.view:eq(2)').css('margin-right', '20px');
    }
    else
    {
        $('.zone_vues').css('display', 'inline-block');
        $('.zone_vues').css('width', '120px');

        $('.zone_ideas_right').css('display', 'inline-block');
        $('.zone_ideas_right').css('width', 'calc(100% - 140px)');
        $('.zone_ideas_right').css('margin-left', '20px');

        $('.view').css('display', 'block');
        $('.view').css('width', '100%');
        $('.view:eq(0)').css('margin-right', '0');
        $('.view:eq(2)').css('margin-right', '0');
    }
}