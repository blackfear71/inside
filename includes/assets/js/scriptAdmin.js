// Au chargement du document complet
$(window).on('load', function()
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
  var id_theme     = $_GET('anchorTheme');
  var offset_theme = 30;

  // Scroll vers l'id
  scrollToId(id_theme, offset_theme);

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

  // On n'affiche la zone des succès qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_succes_admin').css('display', 'block');

  // On lance Masonry après avoir chargé les images (Succès)
  $('.zone_niveau_succes_admin').masonry({
    // Options
    itemSelector: '.ensemble_succes',
    columnWidth: 180,
    fitWidth: true,
    gutter: 10,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_niveau_succes_admin').addClass('masonry');

  // On lance Masonry après avoir chargé les images (Modification succès)
  $('.zone_niveau_mod_succes_admin').masonry({
    // Options
    itemSelector: '.succes_liste_mod',
    columnWidth: 320,
    fitWidth: true,
    gutter: 25,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_niveau_mod_succes_admin').addClass('masonry');
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

// Affiche ou masque les lignes de visualisation/modification du tableau
function afficherMasquerRow(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table-row";
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
  $("#datepicker_saisie_deb, #datepicker_saisie_fin").datepicker(
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

  $('.modify_date_deb_theme, .modify_date_fin_theme').each(function()
  {
    $(this).datepicker(
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
  });
});
