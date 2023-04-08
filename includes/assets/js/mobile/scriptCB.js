/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Ouvre ou ferme la zone de saisie d'année
    $('#afficherSaisieAnnee, #fermerSaisieAnnee').click(function ()
    {
        afficherMasquerIdWithDelay('zone_saisie_annee');
    });

    // Ouvre la zone de saisie d'une recette
    $('#afficherSaisieRecette').click(function ()
    {
        // Affichage de la zone de saisie
        afficherMasquerIdWithDelay('zone_saisie_recette');
    });

    // Ajoute un champ de saisie ingrédient (saisie)
    $('#ajoutIngredient').click(function ()
    {
        ajoutIngredient('zone_ingredients');
    });

    // Ferme la zone de saisie d'une recette
    $('#fermerSaisieRecette').click(function ()
    {
        // Réinitialisation de la saisie
        reinitialisationSaisieRecette();

        // Fermeture de l'affichage
        afficherMasquerIdWithDelay('zone_saisie_recette');
    });

    // Bloque la saisie en cas de soumission (recette)
    $('#validerSaisieRecette').click(function ()
    {
        var idForm          = $('#zone_saisie_recette');
        var zoneForm        = 'zone_contenu_saisie';
        var zoneContenuForm = 'contenu_saisie';

        blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
    });

    // Réinitialise la saisie à la fermeture au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme le zoom d'une recette
        if ($(event.target).attr('class') == 'fond_zoom')
            masquerSupprimerIdWithDelay('zoom_image');

        if ($(event.target).attr('id') == 'zone_saisie_recette')
            reinitialisationSaisieRecette();
    });

    // Ouvre la zone de saisie d'une semaine
    $('#semaineCourante, #semaineSuivante').click(function ()
    {
        afficherSaisieSemaine($(this).attr('id'));
    });

    // Ferme la zone de saisie d'une semaine
    $('#fermerSaisieSemaine').click(function ()
    {
        // Fermeture de l'affichage
        afficherMasquerIdWithDelay('zone_saisie_semaine');
    });

    // Modifier une recette
    $('.modifierRecette').click(function ()
    {
        var idRecette = $(this).attr('id').replace('modifier_', '');

        updateRecipe(idRecette, 'zone_saisie_recette');
    });

    // Affiche une recette en grand
    $('.afficherRecette').click(function ()
    {
        var idRecette = $(this).attr('id').replace('afficher_recette_', '');

        afficherDetailsRecette($(this), idRecette);
    });

    // Ferme le zoom d'une recette (au clic sur la croix)
    $(document).on('click', '#fermerRecette', function ()
    {
        masquerSupprimerIdWithDelay('zoom_image');
    });

    /*** Actions au changement ***/
    // Charge l'image dans la zone de saisie
    $('.loadSaisieRecette').on('change', function (event)
    {
        loadFile(event, 'image_recette', true);
    });

    // Affiche la saisie semaine (saisie)
    $('#saisie_annee').on('change', function ()
    {
        afficherSemaines('saisie_annee');
    });

    // Change la couleur de l'ingrédient à la saisie
    $(document).on('input', '.saisieIngredient', function ()
    {
        var idIngredient = $(this).attr('id');

        changeIngredientColor(idIngredient);
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
// Affiche ou la zone de saisie semaine correspondant à l'année (insertion)
function afficherSemaines(select)
{
    var html     = '';
    var semaines = listWeeks[$('#' + select).val()];

    html += '<select name="week_recipe" id="saisie_semaine" class="saisie_annee_semaine" required>';
        html += '<option value="" hidden>Semaine</option>';

        $.each(semaines, function (key, value)
        {
            html += '<option value="' + value + '">' + value + '</option>';
        });
    html += '</select>';

    if ($('#saisie_semaine').length)
        $('#saisie_semaine').remove();

    $('#' + select).after(html);
}

// Change la couleur de fond lors de la saisie de texte
function changeIngredientColor(id)
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

// Affiche un champ de saisie d'ingrédient
function ajoutIngredient(idParent)
{
    var html         = '';
    var length       = $('#' + idParent + ' .input_ingredient').length;
    var newLength    = length + 1;
    var idIngredient = 'ingredient_' + newLength;
    var unites       = ['sans', 'g', 'kg', 'ml', 'cl', 'L', 'CC', 'CS'];

    html += '<div class="zone_ingredient">';
        // Ingrédient
        html += '<input type="text" placeholder="Ingrédient" value="" id="' + idIngredient + '" name="ingredients[' + newLength + ']" class="input_ingredient saisieIngredient" />';

        // Quantité
        html += '<input type="text" placeholder="Quantité" value="" id="quantite_' + idIngredient + '" name="quantites_ingredients[' + newLength + ']" class="input_quantite" />';

        // Unité
        html += '<select id="unite_' + idIngredient + '" name="unites_ingredients[' + newLength + ']" class="select_unite">';
            html += '<option value="" hidden>Unité</option>';

            $.each(unites, function (key, value)
            {
                html += '<option value="' + value + '">' + value + '</option>';
            });
        html += '</select>';
    html += '</div>';

    $('#ajoutIngredient').before(html);
}

// Réinitialise la zone de saisie d'une recette si fermeture modification
function reinitialisationSaisieRecette(zone)
{
    setTimeout(function ()
    {
        // Test si action = modification
        var currentAction = $('.form_saisie').attr('action').split('&action=');
        var call          = currentAction[currentAction.length - 1]

        if (call == 'doModifierRecette')
        {
            var id          = '';
            var image       = '';
            var annee       = '';
            var semaine     = '';
            var nom         = '';
            var ingredients = '';
            var preparation = '';
            var remarques   = '';
            var titre       = 'Ajouter une recette';
            var action      = 'cookingbox.php?action=doAjouterRecette';

            generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, 'reset');
        }
    }, 200);
}

// Affiche la zone de mise à jour d'une recette
function updateRecipe(id, zone)
{
    // Affichage zone de saisie
    afficherMasquerIdWithDelay(zone);

    var image       = listRecipes[id]['picture'];
    var annee       = listRecipes[id]['year'];
    var semaine     = listRecipes[id]['week'];
    var nom         = listRecipes[id]['name'];
    var ingredients = listRecipes[id]['ingredients'].split(';');
    var preparation = listRecipes[id]['recipe'];
    var remarques   = listRecipes[id]['tips'];
    var titre       = 'Modifier la semaine ' + semaine + ' (' + annee + ')';
    var action      = 'cookingbox.php?year=' + annee + '&action=doModifierRecette';

    // Modification des données
    generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, 'mod');
}

