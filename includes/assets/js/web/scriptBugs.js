/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ajouter un rapport
    $('#ajouterRapport, #fermerRapport').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_rapport');
    });

    // Affiche une image de bug / évolution en grand
    $('.agrandirImage').click(function ()
    {
        afficherDetailsBug($(this));
    });

    // Ferme le zoom d'une image de bug / évolution
    $(document).on('click', '#fermerImage', function ()
    {
        masquerSupprimerIdWithDelay('zoom_image');
    });

    // Bloque le bouton de soumission si besoin
    $('#bouton_saisie_bug').click(function ()
    {
        var zoneButton   = $('.zone_bouton_saisie');
        var submitButton = $(this);
        var formSaisie   = submitButton.closest('form');
        var tabBlock     = null;

        hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
    });

    /*** Actions au changement ***/
    // Charge l'image (saisie)
    $('.loadSaisieReport').on('change', function (event)
    {
        loadFile(event, 'image_report', true);
    });
});

// Au chargement du document complet
$(window).on('load', function ()
{
    // Adaptation mobile
    adaptBugs();

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
    adaptBugs();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des bugs / évolutions sur mobile
function adaptBugs()
{
    if ($(window).width() < 1080)
    {
        $('.zone_vues').css('display', 'block');
        $('.zone_vues').css('width', '100%');

        $('.zone_bugs').css('display', 'block');
        $('.zone_bugs').css('width', '100%');
        $('.zone_bugs').css('margin-left', '0');

        $('.zone_evolutions').css('display', 'block');
        $('.zone_evolutions').css('width', '100%');
        $('.zone_evolutions').css('margin-left', '0');

        $('.view').css('display', 'inline-block');
        $('.view').css('width', 'calc(50% - 10px)');
        $('.view').first().css('margin-right', '20px');
    }
    else
    {
        $('.zone_vues').css('display', 'inline-block');
        $('.zone_vues').css('width', '120px');

        $('.zone_bugs').css('display', 'inline-block');
        $('.zone_bugs').css('width', 'calc(50% - 80px)');
        $('.zone_bugs').css('margin-left', '20px');

        $('.zone_evolutions').css('display', 'inline-block');
        $('.zone_evolutions').css('width', 'calc(50% - 80px)');
        $('.zone_evolutions').css('margin-left', '20px');

        $('.view').css('display', 'block');
        $('.view').css('width', '100%');
        $('.view').first().css('margin-right', '0');
    }
}

// Affiche l'image d'un bug ou d'une évolution
function afficherDetailsBug(element)
{
    var html   = '';
    var path   = element.children().attr('src');
    var split  = path.split('/');
    var report = split[split.length - 1];

    html += '<div id="zoom_image" class="fond_zoom_image">';
        html += '<div class="zone_image_zoom">';
            html += '<a id="fermerImage" class="lien_zoom"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';
            html += '<img src="' + path + '" alt="' + report + '" class="image_zoom" />';
        html += '</div>';
    html += '</div>';

    $('body').append(html);

    // Affichage de l'image
    afficherMasquerIdWithDelay('zoom_image');
}