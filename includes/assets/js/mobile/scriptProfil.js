/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    // Charge l'image dans la zone de saisie
    $('.loadSaisieAvatar').on('change', function (event)
    {
        loadFile(event, 'image_avatar_saisie', true);
    });

    /*** Actions au clic ***/
    // Change la couleur des boutons préférences (radio boutons)
    $('.radioPreference').click(function ()
    {
        var bouton     = $(this);
        var zoneParent = bouton.parents('.zone_preference');

        changeRadioColor(bouton, zoneParent);
    });

    // Change la couleur des boutons préférences (checkboxes)
    $('.checkPreference').click(function ()
    {
        var zoneParent = $(this).parents('.switch_preference_2');

        changeCheckedColor(zoneParent);
    });

    // Affiche les détails d'un succès
    $('.agrandirSucces').click(function ()
    {
        var idSuccess = $(this).attr('id').replace('agrandir_succes_', '');

        afficherDetailsSucces(idSuccess);
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

    /*** Actions au changement ***/
    // Affiche la saisie "Autre" (changement d'équipe)
    $('.select_form_update_team').on('change', function ()
    {
        afficherAutreEquipe('select_form_update_team', 'autre_equipe');
    });

    // Affiche un exemple de police de caractères
    $('#select_police').on('change', function ()
    {
        $('#exemple_police').css('font-family', $(this).val() + ', Times New Roman, Verdana, sans-serif');
    });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Change la couleur d'une préférence (radio boutons)
function changeRadioColor(bouton, zoneParent)
{
    // Réinitialisation des autres boutons
    zoneParent.find('.switch_preference').each(function ()
    {
        $(this).removeClass('bouton_checked');
        $(this).find('input').prop('checked', false);
    })

    // On coche le bouton concerné
    bouton.parent().addClass('bouton_checked');
    bouton.parent().find('input').prop('checked', true);
}

// Change la couleur d'une préférence (checkboxes)
function changeCheckedColor(zoneParent)
{
    if (zoneParent.find('input').prop('checked'))
        zoneParent.removeClass('bouton_checked');
    else
        zoneParent.addClass('bouton_checked');
}

// Affiche le détail d'un succès débloqué
function afficherDetailsSucces(id)
{
    var success = listeSuccess[id];
    var html    = '';

    html += '<div id="zoom_succes" class="fond_zoom_succes" style="display: none;">';
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

            // Classement
            if (success.classement.length)
            {
                var previousRank = 0;

                // Zone classement
                html += '<div class="zone_classement_zoom">';
                    // Titre
                    html += '<div class="titre_classement_zoom">Classement</div>';

                    // Classement
                    html += '<div class="zone_classement_users_zoom">';
                        $.each(success.classement, function (key, ranking)
                        {
                            // Zone médaille
                            if (ranking.rank != previousRank)
                            {
                                previousRank = ranking.rank;

                                // Médaille
                                switch (ranking.rank)
                                {
                                    case '1':
                                        html += '<img src="../../includes/icons/common/medals/or.png" alt="or" class="medaille_classement_zoom" />';
                                        break;

                                    case '2':
                                        html += '<img src="../../includes/icons/common/medals/argent.png" alt="argent" class="medaille_classement_zoom" />';
                                        break;

                                    case '3':
                                        html += '<img src="../../includes/icons/common/medals/bronze.png" alt="bronze" class="medaille_classement_zoom" />';
                                        break;

                                    default:
                                        break;
                                }

                                // Zone
                                html += '<div class="zone_classement_medaille_zoom">';
                            }

                            html += '<div class="zone_utilisateur_classement_zoom">';
                                // Avatar
                                var avatarFormatted = formatAvatar(ranking.avatar, ranking.pseudo, 2, 'avatar');

                                html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_classement_zoom" />';

                                // Pseudo
                                html += '<div class="pseudo_classement_zoom">' + formatString(ranking.pseudo, 20) + '</div>';
                            html += '</div>';

                            // Séparation et fin de la zone
                            if (success.classement[key + 1] == undefined || ranking.rank != success.classement[key + 1].rank)
                            {
                                // Fin de la zone
                                html += '</div>';

                                // Séparation
                                if (success.classement[key + 1] != undefined)
                                    html += '<div class="separation_classement_zoom"></div>';
                            }
                        });
                    html += '</div>';
                html += '</div>';
            }

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