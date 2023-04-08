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
        // Affichage de la zone de saisie
        afficherMasquerIdWithDelay('zone_saisie_vue');
    });

    // Ouvre la zone de saisie d'une idée
    $('#afficherSaisieIdee').click(function ()
    {
        // Initialisation des titres de la saisie
        initialisationSaisieIdee('zone_saisie_idee');

        // Affichage de la zone de saisie
        afficherMasquerIdWithDelay('zone_saisie_idee');
    });

    // Ferme la zone de saisie d'une idée
    $('#fermerSaisieIdee').click(function ()
    {
        // Réinitialisation de la saisie
        reinitialisationSaisieIdee();

        // Fermeture de l'affichage
        afficherMasquerIdWithDelay('zone_saisie_idee');
    });

    // Ouvre la fenêtre de saisie d'une idée en modification
    $('.modifierIdee').click(function ()
    {
        var idIdee = $(this).attr('id').replace('modifier_idee_', '');

        initialisationModificationIdee(idIdee);
    });

    // Réinitialise la saisie à la fermeture au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme la saisie d'une idée
        if ($(event.target).attr('class') == 'fond_saisie')
            reinitialisationSaisieIdee();
    });

    // Bloque la saisie en cas de soumission
    $('#validerSaisieIdee').click(function ()
    {
        var idForm          = $('#zone_saisie_idee');
        var zoneForm        = 'zone_contenu_saisie';
        var zoneContenuForm = 'contenu_saisie';

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
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
// Initialisation des titres de la saisie d'une idée
function initialisationSaisieIdee(idZone)
{
    // Titre
    var titre = 'Proposer une idée';

    // Modification des données
    // Titre
    $('#' + idZone).find('.zone_titre_saisie').html(titre);
}

// Affiche la zone de mise à jour d'une idée
function initialisationModificationIdee(idIdee)
{
    // Récupération des données
    var idee        = listeIdees[idIdee];
    var sujet       = idee['subject'];
    var description = idee['content'];
    var action      = 'ideas.php?view=' + $_GET('view') + '&action=doModifierIdee';
    var titre       = 'Modifier une idée';

    // Modification des données
    // Action du formulaire
    $('#zone_saisie_idee').find('.form_saisie').attr('action', action);

    // Titre
    $('#zone_saisie_idee').find('.zone_titre_saisie').html(titre);

    // Identifiant idée
    $('#zone_saisie_idee').find('#id_saisie_idee').val(idIdee);

    // Titre de l'idée
    $('#zone_saisie_idee').find('.saisie_titre_idee').val(sujet);

    // Description de l'idée
    $('#zone_saisie_idee').find('.saisie_description_idee').val(description);

    // Affiche la zone de saisie
    afficherMasquerIdWithDelay('zone_saisie_idee');
}

// Réinitialise la zone de saisie d'une idée si fermeture modification
function reinitialisationSaisieIdee()
{
    // Déclenchement après la fermeture
    setTimeout(function ()
    {
        // Test si action = modification
        var currentAction = $('#zone_saisie_idee').find('.form_saisie').attr('action').split('&action=');
        var call          = currentAction[currentAction.length - 1]

        if (call == 'doModifierIdee')
        {
            // Action du formulaire
            var action = 'ideas.php?view=' + $_GET('view') + '&action=doAjouterIdee';

            $('#zone_saisie_idee').find('.form_saisie').attr('action', action);

            // Initialisation du titre de la saisie
            initialisationSaisieIdee('zone_saisie_idee');

            // Identifiant idée
            $('#zone_saisie_idee').find('#id_saisie_idee').val('');

            // Titre de l'idée
            $('#zone_saisie_idee').find('.saisie_titre_idee').val('');

            // Description de l'idée
            $('#zone_saisie_idee').find('.saisie_description_idee').val('');
        }
    }, 200);
}