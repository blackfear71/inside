/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/

  /*** Actions au changement ***/
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptChangelog();
  adaptHistory();
});

/***************/
/*** Masonry ***/
/***************/
// Au chargement du document complet
$(window).on('load', function()
{
  // Adaptation mobile
  adaptChangelog();
  adaptHistory();

  // Masonry (Logs par catégories)
  if ($('.zone_logs_semaine').length)
  {
    $('.zone_logs_semaine').masonry().masonry('destroy');

    $('.zone_logs_semaine').masonry({
      // Options
      itemSelector: '.zone_logs_categorie',
      columnWidth: 450,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_logs_semaine').addClass('masonry');
  }
});

/*****************/
/*** Fonctions ***/
/*****************/
// Adaptations des journaux de modification sur mobile
function adaptChangelog()
{
  if ($(window).width() < 1080)
  {
    // Affichage de la page
    $('.zone_changelog_left').css('display', 'block');
    $('.zone_changelog_left').css('width', '100%');

    $('.zone_changelog_right').css('display', 'block');
    $('.zone_changelog_right').css('width', '100%');
    $('.zone_changelog_right').css('margin-left', '0');
  }
  else
  {
    // Affichage de la page
    $('.zone_changelog_left').css('display', 'inline-block');
    $('.zone_changelog_left').css('width', '200px');

    $('.zone_changelog_right').css('display', 'inline-block');
    $('.zone_changelog_right').css('width', 'calc(100% - 220px)');
    $('.zone_changelog_right').css('margin-left', '20px');
  }
}

// Adaptation des traits de l'histoire du site
function adaptHistory()
{
  var taille_totale = $('.zone_changelog_right').width();

  // Calcul de la taille de chaque trait
  $('.event_history').each(function()
  {
    var taille_date  = $(this).children('.date_history').width();
    var taille_trait = taille_totale - taille_date - 15;

    $(this).children('.trait_history').css('width', taille_trait + 'px');
  });
}
