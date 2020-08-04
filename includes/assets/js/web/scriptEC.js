/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptExpenses();

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 30;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);

  /*** Actions au clic ***/
  // Ajouter une dépense
  $('#ajouterDepense').click(function()
  {
    afficherMasquerIdWithDelay('zone_add_depense');
    initMasonry();
  });

  // Réinitialise la saisie dépense à la fermeture
  $('#resetDepense').click(function()
  {
    resetSaisie('zone_add_depense', $_GET('year'), 'P');
  });

  // Ajouter des montants
  $('#ajouterMontants').click(function()
  {
    afficherMasquerIdWithDelay('zone_add_montants');
    initMasonry();
  });

  // Réinitialise la saisie montants à la fermeture
  $('#resetMontants').click(function()
  {
    resetSaisie('zone_add_montants', $_GET('year'), 'M');
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'une dépense
    if ($(event.target).attr('class') == 'fond_saisie_depense')
    {
      switch (event.target.id)
      {
        case 'zone_add_depense':
          resetSaisie('zone_add_depense', $_GET('year'), 'P');
          break;

        case 'zone_add_montants':
          resetSaisie('zone_add_montants', $_GET('year'), 'M');
          break;

        default:
          break;
      }
    }
  });

  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_depense').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = [];

    // Blocage spécifique (bouton modification parts dépense)
    tabBlock.push({element: '.bouton_quantite', property: 'pointer-events', value: 'none'});

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Bloque le bouton de soumission si besoin
  $('#bouton_saisie_montants').click(function()
  {
    var zoneButton   = $('.zone_bouton_saisie_montants');
    var submitButton = $(this);
    var formSaisie   = submitButton.closest('form');
    var tabBlock     = null;

    hideSubmitButton(zoneButton, submitButton, formSaisie, tabBlock);
  });

  // Affiche les explications de saisie de dépense
  $('#afficherExplicationsDepense').click(function()
  {
    afficherExplications($(this).attr('id'), 'explications_depense');
  });

  // Affiche les explications de saisie de montants
  $('#afficherExplicationsMontants').click(function()
  {
    afficherExplications($(this).attr('id'), 'explications_montants');
  });

  // Ajoute une part
  $('.ajouterPart').click(function()
  {
    var idUser = $(this).attr('id').replace('ajouter_part_', '');

    saisirPart('zone_user_' + idUser, 'quantite_user_' + idUser, 1);
  });

  // Retire une part
  $('.retirerPart').click(function()
  {
    var idUser = $(this).attr('id').replace('retirer_part_', '');

    saisirPart('zone_user_' + idUser, 'quantite_user_' + idUser, -1);
  });

  // Modifier une dépense
  $('.modifierDepense').click(function()
  {
    var idDepense = $(this).attr('id').replace('modifier_depense_', '');

    updateExpense(idDepense, $_GET('year'));
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptExpenses();

  // Calcul automatique des tailles des zones après un delai de 150ms
  setTimeout(function()
  {
    if ($('#explications_depense').css('display') == 'block')
      tailleAutoTexte('explications_depense');

    if ($('#explications_montants').css('display') == 'block')
      tailleAutoTexte('explications_montants');

  }, 150);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des dépenses sur mobile
function adaptExpenses()
{
  initMasonry();

  if ($(window).width() < 1080)
  {
    // Bilan et dépenses
    $('.zone_expenses_left').css('display', 'block');
    $('.zone_expenses_left').css('width', '100%');

    $('.zone_expenses_right').css('display', 'block');
    $('.zone_expenses_right').css('width', '100%');
    $('.zone_expenses_right').css('margin-left', '0');

    // Saisie dépense
    if ($('.zone_saisie_depense').length)
    {
      $('.zone_saisie_left').css('display', 'block');
      $('.zone_saisie_left').css('width', 'calc(100% - 20px)');

      $('.zone_saisie_right').css('display', 'block');
      $('.zone_saisie_right').css('width', 'calc(100% - 20px)');
      $('.zone_saisie_right').css('padding', '0 10px 0 10px');
    }
  }
  else
  {
    // Bilan et dépenses
    $('.zone_expenses_left').css('display', 'inline-block');
    $('.zone_expenses_left').css('width', '400px');

    $('.zone_expenses_right').css('display', 'inline-block');
    $('.zone_expenses_right').css('width', 'calc(100% - 420px)');
    $('.zone_expenses_right').css('margin-left', '20px');

    // Saisie dépense
    if ($('.zone_saisie_depense').length)
    {
      $('.zone_saisie_left').css('display', 'inline-block');
      $('.zone_saisie_left').css('width', '280px');

      $('.zone_saisie_right').css('display', 'inline-block');
      $('.zone_saisie_right').css('width', 'calc(100% - 320px)');
      $('.zone_saisie_right').css('padding', '10px 10px 0 10px');
    }
  }
}

// Lance Masonry quand on fait apparaitre la zone
function initMasonry()
{
  // Masonry (Saisie)
  $('.zone_saisie_utilisateurs').masonry({
    // Options
    itemSelector: '.zone_saisie_utilisateur, .zone_saisie_utilisateur_parts',
    columnWidth: 200,
    fitWidth: true,
    gutter: 10,
    horizontalOrder: true
  });
}

// Calcul la taille des explications automatiquement
function tailleAutoTexte(idExplications)
{
  var width = $('#' + idExplications).next('.zone_saisie_utilisateurs').width() - 20;

  $('#' + idExplications).width(width);
}

// Affiche les explications
function afficherExplications(lienExplications, idExplications)
{
  $('#' + lienExplications).css('display', 'none');
  $('#' + idExplications).css('display', 'block');

  // Calcul automatique des tailles des zones
  tailleAutoTexte(idExplications);
}

// Ajoute une part à un utilisateur
function saisirPart(zone, quantite, value)
{
  var currentValue = parseInt($('#' + quantite).val());
  var newValue     = currentValue + value;

  if (newValue > 0)
  {
    $('#' + zone).css('background-color', '#ff1937');
    $('#' + zone).css('color', 'white');
  }
  else
  {
    $('#' + zone).css('background-color', '#e3e3e3');
    $('#' + zone).css('color', '#262626');
  }

  if (newValue >= 0 && newValue <= 5)
    $('#' + quantite).val(newValue);
}

// Affiche la zone de mise à jour d'une dépense
function updateExpense(idDepense, year)
{
  // Récupération des données
  var depense = listExpenses[idDepense];
  var parts   = depense['parts'];
  var type    = depense['type'];
  var price;
  var action;
  var titre;

  // Action du formulaire
  if (type == 'M')
    action = 'expensecenter.php?year=' + year + '&action=doModifierMontants';
  else
    action = 'expensecenter.php?year=' + year + '&action=doModifier';

  // Date du jour
  var date = formatDateForDisplay(depense['date']);

  // Titre
  if (parts.length == 0)
    titre = 'Modifier la régularisation du ' + date;
  else
  {
    if (type == 'M')
      titre = 'Modifier les montants du ' + date;
    else
      titre = 'Modifier la dépense du ' + date;
  }

  // Acheteur
  var buyer   = depense['buyer'];

  // Prix ou frais
  if (type == 'M')
    price = formatAmountForDisplay(depense['frais'], false);
  else
    price = formatAmountForDisplay(depense['price'], false);

  // Commentaire
  var comment = depense['comment'];

  // Modification des données
  $('.form_saisie_depense').attr('action', action);
  $('.titre_saisie_depense').html(titre);
  $('input[name=id_expense]').val(idDepense);
  $('.saisie_buyer').val(buyer);
  $('.saisie_prix').val(price);
  $('.saisie_commentaire').html(comment);

  if (type == 'M')
  {
    $('.zone_saisie_utilisateur').each(function()
    {
      // Initialisation du montant
      $(this).find('.montant').val('');

      // Vérification présence identifiant dans les parts
      var idZone                 = $(this).attr('id');
      var idQuantite             = $(this).find('.montant').attr('id');
      var identifiantLigne       = $(this).find('input[type=hidden]').val();
      var partUtilisateur        = parts[identifiantLigne];

      // Récupération du nombre de parts
      if (partUtilisateur != null)
        $(this).find('.montant').val(formatAmountForDisplay(partUtilisateur['parts']));
    });
  }
  else
  {
    $('.zone_saisie_utilisateur, .zone_saisie_utilisateur_parts').each(function()
    {
      $(this).children('.quantite').val('0');

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
      saisirPart(idZone, idQuantite, nombrePartsUtilisateur);
    });
  }

  // Affiche la zone de saisie
  if (type == 'M')
    afficherMasquerIdWithDelay('zone_add_montants');
  else
    afficherMasquerIdWithDelay('zone_add_depense');

  initMasonry();
}

// Réinitialise la zone de saisie d'une dépense si fermeture modification
function resetSaisie(zone, year, type)
{
  // Fermeture zone de saisie
  afficherMasquerIdWithDelay(zone);

  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie_depense').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == 'doModifier' || call == 'doModifierMontants')
    {
      if (type == 'M')
      {
        // Action du formulaire
        var action = 'expensecenter.php?year=' + year + '&action=doInsererMontants';

        // Titre
        var titre = 'Saisir des montants';
      }
      else
      {
        // Action du formulaire
        var action = 'expensecenter.php?year=' + year + '&action=doInserer';

        // Titre
        var titre = 'Saisir une dépense';
      }

      // Acheteur
      var buyer   = '';

      // Prix ou frais
      var price   = '';

      // Commentaire
      var comment = '';

      // Modification des données
      $('.form_saisie_depense').attr('action', action);
      $('.titre_saisie_depense').html(titre);
      $('input[name=id_expense]').val('');
      $('.saisie_buyer').val(buyer);
      $('.saisie_prix').val(price);
      $('.saisie_commentaire').html(comment);

      if (type == 'M')
      {
        $('.zone_saisie_utilisateur').each(function()
        {
          // Initialisation du montant
          $(this).find('.montant').val('');
        });
      }
      else
      {
        $('.zone_saisie_utilisateur, .zone_saisie_utilisateur_parts').each(function()
        {
          $(this).find('.quantite').val('0');

          // Ajout de la part à la zone
          var idZone                 = $(this).attr('id');
          var idQuantite             = $(this).find('.quantite').attr('id');
          var nombrePartsUtilisateur = 0;

          saisirPart(idZone, idQuantite, nombrePartsUtilisateur);
        });
      }
    }

    // On réinitialise l'affichage des explications
    $('.lien_explications').css('display', 'block');
    $('.explications').css('display', 'none');
  }, 200);
}
