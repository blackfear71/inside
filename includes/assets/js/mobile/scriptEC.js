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

  // Ouvre ou ferme la zone de saisie de filtre
  $('#afficherSaisieFiltre, #fermerSaisieFiltre').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_filtre');
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
    resetSaisie('zone_saisie_depense', $_GET('year'), $_GET('filter'), 'P');

    // Fermeture de l'affichage
    afficherMasquerIdWithDelay('zone_saisie_depense');
  });

  // Ouvre la zone de saisie de montants
  $('#afficherSaisieMontants').click(function()
  {
    afficherMasquerIdWithDelay('zone_saisie_montants');
  });

  // Ferme la zone de saisie de montants
  $('#fermerSaisieMontants').click(function()
  {
    // Réinitialisation de la saisie
    resetSaisie('zone_saisie_montants', $_GET('year'), $_GET('filter'), 'M');

    // Fermeture de l'affichage
    afficherMasquerIdWithDelay('zone_saisie_montants');
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

  // Ouvre la fenêtre de saisie d'une dépense en modification
  $('.modifierDepense').click(function()
  {
    var idDepense = $(this).attr('id').replace('modifier_depense_', '');

    initialisationModification(idDepense, $_GET('year'), $_GET('filter'));
  });

  // Réinitialise la saisie à la fermeture au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'une dépense
    if ($(event.target).attr('class') == 'fond_saisie')
    {
      switch (event.target.id)
      {
        case 'zone_saisie_depense':
          resetSaisie('zone_saisie_depense', $_GET('year'), $_GET('filter'), 'P');
          break;

        case 'zone_saisie_montants':
          resetSaisie('zone_saisie_montants', $_GET('year'), $_GET('filter'), 'M');
          break;

        default:
          break;
      }
    }
  });

  // Bloque la saisie en cas de soumission (dépense)
  $('#validerSaisieDepense').click(function()
  {
    var idForm          = $('#zone_saisie_depense');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });

  // Bloque la saisie en cas de soumission (montants)
  $('#validerSaisieMontants').click(function()
  {
    var idForm          = $('#zone_saisie_montants');
    var zoneForm        = 'zone_contenu_saisie';
    var zoneContenuForm = 'contenu_saisie';

    blockValidationSubmission(idForm, zoneForm, zoneContenuForm);
  });

  /*** Actions à la saisie ***/
  // Colorise un montant
  $('.montant').keyup(function()
  {
    var idUser = $(this).attr('id').replace('montant_user_', '');

    ajouterMontant('zone_user_montant_' + idUser, 'montant_user_' + idUser, $(this).val());
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
    $('#' + zone).find('.pseudo_saisie_depense').css('color', 'white');

    $('#' + quantite).css('background-color', '#ff1937');
    $('#' + quantite).css('color', 'white');
  }
  else
  {
    $('#' + zone).css('background-color', '#e3e3e3');
    $('#' + zone).find('.pseudo_saisie_depense').css('color', '#262626');

    $('#' + quantite).css('background-color', '#e3e3e3');
    $('#' + quantite).css('color', '#262626');
  }

  // Incrit la valeur dans la zone
  if (newValue >= 0 && newValue <= 5)
    $('#' + quantite).val(newValue);
}

// Ajoute un montant à un utilisateur
function ajouterMontant(zone, montant, value)
{
  if (value != '')
  {
    $('#' + zone).css('background-color', '#ff1937');
    $('#' + zone).css('color', 'white');

    $('#' + montant).val(value);
  }
  else
  {
    $('#' + zone).css('background-color', '#e3e3e3');
    $('#' + zone).css('color', '#262626');

    $('#' + montant).val('');
  }
}

