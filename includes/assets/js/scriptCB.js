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

    afficherMasquerNoDelay(id_boutons);
    afficherListboxUtilisateurs('zone_current_week', week_number);
  });

  // Affiche la liste des utilisateurs (semaine suivante)
  $('.afficherUtilisateursNext').click(function()
  {
    var id_boutons  = 'boutons_next_week';
    var week_number = parseInt($(this).attr('id').replace('choix_semaine_suivante_', ''));

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

  // Affiche une recette en grand
  $('.afficherRecette').click(function()
  {
    var id_recette = $(this).attr('id').replace('afficher_recette_', '');

    showRecipe($(this), id_recette);
  });

  // Ferme le zoom d'une recette
  $(document).on('click', '#fermerRecette', function()
  {
    $('#zoom_image').fadeOut(200, function()
    {
      $('#zoom_image').remove();
    });
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Affichage des différentes zones en fondu
  tailleAutoRecette(0)
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
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
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque un élément (délai 0s)
function afficherMasquerNoDelay(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Affiche une liste des utilisateurs
function afficherListboxUtilisateurs(id_zone, week)
{
  var html;

  if (id_zone == 'zone_current_week')
    html = '<form method="post" id="form_current_week" action="cookingbox.php?year=' + $_GET("year") + '&action=doModifier">';
  else
    html = '<form method="post" id="form_next_week" action="cookingbox.php?year=' + $_GET("year") + '&action=doModifier">';

    html += '<input type="hidden" name="week" value="' + week + '" />';

    // Listbox
    html += '<select name="select_user" class="listbox_users" required>';
      html += '<option value="" hidden>Choisissez...</option>';

      $.each(listeUsers, function(key, value)
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

// Affiche les détails de la recette
function showRecipe(link, id)
{
  var html;

  var path_mini        = link.children('.image_recette').attr('src');
  var path_full        = path_mini.replace('mini/', '');
  var split            = path_mini.split('/');
  var image            = split[split.length - 1];
  var recipe           = listeRecipes[id];
  var ingredientsSplit = new Array();
  var ingredientSplit  = new Array();
  var ingredients      = new Array();

  html = '<div id="zoom_image" class="fond_zoom">';
    // Photo
    html += '<div class="zone_image_zoom">';
      html += '<img src="' + path_full + '" alt="' + image + '" class="image_zoom_2" />';
    html += '</div>';

    // Recette
    html += '<div class="zone_texte_zoom">';
      // Lien fermeture
      html += '<a id="fermerRecette" class="lien_zoom" style="display: none;"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_zoom" /></a>';

      // Détails recette
      html += '<div class="texte_zoom">';
        // Nom
        html += '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" />' + recipe['name'] + '</div>';

        // Avatar
        if (recipe['avatar'] != "")
          html += '<img src="../../includes/images/profil/avatars/' + recipe['avatar'] + '" alt="avatar" title="' + recipe['pseudo'] + '" class="avatar_details_recette" />';
        else
          html += '<img src="../../includes/icons/common/default.png" alt="avatar" title="' + recipe['pseudo'] + '" class="avatar_details_recette" />';

        // Réalisateur
        html += '<div class="zone_pseudo_details">';
          html += 'Par <strong>' + recipe['pseudo'] + '</strong>';
        html += '</div>';

        // Ingrédients
        if (recipe['ingredients'] != "")
        {
          ingredientsSplit = recipe['ingredients'].split(';');

          $.each(ingredientsSplit, function(key, value)
          {
            ingredientSplit = value.split('@');

            if (ingredientSplit[0] != "" && ingredientSplit[1] != "")
              ingredients.push({ingredient: ingredientSplit[0], quantity: ingredientSplit[1]});
          });

          html += '<div class="zone_ingredients_details">';
            html += '<div class="titre_details_recette">Ingrédients</div>';

            $.each(ingredients, function(key, value)
            {
              html += '<div class="ingredient_details_recette">' + this['ingredient'] + '</div>';
              html += '<div class="quantite_details_recette">' + this['quantity'] + '</div>';
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
      html += '</div>';
    html += '</div>';
  html += '</div>';

  $('body').append(html);

  // Affichage des différentes zones en fondu
  tailleAutoRecette(500)
}

// Taille automatique zone recette + fondu
function tailleAutoRecette(speed)
{
  $('#zoom_image').fadeIn(200, function()
  {
    $('.zone_texte_zoom').animate(
    {
      height: $('.image_zoom_2').height()
    }, speed, function()
    {
      $('.lien_zoom').fadeIn(200);
    });
  });
}

// Prend en compte les sauts de ligne
function nl2br (str)
{
  var nl2br = str.replace(/(\r\n|\n\r|\r|\n)/g, '<br />');

  return nl2br;
}
