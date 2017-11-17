// Masque la fenêtre des alertes
function masquerAlerte(id)
{
  document.getElementById(id).style.display = "none";
}

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function afficherIndex(id_open, id_close)
{
  if (document.getElementById(id_open).style.display == "none")
    document.getElementById(id_open).style.display = "block";

  if (document.getElementById(id_close).style.display == "block")
    document.getElementById(id_close).style.display = "none";
}

// Masque la fenêtre d'inscription ou de mot de passe perdu
function masquerIndex(id)
{
  if (document.getElementById(id).style.display == "block")
    document.getElementById(id).style.display = "none";
}

// Affiche ou masque le menu latéral gauche + rotation icône menu
function deployLeftMenu(id, icon1, icon2, icon3, icon4)
{
  document.getElementById(id).style.transition = "all ease 0.4s";
  document.getElementById(icon1).style.transition = "all ease 0.2s";
  document.getElementById(icon2).style.transition = "all ease 0.2s";
  document.getElementById(icon3).style.transition = "all ease 0.2s";
  document.getElementById(icon4).style.transition = "all ease 0.2s";

  if (document.getElementById(id).style.marginLeft != "0px")
  {
    document.getElementById(id).style.marginLeft   = "0px";
    document.getElementById(icon1).style.transform = "rotateZ(90deg)";
    document.getElementById(icon2).style.opacity   = "1";
    document.getElementById(icon3).style.opacity   = "1";
    document.getElementById(icon4).style.opacity   = "1";

  }
  else
  {
    document.getElementById(id).style.marginLeft   = "-83px";
    document.getElementById(icon1).style.transform = "rotateZ(0deg)";
    document.getElementById(icon2).style.opacity   = "0";
    document.getElementById(icon3).style.opacity   = "0";
    document.getElementById(icon4).style.opacity   = "0";
  }
}

// Affiche ou masque le menu de navigation + rotation icône menu
function deployTopMenu(id, icon)
{
  document.getElementById(id).style.transition = "all ease 0.4s";
  document.getElementById(icon).style.transition = "all ease 0.4s";

  if (document.getElementById(id).style.marginTop != "0px")
  {
    document.getElementById(id).style.marginTop   = "0px";
    document.getElementById(icon).style.transform = "rotateZ(180deg)";
  }
  else
  {
    document.getElementById(id).style.marginTop   = "-83px";
    document.getElementById(icon).style.transform = "rotateZ(0deg)";
  }
}

// Redimensionne la zone de recherche quand sélectionnée et la refemre quand on clique n'importe où sur le body
$(function()
{
  $("body").click(function()
  {
    if (document.getElementById("resizeBar") != null && document.getElementById("color_search") != null)
    {
      document.getElementById("resizeBar").style.width = "300px";
      document.getElementById("resizeBar").style.transition = "width ease 0.4s";
      document.getElementById("color_search").style.backgroundColor = "#e3e3e3";
      document.getElementById("color_search").style.transition = "background-color ease 0.4s";
    }
  });
  $(document.getElementById("color_search")).click(function(event)
  {
    if (document.getElementById("resizeBar") != null && document.getElementById("color_search") != null)
    {
      document.getElementById("resizeBar").style.width = "100%";
      document.getElementById("resizeBar").style.transition = "width ease 0.4s";
      document.getElementById("color_search").style.backgroundColor = "white";
      document.getElementById("color_search").style.transition = "background-color ease 0.4s";
      event.stopPropagation();
    }
  });
})
