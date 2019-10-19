/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  // Affiche la liste des utilisateurs (semaine courante)
  $('.afficherUtilisateursCurrent').click(function()
  {
    var id_boutons = 'boutons_current_week';

    afficherMasquerNoDelay(id_boutons);
    afficherListboxUtilisateurs('zone_current_week');
  });

  // Affiche la liste des utilisateurs (semaine suivante)
  $('.afficherUtilisateursNext').click(function()
  {
    var id_boutons = 'boutons_next_week';

    afficherMasquerNoDelay(id_boutons);
    afficherListboxUtilisateurs('zone_next_week');
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
function afficherListboxUtilisateurs(id_zone)
{
  var full_date = new Date();
  var week      = full_date.getWeek();
  var html;

  if (id_zone == 'zone_current_week')
  {
    html = '<form method="post" id="form_current_week" action="cookingbox.php?action=doModifier">';
      html += '<input type="hidden" name="week" value="' + week + '" />';
  }
  else
  {
    html = '<form method="post" id="form_next_week" action="cookingbox.php?action=doModifier">';
      html += '<input type="hidden" name="week" value="' + (week + 1) + '" />';
  }
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

// Retourne le numéro de semaine
Date.prototype.getWeek = function()
{
  var oneJan     = new Date(this.getFullYear(), 0, 1);
  var today      = new Date(this.getFullYear(), this.getMonth(), this.getDate());
  var dayOfYear  = ((today - oneJan + 1) / 86400000);
  var weekOfYear = Math.ceil(dayOfYear / 7);

  return weekOfYear;
};
