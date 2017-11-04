// Affiche ou masque le log
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Rotation icône affichage log
function rotateIcon(id)
{
  if (document.getElementById(id).style.transform == "rotate(0deg)")
    document.getElementById(id).style.transform = "rotate(180deg)";
  else
    document.getElementById(id).style.transform = "rotate(0deg)";
}
