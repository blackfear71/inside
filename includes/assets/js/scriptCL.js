/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Plie ou déplie les thèmes
  $('.bouton_fold').click(function()
  {
    var id_fold = $(this).attr('id').replace('fold_', '');

    afficherMasquerChangeLog(id_fold);
  });
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Adaptation mobile
  adaptChangelog();
  adaptHistory();

  // Relance masonry
  initMasonry('0.4s');
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

  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_changelog_right').css('display', 'inline-block');

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

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = 'changelog_' + $_GET('anchor');
  var offset = 70;
  var shadow = false;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
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

// Affiche ou masque un journal
function afficherMasquerChangeLog(id)
{
  if ($('#' + id).css('display') == 'block')
  {
    $('#' + id).css('display', 'none');
    $('#fold_' + id).html('Déplier');
  }
  else
  {
    $('#' + id).css('display', 'block');
    $('#fold_' + id).html('Plier');
    initMasonry(0);
  }
}

// Initialisation manuelle de "Masonry"
function initMasonry(duration)
{
  // On lance Masonry
  $('.zone_logs_semaine').masonry({
    // Options
    itemSelector: '.zone_logs_categorie',
    columnWidth: 450,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true,
    transitionDuration: duration
  });
}
