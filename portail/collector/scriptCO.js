// Affiche la saisie d'un phrase culte
function afficherMasquerSaisiePhraseCulte()
{
  if (document.getElementById('zone_add_collector').style.display == "none")
    document.getElementById('zone_add_collector').style.display = "block";
  else
    document.getElementById('zone_add_collector').style.display = "none";
}

// Affiche la saisie d'une image
function afficherMasquerSaisieImage()
{
  if (document.getElementById('zone_add_image').style.display == "none")
    document.getElementById('zone_add_image').style.display = "block";
  else
    document.getElementById('zone_add_image').style.display = "none";
}

// Insère une prévisualisation de l'image sur la zone
var loadFile = function(event)
{
  var output = document.getElementById('image_collector');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.src.SizeHeight = "115px";
};

// Affiche ou masque un élément
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Affiche ou masque la zone de saisie "Autre" (insertion)
function afficherOther(td, select, id, required)
{
  if (document.getElementById(select).value == "other")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(id).style.display = "table-cell";
      document.getElementById(required).required = true;
      document.getElementById(td).style.width = "19%";
      document.getElementById(select).style.paddingLeft = "2%";
      document.getElementById(select).style.paddingRight = "2%";
    }
  }
  else
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(required).required = false;
    document.getElementById(td).style.width = "50%";
    document.getElementById(select).style.paddingLeft = "1%";
    document.getElementById(select).style.paddingRight = "1%";
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if (document.getElementById(select).value == "other")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(id).style.display = "block";
      document.getElementById(id).required = true;
    }
  }
  else
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(id).required = false;
  }
}

// Combiné avec afficherMasquer(), cela permet de fermer le formulaire en cliquant n'importe où sur le body
$(function()
{
  $("body").click(function()
  {
    $(document.getElementsByName("form_vote_user")).hide();
    $(document.getElementsByName("vote_user")).show();
  });
  $(document.getElementsByName("vote_user")).click(function(event)
  {
    $(document.getElementById("modifier_vote")).show();
    event.stopPropagation();
  });
})

// Génère un ou plusieurs calendrier
$(function()
{
  $("#datepicker1").datepicker(
  {
    firstDay: 1,
    altField: "#datepicker1",
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

  $('.modify_date_collector').each(function(){
      $(this).datepicker(
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
  });
});

// Appel "Masonry"
$(document).ready(function()
{
  // On lance Masonry après avoir chargé les images
  $('.zone_collectors').imagesLoaded(function()
  {
    $('.zone_collectors').masonry({
      // Options
      itemSelector: '.zone_collector',
      columnWidth: 525,
      fitWidth: true,
      gutter: 20,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_collectors').addClass('masonry');

    // On récupère le nom de l'ancre
    var nameHash = window.location.href;
    nameHash = nameHash.replace(/^.*#/, '');
    var anchor = $('#' + nameHash);

    if (anchor.length > 0)
    {
      // On récupère la position en Y de l'ancre
      var posY = anchor.offset().top;

      // On défini la vitesse d'animation et la position finale
      var speed = 750;
      var posScroll = posY - 70;

      // On lance l'animation
      $('html, body').animate({scrollTop: posScroll}, speed);
    }
  });
});

function initMasonry()
{
  // On lance Masonry
  $('.zone_collectors').masonry({
    // Options
    itemSelector: '.zone_collector',
    columnWidth: 525,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
    /*transitionDuration: 0*/
  });

  // Découpe le texte si besoin
  $('.text_collector').wrapInner();
}
