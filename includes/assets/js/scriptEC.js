// Au chargement du document
$(document).ready(function()
{
  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 30;

  // Scroll vers l'id
  scrollToId(id, offset);
});

// Affiche ou masque les lignes de visualisation/modification du tableau
function afficherMasquerRow(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table-row";
  else
    document.getElementById(id).style.display = "none";
}
