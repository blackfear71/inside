/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie d'année
  $('#afficherSaisieAnnee, #fermerSaisieAnnee').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisieAnnee');
  });

  // Ouvre ou ferme la zone de saisie d'une dépense
  $('#afficherSaisieDepense, #fermerSaisieDepense').click(function()
  {
    afficherMasquerIdWithDelay('zoneSaisieDepense');
  });

  // Affiche la zone de détails d'une dépense
  $('.afficherDetailsDepense').click(function()
  {
    var idDepense = $(this).attr('id').replace('details_depense_', '');

    showDetails(idDepense);
  });

  // Ferme la zone de détails d'une dépense
  $('#fermerDetailsDepense').click(function()
  {
    afficherMasquerIdWithDelay('zoneDetailsDepense');
  });

  // Ajoute une part
  $('.ajouterPart').click(function()
  {
    var id_user = $(this).attr('id').replace('ajouter_part_', '');

    ajouterPart('zone_user_' + id_user, 'quantite_user_' + id_user, 1);
  });

  // Retire une part
  $('.retirerPart').click(function()
  {
    var id_user = $(this).attr('id').replace('retirer_part_', '');

    ajouterPart('zone_user_' + id_user, 'quantite_user_' + id_user, -1);
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Ajoute une part à un utilisateur
function ajouterPart(zone, quantite, value)
{
  var currentValue = parseInt($('#' + quantite).val());
  var newValue     = currentValue + value;

  // Colorise en fonction de la valeur
  if (newValue > 0)
  {
    $('#' + zone).css('background-color', '#ff1937');

    $('#' + quantite).css('background-color', '#ff1937');
    $('#' + quantite).css('color', 'white');
  }
  else
  {
    $('#' + zone).css('background-color', '#e3e3e3');

    $('#' + quantite).css('background-color', '#e3e3e3');
    $('#' + quantite).css('color', '#262626');
  }

  // Incrit la valeur dans la zone
  if (newValue >= 0 && newValue <= 5)
    $('#' + quantite).val(newValue);
}

// Affiche la zone de mise à jour d'une dépense
function showDetails(id)
{
  // Récupération des données
  var date           = formatDateForDisplay(listExpenses[id]['date']);
  var prix           = formatAmountForDisplay(listExpenses[id]['price']);
  var avatarAcheteur = formatAvatar(listExpenses[id]['avatar'], listExpenses[id]['pseudo'], 2, 'avatar');
  var pseudoAcheteur = formatString(formatUnknownUser(listExpenses[id]['pseudo'], true, false), 10);
  var commentaires   = listExpenses[id]['comment'];
  var parts          = listExpenses[id]['parts'];

  // Date
  $('.titre_details > .texte_titre_section').html('Dépense du ' + date);

  // Prix
  $('.zone_details_prix').html(prix);

  // Avatar acheteur
  $('.details_avatar_acheteur').attr('src', avatarAcheteur.path);
  $('.details_avatar_acheteur').attr('alt', avatarAcheteur.alt);
  $('.details_avatar_acheteur').attr('title', avatarAcheteur.title);

  // Pseudo acheteur
  $('.details_pseudo_acheteur').html(pseudoAcheteur);

  // Commentaires
  if (commentaires.length != 0)
    $('.details_commentaires').html(commentaires);
  else
    $('.details_commentaires').html('Pas de commentaire.');

  // Répartition
  if (parts.length == 0)
  {
    // Affichage des zones
    $('.zone_details_parts').css('display', 'none');
    $('.details_regularisation').css('display', 'block');
  }
  else
  {
    // Affichage des zones
    $('.zone_details_parts').css('display', 'block');
    $('.details_regularisation').css('display', 'none');

    // Affichage de la répartition
    $('.zone_details_repartition').html('');

    $.each(parts, function()
    {
      // Génération de l'élément
      var partUtilisateur   = '';
      var avatarUtilisateur = formatAvatar(this.avatar, this.pseudo, 2, 'avatar');
      var pseudoUtilisateur = formatString(formatUnknownUser(this.pseudo, true, false), 10);

      partUtilisateur += '<div class="zone_details_utilisateur">';
        partUtilisateur += '<img src="' + avatarUtilisateur.path + '" alt="' + avatarUtilisateur.alt + '" title="' + avatarUtilisateur.title + '" class="details_avatar_utilisateur" />';
        partUtilisateur += '<div class="nombre_parts_user">' + this.parts + '</div>';
        partUtilisateur += '<div class="details_pseudo_utilisateur">' + pseudoUtilisateur + '</div>';
      partUtilisateur += '</div>';

      // Ajout à la zone
      $('.zone_details_repartition').append(partUtilisateur);
    });
  }

  // Affichage des détails
  afficherMasquerIdWithDelay('zoneDetailsDepense');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, true);
  });
}
