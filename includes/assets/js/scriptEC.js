/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptEC();

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
    afficherMasquer('zone_add_depense');
    initMasonry();
  });

  // Réinitialise la saisie dépense à la fermeture
  $('#resetDepense').click(function()
  {
    resetSaisie('zone_add_depense', $_GET('year'));
  });

  // Affiche les explications
  $('#afficherExplications').click(function()
  {
    afficherExplications();
  });

  // Ajoute une part
  $('.ajouterPart').click(function()
  {
    var id_user = $(this).attr('id').replace('ajouter_part_', '');

    saisirPart('zone_user_' + id_user, 'quantite_user_' + id_user, 1);
  });

  // Retire une part
  $('.retirerPart').click(function()
  {
    var id_user = $(this).attr('id').replace('retirer_part_', '');

    saisirPart('zone_user_' + id_user, 'quantite_user_' + id_user, -1);
  });

  // Modifier une dépense
  $('.modifierDepense').click(function()
  {
    var id_depense = $(this).attr('id').replace('modifier_', '');

    updateExpense(id_depense, $_GET('year'));
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Décalage pour mobile
  adaptEC();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations de la section sur mobiles
function adaptEC()
{
  initMasonry();

  if ($(window).width() < 1080)
  {
    $('.zone_expenses_left').css('display', 'block');
    $('.zone_expenses_left').css('width', '100%');

    $('.zone_expenses_right').css('display', 'block');
    $('.zone_expenses_right').css('width', '100%');
    $('.zone_expenses_right').css('margin-left', '0');
  }
  else
  {
    $('.zone_expenses_left').css('display', 'inline-block');
    $('.zone_expenses_left').css('width', '400px');

    $('.zone_expenses_right').css('display', 'inline-block');
    $('.zone_expenses_right').css('width', 'calc(100% - 420px');
    $('.zone_expenses_right').css('margin-left', '20px');
  }
}

// Affiche ou masque un élément (délai 200ms)
function afficherMasquer(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerNoDelay(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
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

  // Calcul automatique des tailles des zones
  tailleAutoTexte();
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
  var date    = listExpenses[id]['date'].substring(6, 8) + '/' + listExpenses[id]['date'].substring(4, 6) + '/' + listExpenses[id]['date'].substring(0, 4);
  var titre   = 'Modifier la dépense du ' + date;
  var buyer   = listExpenses[id]['buyer'];
  var price   = listExpenses[id]['price'];
  var comment = listExpenses[id]['comment'];
  var action  = 'expensecenter.php?year=' + year + '&action=doModifier';
  var identifiant;
  var id_identifiant;
  var parts;

  // Affichage zone de saisie
  afficherMasquer('zone_add_depense');
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
    $(this).children('.qte').val('0');
    $(this).css('background-color', '#e3e3e3');
    $(this).css('color', '#262626');

    identifiant = listExpenses[id]['parts'][$(this).find('input[type=hidden]').val()];

    if (identifiant != null)
    {
      id_identifiant = listExpenses[id]['parts'][$(this).find('input[type=hidden]').val()]['id_identifiant'];
      parts          = listExpenses[id]['parts'][$(this).find('input[type=hidden]').val()]['parts'];

      $('#quantite_user_' + id_identifiant).val(parts);
      $(this).css('background-color', '#ff1937');
      $(this).css('color', 'white');
    }
  });
}

// Réinitialise la zone de saisie d'une dépense si fermeture modification
function resetSaisie(zone, year)
{
  // Fermeture zone de saisie
  afficherMasquer(zone);

  setTimeout(function()
  {
    // Test si action = modification
    var currentAction = $('.form_saisie_depense').attr('action').split('&action=');
    var call          = currentAction[currentAction.length - 1]

    if (call == "doModifier")
    {
      var titre   = 'Saisir une dépense';
      var buyer   = "";
      var price   = "";
      var comment = "";
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
        $(this).children('.qte').val('0');
        $(this).css('background-color', '#e3e3e3');
        $(this).css('color', '#262626');
      });
    }

    // On réinitialise l'affichage des explications
    $('.lien_explications').css('display', 'block');
    $('.explications').css('display', 'none');
  }, 200);
}
