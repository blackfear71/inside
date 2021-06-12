/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche la zone d'inscription + focus
  $('#afficherInscription').click(function()
  {
    var showButton;
    var hideButton = 'afficherInscription';
    var showForm   = 'formInscription';
    var hideForm;
    var marginButton = 'afficherConnexion';

    if ($('#afficherConnexion').css('display') == 'none')
    {
      showButton = 'afficherConnexion';
      hideForm   = 'formConnexion';
    }
    else
    {
      showButton = 'afficherPassword';
      hideForm   = 'formPassword';
    }

    // Changement d'onglet
    switchIndex(showButton, hideButton, marginButton);
    switchIndex(showForm, hideForm);

    // Fermeture des aides
    afficherMasquerPopUp('aideInscription', true);
    afficherMasquerPopUp('aidePassword', true);
  });

  // Affiche la zone de connexion + focus
  $('#afficherConnexion').click(function()
  {
    var showButton;
    var hideForm;
    var hideButton = 'afficherConnexion';
    var showForm   = 'formConnexion';
    var marginButton = 'afficherInscription';

    if ($('#afficherInscription').css('display') == 'none')
    {
      showButton = 'afficherInscription';
      hideForm   = 'formInscription';
    }
    else
    {
      showButton = 'afficherPassword';
      hideForm   = 'formPassword';
    }

    // Changement d'onglet
    switchIndex(showButton, hideButton, marginButton);
    switchIndex(showForm, hideForm);

    // Fermeture des aides
    afficherMasquerPopUp('aideInscription', true);
    afficherMasquerPopUp('aidePassword', true);
  });

  // Affiche la zone de réinitialisation mot de passe + focus
  $('#afficherPassword').click(function()
  {
    var showButton;
    var hideForm;
    var hideButton = 'afficherPassword';
    var showForm   = 'formPassword';
    var marginButton = 'afficherConnexion';

    if ($('#afficherInscription').css('display') == 'none')
    {
      showButton = 'afficherInscription';
      hideForm   = 'formInscription';
    }
    else
    {
      showButton = 'afficherConnexion';
      hideForm   = 'formConnexion';
    }

    // Changement d'onglet
    switchIndex(showButton, hideButton, marginButton);
    switchIndex(showForm, hideForm);

    // Fermeture des aides
    afficherMasquerPopUp('aideInscription', true);
    afficherMasquerPopUp('aidePassword', true);
  });

  // Affiche ou masque l'aide d'inscription
  $('#afficherAideInscription, #fermerAideInscription').click(function()
  {
    afficherMasquerPopUp('aideInscription', false);
  });

  // Affiche ou masque l'aide de changement de mot de passe
  $('#afficherAidePassword, #fermerAidePassword').click(function()
  {
    afficherMasquerPopUp('aidePassword', false);
  });

  /*** Actions au changement ***/
  // Transforme en majuscule les caractères saisis dans l'identifiant
  $('#focus_identifiant, #focus_identifiant_2, #focus_identifiant_3').change(function()
  {
    identifiantMajuscule($(this));
  });

  // Affiche la saisie "Autre" (nouvelle équipe)
  $('.select_form_index').on('change', function()
  {
    afficherAutreEquipe('select_form_index', 'autre_equipe');
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Transforme le contenu d'un champ en majuscules
function identifiantMajuscule(champ)
{
  var value = champ.val();

  if (value != 'admin')
    value = value.toUpperCase();

  champ.val(value);
}

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function switchIndex(idOpen, idClose, idMargin = '')
{
  // Masquage de la zone et traitement
  $('.zone_form_index').fadeOut(200, function()
  {
    // Fermeture de l'ancien formulaire
    $('#' + idClose).css('display', 'none');

    // Affichage du nouveau formulaire
    $('#' + idOpen).css('display', 'inline-block');

    // Gestion de la marge des boutons
    if (idMargin != '')
    {
      $('.lien_index').each(function()
      {
        $(this).removeClass('lien_index_margin_right');
      });

      $('#' + idMargin).addClass('lien_index_margin_right');
    }

    // Affichage de la zone
    $('.zone_form_index').fadeIn(200);
  });
}

// Affiche ou masque la zone de saisie d'une autre équipe
function afficherAutreEquipe(select, required)
{
  if ($('.' + select).val() == 'other')
  {
    $('#' + required).css('display', 'block');
    $('#' + required).prop('required', true);
  }
  else
  {
    $('#' + required).css('display', 'none');
    $('#' + required).prop('required', false);
  }
}
