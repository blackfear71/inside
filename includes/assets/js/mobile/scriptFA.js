/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function () {
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie de propositions
    $('#afficherSaisiePropositions, #fermerSaisiePropositions').click(function ()
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

    // Change la couleur d'une case à cocher à la sélection
    $('#zone_saisie_propositions').find('label').click(function ()
    {
        changeCheckedColor($(this));
    });

    // Affiche la zone de détails d'une proposition
    $('.afficherDetailsProposition').click(function ()
    {
        var idProposition = $(this).attr('id').replace('details_proposition_', '');

        afficherDetailsProposition(idProposition);
    });

    // Ferme la zone de détails d'une proposition
    $('#fermerDetailsProposition').click(function ()
    {
        afficherMasquerIdWithDelay('zone_details_proposition');
    });

    // Ouvre la zone de saisie de résumé
    $('.afficherSaisieResume').click(function ()
    {
        var date = $(this).attr('id').replace('jour_saisie_resume_', '');

        initialisationResume('zone_saisie_resume', date);
    });

    // Ferme la zone de saisie de résumé
    $('#fermerSaisieResume').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_resume');
    });

    // Change la couleur d'un radio bouton à la sélection
    $('#zone_saisie_resume').find('label').click(function ()
    {
        changeRadioColor('zone_saisie_resume', $(this));
    });

    // Ouvre la zone de saisie d'un restaurant
    $('#afficherSaisieRestaurant').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_restaurant');
    });

    // Ferme la zone de saisie d'un restaurant
    $('#fermerSaisieRestaurant').click(function ()
    {
        // Réinitialisation de la saisie
        reinitialisationSaisieRestaurant();

        // Fermeture de l'affichage
        afficherMasquerIdWithDelay('zone_saisie_restaurant');
    });

    // Scroll vers un lieu
    $('.lienLieu').click(function ()
    {
        // Déclenchement du scroll
        var idLieu = $(this).attr('id').replace('link_lieu_', '');
        var offset = 0.1;
        var shadow = false;

        // Scroll vers l'id
        scrollToId(idLieu, offset, shadow);
    });

    // Affiche la zone de détails d'un restaurant
    $('.afficherDetailsRestaurant').click(function (event)
    {
        if ($(event.target).attr('class') != 'form_saisie_rapide' && $(event.target).attr('class') != 'bouton_saisie_rapide')
        {
            var idProposition = $(this).attr('id');

            afficherDetailsRestaurant(idProposition);
        }
    });

    // Ferme la zone de détails d'un restaurant
    $('#fermerDetailsRestaurant').click(function ()
    {
        afficherMasquerIdWithDelay('zone_details_restaurant');
    });

    // Ouvre la fenêtre de saisie d'un restaurant en modification
    $('.modifierRestaurant').click(function ()
    {
        var idRestaurant = $(this).attr('id').replace('modifier_restaurant_', '');

        initialisationModificationRestaurant(idRestaurant);
    });

    // Réinitialise la saisie à la fermeture au clic sur le fond
    $(document).on('click', function (event)
    {
        // Réinitialise la saisie d'un restaurant
        if ($(event.target).attr('id') == 'zone_saisie_restaurant')
            reinitialisationSaisieRestaurant();
    });

    // Change le statut d'un jour d'ouverture
    $('.checkDay').click(function ()
    {
        var idJour = $(this).attr('id').split('_');
        var day    = idJour[idJour.length - 1];

        changeCheckedDay('saisie_checkbox_ouverture_' + day, 'saisie_label_ouverture_' + day, 'label_jour_checked', 'label_jour');
    });

    // Ajoute un champ de saisie libre type de restaurant (saisie)
    $('.addType').click(function ()
    {
        var idParent = $(this).parent().attr('id');

        addOtherType(idParent);
    });

    // Change la couleur des checkbox types de restaurant (saisie & modification)
    $('.checkType').click(function ()
    {
        var idType = $(this).closest('div').attr('id');

        changeCheckedColorTypes(idType);
    });

    // Bloque la saisie en cas de soumission (propositions)
    $('#validerSaisiePropositions').click(function ()
    {
        var idForm          = $('#zone_saisie_propositions');
        var zoneForm        = 'zone_contenu_saisie_live';
        var zoneContenuForm = 'contenu_saisie';
        var rechercheLive   = true;

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm, rechercheLive);
    });

    // Bloque la saisie en cas de soumission (résumé)
    $('#validerSaisieResume').click(function ()
    {
        var idForm          = $('#zone_saisie_resume');
        var zoneForm        = 'zone_contenu_saisie_live';
        var zoneContenuForm = 'contenu_saisie';
        var rechercheLive   = true;

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm, rechercheLive);
    });

    // Bloque la saisie en cas de soumission (restaurant)
    $('#validerSaisieRestaurant').click(function ()
    {
        var idForm          = $('#zone_saisie_restaurant');
        var zoneForm        = 'zone_contenu_saisie';
        var zoneContenuForm = 'contenu_saisie';

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
    });

    /*** Actions au changement ***/
    // Charge l'image dans la zone de saisie
    $('.loadSaisieRestaurant').on('change', function (event)
    {
        loadFile(event, 'image_restaurant_saisie', true);
    });

    // Affiche la saisie "Autre" (lieu)
    $('#saisie_location').on('change', function ()
    {
        afficherOther('saisie_location', 'saisie_other_location');
    });

    // Change la couleur du type à la saisie
    $(document).on('input', '.saisieType', function ()
    {
        var idType = $(this).attr('id');

        changeTypeColor(idType);
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
    $('#' + idForm).find('.zone_recherche_conteneur > .titre_section').each(function ()
    {
        var idZone = $(this).attr('id').replace('titre_', 'afficher_');

        openSection($(this), idZone, 'open');
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
function changeCheckedColor(label)
{
    if (label.find('input').prop('checked'))
    {
        label.find('.image_normal').css('background-color', '#70d55d');
        label.find('.proposition_normal').css('background-color', '#96e687');
        label.find('.zone_checkbox_proposition').css('background-color', '#70d55d');
    }
    else
    {
        label.find('.image_normal').css('background-color', '#d3d3d3');
        label.find('.proposition_normal').css('background-color', '#e3e3e3');
        label.find('.zone_checkbox_proposition').css('background-color', '#d3d3d3');
    }
}

// Change la couleur d'une proposition (radio boutons)
function changeRadioColor(idForm, label)
{
    // On supprime le style de tous les boutons
    $('#' + idForm).find('label').each(function ()
    {
        $(this).find('input[type=radio]').prop('checked', false);

        $(this).find('.image_normal').css('background-color', '#d3d3d3');
        $(this).find('.proposition_normal').css('background-color', '#e3e3e3');
        $(this).find('.zone_checkbox_proposition').css('background-color', '#d3d3d3');
    });

    // On applique le style sur le bouton concerné
    $('#' + idForm).find(label).find('input[type=radio]').prop('checked', true);

    $('#' + idForm).find(label).find('.image_normal').css('background-color', '#70d55d');
    $('#' + idForm).find(label).find('.proposition_normal').css('background-color', '#96e687');
    $('#' + idForm).find(label).find('.zone_checkbox_proposition').css('background-color', '#70d55d');
}

// Affiche la zone de détails d'une proposition
function afficherDetailsProposition(idProposition)
{
    /***********/
    /* Données */
    /***********/
    // Récupération des données
    var proposition = detailsPropositions[idProposition];
    var opened      = proposition['opened'].split(';');

    // Nom du restaurant
    $('.titre_details > .texte_titre_section').html(proposition['name']);

    // Lieu
    $('.lieu_details').html(proposition['location']);

    // Image restaurant
    if (proposition['picture'] != '')
    {
        $('.image_details').attr('src', '../../includes/images/foodadvisor/' + proposition['picture']);
        $('.image_details').attr('alt', proposition['picture']);
        $('.image_details').attr('title', proposition['name']);
        $('.image_details').addClass('image_rounded');
    }
    else
    {
        $('.image_details').attr('src', '../../includes/icons/foodadvisor/restaurants.png');
        $('.image_details').attr('alt', 'restaurants');
        $('.image_details').attr('title', proposition['name']);
        $('.image_details').removeClass('image_rounded');
    }

    // Indicateur réservation
    if (proposition['reserved'] == 'Y')
        $('#reserved_details').css('display', 'block');
    else
        $('#reserved_details').css('display', 'none');

    // Nombre de participants
    var nombreParticipants;

    if (proposition['nb_participants'] == 1)
        nombreParticipants = proposition['nb_participants'] + ' participant';
    else
        nombreParticipants = proposition['nb_participants'] + ' participants';

    $('.nombre_participants_details').html(nombreParticipants);

    // Détermination du jour de la semaine
    var jour  = $_GET('date').substr(6, 2);
    var mois  = $_GET('date').substr(4, 2) - 1;
    var annee = $_GET('date').substr(0, 4);

    var dateJour = new Date(annee, mois, jour);

    // Jours d'ouverture (en fonction de la date sélectionnée)
    var availableDay = true;

    $.each(opened, function (key, value)
    {
        if (value != '')
        {
            if (value == 'Y')
            {
                $('#jour_details_' + key).addClass('jour_oui_details');
                $('#jour_details_' + key).removeClass('jour_non_details');
            }
            else
            {
                $('#jour_details_' + key).addClass('jour_non_details');
                $('#jour_details_' + key).removeClass('jour_oui_details');
            }

            if (dateJour.getDay() == key + 1 && value == 'N')
                availableDay = false;
        }
    });

    // Prix
    if (proposition['min_price'] != '' && proposition['max_price'] != '')
    {
        var prix;

        if (proposition['min_price'] == proposition['max_price'])
            prix = 'Prix ~ ' + formatAmountForDisplay(proposition['min_price'], true);
        else
            prix = 'Prix ' + formatAmountForDisplay(proposition['min_price'], false) + ' - ' + formatAmountForDisplay(proposition['max_price'], true);

        $('.prix_details').css('display', 'block');
        $('.prix_details').html(prix);
    }
    else
    {
        $('.prix_details').css('display', 'none');
        $('.prix_details').html('');
    }

    // Types du restaurant
    $('.zone_types_details').empty();

    if (proposition['types'] != '')
    {
        var types = proposition['types'].split(';');

        $.each(types, function ()
        {
            if (this != '')
            {
                var type = '<div class="type_details">' + this + '</div>';
                $('.zone_types_details').append(type);
            }
        });
    }

    // Appelant & numéro de téléphone
    if (proposition['caller'] != '' || proposition['phone'] != '' || proposition['determined'] == 'Y')
    {
        if (proposition['caller'] != '' || proposition['phone'] != '')
        {
            var avatarAppelant = formatAvatar(proposition['avatar'], proposition['pseudo'], 2, 'avatar');

            $('.zone_appelant_details').css('display', 'block');

            if (proposition['phone'] != '')
                $('.telephone_details').html(proposition['phone']);
            else
                $('.telephone_details').empty();

            if (proposition['determined'] == 'Y' && proposition['caller'] != '')
            {
                $('.avatar_appelant_details').css('display', 'inline-block');
                $('.avatar_appelant_details').attr('src', avatarAppelant.path);
                $('.avatar_appelant_details').attr('alt', avatarAppelant.alt);
                $('.avatar_appelant_details').attr('title', avatarAppelant.title);
            }
            else
            {
                $('.avatar_appelant_details').css('display', 'none');
                $('.avatar_appelant_details').attr('src', '../../includes/icons/common/default.png');
                $('.avatar_appelant_details').attr('alt', 'avatar');
                $('.avatar_appelant_details').attr('title', 'avatar');
            }
        }
        else
            $('.zone_appelant_details').css('display', 'none');
    }
    else
    {
        $('.zone_appelant_details').css('display', 'none');
        $('.avatar_appelant_details').css('display', 'none');
        $('.avatar_appelant_details').attr('src', '../../includes/icons/common/default.png');
        $('.avatar_appelant_details').attr('title', 'avatar');
    }

    // Liens
    if (proposition['website'] != '' || proposition['plan'] != '' || proposition['lafourchette'] != '')
    {
        $('.zone_liens_details').css('display', 'block');

        if (proposition['website'] == '')
        {
            $('#website_details').css('display', 'none');
            $('#website_details').attr('href', '');
        }
        else
        {
            $('#website_details').css('display', 'inline-block');
            $('#website_details').attr('href', proposition['website']);
        }

        if (proposition['plan'] == '')
        {
            $('#plan_details').css('display', 'none');
            $('#plan_details').attr('href', '');
        }
        else
        {
            $('#plan_details').css('display', 'inline-block');
            $('#plan_details').attr('href', proposition['plan']);
        }

        if (proposition['lafourchette'] == '')
        {
            $('#lafourchette_details').css('display', 'none');
            $('#lafourchette_details').attr('href', '');
        }
        else
        {
            $('#lafourchette_details').css('display', 'inline-block');
            $('#lafourchette_details').attr('href', proposition['lafourchette']);
        }
    }
    else
    {
        $('.zone_liens_details').css('display', 'none');
        $('#website_details').attr('href', '');
        $('#plan_details').attr('href', '');
        $('#lafourchette_details').attr('href', '');
    }

    // Participants
    $('.zone_details_participants').empty();

    $.each(proposition['details'], function ()
    {
        var ligne = '';

        ligne += '<div class="zone_user_details">';
            // Avatar
            var avatarFormatted = formatAvatar(this['avatar'], this['pseudo'], 2, 'avatar');

            ligne += '<div class="zone_avatar_user_details">';
                ligne += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_user_details" />';
            ligne += '</div>';

            // Pseudo
            ligne += '<div class="pseudo_user_details">' + formatString(this['pseudo'], 30) + '</div>';
        ligne += '</div>';

        $('.zone_details_participants').append(ligne);
    });

    // Description
    $('.zone_details_texte').empty();

    if (proposition['description'] != '')
    {
        var description = '';

        description += '<div class="description_details">';
            description += nl2br(proposition['description']);
        description += '</div>';

        $('.zone_details_description').css('display', 'block');
        $('.zone_details_texte').append(description);
    }
    else
    {
        $('.zone_details_description').css('display', 'none');
        $('.zone_details_texte').empty();
    }

    /***********/
    /* Actions */
    /***********/
    // Vérification si l'utilisateur participe
    var participe = false;

    $.each(proposition['details'], function ()
    {
        if (userSession == this['identifiant'])
        {
            participe = true;
            return false;
        }
    });

    // Vérification si l'utilisateur a réservé
    var reserved = false;

    if (proposition['reserved'] == 'Y' && userSession == proposition['caller'])
        reserved = true;

    // Bouton réservation (si on a participé)
    if (participe == true && proposition['reserved'] != 'Y')
    {
        $('#reserver_details').css('display', 'block');
        $('#reserver_details').attr('action', 'foodadvisor.php?action=doReserver');
    }
    else
    {
        $('#reserver_details').css('display', 'none');
        $('#reserver_details').attr('action', '');
    }

    // Bouton complet (si appelant sur choix déterminé)
    if (participe == true && proposition['reserved'] != 'Y' && proposition['determined'] == 'Y' && userSession == proposition['caller'])
    {
        $('#choice_complete_details').css('display', 'block');
        $('#choice_complete_details').attr('action', 'foodadvisor.php?action=doComplet');
    }
    else
    {
        $('#choice_complete_details').css('display', 'none');
        $('#choice_complete_details').attr('action', '');
    }

    // Bouton annulation réservation (si on a participé)
    if (reserved == true)
    {
        $('#annuler_details').css('display', 'block');
        $('#annuler_details').attr('action', 'foodadvisor.php?action=doAnnulerReserver');
    }
    else
    {
        $('#annuler_details').css('display', 'none');
        $('#annuler_details').attr('action', '');
    }

    // Id restaurant des boutons
    if (participe == true && proposition['reserved'] != 'Y')
        $('#reserver_details > input[name=id_restaurant]').val(idProposition);
    else
        $('#reserver_details > input[name=id_restaurant]').val('');

    if (participe == true && proposition['reserved'] != 'Y' && proposition['determined'] == 'Y' && userSession == proposition['caller'])
        $('#choice_complete_details > input[name=id_restaurant]').val(idProposition);
    else
        $('#choice_complete_details > input[name=id_restaurant]').val('');

    if (reserved == true)
        $('#annuler_details > input[name=id_restaurant]').val(idProposition);
    else
        $('#annuler_details > input[name=id_restaurant]').val('');

    // On cache la zone si tout est vide
    if ((!$('#reserver_details').length        || $('#reserver_details').css('display')        == 'none')
    &&  (!$('#choice_complete_details').length || $('#choice_complete_details').css('display') == 'none')
    &&  (!$('#annuler_details').length         || $('#annuler_details').css('display')         == 'none')
    &&  (!$('#reserved_details').length        || $('#reserved_details').css('display')        == 'none'))
        $('#indicateurs_details').css('display', 'none');
    else
        $('#indicateurs_details').css('display', 'block');

    // Bouton choix rapide
    if (participe == true || availableDay == false)
    {
        $('#choix_rapide_details').css('display', 'none');
        $('#choix_rapide_details').attr('action', '');
        $('#choix_rapide_details > input[name=id_restaurant]').val('');
    }
    else
    {
        $('#choix_rapide_details').css('display', 'block');
        $('#choix_rapide_details').attr('action', 'foodadvisor.php?action=doChoixRapide');
        $('#choix_rapide_details > input[name=id_restaurant]').val(idProposition);
    }

    /*************/
    /* Affichage */
    /*************/
    // Affichage des détails
    afficherMasquerIdWithDelay('zone_details_proposition');

    // Déplie tous les titres
    $('.div_details').find('.titre_section').each(function ()
    {
        var idZone = $(this).attr('id').replace('titre_', 'afficher_');

        openSection($(this), idZone, 'open');
    });
}

// Initialise la saisie d'un résumé
function initialisationResume(idForm, date)
{
    // Initialisations
    var days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

    // Détermination du jour de la semaine
    var jour  = date.substr(6, 2);
    var mois  = date.substr(4, 2) - 1;
    var annee = date.substr(0, 4);

    var jourSemaine = new Date(annee, mois, jour).getDay();

    // Modification du formulaire
    $('#' + idForm).find('.zone_titre_saisie').html('Choix du ' + days[jourSemaine - 1]);
    $('#' + idForm).find('input[name=date_resume]').val(date);

    $('#' + idForm).find('.zone_checkbox_proposition > input[type=radio]').each(function ()
    {
        $(this).attr('name', 'select_restaurant_resume_' + date);
    });

    // Affichage de la zone de saisie
    afficherMasquerIdWithDelay(idForm);
}

// Affiche la zone de détails d'un restaurant
function afficherDetailsRestaurant(idRestaurant)
{
    /***********/
    /* Données */
    /***********/
    // Récupération des données
    var restaurant = listeRestaurantsJson[idRestaurant];
    var opened     = restaurant['opened'].split(';');

    // Nom du restaurant
    $('.titre_details > .texte_titre_section').html(restaurant['name']);

    // Lieu
    $('.lieu_details').html(restaurant['location']);

    // Image restaurant
    if (restaurant['picture'] != '')
    {
        $('.image_details').attr('src', '../../includes/images/foodadvisor/' + restaurant['picture']);
        $('.image_details').attr('alt', restaurant['picture']);
        $('.image_details').attr('title', restaurant['name']);
        $('.image_details').addClass('image_rounded');
    }
    else
    {
        $('.image_details').attr('src', '../../includes/icons/foodadvisor/restaurants.png');
        $('.image_details').attr('alt', 'restaurants');
        $('.image_details').attr('title', restaurant['name']);
        $('.image_details').removeClass('image_rounded');
    }

    // Jours d'ouverture (la saisie rapide sur les restaurants n'est disponible qu'à la date du jour)
    var dateDuJour   = new Date();
    var availableDay = true;

    $.each(opened, function (key, value)
    {
        if (value != '')
        {
            if (value == 'Y')
            {
                $('#jour_details_' + key).addClass('jour_oui_details');
                $('#jour_details_' + key).removeClass('jour_non_details');
            }
            else
            {
                $('#jour_details_' + key).addClass('jour_non_details');
                $('#jour_details_' + key).removeClass('jour_oui_details');
            }

            if (dateDuJour.getDay() == key + 1 && value == 'N')
                availableDay = false;
        }
    });

    // Prix
    if (restaurant['min_price'] != '' && restaurant['max_price'] != '')
    {
        var prix;

        if (restaurant['min_price'] == restaurant['max_price'])
            prix = 'Prix ~ ' + formatAmountForDisplay(restaurant['min_price'], true);
        else
            prix = 'Prix ' + formatAmountForDisplay(restaurant['min_price'], false) + ' - ' + formatAmountForDisplay(restaurant['max_price'], true);

        $('.prix_details').css('display', 'block');
        $('.prix_details').html(prix);
    }
    else
    {
        $('.prix_details').css('display', 'none');
        $('.prix_details').html('');
    }

    // Types du restaurant
    $('.zone_types_details').empty();

    if (restaurant['types'] != '')
    {
        var types = restaurant['types'].split(';');

        $.each(types, function ()
        {
            if (this != '')
            {
                var type = '<div class="type_details">' + this + '</div>';
                $('.zone_types_details').append(type);
            }
        });
    }

    // Numéro de téléphone
    if (restaurant['phone'] != '')
    {
        $('.zone_appelant_details').css('display', 'block');

        if (restaurant['phone'] != '')
            $('.telephone_details').html(restaurant['phone']);
        else
            $('.telephone_details').empty();
    }
    else
        $('.zone_appelant_details').css('display', 'none');

    // Liens
    if (restaurant['website'] != '' || restaurant['plan'] != '' || restaurant['lafourchette'] != '')
    {
        $('.zone_liens_details').css('display', 'block');

        if (restaurant['website'] == '')
        {
            $('#website_details').css('display', 'none');
            $('#website_details').attr('href', '');
        }
        else
        {
            $('#website_details').css('display', 'inline-block');
            $('#website_details').attr('href', restaurant['website']);
        }

        if (restaurant['plan'] == '')
        {
            $('#plan_details').css('display', 'none');
            $('#plan_details').attr('href', '');
        }
        else
        {
            $('#plan_details').css('display', 'inline-block');
            $('#plan_details').attr('href', restaurant['plan']);
        }

        if (restaurant['lafourchette'] == '')
        {
            $('#lafourchette_details').css('display', 'none');
            $('#lafourchette_details').attr('href', '');
        }
        else
        {
            $('#lafourchette_details').css('display', 'inline-block');
            $('#lafourchette_details').attr('href', restaurant['lafourchette']);
        }
    }
    else
    {
        $('.zone_liens_details').css('display', 'none');
        $('#website_details').attr('href', '');
        $('#plan_details').attr('href', '');
        $('#lafourchette_details').attr('href', '');
    }

    // Description
    $('.zone_details_texte').empty();

    if (restaurant['description'] != '')
    {
        var description = '';

        description += '<div class="description_details">';
        description += nl2br(restaurant['description']);
        description += '</div>';

        $('.zone_details_description').css('display', 'block');
        $('.zone_details_texte').append(description);
    }
    else
    {
        $('.zone_details_description').css('display', 'none');
        $('.zone_details_texte').empty();
    }

    /***********/
    /* Actions */
    /***********/
    // Vérification si l'utilisateur participe
    var participe = false;

    $.each(mesChoixJson, function ()
    {
        if (idRestaurant == this['id_restaurant'])
        {
            participe = true;
            return false;
        }
    });

    // Bouton choix rapide
    if (participe == true || availableDay == false)
    {
        $('#choix_rapide_details').css('display', 'none');
        $('#choix_rapide_details').attr('action', '');
        $('#choix_rapide_details > input[name=id_restaurant]').val('');
    }
    else
    {
        $('#choix_rapide_details').css('display', 'block');
        $('#choix_rapide_details').attr('action', 'restaurants.php?action=doChoixRapide');
        $('#choix_rapide_details > input[name=id_restaurant]').val(idRestaurant);
    }

    // Lien modification
    $('.zone_details_actions > .lien_modifier_restaurant').attr('id', 'modifier_restaurant_' + restaurant['id']);

    // Formulaire suppression
    $('.zone_details_actions > .form_supprimer_restaurant').attr('id', 'delete_restaurant_' + restaurant['id']);
    $('.form_supprimer_restaurant > input[name=id_restaurant]').val(restaurant['id']);

    /*************/
    /* Affichage */
    /*************/
    // Affichage des détails
    afficherMasquerIdWithDelay('zone_details_restaurant');

    // Déplie tous les titres
    $('.div_details').find('.titre_section').each(function ()
    {
        var idZone = $(this).attr('id').replace('titre_', 'afficher_');

        openSection($(this), idZone, 'open');
    });
}

// Affiche ou masque la zone de saisie "Autre"
function afficherOther(select, required)
{
    if ($('#' + select).val() == 'other_location')
    {
        $('#' + required).css('display', 'block');
        $('#' + required).prop('required', true);
    }
    else
    {
        $('#' + required).css('display', 'none');
        $('#' + required).prop('required', false);
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

// Génère une nouvelle zone pour saisir un type
function addOtherType(idParent)
{
    var html   = '';
    var length = $('#' + idParent + ' input').length;
    var idType = idParent + '_' + length;

    if (length % 2 == 0)
        html += '<input type="text" placeholder="Type" value="" id="' + idType + '" name="' + idParent + '[' + length + ']" class="type_other type_other_margin saisieType" />';
    else
        html += '<input type="text" placeholder="Type" value="" id="' + idType + '" name="' + idParent + '[' + length + ']" class="type_other saisieType" />';

    if ($('.addType').hasClass('switch_types_margin'))
        $('.addType').removeClass('switch_types_margin');
    else
        $('.addType').addClass('switch_types_margin');

    $('.addType').before(html);
}

// Change la couleur des checkbox (saisie restaurant)
function changeCheckedColorTypes(input)
{
    if ($('#' + input).find('input').prop('checked'))
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

// Affiche la zone de mise à jour d'un restaurant
function initialisationModificationRestaurant(idRestaurant)
{
    // Récupération des données
    var restaurant = listeRestaurantsJson[idRestaurant];
    var opened     = restaurant['opened'].split(';');
    var days       = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
    var image      = restaurant['picture'];

    // Titre
    var titre = 'Modifier le restaurant';
    $('#zone_saisie_restaurant').find('.zone_titre_saisie').html(titre);

    // Action du formulaire
    var action = 'restaurants.php?action=doModifierRestaurant';
    $('#zone_saisie_restaurant').find('.form_saisie').attr('action', action);

    // Identifiant restaurant
    $('#zone_saisie_restaurant').find('#id_saisie_restaurant').val(idRestaurant);

    // Image restaurant
    if (image != '')
        $('#zone_saisie_restaurant').find('#image_restaurant_saisie').attr('src', '../../includes/images/foodadvisor/' + image);

    $('#zone_saisie_restaurant').find('#saisie_image').attr('name', 'update_image_restaurant_' + idRestaurant);

    // Nom restaurant
    $('#zone_saisie_restaurant').find('#saisie_nom').val(decodeText(restaurant['name']));
    $('#zone_saisie_restaurant').find('#saisie_nom').attr('name', 'update_name_restaurant_' + idRestaurant);

    // Lieu restaurant
    $('#zone_saisie_restaurant').find('#saisie_location').val(decodeText(restaurant['location']));
    $('#zone_saisie_restaurant').find('#saisie_location').attr('name', 'update_location_' + idRestaurant);

    // Lieu "Autre"
    $('#zone_saisie_restaurant').find('#saisie_other_location').css('display', 'none');
    $('#zone_saisie_restaurant').find('#saisie_other_location').val('');
    $('#zone_saisie_restaurant').find('#saisie_other_location').attr('name', 'update_other_location_' + idRestaurant);

    // Jours d'ouverture
    $.each(opened, function (key, value)
    {
        if (value != '')
        {
            if (value == 'Y')
            {
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + days[key]).addClass('label_jour_checked');
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + days[key]).removeClass('label_jour');
                $('#zone_saisie_restaurant').find('#saisie_checkbox_ouverture_' + days[key]).prop('checked', true);
            }
            else
            {
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + days[key]).addClass('label_jour');
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + days[key]).removeClass('label_jour_checked');
                $('#zone_saisie_restaurant').find('#saisie_checkbox_ouverture_' + days[key]).prop('checked', false);
            }

            $('#zone_saisie_restaurant').find('#saisie_checkbox_ouverture_' + days[key]).attr('name', 'update_ouverture_restaurant_' + idRestaurant + '[' + key + ']');
        }
    });

    // Prix min
    $('#zone_saisie_restaurant').find('.saisie_prix_min_restaurant').val(decodeText(restaurant['min_price']));
    $('#zone_saisie_restaurant').find('.saisie_prix_min_restaurant').attr('name', 'update_prix_min_restaurant_' + idRestaurant);

    // Prix max
    $('#zone_saisie_restaurant').find('.saisie_prix_max_restaurant').val(decodeText(restaurant['max_price']));
    $('#zone_saisie_restaurant').find('.saisie_prix_max_restaurant').attr('name', 'update_prix_max_restaurant_' + idRestaurant);

    // Types
    var i = 0;

    $('#zone_saisie_restaurant').find('.switch_types').each(function ()
    {
        var input    = $(this).find('input');
        var inputId  = $(this).find('input').attr('id');
        var idType   = inputId + '_' + idRestaurant;
        var matching = false;

        $.each(restaurant['formatted_types'], function ()
        {
            if (this != '')
            {
                var typeRestaurant = 'type_' + this + '_' + idRestaurant;

                if (typeRestaurant == idType)
                {
                    matching = true;
                    return false;
                }
            }
        });

        if (matching == true)
        {
            input.prop('checked', true);
            $(this).addClass('bouton_checked');
        }
        else
        {
            input.prop('checked', false);
            $(this).removeClass('bouton_checked');
        }

        input.attr('name', 'update_types_restaurant_' + idRestaurant + '[' + i + ']');
        i++;
    });

    // Types libres
    $('#zone_saisie_restaurant').find('.zone_saisie_types').attr('id', 'update_types_restaurant_' + idRestaurant);
    $('#zone_saisie_restaurant').find('.type_other').remove();

    // Téléphone
    $('#zone_saisie_restaurant').find('#saisie_telephone').val(decodeText(restaurant['phone']));
    $('#zone_saisie_restaurant').find('#saisie_telephone').attr('name', 'update_phone_restaurant_' + idRestaurant);

    // Site web
    $('#zone_saisie_restaurant').find('#saisie_website').val(decodeText(restaurant['website']));
    $('#zone_saisie_restaurant').find('#saisie_website').attr('name', 'update_website_restaurant_' + idRestaurant);

    // Plan
    $('#zone_saisie_restaurant').find('#saisie_plan').val(decodeText(restaurant['plan']));
    $('#zone_saisie_restaurant').find('#saisie_plan').attr('name', 'update_plan_restaurant_' + idRestaurant);

    // LaFourchette
    $('#zone_saisie_restaurant').find('#saisie_lafourchette').val(decodeText(restaurant['lafourchette']));
    $('#zone_saisie_restaurant').find('#saisie_lafourchette').attr('name', 'update_lafourchette_restaurant_' + idRestaurant);

    // Description
    $('#zone_saisie_restaurant').find('#saisie_description').val(decodeText(restaurant['description']));
    $('#zone_saisie_restaurant').find('#saisie_description').attr('name', 'update_description_restaurant_' + idRestaurant);

    // Bouton validation
    $('#zone_saisie_restaurant').find('.bouton_saisie_gauche').attr('name', 'update_restaurant_' + idRestaurant);

    // Masque la zone de détails
    afficherMasquerIdWithDelay('zone_details_restaurant');

    // Affiche la zone de saisie
    afficherMasquerIdWithDelay('zone_saisie_restaurant');
}

// Réinitialise la zone de saisie d'un restaurant si fermeture modification
function reinitialisationSaisieRestaurant()
{
    // Déclenchement après la fermeture
    setTimeout(function ()
    {
        // Test si action = modification
        var currentAction = $('#zone_saisie_restaurant').find('.form_saisie').attr('action').split('action=');
        var call          = currentAction[currentAction.length - 1]

        if (call == 'doModifierRestaurant')
        {
            // Titre
            var titre = 'Saisir un restaurant';
            $('#zone_saisie_restaurant').find('.zone_titre_saisie').html(titre);

            // Action du formulaire
            var action = 'restaurants.php?action=doAjouterRestaurant';
            $('#zone_saisie_restaurant').find('.form_saisie').attr('action', action);

            // Identifiant restaurant
            $('#zone_saisie_restaurant').find('#id_saisie_restaurant').val('');

            // Image restaurant
            $('#zone_saisie_restaurant').find('#image_restaurant_saisie').attr('src', '');
            $('#zone_saisie_restaurant').find('#saisie_image').attr('name', 'image_restaurant');

            // Nom restaurant
            $('#zone_saisie_restaurant').find('#saisie_nom').val('');
            $('#zone_saisie_restaurant').find('#saisie_nom').attr('name', 'name_restaurant');

            // Lieu restaurant
            $('#zone_saisie_restaurant').find('#saisie_location').val('');
            $('#zone_saisie_restaurant').find('#saisie_location').attr('name', 'location');

            // Lieu "Autre"
            $('#zone_saisie_restaurant').find('#saisie_other_location').css('display', 'none');
            $('#zone_saisie_restaurant').find('#saisie_other_location').val('');
            $('#zone_saisie_restaurant').find('#saisie_other_location').attr('name', 'saisie_other_location');

            // Jours d'ouverture
            var days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

            $.each(days, function (key, value)
            {
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + value).addClass('label_jour_checked');
                $('#zone_saisie_restaurant').find('#saisie_label_ouverture_' + value).removeClass('label_jour');
                $('#zone_saisie_restaurant').find('#saisie_checkbox_ouverture_' + value).prop('checked', true);
                $('#zone_saisie_restaurant').find('#saisie_checkbox_ouverture_' + this).attr('name', 'ouverture_restaurant[' + key + ']');
            });

            // Prix min
            $('#zone_saisie_restaurant').find('.saisie_prix_min_restaurant').val('');
            $('#zone_saisie_restaurant').find('.saisie_prix_min_restaurant').attr('name', 'prix_min_restaurant');

            // Prix max
            $('#zone_saisie_restaurant').find('.saisie_prix_max_restaurant').val('');
            $('#zone_saisie_restaurant').find('.saisie_prix_max_restaurant').attr('name', 'prix_max_restaurant');

            // Types
            var i = 0;

            $('#zone_saisie_restaurant').find('.switch_types').each(function ()
            {
                $(this).find('input').prop('checked', false);
                $(this).removeClass('bouton_checked');
                $(this).find('input').attr('name', 'types_restaurant[' + i + ']');

                i++;
            });

            // Types libres
            $('#zone_saisie_restaurant').find('.zone_saisie_types').attr('id', 'types_restaurant');
            $('#zone_saisie_restaurant').find('.type_other').remove();

            // Téléphone
            $('#zone_saisie_restaurant').find('#saisie_telephone').val('');
            $('#zone_saisie_restaurant').find('#saisie_telephone').attr('name', 'phone_restaurant');

            // Site web
            $('#zone_saisie_restaurant').find('#saisie_website').val('');
            $('#zone_saisie_restaurant').find('#saisie_website').attr('name', 'website_restaurant');

            // Plan
            $('#zone_saisie_restaurant').find('#saisie_plan').val('');
            $('#zone_saisie_restaurant').find('#saisie_plan').attr('name', 'plan_restaurant');

            // LaFourchette
            $('#zone_saisie_restaurant').find('#saisie_lafourchette').val('');
            $('#zone_saisie_restaurant').find('#saisie_lafourchette').attr('name', 'lafourchette_restaurant');

            // Description
            $('#zone_saisie_restaurant').find('#saisie_description').val('');
            $('#zone_saisie_restaurant').find('#saisie_description').attr('name', 'description_restaurant');

            // Bouton validation
            $('#zone_saisie_restaurant').find('.bouton_saisie_gauche').attr('name', 'insert_restaurant');
        }
    }, 200);
}