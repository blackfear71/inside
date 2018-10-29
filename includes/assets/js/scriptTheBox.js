// Au chargement du document
$(document).ready(function()
{
  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;

  // Scroll vers l'id
  scrollToId(id, offset);
});
