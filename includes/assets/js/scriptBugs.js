// Au chargement du document
$(document).ready(function()
{
  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 70;

  // Scroll vers l'id
  scrollToId(id, offset);

  // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
  if (id != null)
  {
    $('#zone_shadow_' + id).css('box-shadow', '0 3px 10px #262626');

    setTimeout(function()
    {
      $('#zone_shadow_' + id).css('box-shadow', 'none');
      $('#zone_shadow_' + id).css({transition : "box-shadow ease 0.2s"});
    }, 5000);
  }
});
