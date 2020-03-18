/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche la liste des utilisateurs (semaine courante)
  $('.afficherUtilisateursCurrent').click(function()
  {
    var id_boutons  = 'boutons_current_week';
    var week_number = parseInt($(this).attr('id').replace('choix_semaine_courante_', ''));

    if (week_number < 10)
      week_number = '0' + week_number;

    afficherMasquerNoDelay(id_boutons);
    afficherListboxUtilisateurs('zone_current_week', week_number);
  });

  // Affiche la liste des utilisateurs (semaine suivante)
  $('.afficherUtilisateursNext').click(function()
  {
    var id_boutons  = 'boutons_next_week';
    var week_number = parseInt($(this).attr('id').replace('choix_semaine_suivante_', ''));

    if (week_number < 10)
      week_number = '0' + week_number;

    afficherMasquerNoDelay(id_boutons);
    afficherListboxUtilisateurs('zone_next_week', week_number);
  });

  // Masque la liste des utilisateurs (semaine courante)
  $(document).on('click', '.cacherUtilisateursCurrent', function()
  {
    var id_boutons = 'boutons_current_week';
    var id_form    = 'form_current_week';

    afficherMasquerNoDelay(id_boutons);
    $('#' + id_form).remove();
  });

  // Masque la liste des utilisateurs (semaine suivante)
  $(document).on('click', '.cacherUtilisateursNext', function()
  {
    var id_boutons = 'boutons_next_week';
    var id_form    = 'form_next_week';

    afficherMasquerNoDelay(id_boutons);
    $('#' + id_form).remove();
  });

  // Ajouter une recette
  $('#ajouterRecette').click(function()
  {
    afficherMasquer('zone_add_recipe');
  });

  // Réinitialise la saisie recette à la fermeture
  $('#fermerSaisieRecette').click(function()
  {
    resetSaisie('zone_add_recipe');
  });

  // Modifier une recette
  $('.modifierRecette').click(function()
  {
    var id_recette = $(this).attr('id').replace('modifier_', '');

    updateRecipe(id_recette, 'zone_add_recipe');
  });

  // Ajoute un champ de saisie ingrédient (saisie)
  $('#addIngredient').click(function()
  {
    addIngredient('zone_ingredients');
  });

  // Affiche une recette en grand
  $('.afficherRecette').click(function()
  {
    var id_recette = $(this).attr('id').replace('afficher_recette_', '');

    showRecipe($(this), id_recette);
  });

  // Ferme le zoom d'une recette (au clic sur la croix)
  $(document).on('click', '#fermerRecette', function(event)
  {
    closeRecipe();
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme le zoom d'une recette
    if ($(event.target).attr('class') == 'fond_zoom')
      closeRecipe();

    // Ferme la saisie d'une recette
    if ($(event.target).attr('class') == 'fond_saisie_recette')
      closeInput();
  });

  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_recette').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (ajout ingrédient recette)
    tabBlock.push({element: '#addIngredient', property: 'display', value: 'none'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  /*** Actions au changement ***/
  // Charge l'image (saisie)
  $('.loadSaisieRecette').on('change', function()
  {
    loadFile(event, 'image_recette');
  });

  // Affiche la saisie semaine (saisie)
  $('#saisie_annee').on('change', function()
  {
    afficherSemaines('saisie_annee');
  });

  // Change la couleur de l'ingrédient à la saisie
  $(document).on('input', '.saisieIngredient', function()
  {
    id_ingredient = $(this).attr('id');

    changeIngredientColor(id_ingredient);
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptRecipes();

  // Affichage des différentes zones en fondu
  tailleAutoRecette(0);
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation mobile
  adaptRecipes();

  // Masonry (Calendriers & annexes)
  if ($('.zone_recettes').length)
  {
    $('.zone_recettes').masonry().masonry('destroy');

    $('.zone_recettes').masonry({
      // Options
      itemSelector: '.zone_recette',
      columnWidth: 300,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_recettes').addClass('masonry');
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
// Adaptations des recettes sur mobile
function adaptRecipes()
{
  if ($(window).width() < 1080)
  {
    $('.zone_semaines_left').css('display', 'block');
    $('.zone_semaines_left').css('width', '100%');

    $('.zone_semaines_right').css('display', 'block');
    $('.zone_semaines_right').css('width', '100%');
    $('.zone_semaines_right').css('margin-left', '0');
    $('.zone_semaines_right').css('margin-top', '10px');

    $('.zone_cooking_left').css('display', 'block');
    $('.zone_cooking_left').css('width', '100%');

    $('.zone_cooking_right').css('display', 'block');
    $('.zone_cooking_right').css('width', '100%');
    $('.zone_cooking_right').css('margin-left', '0');
    $('.zone_cooking_right').css('margin-top', '10px');
  }
  else
  {
    $('.zone_semaines_left').css('display', 'inline-block');
    $('.zone_semaines_left').css('width', 'calc(50% - 10px)');

    $('.zone_semaines_right').css('display', 'inline-block');
    $('.zone_semaines_right').css('width', 'calc(50% - 10px)');
    $('.zone_semaines_right').css('margin-left', '20px');
    $('.zone_semaines_right').css('margin-top', '0');

    $('.zone_cooking_left').css('display', 'inline-block');
    $('.zone_cooking_left').css('width', '200px');

    $('.zone_cooking_right').css('display', 'inline-block');
    $('.zone_cooking_right').css('width', 'calc(100% - 220px)');
    $('.zone_cooking_right').css('margin-left', '20px');
    $('.zone_cooking_right').css('margin-top', '10px');
  }
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerNoDelay(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Affiche ou masque un élément (délai 200ms)
function afficherMasquer(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Affiche une liste des utilisateurs
function afficherListboxUtilisateurs(id_zone, week)
{
  var html = '';

  if (id_zone == 'zone_current_week')
    html += '<form method="post" id="form_current_week" action="cookingbox.php?year=' + $_GET("year") + '&action=doModifier">';
  else
    html += '<form method="post" id="form_next_week" action="cookingbox.php?year=' + $_GET("year") + '&action=doModifier">';

    html += '<input type="hidden" name="week" value="' + week + '" />';

    // Listbox
    html += '<select name="select_user" class="listbox_users" required>';
      html += '<option value="" hidden>Choisissez...</option>';

      $.each(listUsers, function(key, value)
      {
        html += '<option value="' + key + '">' + value + '</option>';
      });
    html += '</select>';

    // Bouton validation
    html += '<input type="submit" name="submit_week" value="Valider" class="bouton_valider_week" />';

    // Bouton annulation
    if (id_zone == 'zone_current_week')
      html += '<a class="bouton_annuler_week cacherUtilisateursCurrent">Annuler</a>';
    else
      html += '<a class="bouton_annuler_week cacherUtilisateursNext">Annuler</a>';
  html += '</form>';

  $("#" + id_zone).append(html);
}

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event, id)
{
  var output   = document.getElementById(id);
  output.src   = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  EXIF.getData(event.target.files[0], function()
  {
    var orientation = EXIF.getTag(this, "Orientation");
    var degrees     = 0;

    // Les valeurs sont inversées par rapport à la fonction rotateImage() dans fonctions_communes.php
    switch (orientation)
    {
      case 3:
        degrees = 180;
        break;

      case 6:
        degrees = 90;
        break;

      case 8:
        degrees = -90;
        break;

      case 1:
      default:
        degrees = 0;
        break;
    }

    output.setAttribute('style','transform: rotate(' + degrees + 'deg)');
  });
};

// Affiche ou la zone de saisie semaine correspondant à l'année (insertion)
function afficherSemaines(select)
{
  var html     = '';
  var semaines = listWeeks[$('#' + select).val()];

  html += '<select name="week_recipe" id="saisie_semaine" class="saisie_annee_semaine" required>';
    html += '<option value="" hidden>Semaine</option>';

    $.each(semaines, function(key, value)
    {
      html += '<option value="' + value + '">' + value + '</option>';
    });
  html += '</select>';

  if ($('#saisie_semaine').length)
    $('#saisie_semaine').remove();

  $('#saisie_nom').css('width', 'calc(100% - 280px)');

  $('#' + select).after(html);
}

// Affiche un champ de saisie d'ingrédient
function addIngredient(id)
{
  var html          = '';
  var length        = $("#" + id + " .input_ingredient").length;
  var new_length    = length + 1;
  var id_ingredient = 'ingredient_' + new_length;
  var unites        = ['sans', 'g', 'kg', 'ml', 'cl', 'L', 'CC', 'CS'];

  html += '<div class="zone_ingredient">';
    // Ingrédient
    html += '<input type="text" placeholder="Ingrédient" value="" id="' + id_ingredient + '" name="ingredients[' + new_length + ']" class="input_ingredient saisieIngredient" />';

    // Quantité
    html += '<input type="text" placeholder="Quantité" value="" id="quantite_' + id_ingredient + '" name="quantites_ingredients[' + new_length + ']" class="input_quantite" />';

    // Unité
    html += '<select id="unite_' + id_ingredient + '" name="unites_ingredients[' + new_length + ']" class="select_unite">';
      html += '<option value="" hidden>Unité</option>';

      $.each(unites, function(key, value)
      {
        html += '<option value="' + value + '">' + value + '</option>';
      });
    html += '</select>';
  html += '</div>';

  $("#" + id).append(html);
}

// Affiche les détails de la recette
function showRecipe(link, id)
{
  var html             = '';
  var path_mini        = link.children('.image_recette').attr('src');
  var path_full        = path_mini.replace('mini/', '');
  var split            = path_mini.split('/');
  var image            = split[split.length - 1];
  var recipe           = listRecipes[id];
  var ingredientsSplit = new Array();
  var ingredientSplit  = new Array();
  var ingredients      = new Array();
  var avatarFormatted;

  html += '<div id="zoom_image" class="fond_zoom">';
    // Photo
    html += '<div class="zone_image_zoom">';
      html += '<img src="' + path_full + '" alt="' + image + '" class="image_zoom_2" />';
    html += '</div>';

    // Recette
    html += '<div class="zone_texte_zoom">';
      // Lien fermeture
      html += '<a id="fermerRecette" class="lien_zoom" style="display: none;"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';

      // Détails recette
      html += '<div class="texte_zoom" style="display: none;">';
        // Nom
        html += '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" /><div class="texte_titre_section">' + recipe['name'] + '</div></div>';

        // Avatar
        avatarFormatted = formatAvatar(recipe['avatar'], recipe['pseudo'], 2, "avatar");

        html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_details_recette" />';

        // Réalisateur
        html += '<div class="zone_pseudo_details">';
          html += 'Par <strong>' + formatUnknownUser(recipe['pseudo'], false, true) + '</strong>';
        html += '</div>';

        // Ingrédients
        if (recipe['ingredients'] != "")
        {
          ingredientsSplit = recipe['ingredients'].split(';');

          $.each(ingredientsSplit, function(key, value)
          {
            ingredientSplit = value.split('@');

            if (ingredientSplit[0] != "")
              ingredients.push({ingredient: ingredientSplit[0], quantity: ingredientSplit[1], unity: ingredientSplit[2]});
          });

          html += '<div class="zone_ingredients_details">';
            html += '<div class="titre_details_recette">Ingrédients</div>';

            $.each(ingredients, function(key, value)
            {
              html += '<div class="ingredient_details_recette">' + this['ingredient'] + '</div>';

              if (this['quantity'] != "")
              {
                if (this['unity'] == "CC" || this['unity'] == "CS")
                  html += '<div class="quantite_details_recette">' + this['quantity'] + " " + this['unity'] + '</div>';
                else
                  html += '<div class="quantite_details_recette">' + this['quantity'] + this['unity'] + '</div>';
              }
            });
          html += '</div>';
        }

        // Préparation
        if (recipe['recipe'] != "")
        {
          html += '<div class="zone_recette_details">';
            html += '<div class="titre_details_recette">Préparation</div>';
            html += '<div class="contenu_recette_details">' + nl2br(recipe['recipe']) + '</div>';
          html += '</div>';
        }

        // Remarques & astuces
        if (recipe['tips'] != "")
        {
          html += '<div class="zone_ingredients_details">';
            html += '<div class="titre_details_recette">Remarques & astuces</div>';
            html += '<div class="contenu_recette_details">' + nl2br(recipe['tips']) + '</div>';
          html += '</div>';
        }

        // Cas vide
        if (recipe['ingredients'] == "" && recipe['recipe'] == "" && recipe['tips'] == "")
          html += '<div class="empty">Pas de recette disponible</div>';

        // Bouton "Je l'ai fait"
        if (recipe['identifiant'] == userSession)
        {
          if (recipe['cooked'] == "Y")
          {
            html += '<form method="post" action="cookingbox.php?year=' + recipe['year'] + '&action=doAnnuler">';
              html += '<input type="hidden" name="week_cake" value="' + recipe['week'] + '" />';
              html += '<input type="submit" name="cancel_cake" value="Annuler" class="bouton_details" />';
            html += '</form>';
          }
          else
          {
            html += '<form method="post" action="cookingbox.php?year=' + recipe['year'] + '&action=doValider">';
              html += '<input type="hidden" name="week_cake" value="' + recipe['week'] + '" />';
              html += '<input type="submit" name="validate_cake" value="Je l\'ai fait" class="bouton_details" />';
            html += '</form>';
          }
        }
      html += '</div>';
    html += '</div>';
  html += '</div>';

  $('body').append(html);

  // Affichage des différentes zones en fondu
  $('.image_zoom_2').on('load', function()
  {
    tailleAutoRecette(500);
  });
}

// Ferme les détails d'une recette
function closeRecipe()
{
  $('#zoom_image').fadeOut(200, function()
  {
    $('#zoom_image').remove();
  });
}

// Ferme la saisie d'une recette
function closeInput()
{
  resetSaisie('zone_add_recipe');
}

// Affiche la zone de mise à jour d'une recette
function updateRecipe(id, zone)
{
  // Affichage zone de saisie
  afficherMasquer(zone);

  var image       = listRecipes[id]['picture'];
  var annee       = listRecipes[id]['year'];
  var semaine     = listRecipes[id]['week'];
  var nom         = listRecipes[id]['name'];
  var ingredients = listRecipes[id]['ingredients'].split(';');
  var preparation = listRecipes[id]['recipe'];
  var remarques   = listRecipes[id]['tips'];
  var titre       = 'Modifier la recette de la semaine ' + semaine + ' (' + annee + ')';
  var action      = 'cookingbox.php?year=' + annee + '&action=doModifierRecette';

  // Modification des données
  generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, "mod");
}

// Réinitialise la zone de saisie d'une recette si fermeture modification
function resetSaisie(zone)
{
  // Fermeture zone de saisie
  afficherMasquer(zone);

  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie_recette').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == "doModifierRecette")
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

      generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, "reset");
    }
  }, 200);
}

// Génère la zone de saisie recette
function generateSaisie(id, image, annee, semaine, nom, ingredients, preparation, remarques, titre, action, mode)
{
  var ingredient;
  var numIngredient;

  // Modification des données générales
  $('input[name=id_recipe]').val(id);
  $('.titre_saisie_recette').html(titre);
  $('.form_saisie_recette').attr('action', action);
  $('#saisie_nom').val(nom);
  $('#saisie_preparation').val(preparation);
  $('#saisie_remarques').val(remarques);

  // Modification des données spécifiques
  if (mode == "mod")
  {
    $('#saisie_annee').css('display', 'none');
    $('#saisie_annee').prop('required', false);

    $('#saisie_semaine').css('display', 'none');
    $('#saisie_semaine').prop('required', false);

    $('.zone_recette_right').prepend('<input type="hidden" id="hidden_week_recipe" name="hidden_week_recipe" value="' + semaine + '" />');
    $('.zone_recette_right').prepend('<input type="hidden" id="hidden_year_recipe" name="hidden_year_recipe" value="' + annee + '" />');

    $('#saisie_nom').css('width', 'calc(100% - 20px)');

    $('#image_recette').attr('src', '../../includes/images/cookingbox/' + annee + '/mini/' + image);
    $('input[name=image]').prop('required', false);
    $('input[name=insert_recipe]').val("Modifier");
  }
  else
  {
    $('#saisie_annee').css('display', 'inline-block');
    $('#saisie_annee').prop('required', true);

    $('#hidden_week_recipe').remove();
    $('#hidden_year_recipe').remove();

    $('#saisie_nom').css('width', 'calc(100% - 150px)');

    $('#image_recette').removeAttr('src');
    $('input[name=image]').prop('required', true);
    $('input[name=insert_recipe]').val("Ajouter");
  }

  // Modification des données ingrédients
  $('.zone_ingredient').remove();
  addIngredient('zone_ingredients');
  addIngredient('zone_ingredients');
  addIngredient('zone_ingredients');
  addIngredient('zone_ingredients');

  $.each(ingredients, function(key, value)
  {
    ingredient = '';

    if (value != "")
    {
      ingredient    = value.split('@');
      numIngredient = key + 1;

      if (numIngredient > 4)
        addIngredient('zone_ingredients');

      $('#ingredient_' + numIngredient).val(ingredient[0]);
      $('#quantite_ingredient_' + numIngredient).val(ingredient[1]);

      $('#ingredient_' + numIngredient).addClass('filled');

      if (ingredient[2] == "")
        $('#unite_ingredient_' + numIngredient).val("sans");
      else
        $('#unite_ingredient_' + numIngredient).val(ingredient[2]);
    }
  });
}

// Taille automatique zone recette + fondu
function tailleAutoRecette(speed)
{
  // Apparition de la zone
  $('#zoom_image').fadeIn(200, function()
  {
    // Réglage de la hauteur
    $('.zone_texte_zoom').animate(
    {
      height: $('.image_zoom_2').height()
    }, speed, function()
    {
      // Apparition du texte et de la croix
      $('.texte_zoom').fadeIn(200);
      $('.lien_zoom').fadeIn(200);
    });
  });
}

// Change la couleur de fond lors de la saisie de texte
function changeIngredientColor(id)
{
  if ($('#' + id).val() != "")
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
