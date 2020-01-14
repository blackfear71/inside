/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Positionnement du curseur sur "Identifiant" pour se connecter
  $('#focus_identifiant').focus();

  // Adaptation mobile
  adaptIndex();

  /*** Actions au clic ***/
  // Affiche la zone d'inscription + focus
  $('#afficherInscription').click(function()
  {
    var showButton;
    var hideButton = 'afficherInscription';
    var showForm   = 'formInscription';
    var hideForm;
    var showText   = 'texteInscription';
    var hideText;
    var focus      = 'focus_identifiant_2';

    if ($('#afficherConnexion').css('display') == 'none')
    {
      showButton = 'afficherConnexion';
      hideForm   = 'formConnexion';
      hideText   = 'logo';
    }
    else
    {
      showButton = 'afficherPassword';
      hideForm   = 'formPassword';
      hideText   = 'textePassword';
    }

    switchIndex(showButton, hideButton);
    switchIndex(showForm, hideForm, focus);
    switchIndex(showText, hideText);
  });

  // Affiche la zone de connexion + focus
  $('#afficherConnexion').click(function()
  {
    var showButton;
    var hideForm;
    var hideButton = 'afficherConnexion';
    var showForm   = 'formConnexion';
    var showText   = 'logo';
    var hideText;
    var focus      = 'focus_identifiant';

    if ($('#afficherInscription').css('display') == 'none')
    {
      showButton = 'afficherInscription';
      hideForm   = 'formInscription';
      hideText   = 'texteInscription';
    }
    else
    {
      showButton = 'afficherPassword';
      hideForm   = 'formPassword';
      hideText   = 'textePassword';
    }

    switchIndex(showButton, hideButton);
    switchIndex(showForm, hideForm, focus);
    switchIndex(showText, hideText);
  });

  // Affiche la zone de réinitialisation mot de passe + focus
  $('#afficherPassword').click(function()
  {
    var showButton;
    var hideForm;
    var hideButton = 'afficherPassword';
    var showForm   = 'formPassword';
    var showText   = 'textePassword';
    var hideText;
    var focus      = 'focus_identifiant_3';

    if ($('#afficherInscription').css('display') == 'none')
    {
      showButton = 'afficherInscription';
      hideForm   = 'formInscription';
      hideText   = 'texteInscription';
    }
    else
    {
      showButton = 'afficherConnexion';
      hideForm   = 'formConnexion';
      hideText   = 'logo';
    }

    switchIndex(showButton, hideButton);
    switchIndex(showForm, hideForm, focus);
    switchIndex(showText, hideText);
  });

  /*** Actions au changement ***/
  // Transforme en majuscule les caractères saisis dans les différents identifiants
  $('#focus_identifiant, #focus_identifiant_2, #focus_identifiant_3').change(function()
  {
    var value = $(this).val();

    if (value != "admin")
      value = value.toUpperCase();

    $(this).val(value);
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptIndex();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function switchIndex(id_open, id_close, focus = null)
{
  $('#' + id_close).fadeOut(200, function()
  {
    $('#' + id_open).fadeIn(200, function()
    {
      if (focus != null)
        $('#' + focus).focus();
    });
  });
}

// Adaptations de l'index sur mobile
function adaptIndex()
{
  if ($(window).width() < 1480)
  {
    $('.texte_index').css('font-size', '100%');

    $('.zone_logo_categories').css('width', '25px');
    $('.zone_logo_categories').css('height', '25px');
    $('.zone_logo_categories').css('margin-bottom', '5px');

    $('.logo_categories').css('width', '20px');
    $('.logo_categories').css('height', '20px');
    $('.logo_categories').css('margin-top', '2.5px');
  }
  else
  {
    $('.texte_index').css('font-size', '150%');

    $('.zone_logo_categories').css('width', '50px');
    $('.zone_logo_categories').css('height', '50px');
    $('.zone_logo_categories').css('margin-bottom', '10px');

    $('.logo_categories').css('width', '30px');
    $('.logo_categories').css('height', '30px');
    $('.logo_categories').css('margin-top', '10px');
  }
}
