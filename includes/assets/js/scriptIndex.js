// Au chargement du document
$(document).ready(function()
{
  // Positionnement du curseur sur "Identifiant" pour se connecter
  $('#focus_identifiant').focus();

  // Positionnement du curseur sur "Identifiant" au clic pour s'inscrire
  $('#inscription_event').click(function()
  {
    $('#focus_identifiant_2').focus();
  });

  // Positionnement du curseur sur "Identifiant" au clic pour réinitialiser le mot de passe
  $('#password_event').click(function()
  {
    $('#focus_identifiant_3').focus();
  });

  // Positionnement du curseur sur "Identifiant" au clic pour fermer les fenêtres
  $('.close_img').click(function()
  {
    $('#focus_identifiant').focus();
  });

  // Transforme en majuscule les caractères saisis dans les différents identifiants
  $('#focus_identifiant, #focus_identifiant_2, #focus_identifiant_3').change(function()
  {
    var value = $(this).val();

    if (value != "admin")
      value = value.toUpperCase();

    $(this).val(value);
  });
});

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function afficherIndex(id_open, id_close)
{
  document.getElementById(id_open).style.display    = "block";
  document.getElementById(id_open).style.marginLeft = "39.5%";
  document.getElementById(id_open).style.transition = "margin-left 1s";

  document.getElementById(id_close).style.marginLeft = "-100%";
}

// Masque la fenêtre d'inscription ou de mot de passe perdu
function masquerIndex(id)
{
  document.getElementById(id).style.marginLeft = "-100%";
}
