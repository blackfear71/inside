// Au chargement du document complet (on lance Masonry et le scroll après avoir chargé les images)
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_home').css('display', 'block');

  // Masonry
  if ($('.zone_films_accueil').length)
  {
    $('.zone_films_accueil').masonry({
      // Options
      itemSelector: '.zone_film_accueil',
      columnWidth: 250,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_films_accueil').addClass('masonry');
  }

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 20;
  var shadow = false;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

// Affiche ou masque les films cachés
function afficherMasquerTbody(id, hidden)
{
  if (document.getElementById(hidden).innerHTML == '<div class="symbol_hidden">+</div> Films cachés')
    document.getElementById(hidden).innerHTML = '<div class="symbol_hidden">-</div> Films cachés';
  else
    document.getElementById(hidden).innerHTML = '<div class="symbol_hidden">+</div> Films cachés';

  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table-row-group";
  else
    document.getElementById(id).style.display = "none";
}

// Affiche ou masque la saisie des étoiles
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Génère un calendrier
$(function()
{
  if ($("#datepicker_sortie_1").length || $("#datepicker_sortie_2").length || $("#datepicker_doodle").length)
  {
    $("#datepicker_sortie_1, #datepicker_sortie_2, #datepicker_doodle").datepicker(
    {
      autoHide: true,
      language: 'fr-FR',
      format: 'dd/mm/yyyy',
      weekStart: 1,
      days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      daysShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
      daysMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
      monthsShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']
    });
  }
});

// Insère un smiley dans la zone de saisie
function insert_smiley(smiley, id)
{
  // Emplacement
  var where = document.getElementById(id);

  // Texte à insérer + espace
  var phrase = smiley + " ";

  // Contenu déjà présent + Texte à insérer
  where.value += phrase;

  // Positionnement du curseur
  where.focus();
}
