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
    afficherMasquerIdWithDelay('zone_saisie_annee');
  });

  // Ouvre la zone de saisie d'une dépense
  $('#afficherSaisieDepense').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_depense');
  });

  // Ferme la zone de saisie d'une dépense
  $('#fermerSaisieDepense').click(function()
  {
    // Réinitialisation de la saisie
    resetSaisie('zone_saisie_depense', $_GET('year'));

    // Fermeture de l'affichage
    afficherMasquerIdWithDelay('zone_saisie_depense');
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
    afficherMasquerIdWithDelay('zone_details_depense');
  });

  // Ajoute une part
  $('.ajouterPart').click(function()
  {
    var idUser = $(this).attr('id').replace('ajouter_part_', '');

    ajouterPart('zone_user_' + idUser, 'quantite_user_' + idUser, 1);
  });

  // Retire une part
  $('.retirerPart').click(function()
  {
    var idUser = $(this).attr('id').replace('retirer_part_', '');

    ajouterPart('zone_user_' + idUser, 'quantite_user_' + idUser, -1);
  });

  // Ouvre la fenêtre de saisie d'une dépenseen modification
  $('.modifierDepense').click(function()
  {
    var idDepense = $(this).attr('id').replace('modifier_depense_', '');

    initialisationModification(idDepense, $_GET('year'));
  });

  // Réinitialise la saisie à la fermeture au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'une dépense
    if ($(event.target).attr('class') == 'fond_saisie')
      resetSaisie('zone_saisie_depense', $_GET('year'));
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

  // Lien modification
  $('.zone_details_actions > .lien_modifier_depense').attr('id', 'modifier_depense_' + listExpenses[id]['id'])

  // Formulaire suppression
  $('.zone_details_actions > .form_supprimer_depense').attr('id', 'delete_depense_' + listExpenses[id]['id']);
  $('.form_supprimer_depense > input[name=id_expense]').val(listExpenses[id]['id']);
  $('.form_supprimer_depense > .eventMessage').val('Supprimer la dépense de ' + listExpenses[id]['pseudo'] + ' du ' + formatDateForDisplay(listExpenses[id]['date']) + ' et d\'un montant de ' + formatAmountForDisplay(listExpenses[id]['price']) + ' ?');

  // Affichage des détails
  afficherMasquerIdWithDelay('zone_details_depense');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, true);
  });
}

// Affiche la zone de mise à jour d'une dépense
function initialisationModification(idDepense, year)
{
  // Action du formulaire
  var action = 'expensecenter.php?year=' + year + '&action=doModifier';

  // Date du jour
  var date = formatDateForDisplay(listExpenses[idDepense]['date']);

  // Titre
  var titre = 'Modifier la dépense';

  // Sous-titre
  var sousTitre = 'Dépense du ' + date;

  // Acheteur
  var buyer = listExpenses[idDepense]['buyer'];

  // Prix
  var price = listExpenses[idDepense]['price'];

  // Commentaire
  var comment = listExpenses[idDepense]['comment'];

  // Modification des données
  $('.form_saisie').attr('action', action);
  $('.zone_titre_saisie').html(titre);
  $('.titre_section > .texte_titre_section:first').html(sousTitre);
  $('input[name=id_expense]').val(idDepense);
  $('.saisie_acheteur').val(buyer);
  $('.saisie_prix').val(price);
  $('.saisie_commentaire').html(comment);

  $('.zone_saisie_part').each(function()
  {
    // Initialisation de la quantité
    $(this).children('.quantite').val('0');

    // Vérification présence identifiant dans les parts
    var idZone           = $(this).attr('id');
    var idQuantite       = $(this).children('.quantite').attr('id');
    var identifiantLigne = $(this).find('input[type=hidden]').val();
    var partUtilisateur  = listExpenses[idDepense]['parts'][identifiantLigne];
    var nombrePartsUtilisateur;

    // Récupération du nombre de parts
    if (partUtilisateur != null)
      nombrePartsUtilisateur = parseInt(partUtilisateur['parts']);
    else
      nombrePartsUtilisateur = 0;

    // Ajout de la part à la zone
    ajouterPart(idZone, idQuantite, nombrePartsUtilisateur);
  });

  // Masque la zone de détails
  afficherMasquerIdWithDelay('zone_details_depense');

  // Affiche la zone de saisie
  afficherMasquerIdWithDelay('zone_saisie_depense');
}

// Réinitialise la zone de saisie d'une dépense si fermeture modification
function resetSaisie(zone, year)
{
  // Déclenchement après la fermeture
  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == "doModifier")
    {
      // Action du formulaire
      var action = 'expensecenter.php?year=' + year + '&action=doInserer';

      // Titre
      var titre = 'Saisir une dépense';

      // Sous-titre
      var sousTitre = 'La dépense';

      // Acheteur
      var buyer = '';

      // Prix
      var price = '';

      // Commentaire
      var comment = '';

      // Modification des données
      $('.form_saisie').attr('action', action);
      $('.zone_titre_saisie').html(titre);
      $('.titre_section > .texte_titre_section:first').html(sousTitre);
      $('input[name=id_expense]').val('');
      $('.saisie_acheteur').val(buyer);
      $('.saisie_prix').val(price);
      $('.saisie_commentaire').html(comment);

      $('.zone_saisie_part').each(function()
      {
        // Initialisation de la quantité
        $(this).children('.quantite').val('0');

        // Ajout de la part à la zone
        var idZone                 = $(this).attr('id');
        var idQuantite             = $(this).children('.quantite').attr('id');
        var nombrePartsUtilisateur = 0;

        ajouterPart(idZone, idQuantite, nombrePartsUtilisateur);
      });
    }
  }, 200);
}