// Affiche la zone de détails d'une dépense
function showDetails(idDepense)
{
  // Récupération des données
  var depense        = listeDepenses[idDepense];
  var date           = formatDateForDisplay(depense['date']);
  var prix           = formatAmountForDisplay(depense['price'], true);
  var avatarAcheteur = formatAvatar(depense['avatar'], depense['pseudo'], 2, 'avatar');
  var pseudoAcheteur = formatString(formatUnknownUser(depense['pseudo'], true, false), 10);
  var commentaires   = depense['comment'];
  var frais          = formatAmountForDisplay(depense['frais'], true);
  var type           = depense['type'];
  var parts          = depense['parts'];

  // Icône
  if (type == 'M')
  {
    $('.titre_details > .logo_titre_section').attr('src', '../../includes/icons/expensecenter/expenses_grey.png');
    $('.titre_details > .logo_titre_section').attr('alt', 'expenses_grey');
  }
  else
  {
    $('.titre_details > .logo_titre_section').attr('src', '../../includes/icons/expensecenter/expense_center_grey.png');
    $('.titre_details > .logo_titre_section').attr('alt', 'expense_center_grey');
  }

  // Date
  if (parts.length == 0)
    $('.titre_details > .texte_titre_section').html('Régularisation du ' + date);
  else
    $('.titre_details > .texte_titre_section').html('Dépense du ' + date);

  // Prix
  $('.zone_details_prix').html(prix);

  // Avatar acheteur
  $('.details_avatar_acheteur').attr('src', avatarAcheteur.path);
  $('.details_avatar_acheteur').attr('alt', avatarAcheteur.alt);
  $('.details_avatar_acheteur').attr('title', avatarAcheteur.title);

  // Pseudo acheteur
  $('.details_pseudo_acheteur').html(pseudoAcheteur);

  // Frais additionnels
  if (type == 'M' && frais != '')
  {
    $('.details_frais').css('display', 'block');
    $('.details_frais').html(frais + ' de frais');
  }
  else
  {
    $('.details_frais').css('display', 'none');
    $('.details_frais').html('');
  }

  // Commentaires
  if (commentaires.length != 0)
    $('.details_commentaires').html(nl2br(commentaires));
  else
    $('.details_commentaires').html('Pas de commentaire');

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

      if (type == 'M')
      {
        partUtilisateur += '<div class="zone_details_utilisateur">';
          partUtilisateur += '<img src="' + avatarUtilisateur.path + '" alt="' + avatarUtilisateur.alt + '" title="' + avatarUtilisateur.title + '" class="details_avatar_utilisateur" />';
          partUtilisateur += '<div class="montant_user">' + formatAmountForDisplay(this.parts, true) + '</div>';
          partUtilisateur += '<div class="details_pseudo_utilisateur">' + pseudoUtilisateur + '</div>';
        partUtilisateur += '</div>';
      }
      else
      {
        partUtilisateur += '<div class="zone_details_utilisateur">';
          partUtilisateur += '<img src="' + avatarUtilisateur.path + '" alt="' + avatarUtilisateur.alt + '" title="' + avatarUtilisateur.title + '" class="details_avatar_utilisateur" />';
          partUtilisateur += '<div class="nombre_parts_user">' + this.parts + '</div>';
          partUtilisateur += '<div class="details_pseudo_utilisateur">' + pseudoUtilisateur + '</div>';
        partUtilisateur += '</div>';
      }

      // Ajout à la zone
      $('.zone_details_repartition').append(partUtilisateur);
    });
  }

  // Lien modification
  if (depense['pseudo'] != '')
  {
    $('.lien_modifier_depense').css('display', 'inline-block');
    $('.form_supprimer_depense').css('margin-left', '1vh');
    $('.form_supprimer_depense').css('width', 'calc(45% - 1vh)');
    $('.zone_details_actions > .lien_modifier_depense').attr('id', 'modifier_depense_' + depense['id']);
  }
  else
  {
    $('.lien_modifier_depense').css('display', 'none');
    $('.form_supprimer_depense').css('margin-left', '0');
    $('.form_supprimer_depense').css('width', '90%');
    $('.zone_details_actions > .lien_modifier_depense').attr('id', '');
  }

  // Formulaire suppression
  $('.zone_details_actions > .form_supprimer_depense').attr('id', 'delete_depense_' + depense['id']);
  $('.form_supprimer_depense > input[name=id_expense_delete]').val(depense['id']);
  $('.form_supprimer_depense > .eventMessage').val('Supprimer la dépense de ' + depense['pseudo'] + ' du ' + formatDateForDisplay(depense['date']) + ' et d\'un montant de ' + formatAmountForDisplay(depense['price'], true) + ' ?');

  if (type == 'M')
    $('.form_supprimer_depense').attr('action', 'expensecenter.php?year=' + $_GET('year') + '&filter=' + $_GET('filter') + '&action=doSupprimerMontants');
  else
    $('.form_supprimer_depense').attr('action', 'expensecenter.php?year=' + $_GET('year') + '&filter=' + $_GET('filter') + '&action=doSupprimer');

  // Affichage des détails
  afficherMasquerIdWithDelay('zone_details_depense');

  // Déplie tous les titres
  $('.div_details').find('.titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, 'open');
  });
}

