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
  // Décalage pour mobile
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

// Adaptations de la section sur mobiles
function adaptIndex()
{
  if ($(window).width() < 1300)
    $('.texte_index').css('font-size', '100%');
  else
    $('.texte_index').css('font-size', '150%');
}
