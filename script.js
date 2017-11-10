// Masque la fenêtre des alertes
function masquerAlerte(id)
{
  document.getElementById(id).style.display = "none";
}

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function afficherIndex(id_open, id_close)
{
  if (document.getElementById(id_open).style.display == "none")
    document.getElementById(id_open).style.display = "block";

  if (document.getElementById(id_close).style.display == "block")
    document.getElementById(id_close).style.display = "none";
}

// Masque la fenêtre d'inscription ou de mot de passe perdu
function masquerIndex(id)
{
  if (document.getElementById(id).style.display == "block")
    document.getElementById(id).style.display = "none";
}