// Affiche la zone de mise à jour d'une dépense
function initialisationModification(idDepense, year, filter)
{
  // Récupération des données
  var depense = listeDepenses[idDepense];
  var parts   = depense['parts'];
  var type    = depense['type'];
  var price;
  var action;
  var titre;
  var sousTitre;

  if (type == 'M')
  {
    // Action du formulaire
    action = 'expensecenter.php?year=' + year + '&filter=' + filter + '&action=doModifierMontantsMobile';

    // Titre
    titre = 'Modifier des montants';

    // Réduction
    var reduction = '';
  }
  else
  {
    // Action du formulaire
    action = 'expensecenter.php?year=' + year + '&filter=' + filter + '&action=doModifierMobile';

    // Titre
    titre = 'Modifier la dépense';
  }

  // Sous-titre
  if (parts.length == 0)
    sousTitre = 'Régularisation du ' + formatDateForDisplay(depense['date']);
  else
    sousTitre = 'Dépense du ' + formatDateForDisplay(depense['date']);

  // Acheteur
  var buyer = depense['buyer'];

  // Prix ou frais
  if (type == 'M')
    price = formatAmountForDisplay(depense['frais'], false);
  else
    price = formatAmountForDisplay(depense['price'], false);

  // Date
  var date = formatDateForDisplayMobile(depense['date']);

  // Commentaire
  var comment = depense['comment'];

  // Modification des données
  $('.form_saisie').attr('action', action);
  $('.zone_titre_saisie').html(titre);
  $('.titre_section > .texte_titre_section:first').html(sousTitre);
  $('input[name=id_expense_saisie]').val(idDepense);
  $('.saisie_acheteur').val(buyer);
  $('.saisie_prix').val(price);
  $('.saisie_date_depense').val(date);
  $('.saisie_commentaire').html(comment);

  if (type == 'M')
  {
    // Réduction
    $('.saisie_reduction').val(reduction);

    // Alimentation des zones utilisateurs inscrits
    $('#zone_saisie_montants').find('.zone_saisie_utilisateur').each(function()
    {
      // Initialisation du montant
      $(this).find('.montant').val('');

      // Vérification présence identifiant dans les parts
      var idZone           = $(this).attr('id');
      var idMontant        = $(this).find('.montant').attr('id');
      var identifiantLigne = $(this).find('input[type=hidden]').val();
      var partUtilisateur  = parts[identifiantLigne];
      var montantUtilisateur;

      // Récupération du nombre de parts
      if (partUtilisateur != null)
        montantUtilisateur = formatAmountForDisplay(partUtilisateur['parts']);
      else
        montantUtilisateur = '';

      // Ajout du montant à la zone
      ajouterMontant(idZone, idMontant, montantUtilisateur);
    });

    // Affichage des utilisateurs désinscrits
    var listeMontantsDes = '';

    $.each(parts, function(identifiant, user)
    {
      if (user.inscrit == false)
      {
        var montantDes = '';

        // Zone utilisateur
        montantDes += '<div class="zone_saisie_utilisateur part_selected">';
          // Utilisateur
          var avatarFormatted = formatAvatar(user.avatar, user.pseudo, 2, 'avatar');

          montantDes += '<div class="zone_saisie_utilisateur_avatar">';
            // Avatar
            montantDes += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_saisie_depense" />';

            // Pseudo
            montantDes += '<div class="pseudo_saisie_depense">' + formatString(formatUnknownUser(user.pseudo, true, false), 8) + '</div>';
          montantDes += '</div>';

          // Identifiant (caché)
          montantDes += '<input type="hidden" name="identifiant_montant[]" value="' + identifiant + '" />';

          // Montant
          montantDes += '<div class="zone_saisie_montant">';
            montantDes += '<input type="text" name="montant_user[]" maxlength="6" value="' + formatAmountForDisplay(user.parts) + '" class="montant_des" />';
            montantDes += '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro_saisie" />';
          montantDes += '</div>';
        montantDes += '</div>';

        listeMontantsDes += montantDes;
      }
    });

    $('#zone_saisie_montants').find('.zone_saisie_utilisateurs').append(listeMontantsDes);
  }
  else
  {
    // Alimentation des zones utilisateurs inscrits
    $('#zone_saisie_depense').find('.zone_saisie_utilisateur').each(function()
    {
      // Initialisation de la quantité
      $(this).find('.quantite').val('0');

      // Vérification présence identifiant dans les parts
      var idZone           = $(this).attr('id');
      var idQuantite       = $(this).find('.quantite').attr('id');
      var identifiantLigne = $(this).find('input[type=hidden]').val();
      var partUtilisateur  = parts[identifiantLigne];
      var nombrePartsUtilisateur;

      // Récupération du nombre de parts
      if (partUtilisateur != null)
        nombrePartsUtilisateur = parseInt(partUtilisateur['parts']);
      else
        nombrePartsUtilisateur = 0;

      // Ajout de la part à la zone
      ajouterPart(idZone, idQuantite, nombrePartsUtilisateur);
    });

    // Affichage des utilisateurs désinscrits
    var listePartsDes = '';

    $.each(parts, function(identifiant, user)
    {
      if (user.inscrit == false)
      {
        var partsDes = '';

        // Zone utilisateur
        partsDes += '<div class="zone_saisie_utilisateur part_selected">';
          // Utilisateur
          var avatarFormatted = formatAvatar(user.avatar, user.pseudo, 2, 'avatar');

          partsDes += '<div class="zone_saisie_utilisateur_avatar">';
            // Avatar
            partsDes += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_saisie_depense" />';

            // Pseudo
            partsDes += '<div class="pseudo_saisie_depense">' + formatString(formatUnknownUser(user.pseudo, true, false), 8) + '</div>';
          partsDes += '</div>';

          // Identifiant (caché)
          partsDes += '<input type="hidden" name="identifiant_quantite[]" value="' + identifiant + '" />';

          // Parts
          partsDes += '<input type="text" name="quantite_user[]" value="' + user.parts + '" class="quantite_des part_selected" readonly />';
        partsDes += '</div>';

        listePartsDes += partsDes;
      }
    });

    $('#zone_saisie_depense').find('.zone_saisie_utilisateurs').append(listePartsDes);
  }

  // Masque la zone de détails
  afficherMasquerIdWithDelay('zone_details_depense');

  // Affiche la zone de saisie
  if (type == 'M')
    afficherMasquerIdWithDelay('zone_saisie_montants');
  else
    afficherMasquerIdWithDelay('zone_saisie_depense');
}

