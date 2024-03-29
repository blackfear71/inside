/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function () {
    /*** Actions au chargement ***/
    // Affichage de l'expérience
    if ($('.experience_profil').length)
    {
        var experience = $('#valeur_experience_profil').val();

        $('.experience_profil').each(function ()
        {
            afficherExperienceProfil($(this).attr('id'), experience);
        });
    }

    /*** Actions au clic ***/
    // Change la couleur des boutons préférences
    $('.label_switch').click(function ()
    {
        var idBouton = $(this).closest('div').attr('id');

        switch (idBouton)
        {
            // Notifications
            case 'bouton_me':
            case 'bouton_today':
            case 'bouton_week':
            case 'bouton_all_n':
                switchCheckedColor('switch_default_view_notifications', idBouton);
                break;

            // Films
            case 'bouton_accueil':
            case 'bouton_cards':
                switchCheckedColor('switch_default_view_movies', idBouton);
                break;

            case 'bouton_semaine':
            case 'bouton_waited':
            case 'bouton_way_out':
                changeCheckedColor(idBouton);
                break;

            // #TheBox
            case 'bouton_all':
            case 'bouton_inprogress':
            case 'bouton_mine':
            case 'bouton_done':
                switchCheckedColor('switch_default_view_ideas', idBouton);
                break;

            // INSIDE Room
            case 'bouton_chat_yes':
            case 'bouton_chat_no':
                switchCheckedColor('switch_default_view_chat', idBouton);
                break;

            // Celsius
            case 'bouton_celsius_yes':
            case 'bouton_celsius_no':
                switchCheckedColor('switch_default_view_celsius', idBouton);
                break;

            default:
                break;
        }
    });

    // Affiche les détails d'un succès
    $('.agrandirSucces').click(function ()
    {
        var idSuccess = $(this).attr('id').replace('agrandir_succes_', '');

        afficherDetailsSucces(idSuccess);
    });

    // Plie ou déplie les thèmes
    $('#fold_themes_user, #fold_themes_missions').click(function ()
    {
        var idZone = $(this).attr('id').replace('fold_', '');

        afficherMasquerSection($(this), idZone, '');

        if ($(this).attr('id') == 'fold_themes_user')
            initMasonryThemes('#themes_user');
        else
            initMasonryThemes('#themes_missions');
    });

    // Affiche un aperçu d'un thème
    $('.apercuTheme').click(function ()
    {
        var reference;
        var withLogo = $(this).attr('id').split('_')[0];
        var logo;

        if (withLogo == 'nologo')
            reference = $(this).attr('id').replace('nologo_', '');
        else
            reference = $(this).attr('id');

        var background = '/inside/includes/images/themes/backgrounds/' + reference + '.png';
        var header     = '/inside/includes/images/themes/headers/' + reference + '_h.png';
        var footer     = '/inside/includes/images/themes/footers/' + reference + '_f.png';

        if (withLogo != 'nologo')
            logo = '/inside/includes/images/themes/logos/' + reference + '_l.png';
        else
            logo = '/inside/includes/icons/common/inside.png';

        changeTheme(background, header, footer, logo);
    });

    // Bloque le bouton de soumission si besoin
    $('#bouton_saisie_avatar').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = null;

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au changement ***/
    // Charge l'avatar
    $('.loadAvatar').on('change', function (event)
    {
        loadFile(event, 'avatar', false);
    });

    // Affiche la saisie "Autre" (changement d'équipe)
    $('.select_form_saisie').on('change', function ()
    {
        afficherAutreEquipe('select_form_saisie', 'autre_equipe');
    });

    // Affiche un exemple de police de caractères
    $('#select_police').on('change', function ()
    {
        $('#exemple_police').css('font-family', $(this).val() + ', Times New Roman, Verdana, sans-serif');
    });

    /*** Calendriers ***/
    if ($('#datepicker_anniversary').length)
    {
        $('#datepicker_anniversary').datepicker(
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

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation mobile
    adaptProfil();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function ()
{
    // Adaptation mobile
    adaptProfil();

    // Masonry (Contributions)
    if ($('.zone_profil_contributions').length)
    {
        $('.zone_profil_contributions').masonry().masonry('destroy');

        $('.zone_profil_contributions').masonry(
        {
            // Options
            itemSelector: '.zone_contributions',
            columnWidth: 360,
            fitWidth: true,
            gutter: 40,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_profil_contributions').addClass('masonry');
    }

    // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
    $('.zone_succes_profil').css('display', 'block');

    // Masonry (Succès)
    if ($('.succes_liste').length)
    {
        $('.zone_niveau_succes').masonry().masonry('destroy');

        $('.zone_niveau_succes').masonry(
        {
            // Options
            itemSelector: '.succes_liste',
            columnWidth: 160,
            fitWidth: true,
            gutter: 30,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_niveau_succes').addClass('masonry');
    }

    // Masonry (Classement)
    if ($('.classement_liste').length)
    {
        $('.zone_niveau_succes').masonry().masonry('destroy');

        $('.zone_niveau_succes').masonry(
        {
            // Options
            itemSelector: '.classement_liste',
            columnWidth: 195,
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('.zone_niveau_succes').addClass('masonry');
    }

    // On n'affiche la zone des thèmes qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
    $('.zone_themes_user').css('display', 'block');

    // Masonry (Thèmes utilisateur)
    if ($('#themes_user').length)
    {
        $('#themes_user').masonry().masonry('destroy');

        $('#themes_user').masonry(
        {
            // Options
            itemSelector: '.zone_theme',
            columnWidth: 500,
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('#themes_user').addClass('masonry');
    }

    // Masonry (Thèmes missions)
    if ($('#themes_missions').length)
    {
        $('#themes_missions').masonry().masonry('destroy');

        $('#themes_missions').masonry(
        {
            // Options
            itemSelector: '.zone_theme',
            columnWidth: 500,
            fitWidth: true,
            gutter: 20,
            horizontalOrder: true
        });

        // On associe une classe pour y ajouter une transition dans le css
        $('#themes_missions').addClass('masonry');
    }
});

/*****************/
/*** Fonctions ***/
/*****************/
// Initialisation manuelle de "Masonry" (thèmes)
function initMasonryThemes(zone)
{
    $(zone).masonry(
    {
        // Options
        itemSelector: '.zone_theme',
        columnWidth: 500,
        fitWidth: true,
        gutter: 20,
        horizontalOrder: true,
        transitionDuration: 0
    });

    // Evite le clignotement des images à l'application de la masonry
    $(zone).css('opacity', '0');
    $(zone).css('transition', 'opacity 0s ease');
}

// Adaptations du profil sur mobile
function adaptProfil()
{
    if ($(window).width() < 1080)
    {
        $('.form_update_infos').css('display', 'block');
        $('.form_update_infos').css('width', '100%');
        $('.form_update_infos').css('margin-left', '0');
        $('.form_update_infos').css('margin-top', '20px');

        $('.form_update_avatar').css('border', '0');

        // Adaptation succès
        if ($('.zone_succes_profil').length)
        {
            $('.zone_profil_left').css('display', 'block');
            $('.zone_profil_left').css('width', '100%');

            $('.zone_profil_right').css('display', 'block');
            $('.zone_profil_right').css('width', '100%');
            $('.zone_profil_right').css('margin-left', '0');
        }
    }
    else
    {
        $('.form_update_infos').css('display', 'inline-block');
        $('.form_update_infos').css('width', 'calc(100% - 442px)');
        $('.form_update_infos').css('margin-left', '20px');
        $('.form_update_infos').css('margin-top', '0');

        $('.form_update_avatar').css('border-right', 'solid 1px #b3b3b3');

        // Adaptation succès
        if ($('.zone_succes_profil').length)
        {
            $('.zone_profil_left').css('display', 'inline-block');
            $('.zone_profil_left').css('width', '260px');

            $('.zone_profil_right').css('display', 'inline-block');
            $('.zone_profil_right').css('width', 'calc(100% - 280px)');
            $('.zone_profil_right').css('margin-left', '20px');
        }
    }
}

// Affiche ou masque la zone de saisie d'une autre équipe
function afficherAutreEquipe(select, required)
{
    if ($('.' + select).val() == 'other')
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

// Affichage de l'expérience du profil
function afficherExperienceProfil(id, experience)
{
    // Initialisations
    var rayonArc       = 55;
    var epaisseurLigne = 10;
    var abcisseCentre  = rayonArc + epaisseurLigne;
    var ordonneeCentre = rayonArc + epaisseurLigne;

    // Récupération des données
    var pourcentage = id.replace('canvas_profil_', '');
    var canvas      = $('#' + id)[0];
    var context     = canvas.getContext("2d");

    // Correction du flou
    var size  = 2 * (rayonArc + epaisseurLigne);
    var scale = window.devicePixelRatio;

    canvas.style.width  = size + 'px';
    canvas.style.height = size + 'px';
    canvas.width        = Math.floor(size * scale);
    canvas.height       = Math.floor(size * scale);

    context.scale(scale, scale);

    // Calcul du début et de la fin de l'arc
    var debutArc = Math.PI / 2;
    var finArc   = -1 * pourcentage * Math.PI / 50 + debutArc;

    // Suppression du dessin précédent
    context.clearRect(0, 0, canvas.width, canvas.height);

    // Début de la ligne
    context.beginPath();

    // Epaisseur de la ligne
    context.lineWidth = epaisseurLigne;

    // Couleur de la ligne
    if (pourcentage == 100)
        context.strokeStyle = '#d3d3d3';
    else
        context.strokeStyle = '#ff1937';

    // Lissage du contour
    context.imageSmoothingEnabled = true;

    // Définition de l'arc de cercle (dans le sens inverse des aiguilles d'une montre avec true)
    context.arc(abcisseCentre, ordonneeCentre, rayonArc, debutArc, finArc, true);

    // Création de la ligne
    context.stroke();

    // Texte
    if (pourcentage == 100)
    {
        context.font      = '100% inside_font_light, Verdana, sans-serif';
        context.textAlign = 'center';
        context.fillStyle = '#262626';

        context.fillText(experience + ' XP', 65, 72);
    }
}

// Change la couleur des radio boutons (préférences)
function switchCheckedColor(zone, input)
{
    $('.' + zone).each(function ()
    {
        $(this).removeClass('bouton_checked');
        $(this).children('input').prop('checked', false);
    })

    $('#' + input).addClass('bouton_checked');
    $('#' + input).children('input').prop('checked', true);
}

// Change la couleur des checkbox (préférences)
function changeCheckedColor(input)
{
    if ($('#' + input).children('input').prop('checked'))
        $('#' + input).removeClass('bouton_checked');
    else
        $('#' + input).addClass('bouton_checked');
}

// Affiche le détail d'un succès débloqué
function afficherDetailsSucces(id)
{
    var success = listeSuccess[id];
    var html    = '';

    html += '<div id="zoom_succes" class="fond_zoom_succes">';
        // Affichage du succès
        html += '<div class="zone_success_zoom">';
            // Succès
            html += '<div class="zone_succes_zoom">';
                // Titre du succès
                html += '<div class="titre_succes_zoom">' + success['title'] + '</div>';

                // Logo du succès
                html += '<img src="/inside/includes/images/profil/success/' + success['reference'] + '.png" alt="' + success['reference'] + '" class="logo_succes_zoom" />';

                // Description du succès
                html += '<div class="description_succes_zoom">' + success['description'] + '</div>';

                // Explications du succès
                html += '<div class="explications_succes_zoom">' + success['explanation'].replace('%limit%', formatNumericForDisplay(success['limit_success'])) + '</div>';
            html += '</div>';

            // Bouton
            html += '<div class="zone_boutons_succes_zoom">';
                // Bouton fermeture
                html += '<a id="closeZoomSuccess" class="bouton_succes_zoom">Cool !</a>';
            html += '</div>';
        html += '</div>';
    html += '</div>';

    $('body').append(html);

    $('#zoom_succes').fadeIn(200);
}