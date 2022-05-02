/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Ouvre ou ferme la zone de saisie de la vue
  $(document).on('click', '.zone_logo_news', function()
  {
    var idBoutonNews = $(this).attr('id');
    var classNews    = $(this).attr('id').replace('bouton_', 'news_');

    afficherMasquerNews(idBoutonNews, classNews);
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque des news
function afficherMasquerNews(idBoutonNews, classNews)
{
  // Détermination de l'évènement (ouverture ou fermeture)
  var evenement = 'ouverture';

  $('.zone_logo_news').each(function()
  {
    if ($(this).attr('id') == idBoutonNews && $(this).css('background-color') == 'rgb(255, 25, 55)')
      evenement = 'fermeture';
  });
  
  // Fermeture si on clique sur le même, sinon ouverture
  if (evenement == 'fermeture')
  {
    // Changement couleur boutons
    $('.zone_logo_news').each(function()
    {
      $(this).css('background-color', '#374650');
    });

    // Masquage de la zone
    if ($('#zone_affichage_news').css('display') == 'block')
      afficherMasquerIdWithDelay('zone_affichage_news');

    // Masquage des news correspondantes
    $('.zone_news').each(function()
    {
      $(this).fadeOut(200);
    });
  }
  else
  {
    // Changement couleur boutons
    $('.zone_logo_news').each(function()
    {
      if ($(this).attr('id') == idBoutonNews)
        $(this).css('background-color', '#ff1937');
      else
        $(this).css('background-color', '#374650');
    });
    
    // Affichage de la zone
    if ($('#zone_affichage_news').css('display') == 'none')
      afficherMasquerIdWithDelay('zone_affichage_news');
    
    // Affichage des news correspondantes
    $('.zone_news').each(function()
    {
      if ($(this).hasClass(classNews))
      {
        if ($(this).css('display') == 'none')
          $(this).fadeIn(200);
      }
      else
      {
        if ($(this).css('display') != 'none')
          $(this).fadeOut(0);
      }
    });
  }
}