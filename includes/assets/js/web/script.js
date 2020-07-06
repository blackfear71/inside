/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au chargement ***/
  // Applique le thème utilisateur si présent
  if (themeUser != null)
    changeTheme(themeUser.background, themeUser.header, themeUser.footer, themeUser.logo);

  // Affiche la barre d'expérience autour de l'avatar
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

  // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
  updatePing();
  setInterval(updatePing, 60000);

  // Mise à jour du compteur des notifications toutes les 60 secondes
  updateNotifications();
  majNotifications = setInterval(updateNotifications, 60000);

  // Mise à jour du compteur des bugs/évolutions toutes les 60 secondes
  updateBugs();
  majBugs = setInterval(updateBugs, 60000);

  // Animation symbole chargement de la page
  if ($('.zone_loading_page').length)
    loadingPage();

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
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });

  // Messages de confirmation
  $('.eventConfirm').click(function()
  {
    var id_form = $(this).closest('form').attr('id');
    var message = $(this).closest('form').find('.eventMessage').val();

    if (!confirmAction(id_form, message))
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

  // Fermeture zoom succès
  $(document).on('click', '#closeZoomSuccess', function()
  {
    masquerSupprimerIdWithDelay('zoom_succes');
  });

  /*** Actions au passage de la souris ***/
  // Changement couleur barre de recherche (entrée)
  $('#color_search').mouseover(function()
  {
    changeColorToWhite('color_search');
  });

  // Changement couleur barre de recherche (sortie)
  $('#color_search').mouseout(function()
  {
    changeColorToGrey('color_search', 'resizeBar');
  });

  // Affichage détail notifications (entrée)
  $('#afficherDetailNotifications').mouseover(function()
  {
    showNotifications();
  });

  // Affichage détail notifications (sortie)
  $('#afficherDetailNotifications').mouseout(function()
  {
    hideNotifications();
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Suppression de la barre de chargement de la page
  $('.zone_loading_page').remove();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Affiche ou masque un élément (délai 200ms)
function afficherMasquerIdWithDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerIdNoDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Affiche ou masque les lignes d'un tableau
function afficherMasquerIdRow(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).css('display', 'table-row');
  else
    $('#' + id).css('display', 'none');
}

// Masque et supprime un élément (délai 200ms)
function masquerSupprimerIdWithDelay(id)
{
  $('#' + id).fadeOut(200, function()
  {
    $(this).remove();
  });
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
  if (background != null)
  {
    $('section:not(.section_index)').css('background-image', 'url(' + background + '), linear-gradient(transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px), linear-gradient(90deg, transparent 199px, rgba(220, 220, 200, 0.6) 200px, transparent 200px)');
    $('section:not(.section_index)').css('background-repeat', 'repeat-y, repeat, repeat');
    $('section:not(.section_index)').css('background-size', '100%, 100% 200px, 200px 100%');
  }

  if (header != null)
  {
    $('.zone_bandeau').css('background-image', 'url(' + header + ')');
    $('.zone_bandeau').css('background-repeat', 'repeat-x');
  }

  if (footer != null)
  {
    $('footer').css('background-image', 'url(' + footer + ')');
    $('footer').css('background-repeat', 'repeat-x');
  }

  if (logo != null)
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

  for (var i = 0; i < ca.length; i++)
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

// Exécute le script php de mise à jour du compteur de notifications
function updateNotifications()
{
  $.get('/inside/includes/functions/notifications.php', {function: 'count_notifications'}, function(data)
  {
    var identifiant         = data.identifiant;
    var nbNotificationsJour = data.nbNotificationsJour;
    var view                = data.view;
    var page                = data.page;
    var html                = '';

    // On n'exécute de manière récurrente que si on n'est pas l'admin
    if (identifiant != 'admin')
    {
      // La première fois on génère la zone
      if (!$('.link_notifications').length)
      {
        html += '<a href="/inside/portail/notifications/notifications.php?view=all&action=goConsulter&page=1" class="link_notifications">';

          if (nbNotificationsJour > 0)
            html += '<img src="/inside/includes/icons/common/notifications.png" alt="notifications" class="icon_notifications" />';
          else
            html += '<img src="/inside/includes/icons/common/notifications_blue.png" alt="notifications" class="icon_notifications" />';

          html += '<div class="number_notifications"></div>';
        html += '</a>';

        $('.zone_notifications_bandeau').html(html);
      }

      // On met à jour le contenu
      if (nbNotificationsJour > 0)
      {
        $('.link_notifications').attr('href', '/inside/portail/notifications/notifications.php?view=' + view + '&action=goConsulter' + page)
        $('.icon_notifications').attr('src', '/inside/includes/icons/common/notifications_blue.png');

        if (nbNotificationsJour <= 9)
        {
          $('.number_notifications').html(nbNotificationsJour);
          $('.number_notifications').css('color', 'white');
        }
        else
        {
          $('.number_notifications').html('9+');
          $('.number_notifications').css('color', 'white');
        }
      }
      else
      {
        $('.link_notifications').attr('href', '/inside/portail/notifications/notifications.php?view=' + view + '&action=goConsulter' + page)
        $('.icon_notifications').attr('src', '/inside/includes/icons/common/notifications.png');
        $('.number_notifications').html('0');
        $('.number_notifications').css('color', '#262626');
      }
    }
    else
      clearInterval(majNotifications);
  }, "json");
}

// Exécute le script php de mise à jour du compteur de bugs/évolutions
function updateBugs()
{
  $.get('/inside/includes/functions/count_bugs.php', function(data)
  {
    var identifiant = data.identifiant;
    var nbBugs      = data.nbBugs;
    var html        = '';

    // On n'exécute de manière récurrente que si on n'est pas l'admin
    if (identifiant != 'admin')
    {
      if (nbBugs > 0)
      {
        // La première fois on génère la zone
        if (!$('.count_bugs').length)
        {
          html += '<div class="count_bugs">';
            html += '<div class="number_bugs"></div>';
          html += '</div>';

          $('.zone_compteur_footer').html(html);
        }

        // On met à jour le contenu
        $('.number_bugs').html(nbBugs);
      }
      else
      {
        // On efface la zone si présente
        if ($('.count_bugs').length)
          $('.count_bugs').remove();
      }
    }
    else
      clearInterval(majBugs);
  }, "json");
}

// Fonction équivalente au $_GET en php
function $_GET(param)
{
	var vars = {};

	window.location.href.replace(location.hash, '').replace(
    // Expression régulière
		/[?&]+([^=&]+)=?([^&]*)?/gi,

    // Fonction retour
		function(m, key, value)
    {
			vars[key] = value !== undefined ? value : '';
		}
	);

	if (param)
		return vars[param] ? vars[param] : null;

  // Retour
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
  // Suppression fenêtre éventuellement existante
  if ($('#confirmBox').length)
    $('#confirmBox').remove();

  // Génération nouvelle fenêtre de confirmation
  var html = '';

  html += '<div class="fond_alerte" id="confirmBox">';
    html += '<div class="zone_affichage_alerte">';
      html += '<input type="hidden" id="actionForm" value="' + form + '" />';

      html += '<div class="titre_alerte">';
        html += 'Inside';
      html += '</div>';

      html += '<div class="zone_alertes">';
        html += '<div class="zone_texte_alerte">';
          html += '<img src="/inside/includes/icons/common/question.png" alt="question" title="Confirmer ?" class="logo_alerte" />';

          html += '<div class="texte_alerte">';
            html += message;
          html += '</div>';
        html += '</div>';
      html += '</div>';

      html += '<div class="zone_boutons_alerte">';
        html += '<a id="boutonAnnuler" class="bouton_alerte">Annuler</a>';
        html += '<a id="boutonConfirmer" class="bouton_alerte">Oui</a>';
      html += '</div>';
    html += '</div>';
  html += '</div>';

  // Ajout à la page
  $('body').append(html);
}

// Ferme la fenêtre ou execute le formulaire
function executeAction(form, action)
{
  if (action == 'cancel')
    masquerSupprimerIdWithDelay('confirmBox');
  else
    $('#' + form).submit();
}

// Animation chargement de la page en boucle
function loadingPage()
{
  $('.zone_loading_page').css("padding-top", "40px");
  $('.zone_loading_page').css("padding-bottom", "40px");
  $('#loading_page').css("height", "5px");
  $('#loading_page').css("margin-left", 0);
  $('#loading_page').css("opacity", 1);

  $('#loading_page').animate(
  {
    width: "+=100%",
    marginLeft: "0%"
  }, 800, "easeInOutCubic", function()
  {
    $('#loading_page').animate(
    {
      width: "-=100%",
      marginLeft: "100%"
    }, 800, "easeInOutCubic", function()
    {
      $('#loading_page').css("opacity", 0);

      setTimeout(function()
      {
        loadingPage();
      }, 200);
    });
  });
}

// Cache un bouton de soumission si tous les champs obligatoires sont renseignés
function hideSubmitButton(zone, button, form, tabBlock)
{
  var hideButton = true;

  // On vérifie chaque saisie obligatoire
  form.find('input, textarea, select').each(function()
  {
    if ($(this).prop('required') == true && $(this).val() == "")
    {
      hideButton = false;
      return false;
    }
  });

  if (hideButton == true)
  {
    // On fait disparaitre le bouton
    button.css('display', 'none');

    // On bloque les saisies
    form.find('input, textarea, select, label').each(function()
    {
      $(this).prop('readonly', true);
      $(this).css('pointer-events', 'none');
      $(this).css('color', '#a3a3a3');
    });

    // Blocage des saisies spécifiques à partir d'un tableau
    if (tabBlock != null)
    {
      $.each(tabBlock, function()
      {
        var element  = $(this).attr('element');
        var property = $(this).attr('property');
        var value    = $(this).attr('value');

        if ($(element).length)
          $(element).css(property, value);
      });
    }

    // On ajoute le symbole de chargement
    var loading = '';

    loading += '<div class="zone_loading_form">';
      loading += '<div id="loading_form" class="loading_form"></div>';
    loading += '</div>';

    zone.append(loading);

    // On lance l'animation
    loadingForm(zone);
  }
}

// Animation chargement soumission formulaire
function loadingForm(zone)
{
  // On récupère la classe CSS du parent dans le cas où il y a plusieurs symboles possibles sur la même page
  var classZone = zone.attr('class');

  $('.' + classZone + ' .loading_form').css("height", "5px");
  $('.' + classZone + ' .loading_form').css("margin-left", 0);
  $('.' + classZone + ' .loading_form').css("opacity", 1);

  $('.' + classZone + ' .loading_form').animate(
  {
    width: "+=100%",
    marginLeft: "0%"
  }, 800, "easeInOutCubic", function()
  {
    $('.' + classZone + ' .loading_form').animate(
    {
      width: "-=100%",
      marginLeft: "100%"
    }, 800, "easeInOutCubic", function()
    {
      $('.' + classZone + ' .loading_form').css("opacity", 0);

      setTimeout(function()
      {
        loadingForm(zone);
      }, 200);
    });
  });
}

// Affiche le détail des notifications
function showNotifications()
{
  $.get('/inside/includes/functions/notifications.php', {function: 'get_details_notifications'}, function(data)
  {
    var identifiant            = data.identifiant;
    var nbNotificationsJour    = data.nbNotificationsJour;
    var nbNotificationsSemaine = data.nbNotificationsSemaine;
    var html                   = '';

    // On n'affiche le détail que si on n'est pas l'admin
    if (identifiant != 'admin')
    {
      var html = '';

      // Nombre de notifications
      html += '<div class="zone_details_notifications">';
        // Flèche
        html += '<div class="triangle_details_notifications"></div>';

        html += '<div class="compteurs_details_notifications">';
          // Notifications du jour
          html += '<div class="ligne_details_notifications">';
            if (nbNotificationsJour == 1)
              html += '<div class="number_notifications_details">' + nbNotificationsJour + '</div> notification aujourd\'hui';
            else
              html += '<div class="number_notifications_details">' + nbNotificationsJour + '</div> notifications aujourd\'hui';
          html += '</div>';

          // Notifications de la semaine
          html += '<div class="ligne_details_notifications">';
            if (nbNotificationsSemaine == 1)
              html += '<div class="number_notifications_details">' + nbNotificationsSemaine + '</div> notification cette semaine';
            else
              html += '<div class="number_notifications_details">' + nbNotificationsSemaine + '</div> notifications cette semaine';
          html += '</div>';
        html += '</div>';
      html += '</div>';

      $('#afficherDetailNotifications').append(html);
    }
  }, "json");
}

// Cache le nombre de notifications
function hideNotifications()
{
  $('.zone_details_notifications').remove();
}

// Génère le chemin vers l'avatar
function formatAvatar(avatar, pseudo, niveau, alt)
{
  var level;
  var path;

  // Niveau chemin
  switch (niveau)
  {
    case 1:
      level = "..";
      break;

    case 2:
      level = "../..";
      break;

    case 0:
    default:
      level = "/inside";
      break;
  }

  // Chemin
  if (avatar != "" && avatar != undefined)
    path = level + "/includes/images/profil/avatars/" + avatar;
  else
    path = level + "/includes/icons/common/default.png";

  // Pseudo
  pseudo = formatUnknownUser(pseudo, true, false);

  // Formatage
  var formattedAvatar = {"path" : path, "alt" : alt, "title" : pseudo};

  return formattedAvatar;
}

// Formate une chaîne de caractères en longueur
function formatString(string, limit)
{
  if (string.length > limit)
    string = string.substr(0, limit) + "...";

  return string;
}

// Formate le pseudo utilisateur désinscrit
function formatUnknownUser(pseudo, majuscule, italique)
{
  if (pseudo == "")
  {
    if (majuscule == true)
    {
      if (italique == true)
        pseudo = "<i>Un ancien utilisateur</i>";
      else
        pseudo = "Un ancien utilisateur";
    }
    else
    {
      if (italique == true)
        pseudo = "<i>un ancien utilisateur</i>";
      else
        pseudo = "un ancien utilisateur";
    }
  }

  return pseudo;
}

// Prend en compte les sauts de ligne
function nl2br(chaine)
{
  // Remplacement des sauts de ligne
  var nl2br = chaine.replace(/(\r\n|\n\r|\r|\n)/g, '<br />');

  // Retour
  return nl2br;
}

// Formate un montant pour affichage
function formatAmountForDisplay(amount, withCurrency)
{
  // Initialisation de la devise
  if (withCurrency == true)
    currency = ' €';
  else
    currency = '';

  // Conversion en numérique
  var amountNumeric = parseFloat(amount.replace(',', '.'));

  // Formatage avec 2 chiffres après la virgule
  var amountRounded = amountNumeric.toFixed(2);

  // Formatage en chaîne
  var amountFormatted = amountRounded.replace('.', ',') + currency;

  // Retour
  return amountFormatted;
}
