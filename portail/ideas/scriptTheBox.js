// Déclenchement du scroll
$(document).ready(function()
{
  // On récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;

  // Scroll vers l'id
  scrollToId(id, offset);
});
