/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  $('#progress_circle_header').circlize(
  {
		radius: 32,
    percentage: $('#progress_circle_header').attr('data-perc'),
		text: $('#progress_circle_header').attr('data-text'),
    min: $('#progress_circle_header').attr('data-perc'),
    max: 100,
    typeUse: "useText",
		useAnimations: false,
		useGradient: false,
		background: "white",
		foreground: "#ff1937",
		stroke: 3,
		duration: 1000
	});

  // Mise à jour du ping à chaque chargement de page et toutes les minutes (si page ouverte)
  updatePing();
  setInterval(updatePing, 60000);

  /*** Actions au clic ***/
  // Referme la barre de recherche quand on clique n'importe où sur le body
  $('body').click(function()
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

  // Redimensionne la zone de recherche quand sélectionnée et gère le changement de couleur
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

  // Bouton fermer alerte
  $('#boutonFermer').click(function()
  {
    masquerAlerte('alerte');
  });

  // Messages de confirmation
  $('.eventConfirm').click(function()
  {
    var id_form = $(this).closest('form').attr('id');
    var message = $(this).closest('form').find('.eventMessage').val();

    if(!confirmAction(id_form, message))
      return false;
  });

  // Valider confirmation
  $(document).on('click', '#boutonAnnuler', function()
  {
    var action_form = $('#actionForm').val();

    executeAction(action_form, 'cancel');
  });

  // Annuler confirmation
  $(document).on('click', '#boutonConfirmer', function()
  {
    var action_form = $('#actionForm').val();

    executeAction(action_form, 'validate');
  });

  // Déployer menu latéral
  $('#menuLateral').click(function()
  {
    deployLeftMenu('left_menu', 'icon_menu_m', 'icon_menu_e', 'icon_menu_n', 'icon_menu_u');
  });
});

/*****************/
/*** Fonctions ***/
/*****************/
// Masque la fenêtre des alertes
function masquerAlerte(id)
{
  $('#' + id).fadeOut(200);
}

// Affiche ou masque le menu latéral gauche + rotation icône menu
function deployLeftMenu(id, icon1, icon2, icon3, icon4)
{
  $('#' + id).css('transition', 'all ease 0.4s');
  $('#' + icon1).css('transition', 'all ease 0.2s');
  $('#' + icon2).css('transition', 'all ease 0.2s');
  $('#' + icon3).css('transition', 'all ease 0.2s');
  $('#' + icon4).css('transition', 'all ease 0.2s');

  if ($('#' + id).css('margin-left') != '0px')
  {
    $('#' + id).css('margin-left', '0px');
    $('#' + icon1).css('transform', 'rotateZ(90deg)');
    $('#' + icon2).css('opacity', '1');
    $('#' + icon3).css('opacity', '1');
    $('#' + icon4).css('opacity', '1');
  }
  else
  {
    $('#' + id).css('margin-left', '-83px');
    $('#' + icon1).css('transform', 'rotateZ(0deg)');
    $('#' + icon2).css('opacity', '0');
    $('#' + icon3).css('opacity', '0');
    $('#' + icon4).css('opacity', '0');
  }
}

// Changement thème
function changeTheme(background, header, footer, logo)
{
  if (background != "")
  {
    $('body').css('background-image', 'url(' + background + '), linear-gradient(transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px), linear-gradient(90deg, transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px)');
    $('body').css('background-repeat', 'repeat-y, repeat, repeat');
    $('body').css('background-size', '100%, 100% 200px, 200px 100%');
  }

  if (header != "")
  {
    $('.zone_bandeau').css('background-image', 'url(' + header + ')');
    $('.zone_bandeau').css('background-repeat', 'repeat-x');
  }

  if (footer != "")
  {
    $('footer').css('background-image', 'url(' + footer + ')');
    $('footer').css('background-repeat', 'repeat-x');
  }

  if (logo != "")
    $('#logo_inside_header').attr('src', logo);
}

// Colorise la barre de recherche au survol
function changeColorToWhite(id)
{
  $('#' + id).css('background-color', 'white');
  $('#' + id).css('transition', 'background-color ease 0.2s');
}

function changeColorToGrey(id, active)
{
  if (document.getElementById(active).style.width != '100%')
  {
    $('#' + id).css('background-color', '#e3e3e3');
    $('#' + id).css('transition', 'background-color ease 0.2s');
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

// Exécute le script php de mise à jour du ping
function updatePing()
{
  $.post('/inside/includes/functions/ping.php', {function: 'updatePing'});
}

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
function scrollToId(id, offset, shadow = false)
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

    // Affichage d'une ombre pour un id "#zone_shadow_id"
    if (shadow == true)
    {
      // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
      $('#zone_shadow_' + id).css('box-shadow', '0 3px 10px #262626');

      setTimeout(function()
      {
        $('#zone_shadow_' + id).css('box-shadow', '0 0 3px #7c7c7c');
        $('#zone_shadow_' + id).css({transition : "box-shadow ease 0.2s"});
      }, 5000);
    }
  }
}

// Ouvre une fenêtre de confirmation
function confirmAction(form, message)
{
  if ($('#confirmBox').length)
    $('#confirmBox').remove();

  var html = "";

  html += '<div class="message_alerte" id="confirmBox">';
    html += '<input type="hidden" id="actionForm" value="' + form + '" />';

    html += '<div class="inside_alerte">';
      html += 'Inside';
    html += '</div>';

    html += '<div class="texte_alerte">';
      html += '<img src="/inside/includes/icons/common/question.png" alt="question" title="Confirmer ?" class="logo_alerte" />';
      html += message;
    html += '</div>';

    html += '<div class="boutons_alerte">';
      html += '<a id="boutonAnnuler" class="bouton_alerte">Annuler</a>';
      html += '<a id="boutonConfirmer" class="bouton_alerte">Oui</a>';
    html += '</div>';
  html += '</div>';

  $('body').append(html);
}

// Ferme la fenêtre ou execute le formulaire
function executeAction(form, action)
{
  if (action == 'cancel')
  {
    $('#confirmBox').fadeOut(200, function()
    {
      $(this).remove();
    });
  }
  else
    $('#' + form).submit();
}
