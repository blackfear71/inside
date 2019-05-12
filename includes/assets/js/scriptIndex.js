/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(document).ready(function()
{
  /*** Actions au chargement ***/
  // Positionnement du curseur sur "Identifiant" pour se connecter
  $('#focus_identifiant').focus();

  /*** Actions au clic ***/
  // Affiche la zone d'inscription + focus
  $('#afficherInscription').click(function()
  {
    afficherIndex('inscription', 'password');
    $('#focus_identifiant_2').focus();
  });

  // Affiche la zone de demande de réinitialisation de mot de passe + focus
  $('#afficherPassword').click(function()
  {
    afficherIndex('password', 'inscription');
    $('#focus_identifiant_3').focus();
  });

  // Ferme la demande d'inscription
  $('#masquerInscription').click(function()
  {
    masquerIndex('inscription');
    $('#focus_identifiant').focus();
  });

  // Ferme la demande de réinitialisation de mot de passe + focus
  $('#masquerPassword').click(function()
  {
    masquerIndex('password');
    $('#focus_identifiant').focus();
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

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function afficherIndex(id_open, id_close)
{
  $('#' + id_open).css('display', 'block');
  $('#' + id_open).css('margin-left', '39.5%');
  $('#' + id_open).css('transition', 'margin-left 1s');

  $('#' + id_close).css('margin-left', '-100%');
}

// Masque la fenêtre d'inscription ou de mot de passe perdu
function masquerIndex(id)
{
  $('#' + id).css('margin-left', '-100%');
}
