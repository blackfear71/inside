/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie de la vue
    $('#afficherSaisieVue, #fermerSaisieVue').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_vue');
    });

    // Ajouter un rapport
    $('#afficherSaisieRapport, #fermerSaisieRapport').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_rapport');
    });

    // Affiche une image de bug/évolution en grand
    $('.agrandirImage').click(function ()
    {
        afficherDetailsBug($(this));
    });

    // Ferme le zoom d'une image de bug/évolution
    $(document).on('click', '#fermerImage', function ()
    {
        masquerSupprimerIdWithDelay('zoom_image');
    });

    // Bloque la saisie en cas de soumission
    $('#validerSaisieRapport').click(function ()
    {
        var idForm          = $('#zone_saisie_rapport');
        var zoneForm        = 'zone_contenu_saisie';
        var zoneContenuForm = 'contenu_saisie';

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
    });

    /*** Actions au changement ***/
    // Charge l'image dans la zone de saisie
    $('.loadSaisieRapport').on('change', function (event)
    {
        loadFile(event, 'image_report', true);
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
// Affiche l'image d'un bug ou d'une évolution
function afficherDetailsBug(element)
{
    var html   = '';
    var path   = element.children().attr('src');
    var split  = path.split('/');
    var report = split[split.length - 1];

    html += '<div id="zoom_image" class="fond_zoom_image" style="display: none;">';
        // Affichage de l'image
        html += '<div class="zone_image_zoom">';
            // Image
            html += '<img src="' + path + '" alt="' + report + '" class="image_zoom" />';

            // Bouton
            html += '<div class="zone_boutons_image_zoom">';
                // Bouton fermeture
                html += '<a id="fermerImage" class="bouton_image_zoom">Fermer l\'image</a>';
            html += '</div>';
        html += '</div>';
    html += '</div>';

    $('body').append(html);

    $('#zoom_image').fadeIn(200);
}