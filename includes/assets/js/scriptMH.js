// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_home').css('display', 'block');

  // On lance Masonry et le scroll après avoir chargé les images
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

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 20;

  // Scroll vers l'id
  scrollToId(id, offset);
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
  $("#datepicker").datepicker(
  {
    firstDay: 1,
    altField: "#datepicker",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy'
  });

  $("#datepicker2").datepicker(
  {
    firstDay: 1,
    altField: "#datepicker2",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy'
  });

  $("#datepicker3").datepicker(
  {
    firstDay: 1,
    altField: "#datepicker3",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy'
  });
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