// Réinitialise la zone de saisie d'une dépense si fermeture modification
function resetSaisie(zone, year, filter, type)
{
  // Déclenchement après la fermeture
  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == 'doModifierMobile' || call == 'doModifierMontantsMobile')
    {
      var action;
      var titre;

      if (type == 'M')
      {
        // Action du formulaire
        action = 'expensecenter.php?year=' + year + '&filter=' + filter + '&action=doInsererMontantsMobile';

        // Titre
        titre = 'Saisir des montants';

        // Réduction
        var reduction = '';
      }
      else
      {
        // Action du formulaire
        action = 'expensecenter.php?year=' + year + '&filter=' + filter + '&action=doInsererMobile';

        // Titre
        titre = 'Saisir une dépense';
      }

      // Sous-titre
      var sousTitre = 'La dépense';

      // Acheteur
      var buyer = '';

      // Prix ou frais
      var price = '';

      // Date
      var dateDuJour = new Date();
      var moisFull;
      var jourFull;

      if ((dateDuJour.getMonth() + 1) < 10)
        moisFull = '0' + dateDuJour.getMonth() + 1;
      else
        moisFull = dateDuJour.getMonth() + 1;

      if (dateDuJour.getDate() < 10)
        jourFull = '0' + dateDuJour.getDate();
      else
        jourFull = dateDuJour.getDate();

      var date = dateDuJour.getFullYear() + '-' + moisFull + '-' + jourFull;

      // Commentaire
      var comment = '';

      // Modification des données
      $('.form_saisie').attr('action', action);
      $('.zone_titre_saisie').html(titre);
      $('.titre_section > .texte_titre_section:first').html(sousTitre);
      $('input[name=id_expense_saisie]').val('');
      $('.saisie_acheteur').val(buyer);
      $('.saisie_prix').val(price);
      $('.saisie_date_depense').val(date);
      $('.saisie_commentaire').html(comment);

      if (type == 'M')
      {
        // Réduction
        $('.saisie_reduction').val(reduction);

        // Alimentation des zones utilisateurs
        $('#zone_saisie_montants').find('.zone_saisie_utilisateur').each(function()
        {
          // Initialisation du montant ou suppression zone utilisateur désinscrit
          if ($(this).attr('id') == undefined)
            $(this).remove();
          else
            $(this).find('.montant').val('');

          // Ajout du montant à la zone
          var idZone             = $(this).attr('id');
          var idMontant          = $(this).find('.montant').attr('id');
          var montantUtilisateur = '';

          ajouterMontant(idZone, idMontant, montantUtilisateur);
        });
      }
      else
      {
        // Alimentation des zones utilisateurs
        $('#zone_saisie_depense').find('.zone_saisie_utilisateur').each(function()
        {
          // Initialisation de la quantité ou suppression zone utilisateur désinscrit
          if ($(this).attr('id') == undefined)
            $(this).remove();
          else
            $(this).find('.quantite').val('0');

          // Initialisation de la quantité
          $(this).find('.quantite').val('0');

          // Ajout de la part à la zone
          var idZone                 = $(this).attr('id');
          var idQuantite             = $(this).find('.quantite').attr('id');
          var nombrePartsUtilisateur = 0;

          ajouterPart(idZone, idQuantite, nombrePartsUtilisateur);
        });
      }
    }
  }, 200);

  // On replie les explications
  if (type == 'M')
  {
    var explicationsMontants = $('#titre_explications_montants');

    openSection(explicationsMontants, 'afficher_explications_montants', 'close');
  }
  else
  {
    var explicationsDepense  = $('#titre_explications_depense');

    openSection(explicationsDepense, 'afficher_explications_depense', 'close');
  }
}
