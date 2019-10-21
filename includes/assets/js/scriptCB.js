/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
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