// Génère la zone de saisie recette
function generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, mode)
{
    var ingredient;
    var numIngredient;

    // Modification des données générales
    $('input[name=id_recipe]').val(id);
    $('.zone_titre_saisie').html(titre);
    $('.form_saisie').attr('action', action);
    $('#saisie_nom').val(nom);
    $('#saisie_preparation').val(preparation);
    $('#saisie_remarques').val(remarques);

    // Modification des données spécifiques
    if (mode == 'mod')
    {
        $('#saisie_annee').css('display', 'none');
        $('#saisie_annee').prop('required', false);

        $('#saisie_semaine').css('display', 'none');
        $('#saisie_semaine').prop('required', false);

        $('#zone_saisie_recette').find('.form_saisie').prepend('<input type="hidden" id="hidden_week_recipe" name="hidden_week_recipe" value="' + semaine + '" />');
        $('#zone_saisie_recette').find('.form_saisie').prepend('<input type="hidden" id="hidden_year_recipe" name="hidden_year_recipe" value="' + annee + '" />');

        $('#image_recette').attr('src', '../../includes/images/cookingbox/' + annee + '/mini/' + image);
        $('input[name=image]').prop('required', false);
    }
    else
    {
        $('#saisie_annee').css('display', 'block');
        $('#saisie_annee').prop('required', true);

        $('#hidden_week_recipe').remove();
        $('#hidden_year_recipe').remove();

        $('#image_recette').removeAttr('src');
        $('input[name=image]').prop('required', true);
    }

    // Modification des données ingrédients
    $('.zone_ingredient').remove();

    ajoutIngredient('zone_ingredients');
    ajoutIngredient('zone_ingredients');
    ajoutIngredient('zone_ingredients');
    ajoutIngredient('zone_ingredients');

    $.each(ingredients, function (key, value)
    {
        ingredient = '';

        if (value != '')
        {
            ingredient    = value.split('@');
            numIngredient = key + 1;

            if (numIngredient > 4)
                ajoutIngredient('zone_ingredients');

            $('#ingredient_' + numIngredient).val(ingredient[0]);
            $('#quantite_ingredient_' + numIngredient).val(ingredient[1]);

            $('#ingredient_' + numIngredient).addClass('filled');

            if (ingredient[2] == '')
                $('#unite_ingredient_' + numIngredient).val('sans');
            else
                $('#unite_ingredient_' + numIngredient).val(ingredient[2]);
        }
    });
}

