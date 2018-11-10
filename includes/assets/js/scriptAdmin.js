// Au chargement du document
$(document).ready(function()
{
  // Déclenchement du scroll pour "anchor" : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 30;

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

  // On lance Masonry après avoir chargé les images (Thèmes)
  $('.zone_themes').imagesLoaded(function()
  {
    $('.zone_themes').masonry({
      // Options
      itemSelector: '.zone_theme',
      columnWidth: 500,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_themes').addClass('masonry');

    // Déclenchement du scroll pour "anchorTheme" : on récupère l'id de l'ancre dans l'url (fonction JS)
    var id     = $_GET('anchorTheme');
    var offset = 30;

    // Scroll vers l'id
    scrollToId(id, offset);
  });

  // On lance Masonry après avoir chargé les zones (Portail)
  $('.menu_admin').ready(function()
  {
    $('.menu_admin').masonry({
      // Options
      itemSelector: '.menu_link_admin',
      columnWidth: 300,
      fitWidth: true,
      gutter: 15,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.menu_admin').addClass('masonry');
  });

  // On lance Masonry après avoir chargé les images (Infos utilisateurs)
  $('.zone_infos').imagesLoaded(function()
  {
    $('.zone_infos').masonry({
      // Options
      itemSelector: '.zone_infos_user',
      columnWidth: 300,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_infos').addClass('masonry');
  });
});

// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // On lance Masonry
  $('.zone_themes').masonry({
    // Options
    itemSelector: '.zone_theme',
    columnWidth: 500,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });
}

// Affiche ou masque le log
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Rotation icône affichage log
function rotateIcon(id)
{
  if (document.getElementById(id).style.transform == "rotate(0deg)")
    document.getElementById(id).style.transform = "rotate(180deg)";
  else
    document.getElementById(id).style.transform = "rotate(0deg)";
}

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};

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

  $('.modify_date_deb_theme').each(function(){
      $(this).datepicker(
        {
          firstDay: 1,
          altField: "#datepicker_mod_deb",
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

  $('.modify_date_fin_theme').each(function(){
      $(this).datepicker(
        {
          firstDay: 1,
          altField: "#datepicker_mod_fin",
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
});
