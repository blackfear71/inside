// Au chargement du document
$(document).ready(function()
{
  $('#progress_circle_header').circlize(
  {
		radius: 31,
    percentage: $('#progress_circle_header').attr('data-perc'),
		text: $('#progress_circle_header').attr('data-text'),
    min: $('#progress_circle_header').attr('data-perc'),
    max: 100,
    typeUse: "useText",
		useAnimations: false,
		useGradient: false,
		background: "#d3d3d3",
		foreground: "#ff1937",
		stroke: 3,
		duration: 1000
	});
});

// Masque la fenêtre des alertes
function masquerAlerte(id)
{
  $('#' + id).fadeOut(200);
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

// Changement thème
function changeTheme(background, header, footer, logo)
{
  if (background != "")
  {
    document.body.style.backgroundImage  = "url(" + background + "), linear-gradient(transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px), linear-gradient(90deg, transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px)";
    document.body.style.backgroundRepeat = "repeat-y, repeat, repeat";
    document.body.style.backgroundSize   = "100%, 100% 200px, 200px 100%";
  }

  if (header != "")
  {
    document.getElementsByClassName("zone_bandeau")[0].style.backgroundImage  = "url('" + header + "')";
    document.getElementsByClassName("zone_bandeau")[0].style.backgroundRepeat = "repeat-x";
  }

  if (footer != "")
  {
    document.getElementsByTagName("footer")[0].style.backgroundImage  = "url('" + footer + "')";
    document.getElementsByTagName("footer")[0].style.backgroundRepeat = "repeat-x";
  }

  if (logo != "")
  {
    var image  = document.getElementById("logo_inside_header");
    var source = image.getAttribute("src");
    image.setAttribute("src", logo);
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

// Gestion des cookies
function setCookie(cookieName, cookieValue)
{
  // Date expiration cookie (1 jour)
  var today   = new Date();
  var expires = new Date();

  expires.setTime(today.getTime() + (1*24*60*60*1000));

  // Cookie global (path=/)
  document.cookie = cookieName + "=" + encodeURIComponent(cookieValue) + ";expires=" + expires.toGMTString() + ";path=/";
}

function getCookie(cookieName)
{
  var name          = cookieName + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca            = decodedCookie.split(';');

  for(var i = 0; i < ca.length; i++)
  {
    var c = ca[i];

    while (c.charAt(0) == ' ')
    {
      c = c.substring(1);
    }

    if (c.indexOf(name) == 0)
      return c.substring(name.length, c.length);
  }

  return null;
}

function deleteCookie(cookieName)
{
  document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain=' + location.host;
}

// Quand le document est prêt
$(function()
{
  // Redimensionne la zone de recherche quand sélectionnée et la referme quand on clique n'importe où sur le body
  $("body").click(function()
  {
    // Barre de recherche
    if ($('#resizeBar') != null && $('#color_search') != null)
    {
      $('#resizeBar').css('width', '300px');
      $("#resizeBar").css('transition', 'width ease 0.4s');
      $("#color_search").css('background-color', '#e3e3e3');
      $("#color_search").css('transition', 'background-color ease 0.4s');
    }
  });
  $('#color_search').click(function(event)
  {
    if ($('#resizeBar') != null && $('#color_search') != null)
    {
      $("#resizeBar").css('width', '100%');
      $("#resizeBar").css('transition', 'width ease 0.4s');
      $("#color_search").css('background-color', 'white');
      $("#color_search").css('transition', 'background-color ease 0.4s');
      event.stopPropagation();
    }
  });

  // Mise à jour du ping à chaque chargement de page et toutes les minutes (si page ouverte)
  updatePing();
  setInterval(updatePing, 60000);

  function updatePing()
  {
    $.post('/inside/includes/functions/ping.php', {function: 'updatePing'});
  }
});

// Fonction équivalente au $_GET en php
function $_GET(param)
{
	var vars = {};
	window.location.href.replace(location.hash, '').replace(
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function(m, key, value)
    {
      // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if (param)
  {
		return vars[param] ? vars[param] : null;
	}
	return vars;
}

// Positionnement du scroll vertical en fonction de l'id et de l'offset (en px)
function scrollToId(id, offset)
{
  if (offset == null)
    offset = 0;

  if (id != null && id.length > 0)
  {
    var anchor = $('#' + id);

    // On récupère la position en Y de l'ancre
    var posY = anchor.offset().top;

    // On défini la vitesse d'animation et la position finale
    var speed     = 750;
    var posScroll = posY - offset;

    // On lance l'animation
    $('html, body').animate({scrollTop: posScroll}, speed);
  }
}