// Affiche la saisie d'une semaine de gâteau
function afficherSaisieSemaine(idSemaine)
{
    // Récupération des données de la semaine
    var semaine;

    if (idSemaine == 'semaineCourante')
        semaine = currentWeek;
    else
        semaine = nextWeek;

    var titre  = 'Semaine ' + semaine['week'];
    var action = 'cookingbox.php?year=' + $_GET('year') + '&action=doModifierSemaine';

    // Titre
    $('#zone_saisie_semaine').find('.zone_titre_saisie').html(titre);

    // On autorise la saisie seulement si le gâteau n'a pas été fait
    if (semaine['cooked'] == 'N' || semaine['cooked'] == '')
    {
        // Affichage de la saisie
        $('#zone_saisie_semaine').find('.form_saisie_semaine').css('display', 'block');

        // Action 
        $('#zone_saisie_semaine').find('.form_saisie_semaine').attr('action', action);

        // Numéro de semaine
        $('#zone_saisie_semaine').find('input[name=week]').val(semaine['week']);

        // Sélection de l'utilisateur
        if (semaine['identifiant'] != '')
            $('#zone_saisie_semaine').find('select[name=select_user] option[value=' + semaine['identifiant'] + ']').prop('selected', true);
        else
            $('#zone_saisie_semaine').find('select[name=select_user] option:selected').prop('selected', false);

        // Je l'ai fait
        $('#zone_saisie_semaine').find('.cake_done').css('display', 'none');

        if (semaine['identifiant'] == userSession)
        {
            $('#zone_saisie_semaine').find('.form_saisie_realisation').css('display', 'block');
            $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=week_cake]').val(semaine['week']);
            $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=year_cake]').val(semaine['year']);
        }
        else
        {
            $('#zone_saisie_semaine').find('.form_saisie_realisation').css('display', 'none');
            $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=week_cake]').val('');
            $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=year_cake]').val('');
        }

        $('#zone_saisie_semaine').find('.form_saisie_annulation').css('display', 'none');
        $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=week_cake]').val('');
        $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=year_cake]').val('');
    }
    else
    {
        // Masquage de la saisie
        $('#zone_saisie_semaine').find('.form_saisie_semaine').css('display', 'none');

        // Action 
        $('#zone_saisie_semaine').find('.form_saisie_semaine').attr('action', '');

        // Numéro de semaine
        $('#zone_saisie_semaine').find('input[name=week]').val('');

        // Je l'ai fait
        $('#zone_saisie_semaine').find('.cake_done').css('display', 'block');

        if (semaine['identifiant'] == userSession)
        {
            $('#zone_saisie_semaine').find('.form_saisie_annulation').css('display', 'block');
            $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=week_cake]').val(semaine['week']);
            $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=year_cake]').val(semaine['year']);
        }
        else
        {
            $('#zone_saisie_semaine').find('.form_saisie_annulation').css('display', 'none');
            $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=week_cake]').val('');
            $('#zone_saisie_semaine').find('.form_saisie_annulation').find('input[name=year_cake]').val('');
        }

        $('#zone_saisie_semaine').find('.form_saisie_realisation').css('display', 'none');
        $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=week_cake]').val('');
        $('#zone_saisie_semaine').find('.form_saisie_realisation').find('input[name=year_cake]').val('');
    }

    // Affichage zone de saisie
    afficherMasquerIdWithDelay('zone_saisie_semaine');
}

