/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Adaptation mobile
  adaptNotifications();

  /*** Actions au clic ***/
  // Redirige vers le détail des films au clic Doodle des fiches
  $('.lienDetails').click(function()
  {
    var id_film = $(this).attr('id').replace('lien_details_', '');

    document.location.href = '/inside/portail/moviehouse/details.php?id_film=' + id_film + '&action=goConsulter';
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptNotifications();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des notifications sur mobile
function adaptNotifications()
{
  if ($(window).width() < 1080)
  {
    $('.zone_notifications_left').css('display', 'block');
    $('.zone_notifications_left').css('width', '100%');

    $('.zone_notifications_right').css('display', 'block');
    $('.zone_notifications_right').css('width', '100%');
    $('.zone_notifications_right').css('margin-left', '0');

    $('.view').css('display', 'inline-block');
    $('.view').css('width', 'calc(50% - 10px)');
    $('.view:eq(0)').css('margin-right', '20px');
    $('.view:eq(2)').css('margin-right', '20px');
  }
  else
  {
    $('.zone_notifications_left').css('display', 'inline-block');
    $('.zone_notifications_left').css('width', '120px');

    $('.zone_notifications_right').css('display', 'inline-block');
    $('.zone_notifications_right').css('width', 'calc(100% - 140px)');
    $('.zone_notifications_right').css('margin-left', '20px');

    $('.view').css('display', 'block');
    $('.view').css('width', '100%');
    $('.view:eq(0)').css('margin-right', '0');
    $('.view:eq(2)').css('margin-right', '0');
  }
}
