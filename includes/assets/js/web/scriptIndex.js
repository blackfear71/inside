/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au chargement ***/
    // Positionnement du curseur sur "Identifiant" pour se connecter
    $('#focus_identifiant').focus();

    // Adaptation mobile
    adaptIndex();

    /*** Actions au clic ***/
    // Affiche la zone de connexion + focus
    $('#afficherConnexion').click(function ()
    {
        var linkSelected = 'afficherConnexion';
        var showForm     = 'formConnexion';
        var hideForm;
        var showText     = '';
        var hideText;
        var focus        = 'focus_identifiant';

        if ($('#formPassword').css('display') == 'none')
        {
            hideForm = 'formInscription';
            hideText = 'texteInscription';
        }
        else
        {
            hideForm = 'formPassword';
            hideText = 'textePassword';
        }

        // Changement d'onglet
        switchIndex(showForm, hideForm, focus);
        switchIndex(showText, hideText);

        // Sélection du lien
        selectionLien(linkSelected);

        // Fermeture des aides
        afficherMasquerPopUp('aideInscription', true);
        afficherMasquerPopUp('aidePassword', true);
    });

    // Affiche la zone d'inscription + focus
    $('#afficherInscription').click(function ()
    {
        var linkSelected = 'afficherInscription';
        var showForm     = 'formInscription';
        var hideForm;
        var showText     = 'texteInscription';
        var hideText;
        var focus        = 'focus_identifiant_2';

        if ($('#formPassword').css('display') == 'none')
        {
            hideForm = 'formConnexion';
            hideText = '';
        }
        else
        {
            hideForm = 'formPassword';
            hideText = 'textePassword';
        }

        // Changement d'onglet
        switchIndex(showForm, hideForm, focus);
        switchIndex(showText, hideText);

        // Sélection du lien
        selectionLien(linkSelected);

        // Fermeture des aides
        afficherMasquerPopUp('aideInscription', true);
        afficherMasquerPopUp('aidePassword', true);
    });

    // Affiche la zone de réinitialisation mot de passe + focus
    $('#afficherPassword').click(function ()
    {
        var linkSelected = 'afficherPassword';
        var showForm     = 'formPassword';
        var hideForm;
        var showText     = 'textePassword';
        var hideText;
        var focus        = 'focus_identifiant_3';

        if ($('#formConnexion').css('display') == 'none')
        {
            hideForm = 'formInscription';
            hideText = 'texteInscription';
        }
        else
        {
            hideForm = 'formConnexion';
            hideText = '';
        }

        // Changement d'onglet
        switchIndex(showForm, hideForm, focus);
        switchIndex(showText, hideText);

        // Sélection du lien
        selectionLien(linkSelected);

        // Fermeture des aides
        afficherMasquerPopUp('aideInscription', true);
        afficherMasquerPopUp('aidePassword', true);
    });

    // Affiche ou masque l'aide d'inscription
    $('#afficherAideInscription, #fermerAideInscription').click(function ()
    {
        afficherMasquerPopUp('aideInscription', false);
    });

    // Affiche ou masque l'aide de changement de mot de passe
    $('#afficherAidePassword, #fermerAidePassword').click(function ()
    {
        afficherMasquerPopUp('aidePassword', false);
    });

    /*** Actions au changement ***/
    // Transforme en majuscule les caractères saisis dans les différents identifiants
    $('#focus_identifiant, #focus_identifiant_2, #focus_identifiant_3').change(function ()
    {
        identifiantMajuscule($(this));
    });

    // Affiche la saisie "Autre" (nouvelle équipe)
    $('.select_form_index').on('change', function ()
    {
        afficherAutreEquipe('select_form_index', 'autre_equipe');
    });
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation mobile
    adaptIndex();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations de l'index sur mobile
function adaptIndex()
{
    if ($(window).width() < 1100)
    {
        $('.lien_index').css('display', 'block');
        $('.lien_index').css('width', '150px');
        $('.lien_index').css('height', 'calc(80px / 3)');
        $('.lien_index').css('line-height', 'calc(80px / 3)');

        $('.logo_categories').css('width', '30px');
        $('.logo_categories').css('height', '30px');
        $('.logo_categories').css('margin-top', '25px');
    }
    else
    {
        $('.lien_index').css('display', 'inline-block');
        $('.lien_index').css('width', 'unset');
        $('.lien_index').css('height', '80px');
        $('.lien_index').css('line-height', '80px');

        $('.logo_categories').css('width', '40px');
        $('.logo_categories').css('height', '40px');
        $('.logo_categories').css('margin-top', '20px');
    }
}

// Transforme le contenu d'un champ en majuscules
function identifiantMajuscule(champ)
{
    var value = champ.val();

    if (value != 'admin')
        value = value.toUpperCase();

    champ.val(value);
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

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function switchIndex(idOpen, idClose, focus = null)
{
    if (idOpen == '')
    {
        $('#' + idClose).fadeOut(200, function ()
        {
            if (focus != null)
                $('#' + focus).focus();
        });
    }
    else if (idClose == '')
    {
        $('#' + idOpen).delay(200).fadeIn(200, function ()
        {
            if (focus != null)
                $('#' + focus).focus();
        });
    }
    else
    {
        $('#' + idClose).fadeOut(200, function ()
        {
            $('#' + idOpen).fadeIn(200, function ()
            {
                if (focus != null)
                    $('#' + focus).focus();
            });
        });
    }
}

// Sélectionne un lien dans les onglets
function selectionLien(lien)
{
    switch (lien)
    {
        case 'afficherConnexion':
            $('#afficherConnexion').addClass('lien_index_selected');
            $('#afficherInscription').removeClass('lien_index_selected');
            $('#afficherPassword').removeClass('lien_index_selected');
            break;

        case 'afficherInscription':
            $('#afficherConnexion').removeClass('lien_index_selected');
            $('#afficherInscription').addClass('lien_index_selected');
            $('#afficherPassword').removeClass('lien_index_selected');
            break;

        case 'afficherPassword':
            $('#afficherConnexion').removeClass('lien_index_selected');
            $('#afficherInscription').removeClass('lien_index_selected');
            $('#afficherPassword').addClass('lien_index_selected');
            break;

        default:
            $('#afficherConnexion').removeClass('lien_index_selected');
            $('#afficherInscription').removeClass('lien_index_selected');
            $('#afficherPassword').removeClass('lien_index_selected');
            break;
    }
}