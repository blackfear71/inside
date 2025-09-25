/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Affiche la saisie de propositions
    $('#saisiePropositions, #fermerPropositions').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_propositions');
    });

    // Efface la zone de recherche au clic sur la croix
    $('.logo_recherche_live').click(function ()
    {
        var idSearch = $(this).attr('id');
        var idForm;

        switch (idSearch)
        {
            case 'reset_recherche_live_propositions':
                idForm = 'zone_saisie_propositions';
                break;

            case 'reset_recherche_live_resume':
                idForm = 'zone_saisie_resume';
                break;

            default:
                idForm = '';
                break;
        }

        if (idForm != '')
            reinitialisationRechercheLive(idForm);
    });

    // Change la couleur d'une case à cocher à la sélection et affiche les options supplémentaires
    $(document).on('click', '.zone_saisie_proposition', function ()
    {
        // Changement de la couleur de la ligne
        changeCheckedColorProposition($(this));

        // Affichage des choix supplémentaires
        afficherOptionsProposition($(this));
    });

    // Affiche la saisie horaire restaurant
    $(document).on('click', '.afficherHoraire', function ()
    {
        var idBouton     = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('choix_horaire_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idHoraires   = 'zone_listbox_horaire_' + idRestaurant;

        afficherMasquerIdNoDelay(idBouton);
        afficherListboxHoraires(idRestaurant, idHoraires, 'create');
        adaptProposition(idSaisie, true);
    });

    // Affiche la saisie transports restaurant
    $(document).on('click', '.afficherTransports', function ()
    {
        var idBouton     = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('choix_transports_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idCheckbox   = 'zone_checkbox_transports_' + idRestaurant;

        afficherMasquerIdNoDelay(idBouton);
        afficherCheckboxTransports(idRestaurant, idCheckbox);
        adaptProposition(idSaisie, true);
    });

    // Affiche la saisie menu restaurant
    $(document).on('click', '.afficherMenu', function ()
    {
        var idBouton     = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('choix_menu_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idMenu       = 'zone_saisie_menu_' + idRestaurant;

        afficherMasquerIdNoDelay(idBouton);
        afficherSaisieMenu(idRestaurant, idMenu);
        adaptProposition(idSaisie, true);
    });

    // Masque la saisie horaire restaurant
    $(document).on('click', '.annulerHoraire', function ()
    {
        var idAnnuler    = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('annuler_horaires_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idZone       = 'choix_horaire_' + idRestaurant;
        var idSelectH    = 'select_heures_' + idRestaurant;
        var idSelectM    = 'select_minutes_' + idRestaurant;

        cacherListboxHoraires(idZone, idAnnuler, idSelectH, idSelectM);
        adaptProposition(idSaisie, false);
    });

    // Masque la saisie transports restaurant
    $(document).on('click', '.annulerTransports', function ()
    {
        var idAnnuler    = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('annuler_transports_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idZone       = 'choix_transports_' + idRestaurant;
        var idTransport  = 'zone_transports_' + idRestaurant;

        cacherCheckboxTransports(idZone, idAnnuler, idTransport);
        adaptProposition(idSaisie, false);
    });

    // Masque la saisie menu restaurant
    $(document).on('click', '.annulerMenu', function ()
    {
        var idAnnuler    = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('annuler_menu_', '');
        var idSaisie     = 'zone_saisie_proposition_' + idRestaurant;
        var idZone       = 'choix_menu_' + idRestaurant;
        var idMenu       = 'zone_menu_' + idRestaurant;

        cacherSaisieMenu(idZone, idAnnuler, idMenu);
        adaptProposition(idSaisie, false);
    });

    // Coche / décoche le mode de transport
    $(document).on('click', '.cocherTransport', function ()
    {
        var idCheck = $(this).closest('div').attr('id');

        changeCheckedColorTransport(idCheck);
    });

    // Bloque le bouton de soumission si besoin (saisie propositions)
    $('#bouton_saisie_propositions').click(function ()
    {
        var zoneButton   = $('.zone_boutons_saisie_propositions');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (boutons actions)
        tabBlock.push({ element: '.zone_bouton_option_proposition', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.bouton_annuler_proposition', property: 'pointer-events', value: 'none' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Affiche la modification d'un choix
    $('.modifierChoix').click(function ()
    {
        var idChoix = $(this).attr('id').replace('modifier_', '');

        afficherMasquerIdNoDelay('modifier_choix_' + idChoix);
        afficherMasquerIdNoDelay('visualiser_choix_' + idChoix);
        initMasonry();
    });

    // Ferme la modification d'un choix
    $('.annulerChoix').click(function ()
    {
        var idChoix = $(this).attr('id').replace('annuler_update_choix_', '');

        afficherMasquerIdNoDelay('modifier_choix_' + idChoix);
        afficherMasquerIdNoDelay('visualiser_choix_' + idChoix);
        initMasonry();
    });

    // Affiche la modification de l'horaire d'un choix
    $(document).on('click', '.afficherHoraireUpdate', function ()
    {
        var idBouton     = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('update_horaire_', '');
        var idHoraires   = 'zone_update_listbox_horaire_' + idRestaurant;

        afficherMasquerIdNoDelay(idBouton);
        afficherListboxHoraires(idRestaurant, idHoraires, 'update');
    });

    // Masque la modification de l'horaire d'un choix
    $(document).on('click', '.annulerHoraireUpdate', function ()
    {
        var idAnnuler    = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('annuler_horaires_', '');
        var idZone       = 'update_horaire_' + idRestaurant;
        var idSelectH    = 'select_heures_' + idRestaurant;
        var idSelectM    = 'select_minutes_' + idRestaurant;

        cacherListboxHoraires(idZone, idAnnuler, idSelectH, idSelectM);
    });

    // Affiche les détails d'une proposition
    $('.afficherDetails').click(function ()
    {
        var idDetails = $(this).attr('id').replace('afficher_details_', '');

        afficherDetailsProposition('zone_details', idDetails);

        // Adaptation mobile
        adaptDetails();
    });

    // Affiche la description longue d'un restaurant (détails proposition)
    $(document).on('click', '#descriptionDetails', function ()
    {
        afficherMasquerIdNoDelay('details_description_short');
        afficherMasquerIdNoDelay('details_description_long');
    });

    // Affiche la saisie lieu restaurant (résumé)
    $('.afficherResume').click(function ()
    {
        var idBouton = $(this).attr('id');
        var date     = $(this).attr('id').replace('choix_resume_', '');

        afficherMasquerIdNoDelay(idBouton);
        afficherListboxLieuxResume(date);
    });

    // Masque la saisie lieu restaurant
    $(document).on('click', '.annulerLieuResume', function ()
    {
        var idAnnuler = $(this).attr('id');
        var date      = $(this).attr('id').replace('annuler_restaurant_resume_', '');
        var idValider = 'valider_restaurant_resume_' + date;
        var idZone    = 'zone_listbox_resume_' + date;

        cacherListboxRestaurantsResume(date, idZone, idValider, idAnnuler);
    });

    // Affiche la saisie de restaurant
    $('#saisieRestaurant, #fermerRestaurant').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_restaurant');
    });

    // Change le statut d'un jour d'ouverture (saisie restaurant)
    $('.checkDay').click(function ()
    {
        var idJour = $(this).attr('id').split('_');
        var jour   = idJour[idJour.length - 1];

        changeCheckedDay('saisie_checkbox_ouverture_' + jour, 'saisie_label_ouverture_' + jour, 'label_jour_checked', 'label_jour');
    });

    // Ajoute un champ de saisie libre type de restaurant (saisie restaurant)
    $('#addType').click(function ()
    {
        var idBouton = $(this).attr('id');
        var idParent = 'types_restaurant';

        addOtherType(idBouton, idParent);
    });

    // Change la couleur des checkbox types de restaurant (saisie & modification restaurant)
    $('.checkType, .checkTypeUpdate').click(function ()
    {
        var idType = $(this).closest('div').attr('id');

        changeCheckedColorType(idType);
    });

    // Scroll vers un lieu
    $('.lienLieu').click(function ()
    {
        var idLieu = $(this).attr('id').replace('link_', '');
        var offset = 20;
        var shadow = false;

        scrollToId(idLieu, offset, shadow);
    });

    // Plie ou déplie les lieux
    $('.bouton_fold').click(function ()
    {
        var idZone = $(this).attr('id').replace('fold_', '');

        afficherMasquerSection($(this), idZone, '');

        // Réinitialisation Masonry
        initMasonry();

        // Evite le clignotement des images à l'application de la masonry
        $('#' + idZone).css('opacity', '0');
        $('#' + idZone).css('transition', 'opacity 0s ease');
    });

    // Affiche la description longue d'un restaurant
    $('.descriptionRestaurant').click(function ()
    {
        var idRestaurant = $(this).attr('id').replace('description_', '');

        afficherMasquerIdNoDelay('short_description_' + idRestaurant);
        afficherMasquerIdNoDelay('long_description_' + idRestaurant);
        initMasonry();
    });

    // Affiche la zone de modification d'un restaurant
    $('.modifierRestaurant').click(function ()
    {
        var idRestaurant = $(this).attr('id').replace('modifier_', '');

        afficherMasquerIdNoDelay('modifier_restaurant_' + idRestaurant);
        afficherMasquerIdNoDelay('visualiser_restaurant_' + idRestaurant);
        initMasonry();
    });

    // Ferme la zone de modification d'un restaurant
    $('.annulerRestaurant').click(function ()
    {
        var idRestaurant = $(this).attr('id').replace('annuler_update_restaurant_', '');

        afficherMasquerIdNoDelay('modifier_restaurant_' + idRestaurant);
        afficherMasquerIdNoDelay('visualiser_restaurant_' + idRestaurant);
        initMasonry();
    });

    // Change le statut d'un jour d'ouverture (modification restaurant)
    $('.checkDayUpdate').click(function ()
    {
        var idJour       = $(this).attr('id').split('_');
        var idRestaurant = idJour[idJour.length - 1];
        var jour         = idJour[idJour.length - 2];

        changeCheckedDay('checkbox_update_ouverture_' + jour + '_' + idRestaurant, 'label_update_ouverture_' + jour + '_' + idRestaurant, 'update_label_jour_checked', 'update_label_jour');
    });

    // Ajoute un champ de saisie libre type de restaurant (modification restaurant)
    $('.addTypeUpdate').click(function ()
    {
        var idBouton     = $(this).attr('id');
        var idRestaurant = $(this).attr('id').replace('type_update_', '');

        addOtherType(idBouton, 'update_types_restaurant_' + idRestaurant);
    });

    // Bloque le bouton de soumission si besoin (ajout restaurant)
    $('#bouton_saisie_restaurant').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie_restaurant');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (ajout type restaurant - ajout restaurant)
        tabBlock.push({ element: '#addType', property: 'display', value: 'none' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    // Bloque le bouton de validation si besoin (modification restaurant)
    $('.icone_valider_restaurant').click(function ()
    {
        var submitButton = $('#' + $(this).attr('id'));
        var zoneButton   = $('#' + submitButton.parent().attr('id'));
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = [];

        // Blocage spécifique (ajout type restaurant - modification restaurant)
        tabBlock.push({ element: '.bouton_update_type_autre', property: 'display', value: 'none' });

        // Blocage spécifique (liens actions)
        tabBlock.push({ element: '.lien_modifier_restaurant', property: 'display', value: 'none' });
        tabBlock.push({ element: '.lien_supprimer_restaurant', property: 'display', value: 'none' });
        tabBlock.push({ element: '.lien_choix_rapide_restaurant', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_valider_restaurant', property: 'display', value: 'none' });
        tabBlock.push({ element: '.icone_annuler_restaurant', property: 'display', value: 'none' });

        // Blocage spécifique (toutes zones de saisie autres restaurants)
        tabBlock.push({ element: '.zone_fiches_restaurants input', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_fiches_restaurants input', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_fiches_restaurants input', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_fiches_restaurants textarea', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_fiches_restaurants textarea', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_fiches_restaurants textarea', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_fiches_restaurants select', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_fiches_restaurants select', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_fiches_restaurants select', property: 'color', value: '#a3a3a3' });
        tabBlock.push({ element: '.zone_fiches_restaurants label', property: 'readonly', value: true });
        tabBlock.push({ element: '.zone_fiches_restaurants label', property: 'pointer-events', value: 'none' });
        tabBlock.push({ element: '.zone_fiches_restaurants label', property: 'color', value: '#a3a3a3' });

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au changement ***/
    // Affiche la saisie restaurant liée au lieu (résumé)
    $(document).on('change', '.afficherRestaurantResume', function ()
    {
        var date = $(this).attr('id').replace('select_lieu_resume_', '');

        afficherListboxRestaurantsResume(date, 'select_lieu_resume_' + date, 'zone_listbox_resume_' + date);
    });

    // Change la couleur du type à la saisie restaurant
    $(document).on('input', '.saisieType', function ()
    {
        var idType = $(this).attr('id');

        changeTypeColor(idType);
    });

    // Affiche la saisie "Autre" (saisie restaurant)
    $('#saisie_location').on('change', function ()
    {
        afficherOther('saisie_location', 'saisie_other_location', 'saisie_nom');
    });

    // Charge l'image (saisie restaurant)
    $('.loadSaisieRestaurant').on('change', function (event)
    {
        loadFile(event, 'image_restaurant_saisie', true);
    });

    // Affiche la saisie "Autre" (modification restaurant)
    $('.changeLieu').on('change', function ()
    {
        var idRestaurant = $(this).attr('id').replace('update_location_', '');

        afficherModifierOther('update_location_' + idRestaurant, 'other_location_' + idRestaurant, 'saisie_nom');
    });

    // Charge l'image (modification restaurant)
    $('.loadModifierRestaurant').on('change', function (event)
    {
        var idRestaurant = $(this).attr('id').replace('modifier_image_', '');

        loadFile(event, 'img_restaurant_' + idRestaurant, true);
    });

    /*** Actions à la saisie ***/
    // Filtre la recherche
    $('.input_recherche_live').keyup(function ()
    {
        var idInput      = $(this).attr('id');
        var inputContent = $.trim($(this).val());
        var idForm;

        switch (idInput)
        {
            case 'recherche_live_propositions':
                idForm = 'zone_saisie_propositions';
                break;

            case 'recherche_live_resume':
                idForm = 'zone_saisie_resume';
                break;

            default:
                idForm = '';
                break;
        }

        if (idForm != '')
            liveSearch(idForm, inputContent);
    });

    /*** Actions au redimensionnement */
    // Adaptation de la masonry
    $('.zone_update_types, .textarea_update_description_restaurant').mouseup(function ()
    {
        if (this.style.width != this.outerWidth || this.style.height != this.outerHeight)
            initMasonry();
    });
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation mobile
    adaptPropositions();
    adaptDetails();
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function ()
{
    // Adaptation mobile
    adaptPropositions();

    // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
    $('.zone_propositions_determination').css('display', 'block');
    $('.zone_restaurants').css('display', 'block');

    // Calcul automatique des tailles des zones
    tailleAutoZones();

    // Masonry (Propositions)
    if ($('.zone_propositions').length)
    {
        $('.zone_propositions').masonry().masonry('destroy');

        $('.zone_propositions').masonry(
        {
            // Options
            itemSelector: '.zone_proposition, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
            columnWidth: 200,
            fitWidth: true,
            gutter: 30,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_propositions').addClass('masonry');
    }

    // Masonry (Restaurants)
    if ($('.zone_fiches_restaurants').length)
    {
        $('.zone_fiches_restaurants').masonry().masonry('destroy');

        $('.zone_fiches_restaurants').masonry(
        {
            // Options
            itemSelector: '.fiche_restaurant',
            columnWidth: 500,
            fitWidth: true,
            gutter: 30,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_fiches_restaurants').addClass('masonry');
    }

    // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
    var id     = $_GET('anchor');
    var offset = 60;
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
    // Masonry (Propositions)
    $('.zone_propositions').masonry(
    {
        // Options
        itemSelector: '.zone_proposition, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
        columnWidth: 200,
        fitWidth: true,
        gutter: 30,
        horizontalOrder: true
    });

    // Masonry (Restaurants)
    $('.zone_fiches_restaurants').masonry(
    {
        // Options
        itemSelector: '.fiche_restaurant',
        columnWidth: 500,
        fitWidth: true,
        gutter: 30,
        horizontalOrder: true
    });

    // Découpe le texte si besoin
    $('.description_restaurant').wrapInner();
}

// Adaptations des propositions sur mobile
function adaptPropositions()
{
    if ($(window).width() < 1080)
    {
        $('.zone_propositions_left').css('display', 'block');
        $('.zone_propositions_left').css('width', '100%');

        $('.zone_propositions_right').css('display', 'block');
        $('.zone_propositions_right').css('width', '100%');
        $('.zone_propositions_right').css('margin-left', '0');
    }
    else
    {
        $('.zone_propositions_left').css('display', 'inline-block');
        $('.zone_propositions_left').css('width', '200px');

        $('.zone_propositions_right').css('display', 'inline-block');
        $('.zone_propositions_right').css('width', 'calc(100% - 220px)');
        $('.zone_propositions_right').css('margin-left', '20px');
    }
}

// Adaptation des détails d'une proposition sur mobile
function adaptDetails()
{
    if ($(window).width() < 1080)
    {
        if ($('.zone_details_description_bottom').length
            && $('.zone_details_description_bottom').css('display') != 'none')
        {
            $('.zone_details_description_bottom').css('display', 'block');
            $('.zone_details_description_bottom').css('width', '100%');
            $('.zone_details_description_bottom').css('margin-right', '0');

            $('.zone_details_menus_bottom').css('display', 'block');
            $('.zone_details_menus_bottom').css('width', '100%');
            $('.zone_details_menus_bottom').css('margin-top', '20px');
        }
    }
    else
    {
        if ($('.zone_details_description_bottom').length
        &&  $('.zone_details_description_bottom').css('display') != 'none')
        {
            $('.zone_details_description_bottom').css('display', 'inline-block');
            $('.zone_details_description_bottom').css('width', 'calc(32% - 10px)');
            $('.zone_details_description_bottom').css('margin-right', '20px');

            $('.zone_details_menus_bottom').css('display', 'inline-block');
            $('.zone_details_menus_bottom').css('width', 'calc(68% - 10px)');
            $('.zone_details_menus_bottom').css('margin-top', '20px');
        }
    }
}

// Calcul les tailles des zones automatiquement
function tailleAutoZones()
{
    // Taille des zones de résumé en fonction de la plus grande
    var buttonHeight;
    var calculPaddingNoProposal;
    var dayHeight  = $('.jour_semaine_resume').height();
    var textHeight = $('.no_proposal').height();

    // Hauteur maximale et calcul du padding
    var heights = [];

    $('.zone_proposition_resume').each(function ()
    {
        heights.push($(this).height());
    });

    var maxHeight     = Math.max.apply(null, heights);
    var calculPadding = (maxHeight - (dayHeight + textHeight + 20)) / 2;

    // Cas si ajout choix résumé présent
    $('.zone_proposition_resume > .no_proposal').each(function ()
    {
        var dateResume = this.id.replace('no_proposal_', '');

        if ($('#choix_resume_' + dateResume).length)
        {
            buttonHeight            = $('#choix_resume_' + dateResume).height();
            calculPaddingNoProposal = calculPadding - ((buttonHeight + 20) / 2);

            $('#no_proposal_' + dateResume).css('padding-top', calculPaddingNoProposal);
            $('#no_proposal_' + dateResume).css('padding-bottom', calculPaddingNoProposal);
        }
        else
        {
            $('#no_proposal_' + dateResume).css('padding-top', calculPadding);
            $('#no_proposal_' + dateResume).css('padding-bottom', calculPadding);
        }
    });

    $('.zone_proposition_resume').css('min-height', maxHeight);
}

// Réinitialise la zone de recherche saisie
function reinitialisationRechercheLive(idForm)
{
    // On vide la saisie
    $('#' + idForm).find('.input_recherche_live').val('');

    // On cache le message vide
    $('#' + idForm).find('.empty_recherche_live').hide();

    // Affiche tous les lieux par défaut
    $('#' + idForm).find('.zone_recherche_conteneur').show();

    // Affiche tous les restaurants par défaut
    $('#' + idForm).find('.zone_recherche_item').show();

    // Affiche le message si vide s'il n'y a pas de restaurants
    if ($('#' + idForm).find('.empty_propositions').length);
        $('#' + idForm).find('.empty_propositions').show();
}

// Filtre la zone de recherche en fonction de la saisie
function liveSearch(idForm, input)
{
    // Déplie toutes les zones de recherche
    $('#' + idForm).find('.zone_recherche_conteneur > .titre_section .bouton_fold').each(function ()
    {
        var idZone = $(this).attr('id').replace('fold_', '');

        afficherMasquerSection($(this), idZone, 'open');
    });

    // Si zone vide, on fait tout apparaitre
    if (!input)
    {
        // Affiche tous les lieux par défaut
        $('#' + idForm).find('.zone_recherche_conteneur').show();

        // Affiche tous les restaurants par défaut
        $('#' + idForm).find('.zone_recherche_item').show();

        // On cache le message vide
        $('#' + idForm).find('.empty_recherche_live').hide();

        // Affiche le message si vide s'il n'y a pas de restaurants
        if ($('#' + idForm).find('.empty_propositions').length);
            $('#' + idForm).find('.empty_propositions').show();
    }
    // Sinon on filtre
    else
    {
        // Affiche tous les lieux par défaut
        $('#' + idForm).find('.zone_recherche_conteneur').show();

        // Cache les restaurants qui ne correspondent pas
        $('#' + idForm).find('.zone_recherche_item').show().not(':containsCaseInsensitive(' + input + ')').hide();

        // Cache une zone qui ne contient pas de restaurant qui corresponde
        $('#' + idForm).find('.zone_recherche_contenu').show().not(':containsCaseInsensitive(' + input + ')').parent().hide();

        // Filtrage de l'affichage
        if (!$('.zone_recherche_item').is(':visible'))
            $('#' + idForm).find('.zone_recherche_conteneur').hide();

        // Affichage / masquage message vide
        if ($('.zone_recherche_conteneur').is(':visible'))
        {
            $('#' + idForm).find('.empty_recherche_live').hide();

            // Affiche le message si vide s'il n'y a pas de restaurants
            if ($('#' + idForm).find('.empty_propositions').length);
                $('#' + idForm).find('.empty_propositions').show();
        }
        else
        {
            $('#' + idForm).find('.empty_recherche_live').show();
            
            // Affiche le message si vide s'il n'y a pas de restaurants
            if ($('#' + idForm).find('.empty_propositions').length);
                $('#' + idForm).find('.empty_propositions').hide();
        }
    }
}

// Rend la recherche insensible à la casse
$.expr[':'].containsCaseInsensitive = $.expr.createPseudo(function (arg)
{
    return function (elem)
    {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});

// Change la couleur d'une proposition (checkbox)
function changeCheckedColorProposition(zone)
{
    if (zone.find('.checkbox_proposition').prop('checked'))
    {
        zone.css('background-color', '#96e687');
        zone.find('.zone_image_proposition').css('background-color', '#70d55d');
        zone.find('.zone_checkbox_proposition').css('background-color', '#70d55d');
    }
    else
    {
        zone.css('background-color', '#e3e3e3');
        zone.find('.zone_image_proposition').css('background-color', '#d3d3d3');
        zone.find('.zone_checkbox_proposition').css('background-color', '#d3d3d3');
    }
}

// Affiche les options supplémentaires d'une proposition
function afficherOptionsProposition(zone)
{
    if (zone.find('.checkbox_proposition').prop('checked'))
    {
        // Adapte la taille du nom
        zone.find('.nom_proposition').css('width', 'calc(25% - 35px)');

        // Affichage des choix horaire, transport et menu
        zone.find('.zone_bouton_option_proposition').each(function ()
        {
            $(this).css('display', 'inline-block');
        });

        // Adaptation de la zone de saisie
        adaptProposition(zone.attr('id'), true)
    }
    else
    {
        // Adapte la taille du nom
        zone.find('.nom_proposition').css('width', 'calc(100% - 140px)');

        // Masquage des choix horaire, transport et menu
        zone.find('.zone_bouton_option_proposition').each(function ()
        {
            $(this).css('display', 'none');
        });

        // Adaptation de la zone de saisie
        adaptProposition(zone.attr('id'), false)
    }
}

// Adapte la saisie d'une proposition
function adaptProposition(idZone, afficher)
{
    if (afficher == true)
    {
        // Image
        $('#' + idZone + ' .zone_image_proposition').css('height', 'calc(' + $('#' + idZone).height() + 'px - 10px)');
        $('#' + idZone + ' .zone_image_proposition').css('line-height', 'calc(' + $('#' + idZone).height() + 'px - 15px)');

        // Case à cocher
        $('#' + idZone + ' .zone_checkbox_proposition').css('height', $('#' + idZone).height());
        $('#' + idZone + ' .zone_checkbox_proposition').css('line-height', 'calc(' + $('#' + idZone).height() + 'px - 5px)');
    }
    else
    {
        // Image
        $('#' + idZone + ' .zone_image_proposition').css('height', '50px');
        $('#' + idZone + ' .zone_image_proposition').css('line-height', '45px');

        // Case à cocher
        $('#' + idZone + ' .zone_checkbox_proposition').css('height', '60px');
        $('#' + idZone + ' .zone_checkbox_proposition').css('line-height', '55px');
    }
}

// Affiche les listbox des horaires
function afficherListboxHoraires(idRestaurant, zone, type)
{
    var html = '';
    var nameSelectH;
    var nameSelectM;
    var classListbox;
    var classBouton;

    if (type == 'create')
    {
        nameSelectH  = 'select_heures[' + idRestaurant + ']';
        nameSelectM  = 'select_minutes[' + idRestaurant + ']';
        classListbox = 'listbox_horaires';
        classBouton  = 'bouton_annuler_proposition annulerHoraire';
    }
    else if (type == 'update')
    {
        nameSelectH  = 'select_heures_' + idRestaurant;
        nameSelectM  = 'select_minutes_' + idRestaurant;
        classListbox = 'listbox_horaires_update';
        classBouton  = 'bouton_annuler_update annulerHoraireUpdate';
    }

    var idSelectH = 'select_heures_' + idRestaurant;
    var idSelectM = 'select_minutes_' + idRestaurant;
    var idAnnuler = 'annuler_horaires_' + idRestaurant;

    html += '<select id="' + idSelectH + '" name="' + nameSelectH + '" class="' + classListbox + '">';
        for (var i = 11; i < 14; i++)
        {
            if (i == 12)
                html += '<option value="' + i + '" selected>' + i + '</option>';
            else
                html += '<option value="' + i + '">' + i + '</option>';
        }
    html += '</select>';

    html += '<select id="' + idSelectM + '" name="' + nameSelectM + '" class="' + classListbox + '">';
        for (var j = 0; j < 4; j++)
        {
            if (j == 0)
                html += '<option value="0' + j + '" selected>0' + j + '</option>';
            else
                html += '<option value="' + j * 15 + '">' + j * 15 + '</option>';
        }
    html += '</select>';

    html += '<a id="' + idAnnuler + '" class="' + classBouton + '">Annuler</a>';

    $('#' + zone).append(html);
}

// Cache les lisbox des horaires
function cacherListboxHoraires(zone, bouton, heures, minutes)
{
    $('#' + heures).remove();
    $('#' + minutes).remove();
    $('#' + bouton).remove();

    $('#' + zone).css('display', 'block');
}

// Affiche les checkbox des transports
function afficherCheckboxTransports(idRestaurant, zone)
{
    var html      = '';
    var idZone    = 'zone_transports_' + idRestaurant;
    var idCheckF  = 'checkbox_feet_' + idRestaurant;
    var idCheckB  = 'checkbox_bike_' + idRestaurant;
    var idCheckT  = 'checkbox_tram_' + idRestaurant;
    var idCheckC  = 'checkbox_car_' + idRestaurant;
    var idAnnuler = 'annuler_transports_' + idRestaurant;

    html += '<div id="' + idZone + '" class="zone_transports">';
        html += '<div id="bouton_' + idCheckF + '" class="switch_transport">';
            html += '<input id="' + idCheckF + '" type="checkbox" value="F" name="checkbox_feet[' + idRestaurant + ']" />';
            html += '<label for="' + idCheckF + '" class="label_switch_transport cocherTransport"><img src="/includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox_transport" /></label>';
        html += '</div>';

        html += '<div id="bouton_' + idCheckB + '" class="switch_transport">';
            html += '<input id="' + idCheckB + '" type="checkbox" value="B" name="checkbox_bike[' + idRestaurant + ']" />';
            html += '<label for="' + idCheckB + '" class="label_switch_transport cocherTransport"><img src="/includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox_transport" /></label>';
        html += '</div>';

        html += '<div id="bouton_' + idCheckT + '" class="switch_transport">';
            html += '<input id="' + idCheckT + '" type="checkbox" value="T" name="checkbox_tram[' + idRestaurant + ']" />';
            html += '<label for="' + idCheckT + '" class="label_switch_transport cocherTransport"><img src="/includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox_transport" /></label>';
        html += '</div>';

        html += '<div id="bouton_' + idCheckC + '" class="switch_transport">';
            html += '<input id="' + idCheckC + '" type="checkbox" value="C" name="checkbox_car[' + idRestaurant + ']" />';
            html += '<label for="' + idCheckC + '" class="label_switch_transport cocherTransport"><img src="/includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox_transport" /></label>';
        html += '</div>';
    html += '</div>';

    html += '<a id="' + idAnnuler + '" class="bouton_annuler_proposition annulerTransports">Annuler</a>';

    $('#' + zone).append(html);
}

// Cache les checkbox des transports
function cacherCheckboxTransports(zone, bouton, transport)
{
    $('#' + transport).remove();
    $('#' + bouton).remove();
    $('#' + zone).css('display', 'block');
}

// Change la couleur des checkbox (saisie transport)
function changeCheckedColorTransport(input)
{
    if ($('#' + input).children('input').prop('checked'))
        $('#' + input).removeClass('bouton_transport_checked');
    else
        $('#' + input).addClass('bouton_transport_checked');
}

// Affiche la saisie du menu
function afficherSaisieMenu(idRestaurant, zone)
{
    var html      = '';
    var idZone    = 'zone_menu_' + idRestaurant;
    var idAnnuler = 'annuler_menu_' + idRestaurant;

    html += '<div id="' + idZone + '" class="zone_saisie_menu">';
        html += '<input type="text" placeholder="Entrée" name="saisie_entree[' + idRestaurant + ']" class="saisie_menu" />';
        html += '<input type="text" placeholder="Plat" name="saisie_plat[' + idRestaurant + ']" class="saisie_menu" />';
        html += '<input type="text" placeholder="Dessert" name="saisie_dessert[' + idRestaurant + ']" class="saisie_menu" />';
    html += '</div>';

    html += '<a id="' + idAnnuler + '" class="bouton_annuler_proposition annulerMenu">Annuler</a>';

    $('#' + zone).append(html);
}

// Cache la saisie du menu
function cacherSaisieMenu(zone, bouton, menu)
{
    $('#' + menu).remove();
    $('#' + bouton).remove();
    $('#' + zone).css('display', 'block');
}

// Affiche les détails d'une proposition
function afficherDetailsProposition(zone, id)
{
    // Modification des données
    var details = detailsPropositions[id];
    var avatarFormatted;

    /******************/
    /*** Restaurant ***/
    /******************/
    // Lien image
    $('#lien_details_proposition').attr('href', 'restaurants.php?action=goConsulter&anchor=' + details['id_restaurant']);

    // Image restaurant
    if (details['picture'] != '')
    {
        $('#image_details_proposition').attr('src', '../../includes/images/foodadvisor/' + details['picture']);
        $('#image_details_proposition').addClass('image_rounded');
    }
    else
    {
        $('#image_details_proposition').attr('src', '../../includes/icons/foodadvisor/restaurants.png');
        $('#image_details_proposition').removeClass('image_rounded');
    }

    // Nom du restaurant
    $('#nom_details_proposition').html(details['name']);

    // Détermination du jour de la semaine
    var jour  = $_GET('date').substr(6, 2);
    var mois  = $_GET('date').substr(4, 2) - 1;
    var annee = $_GET('date').substr(0, 4);

    var dateJour = new Date(annee, mois, jour);

    // Jours d'ouverture (en fonction de la date sélectionnée)
    var opened       = details['opened'].split(';');
    var availableDay = true;

    $.each(opened, function (key, value)
    {
        if (value != '')
        {
            if (value == 'Y')
                $('#jour_details_proposition_' + key).addClass('jour_oui_fa');
            else
                $('#jour_details_proposition_' + key).addClass('jour_non_fa');

            if (dateJour.getDay() == key + 1 && value == 'N')
                availableDay = false;
        }
    });

    // Prix
    if (details['min_price'] != '' && details['max_price'] != '')
    {
        var price;

        if (details['min_price'] == details['max_price'])
            price = 'Prix ~ ' + formatAmountForDisplay(details['min_price'], true);
        else
            price = 'Prix ' + formatAmountForDisplay(details['min_price'], false) + ' - ' + formatAmountForDisplay(details['max_price'], true);

        $('.zone_price_details').css('display', 'block');
        $('#prix_details_proposition').html(price);
    }
    else
    {
        $('.zone_price_details').css('display', 'none');
        $('#prix_details_proposition').html();
    }

    // Lieu
    $('#lieu_details_proposition').html(details['location']);

    // Nombre de participants
    var nbParticipants;

    if (details['nb_participants'] == 1)
        nbParticipants = details['nb_participants'] + ' participant';
    else
        nbParticipants = details['nb_participants'] + ' participants';

    $('#participants_details_proposition').html(nbParticipants);

    // Type de restaurant
    $('#types_details_proposition').empty();

    if (details['types'] != '')
    {
        $('#types_details_proposition').css('display', 'block');

        var types = details['types'].split(';');

        $.each(types, function ()
        {
            if (this != '')
                $('#types_details_proposition').append('<span class="horaire_proposition">' + this + '</span>');
        });
    }
    else
    {
        $('#types_details_proposition').css('display', 'none');
        $('#types_details_proposition').html();
    }

    // Appelant
    if (details['caller'] != '' || details['phone'] != '' || details['determined'] == 'Y')
    {
        if (details['caller'] != '' || details['phone'] != '')
        {
            $('.zone_caller_details').css('display', 'block');

            if (details['phone'] != '')
                $('#telephone_details_proposition').html(details['phone']);
            else
                $('#telephone_details_proposition').empty();

            if (details['determined'] == 'Y' && details['caller'] != '')
            {
                $('#caller_details_propositions').parent().css('display', 'inline-block');

                if (details['avatar'] != '')
                {
                    $('#caller_details_propositions').attr('src', '../../includes/images/profil/avatars/' + details['avatar']);
                    $('#caller_details_propositions').attr('title', details['pseudo']);
                }
                else
                {
                    $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
                    $('#caller_details_propositions').attr('title', details['pseudo']);
                }
            }
            else
            {
                $('#caller_details_propositions').parent().css('display', 'none');
                $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
                $('#caller_details_propositions').attr('title', 'avatar');
            }
        }
        else
            $('.zone_caller_details').css('display', 'none');
    }
    else
    {
        $('.zone_caller_details').css('display', 'none');
        $('#caller_details_propositions').parent().css('display', 'none');
        $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
        $('#caller_details_propositions').attr('title', 'avatar');
    }

    // Liens
    if (details['website'] != '' || details['plan'] != '' || details['lafourchette'] != '')
    {
        $('.zone_liens_details').css('display', 'block');

        if (details['website'] == '')
        {
            $('#website_details_proposition').css('display', 'none');
            $('#website_details_proposition').attr('href', '');
        }
        else
        {
            $('#website_details_proposition').css('display', 'inline-block');
            $('#website_details_proposition').attr('href', details['website']);
        }

        if (details['plan'] == '')
        {
            $('#plan_details_proposition').css('display', 'none');
            $('#plan_details_proposition').attr('href', '');
        }
        else
        {
            $('#plan_details_proposition').css('display', 'inline-block');
            $('#plan_details_proposition').attr('href', details['plan']);
        }

        if (details['lafourchette'] == '')
        {
            $('#lafourchette_details_proposition').css('display', 'none');
            $('#lafourchette_details_proposition').attr('href', '');
        }
        else
        {
            $('#lafourchette_details_proposition').css('display', 'inline-block');
            $('#lafourchette_details_proposition').attr('href', details['lafourchette']);
        }
    }
    else
    {
        $('.zone_liens_details').css('display', 'none');
        $('#website_details_proposition').attr('href', '');
        $('#plan_details_proposition').attr('href', '');
        $('#lafourchette_details_proposition').attr('href', '');
    }

    // Vérification si l'utilisateur participe
    var participe = false;

    $.each(details['details'], function ()
    {
        if (userSession == this['identifiant'])
        {
            participe = true;
            return false;
        }
    });

    // Bouton réservation (si on a participé)
    if (participe == true && details['reserved'] != 'Y')
    {
        $('#reserver_details_proposition').css('display', 'block');
        $('#reserver_details_proposition').attr('action', 'foodadvisor.php?action=doReserver');
    }
    else
    {
        $('#reserver_details_proposition').css('display', 'none');
        $('#reserver_details_proposition').attr('action', '');
    }

    // Bouton complet (si appelant sur choix déterminé)
    if (participe == true && details['reserved'] != 'Y' && details['determined'] == 'Y' && userSession == details['caller'])
    {
        $('#choice_complete_details_proposition').css('display', 'block');
        $('#choice_complete_details_proposition').attr('action', 'foodadvisor.php?action=doComplet');
    }
    else
    {
        $('#choice_complete_details_proposition').css('display', 'none');
        $('#choice_complete_details_proposition').attr('action', '');
    }

    // Indicateur réservation
    if (details['reserved'] == 'Y')
        $('#reserved_details_proposition').css('display', 'block');
    else
        $('#reserved_details_proposition').css('display', 'none');

    // Vérification si l'utilisateur a réservé
    var reserved = false;

    if (details['reserved'] == 'Y' && userSession == details['caller'])
        reserved = true;

    // Bouton annulation réservation (si on a participé)
    if (reserved == true)
    {
        $('#annuler_details_proposition').css('display', 'block');
        $('#annuler_details_proposition').attr('action', 'foodadvisor.php?action=doAnnulerReserver');
    }
    else
    {
        $('#annuler_details_proposition').css('display', 'none');
        $('#annuler_details_proposition').attr('action', '');
    }

    // Id restaurant des boutons
    if (participe == true && details['reserved'] != 'Y')
        $('#reserver_details_proposition > input[name=id_restaurant]').val(id);
    else
        $('#reserver_details_proposition > input[name=id_restaurant]').val('');

    if (participe == true && details['reserved'] != 'Y' && details['determined'] == 'Y' && userSession == details['caller'])
        $('#choice_complete_details_proposition > input[name=id_restaurant]').val(id);
    else
        $('#choice_complete_details_proposition > input[name=id_restaurant]').val('');

    if (reserved == true)
        $('#annuler_details_proposition > input[name=id_restaurant]').val(id);
    else
        $('#annuler_details_proposition > input[name=id_restaurant]').val('');

    // On cache la zone si tout est vide
    if ((!$('#reserver_details_proposition').length        || $('#reserver_details_proposition').css('display')        == 'none')
    &&  (!$('#choice_complete_details_proposition').length || $('#choice_complete_details_proposition').css('display') == 'none')
    &&  (!$('#annuler_details_proposition').length         || $('#annuler_details_proposition').css('display')         == 'none')
    &&  (!$('#reserved_details_proposition').length        || $('#reserved_details_proposition').css('display')        == 'none'))
        $('#indicateurs_details_proposition').css('display', 'none');
    else
        $('#indicateurs_details_proposition').css('display', 'block');

    /********************/
    /*** Participants ***/
    /********************/
    $('#top_details_proposition').empty();
    var ligne;
    var transports;

    // Bouton choix rapide
    if (participe == true || availableDay == false)
    {
        $('#choix_rapide_details_proposition').css('display', 'none');
        $('#choix_rapide_details_proposition').attr('action', '');
        $('#choix_rapide_details_proposition > input[name=id_restaurant]').val('');
    }
    else
    {
        $('#choix_rapide_details_proposition').css('display', 'inline-block');
        $('#choix_rapide_details_proposition').attr('action', 'foodadvisor.php?action=doChoixRapide');
        $('#choix_rapide_details_proposition > input[name=id_restaurant]').val(id);
    }

    // Participants
    $.each(details['details'], function ()
    {
        ligne      = '';
        transports = '';

        ligne += '<div class="zone_details_user_top">';
            // Avatar
            avatarFormatted = formatAvatar(this['avatar'], this['pseudo'], 2, 'avatar');

            ligne += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_details" />';

            // Pseudo
            ligne += '<div class="pseudo_details">' + this['pseudo'] + '</div>';

            // Transports
            ligne += '<div class="zone_details_transports">';
                if (this['transports'] != '')
                {
                    transports = this['transports'].split(';');

                    $.each(transports, function (key, value)
                    {
                        switch (value)
                        {
                            case 'F':
                                ligne += '<img src="../../includes/icons/foodadvisor/feet.png" alt="feet" class="icone_details" />';
                                break;

                            case 'B':
                                ligne += '<img src="../../includes/icons/foodadvisor/bike.png" alt="bike" class="icone_details" />';
                                break;

                            case 'T':
                                ligne += '<img src="../../includes/icons/foodadvisor/tram.png" alt="tram" class="icone_details" />';
                                break;

                            case 'C':
                                ligne += '<img src="../../includes/icons/foodadvisor/car.png" alt="car" class="icone_details" />';
                                break;

                            default:
                                break;
                        }
                    });
                }
            ligne += '</div>';

            // Horaires
            if (this['horaire'] != '')
                ligne += '<div class="horaire_details">' + formatTimeForDisplayLight(this['horaire']) + '</div>';

        ligne += '</div>';

        $('#top_details_proposition').append(ligne);
    });

    /*************/
    /*** Menus ***/
    /*************/
    $('.zone_details_menus').empty();

    var menuPresent = false;
    var colonne;
    var menu;

    // Menus
    $.each(details['details'], function ()
    {
        colonne = '';

        if (this['menu'] != ';;;')
        {
            menuPresent = true;
            menu        = this['menu'].split(';');

            colonne += '<div class="zone_details_user_menu">';
                // Avatar
                avatarFormatted = formatAvatar(this['avatar'], this['pseudo'], 2, 'avatar');

                colonne += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_menus" />';

                // Entrée
                if (menu[0] != '')
                {
                    colonne += '<div class="zone_menu_mes_choix">';
                        colonne += '<span class="titre_texte_mon_choix">Entrée</span>';
                        colonne += '<div class="texte_mon_choix">' + menu[0] + '</div>';
                    colonne += '</div>';
                }

                // Plat
                if (menu[1] != '')
                {
                    colonne += '<div class="zone_menu_mes_choix">';
                        colonne += '<span class="titre_texte_mon_choix">Plat</span>';
                        colonne += '<div class="texte_mon_choix">' + menu[1] + '</div>';
                    colonne += '</div>';
                }

                // Dessert
                if (menu[2] != '')
                {
                    colonne += '<div class="zone_menu_mes_choix">';
                        colonne += '<span class="titre_texte_mon_choix">Dessert</span>';
                        colonne += '<div class="texte_mon_choix">' + menu[2] + '</div>';
                    colonne += '</div>';
                }
            colonne += '</div>';

            $('.zone_details_menus').append(colonne);
        }
    });

    // Pas de menus
    if (menuPresent == false)
        $('.zone_details_menus').html('<div class="empty">Pas de menus proposés pour ce choix...</div>');

    /*******************/
    /*** Description ***/
    /*******************/
    $('.zone_details_description').empty();

    if (details['description'] != '')
    {
        var longueurMax = 300;
        var description = '';

        description += '<div class="details_description">';
            if (details['description'].length > longueurMax)
            {
                description += '<div id="details_description_long" style="display: none;">' + nl2br(details['description']) + '</div>';
                description += '<div id="details_description_short">' + nl2br(details['description'].substr(0, 300)) + '...</div>';
                description += '<a id="descriptionDetails"><img src="../../includes/icons/foodadvisor/expand.png" alt="expand" class="expand_details_description" /></a>';
            }
            else
                description += nl2br(details['description']);
        description += '</div>';

        $('.zone_details_description').append(description);

        $('.zone_details_description_bottom').css('display', 'inline-block');
        $('.zone_details_menus_bottom').css('width', 'calc(68% - 10px)');
    }
    else
    {
        $('.zone_details_description_bottom').css('display', 'none');
        $('.zone_details_menus_bottom').css('width', '100%');
        $('.zone_details_description').empty();
    }

    // Affichage de la zone
    afficherMasquerIdWithDelay(zone);
}

// Affiche la listbox des lieux (résumé)
function afficherListboxLieuxResume(date)
{
    var idZone    = 'zone_listbox_resume_' + date;
    var idSelect  = 'select_lieu_resume_' + date;
    var idAnnuler = 'annuler_restaurant_resume_' + date;
    var idReplace = 'no_proposal_' + date;
    var idBouton  = 'choix_resume_' + date;
    var html      = '';

    var previousHeight = $('#' + idReplace).outerHeight() + $('#' + idBouton).height() + 20;

    html += '<div id="' + idZone + '" class="zone_listbox_restaurant_resume">';
        html += '<select id="' + idSelect + '" name="select_lieu_resume_' + date + '" class="listbox_choix_resume afficherRestaurantResume" required>';
            html += '<option value="" hidden>Choisissez...</option>';

            if (listeLieuxResume.length > 0)
            {
                $.each(listeLieuxResume, function (key, value)
                {
                    html += '<option value="' + value + '">' + value + '</option>';
                });
            }
            else
                html += '<option value="" disabled>Aucun choix disponible</option>';
        html += '</select>';

        html += '<a id="' + idAnnuler + '" class="bouton_annuler_resume annulerLieuResume">Annuler</a>';
    html += '</div>';

    $('#' + idReplace).html(html);

    // Calcul marges en fonction des éléments
    var actionsHeight = $('#' + idZone).height();
    var newPadding    = (previousHeight - actionsHeight) / 2;

    $('#' + idReplace).css('padding-top', newPadding);
    $('#' + idReplace).css('padding-bottom', newPadding);
}

// Affiche la listbox des restaurants associés (résumé)
function afficherListboxRestaurantsResume(date, idSelect, zone)
{
    var lieu      = escapeHtml($('#' + idSelect).val());
    var idSelect2 = 'select_restaurant_resume_' + date;
    var idReplace = 'no_proposal_' + date;
    var idValider = 'valider_restaurant_resume_' + date;
    var idAnnuler = 'annuler_restaurant_resume_' + date;
    var html      = '';

    var previousHeight = $('#' + idReplace).outerHeight();

    if ($('#' + idValider).length)
        $('#' + idValider).remove();

    if ($('#' + idAnnuler).length)
        $('#' + idAnnuler).remove();

    html += '<form id="' + idValider + '" method="post" action="foodadvisor.php?action=doAjouterResume">';
        html += '<select id="' + idSelect2 + '" name="' + idSelect2 + '" class="listbox_choix_resume" required>';
            html += '<option value="" hidden>Choisissez...</option>';

            $.each(listeRestaurantsResume[lieu], function (key, value)
            {
                html += '<option value="' + value.id + '">' + value.name + '</option>';
            });
        html += '</select>';

        html += '<input type="hidden" name="date_resume" value="' + date + '" />';
        html += '<input type="hidden" name="date" value="' + $_GET('date') + '" />';
        html += '<input type="submit" name="submit_resume" value="Valider" class="bouton_valider_resume" />';
    html += '</form>';

    html += '<a id="' + idAnnuler + '" class="bouton_annuler_resume annulerLieuResume">Annuler</a>';

    $('#' + zone).append(html);

    // Calcul marges en fonction des éléments
    var actionsHeight = $('#' + zone).height();
    var newPadding    = (previousHeight - actionsHeight) / 2;

    $('#' + idReplace).css('padding-top', newPadding);
    $('#' + idReplace).css('padding-bottom', newPadding);
}

// Cache les lisbox des restaurants (résumé)
function cacherListboxRestaurantsResume(date, zone, boutonValider, boutonAnnuler)
{
    var previousHeight = $('#no_proposal_' + date).outerHeight();

    $('#' + zone).remove();
    $('#' + boutonValider).remove();
    $('#' + boutonAnnuler).remove();
    $('#no_proposal_' + date).html('Pas de proposition pour ce jour');

    $('#choix_resume_' + date).css('display', 'block');

    // Calcul marges en fonction des éléments
    var textHeight = $('#no_proposal_' + date).height() + $('#choix_resume_' + date).height() + 20;
    var newPadding = (previousHeight - textHeight) / 2;

    $('#no_proposal_' + date).css('padding-top', newPadding);
    $('#no_proposal_' + date).css('padding-bottom', newPadding);
}

// Change la couleur des types de restaurants (saisie et modification restaurant)
function changeCheckedColorType(input)
{
    if ($('#' + input).children('input').prop('checked'))
        $('#' + input).removeClass('bouton_checked');
    else
        $('#' + input).addClass('bouton_checked');
}

// Change la couleur de fond lors de la saisie de texte
function changeTypeColor(id)
{
    if ($('#' + id).val() != '')
    {
        $('#' + id).css('background-color', '#70d55d');
        $('#' + id).css('color', 'white');
    }
    else
    {
        $('#' + id).css('background-color', '#e3e3e3');
        $('#' + id).css('color', '#262626');
    }
}

// Génère une nouvelle zone pour saisir un type
function addOtherType(idBouton, idParent)
{
    var html      = '';
    var length    = $('#' + idParent + ' input').length;
    var newLength = length + 1;
    var idType    = idParent + '_' + newLength;

    html += '<input type="text" placeholder="Type" value="" id="' + idType + '" name="' + idParent + '[' + newLength + ']" class="type_other saisieType" />';

    $('#' + idBouton).before(html);
}

// Affiche ou masque la zone de saisie lieu "Autre" (insertion)
function afficherOther(select, id, name)
{
    if ($('#' + select).val() == 'other_location')
    {
        if ($('#' + id).css('display') == 'none')
        {
            $('#' + select).css('width', '20%');
            $('#' + name).css('width', 'calc(60% - 270px)');
            $('#' + id).css('width', '20%');
            $('#' + id).css('display', 'inline-block');
            $('#' + id).prop('required', true);
        }
    }
    else
    {
        $('#' + select).css('width', '20%');
        $('#' + name).css('width', 'calc(80% - 240px)');
        $('#' + id).css('display', 'none');
        $('#' + id).prop('required', false);
    }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
    if ($('#' + select).val() == 'other_location')
    {
        if ($('#' + id).css('display') == 'none')
        {
            $('#' + id).css('display', 'block');
            $('#' + id).prop('required', true);
        }
    }
    else
    {
        $('#' + id).css('display', 'none');
        $('#' + id).prop('required', false);
    }
}

// Fixe la couleur de fond lors du changement de statut
function changeCheckedDay(idCheckbox, idLabel, classChecked, classNoCheck)
{
    if ($('#' + idCheckbox).prop('checked') == true)
    {
        $('#' + idLabel).removeClass(classChecked);
        $('#' + idLabel).addClass(classNoCheck);
    }
    else
    {
        $('#' + idLabel).addClass(classChecked);
        $('#' + idLabel).removeClass(classNoCheck);
    }
}