// Affiche ou masque la modification de phrase culte
function afficherMasquerRow(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table";
  else
    document.getElementById(id).style.display = "none";
}

// Affiche ou masque la modification du vote
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
      document.getElementById(td).style.width = "20%";
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
  $("#datepickerSaisie").datepicker(
  {
    firstDay: 1,
    altField: "#datepickerSaisie",
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

$(document).ready(function()
{
  $('.zone_collectors').masonry({
    // Options
    itemSelector: '.zone_collector',
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
  });
});
