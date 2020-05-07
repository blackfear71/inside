/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /** Actions au chargement ***/
  // Affichage des alertes
  if ($('#alerte').length)
    afficherMasquerIdWithDelay('alerte');

  // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
  updatePing();
  setInterval(updatePing, 60000);

  /*** Actions au clic ***/
  // Ouverture menu latéral gauche
  $('#deployAsidePortail').click(function()
  {
    deployerMenuPortail();
  });

  // Ouverture menu latéral droit
  $('#deployAsideUser').click(function()
  {
    deployerMenuUser();
  });

  // Ferme un menu au clic sur le fond
  $(document).on('click', function(event)
  {
    console.log($(event.target));

    // Ferme le menu latéral gauche
    if ($(event.target).attr('class') != 'aside_portail'
    &&  $(event.target).attr('class') != 'lien_aside'
    &&  $(event.target).attr('class') != 'icone_aside'
    &&  $(event.target).attr('class') != 'titre_aside'
    &&  $('.aside_portail').css('left') == '0px')
      deployerMenuPortail();

    // Ferme le menu latéral droit
    if ($(event.target).attr('class') != 'aside_user'
    &&  $(event.target).attr('class') != 'lien_aside'
    &&  $(event.target).attr('class') != 'icone_aside'
    &&  $(event.target).attr('class') != 'titre_aside'
    &&  $('.aside_user').css('right') == '0px')
      deployerMenuUser();
  });

  // Bouton fermer alerte
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque un élément (délai 200ms)
function afficherMasquerIdWithDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Masque et supprime un élément (délai 200ms)
function masquerSupprimerIdWithDelay(id)
{
  $('#' + id).fadeOut(200, function()
  {
    $(this).remove();
  });
}

// Exécute le script php de mise à jour du ping
function updatePing()
{
  $.post('/inside/includes/functions/ping.php', {function: 'updatePing'});
}

// Déploie le menu latéral gauche
function deployerMenuPortail()
{
  if ($('.aside_portail').css('left') == '0px')
    $('.aside_portail').css('left', ('-80%'));
  else
    $('.aside_portail').css('left', ('0%'));

  $('.aside_portail').css('transition', 'left ease 0.3s');
}

// Déploie le menu latéral gauche
function deployerMenuUser()
{
  if ($('.aside_user').css('right') == '0px')
    $('.aside_user').css('right', ('-80%'));
  else
    $('.aside_user').css('right', ('0%'));

  $('.aside_user').css('transition', 'right ease 0.3s');
}
