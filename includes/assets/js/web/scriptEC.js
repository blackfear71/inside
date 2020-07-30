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
    closeInput();
  });

  // Ferme au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la saisie d'une dépense
    if ($(event.target).attr('class') == 'fond_saisie_depense')
      closeInput();
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

  // Affiche les explications
  $('#afficherExplications').click(function()
  {
    afficherExplications();
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
    tailleAutoTexte();
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

// Ferme la saisie d'une dépense
function closeInput()
{
  resetSaisie('zone_add_depense', $_GET('year'));
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
function tailleAutoTexte()
{
  var width = $('.zone_saisie_utilisateurs').width() - 20;

  $('.explications').width(width);
}

// Affiche les explications
function afficherExplications()
{
  $('.lien_explications').css('display', 'none');
  $('.explications').css('display', 'block');

  // Calcul automatique des tailles des zones
  tailleAutoTexte();
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
function updateExpense(id, year)
{
  // Récupération des données
  var depense = listExpenses[id];
  var buyer   = depense['buyer'];
  var comment = depense['comment'];
  var parts   = depense['parts'];
  var date    = depense['date'].substring(6, 8) + '/' + depense['date'].substring(4, 6) + '/' + depense['date'].substring(0, 4);
  var price   = formatAmountForDisplay(depense['price'], false);
  var action  = 'expensecenter.php?year=' + year + '&action=doModifier';
  var titre;
  var identifiant;
  var idIdentifiant;
  var partUser;

  // Titre
  if (parts.length == 0)
    titre = 'Modifier la régularisation du ' + date;
  else
    titre = 'Modifier la dépense du ' + date;

  // Affichage zone de saisie
  afficherMasquerIdWithDelay('zone_add_depense');
  initMasonry();

  // Modification des données
  $('input[name=id_expense]').val(id);
  $('.titre_saisie_depense').html(titre);
  $('.form_saisie_depense').attr('action', action);
  $('#select_user').val(buyer);
  $('.saisie_prix').val(price);
  $('.saisie_commentaire').html(comment);

  $('.zone_saisie_utilisateur').each(function()
  {
    $(this).children('.quantite').val('0');
    $(this).css('background-color', '#e3e3e3');
    $(this).css('color', '#262626');

    identifiant = parts[$(this).find('input[type=hidden]').val()];

    if (identifiant != null)
    {
      idIdentifiant = parts[$(this).find('input[type=hidden]').val()]['id_identifiant'];
      partUser      = parts[$(this).find('input[type=hidden]').val()]['parts'];

      $('#quantite_user_' + idIdentifiant).val(partUser);
      $(this).css('background-color', '#ff1937');
      $(this).css('color', 'white');
    }
  });
}

// Réinitialise la zone de saisie d'une dépense si fermeture modification
function resetSaisie(zone, year)
{
  // Fermeture zone de saisie
  afficherMasquerIdWithDelay(zone);

  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie_depense').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == 'doModifier')
    {
      var titre   = 'Saisir une dépense';
      var buyer   = '';
      var price   = '';
      var comment = '';
      var action  = 'expensecenter.php?year=' + year + '&action=doInserer';

      // Modification des données
      $('input[name=id_expense]').val('');
      $('.titre_saisie_depense').html(titre);
      $('.form_saisie_depense').attr('action', action);
      $('#select_user').val(buyer);
      $('.saisie_prix').val(price);
      $('.saisie_commentaire').html(comment);

      $('.zone_saisie_utilisateur').each(function()
      {
        $(this).children('.quantite').val('0');
        $(this).css('background-color', '#e3e3e3');
        $(this).css('color', '#262626');
      });
    }

    // On réinitialise l'affichage des explications
    $('.lien_explications').css('display', 'block');
    $('.explications').css('display', 'none');
  }, 200);
}