// Affiche les détails de la recette
function afficherDetailsRecette(link, id)
{
    var html             = '';
    var path_mini        = link.children('.image_recette').attr('src');
    var path_full        = path_mini.replace('mini/', '');
    var split            = path_mini.split('/');
    var image            = split[split.length - 1];
    var recipe           = listRecipes[id];
    var ingredientsSplit = [];
    var ingredientSplit  = [];
    var ingredients      = [];
    var avatarFormatted;

    html += '<div id="zoom_image" class="fond_zoom_image" style="display: none;">';
        // Recette
        html += '<div class="zone_image_zoom">';
            // Image
            html += '<img src="' + path_full + '" alt="' + image + '" class="image_zoom" />';

            // Détails recette
            html += '<div class="zone_texte_zoom">';
                // Nom de la recette
                html += '<div class="titre_details">';
                    html += '<img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" />';
                    html += '<div class="texte_titre_section">' + recipe['name'] + '</div>';
                html += '</div>';

                // Avatar
                avatarFormatted = formatAvatar(recipe['avatar'], recipe['pseudo'], 2, 'avatar');

                html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_details_recette" />';

                // Réalisateur
                html += '<div class="zone_pseudo_details">';
                    html += 'Par <strong>' + formatUnknownUser(recipe['pseudo'], false, true) + '</strong>';
                html += '</div>';

                // Ingrédients
                if (recipe['ingredients'] != '')
                {
                    ingredientsSplit = recipe['ingredients'].split(';');

                    $.each(ingredientsSplit, function (key, value)
                    {
                        ingredientSplit = value.split('@');

                        if (ingredientSplit[0] != '')
                            ingredients.push({ ingredient: ingredientSplit[0], quantity: ingredientSplit[1], unity: ingredientSplit[2] });
                    });

                    html += '<div class="zone_ingredients_details">';
                        html += '<div class="titre_details_recette">Ingrédients</div>';

                        $.each(ingredients, function (key, value)
                        {
                            html += '<div class="ingredient_details_recette">' + this['ingredient'] + '</div>';

                            if (this['quantity'] != '')
                            {
                                if (this['unity'] == 'CC' || this['unity'] == 'CS')
                                    html += '<div class="quantite_details_recette">' + this['quantity'] + ' ' + this['unity'] + '</div>';
                                else
                                    html += '<div class="quantite_details_recette">' + this['quantity'] + this['unity'] + '</div>';
                            }
                        });
                    html += '</div>';
                }

                // Préparation
                if (recipe['recipe'] != '')
                {
                    html += '<div class="zone_ingredients_details">';
                        html += '<div class="titre_details_recette">Préparation</div>';
                        html += '<div class="contenu_recette_details">' + nl2br(recipe['recipe']) + '</div>';
                    html += '</div>';
                }

                // Remarques & astuces
                if (recipe['tips'] != '')
                {
                    html += '<div class="zone_ingredients_details">';
                        html += '<div class="titre_details_recette">Remarques & astuces</div>';
                        html += '<div class="contenu_recette_details">' + nl2br(recipe['tips']) + '</div>';
                    html += '</div>';
                }

                // Cas vide
                if (recipe['ingredients'] == '' && recipe['recipe'] == '' && recipe['tips'] == '')
                    html += '<div class="recette_vide">Pas de recette disponible...</div>';

                // Bouton "Je l'ai fait"
                if (recipe['identifiant'] == userSession)
                {
                    if (recipe['cooked'] == 'Y')
                    {
                        html += '<form method="post" action="cookingbox.php?year=' + recipe['year'] + '&action=doAnnulerSemaine" class="form_details">';
                            html += '<input type="hidden" name="week_cake" value="' + recipe['week'] + '" />';
                            html += '<input type="hidden" name="year_cake" value="' + recipe['year'] + '" />';
                            html += '<input type="submit" name="cancel_cake" value="Annuler" class="bouton_details" />';
                        html += '</form>';
                    }
                    else
                    {
                        html += '<form method="post" action="cookingbox.php?year=' + recipe['year'] + '&action=doValiderSemaine" class="form_details">';
                            html += '<input type="hidden" name="week_cake" value="' + recipe['week'] + '" />';
                            html += '<input type="hidden" name="year_cake" value="' + recipe['year'] + '" />';
                            html += '<input type="submit" name="validate_cake" value="Je l\'ai fait" class="bouton_details" />';
                        html += '</form>';
                    }
                }
            html += '</div>';

            // Bouton
            html += '<div class="zone_boutons_image_zoom">';
                // Bouton fermeture
                html += '<a id="fermerRecette" class="bouton_image_zoom">Fermer la recette</a>';
            html += '</div>';
        html += '</div>';
    html += '</div>';

    $('body').append(html);

    $('#zoom_image').fadeIn(200);
}