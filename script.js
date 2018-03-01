// Masque la fenêtre des alertes
function masquerAlerte(id)
{
  document.getElementById(id).style.display = "none";
}

// Affiche la fenêtre d'inscription ou de mot de passe perdu (en fermant l'autre)
function afficherIndex(id_open, id_close)
{
  document.getElementById(id_open).style.display    = "block";
  document.getElementById(id_open).style.marginLeft = "39.5%";
  document.getElementById(id_open).style.transition = "margin-left 1s";

  document.getElementById(id_close).style.marginLeft = "-100%";
}

// Masque la fenêtre d'inscription ou de mot de passe perdu
function masquerIndex(id)
{
  document.getElementById(id).style.marginLeft = "-100%";
}

// Affiche ou masque le menu latéral gauche + rotation icône menu
function deployLeftMenu(id, icon1, icon2, icon3, icon4)
{
  document.getElementById(id).style.transition    = "all ease 0.4s";
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
  document.getElementById(id).style.transition   = "all ease 0.4s";
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

// Changement thème
function changeTheme(background, header, footer)
{
  if (background != null)
  {
    document.body.style.backgroundImage  = "url(" + background + "), linear-gradient(transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px), linear-gradient(90deg, transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px)";
    document.body.style.backgroundRepeat = "repeat-y, repeat, repeat";
    document.body.style.backgroundSize   = "100%, 100% 200px, 200px 100%";
  }

  if (header != null)
  {
    document.getElementsByClassName("zone_bandeau")[0].style.backgroundImage  = "url('" + header + "')";
    document.getElementsByClassName("zone_bandeau")[0].style.backgroundRepeat = "repeat-x";
  }

  if (footer != null)
  {
    document.getElementsByTagName("footer")[0].style.backgroundImage  = "url('" + footer + "')";
    document.getElementsByTagName("footer")[0].style.backgroundRepeat = "repeat-x";
  }
}

// Colorise la barre de recherche au survol
function changeColorToWhite(id)
{
  document.getElementById(id).style.backgroundColor = "white";
  document.getElementById(id).style.transition      = "background-color ease 0.2s";
}

function changeColorToGrey(id, active)
{
  if (document.getElementById(active).style.width != "100%")
  {
    document.getElementById(id).style.backgroundColor = "#e3e3e3";
    document.getElementById(id).style.transition      = "background-color ease 0.2s";
  }
}

// Redimensionne la zone de recherche quand sélectionnée et la refemre quand on clique n'importe où sur le body
$(function()
{
  $("body").click(function()
  {
    // Barre de recherche
    if (document.getElementById("resizeBar") != null && document.getElementById("color_search") != null)
    {
      document.getElementById("resizeBar").style.width              = "300px";
      document.getElementById("resizeBar").style.transition         = "width ease 0.4s";
      document.getElementById("color_search").style.backgroundColor = "#e3e3e3";
      document.getElementById("color_search").style.transition      = "background-color ease 0.4s";
    }
  });
  $(document.getElementById("color_search")).click(function(event)
  {
    if (document.getElementById("resizeBar") != null && document.getElementById("color_search") != null)
    {
      document.getElementById("resizeBar").style.width              = "100%";
      document.getElementById("resizeBar").style.transition         = "width ease 0.4s";
      document.getElementById("color_search").style.backgroundColor = "white";
      document.getElementById("color_search").style.transition      = "background-color ease 0.4s";
      event.stopPropagation();
    }
  });
})
