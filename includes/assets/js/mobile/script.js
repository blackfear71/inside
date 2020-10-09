/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /** Actions au chargement ***/
  // Forçage taille écran (viewport)
  fixViewport();

  // Initialisation de la position Celsius
  initPositionCelsius();

  // Animation symbole chargement de la page
  loadingPage();
  loadPage = setInterval(loadingPage, 100);

  // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
  updatePing();
  majPing = setInterval(updatePing, 60000);

  /*** Actions au clic ***/
  // Ouverture menu latéral gauche
  $('#deployAsidePortail').click(function()
  {
    deployerMenuPortail();
  });

  // Ouverture menu latéral droit
  $('#deployAsideUser').click(function()
  {
    deployerMenuUser();
  });

  // Déplie une zone en cliquant sur le titre
  $('.titre_section').click(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, '');
  });

  // Ferme un menu au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme le menu latéral gauche
    if ($(event.target).attr('class')   != 'aside_portail'
    &&  $(event.target).attr('class')   != 'lien_aside'
    &&  $(event.target).attr('class')   != 'icone_aside'
    &&  $(event.target).attr('class')   != 'titre_aside'
    &&  $('.aside_portail').css('left') == '0px')
      deployerMenuPortail();

    // Ferme le menu latéral droit
    if ($(event.target).attr('class') != 'aside_user'
    &&  $(event.target).attr('class') != 'lien_aside'
    &&  $(event.target).attr('class') != 'icone_aside'
    &&  $(event.target).attr('class') != 'titre_aside'
    &&  $(event.target).attr('class') != 'zone_infos_user_aside'
    &&  $(event.target).attr('class') != 'niveau_aside'
    &&  $(event.target).attr('class') != 'pseudo_aside'
    &&  $('.aside_user').css('right') == '0px')
      deployerMenuUser();

    // Ferme le contenu Celsius
    if ($(event.target).attr('class')       != 'zone_contenu_celsius'
    &&  $(event.target).attr('class')       != 'titre_contenu_celsius'
    &&  $(event.target).attr('class')       != 'zone_texte_celsius'
    &&  $(event.target).attr('class')       != 'texte_contenu_celsius'
    &&  $(event.target).attr('class')       != 'zone_boutons_celsius'
    &&  $(event.target).attr('class')       != 'bouton_celsius'
    &&  $(event.target).attr('class')       != 'celsius'
    &&  $('#contenuCelsius').css('display') != 'none')
      afficherMasquerIdWithDelay('contenuCelsius');

    // Ferme une zone de saisie ou de détails
    if ($(event.target).attr('class') == 'fond_saisie'
    ||  $(event.target).attr('class') == 'fond_details')
      afficherMasquerIdWithDelay(event.target.id);
  });

  // Bouton fermer alerte
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });

  // Messages de confirmation
  $('.eventConfirm').click(function()
  {
    // Fermeture des détails si besoin
    if ($('.fond_details').css('display') != 'none')
    {
      var idDetails = $('.fond_details').attr('id');
      afficherMasquerIdWithDelay(idDetails);
    }

    // Affichage du message de confirmation
    var idForm  = $(this).closest('form').attr('id');
    var message = $(this).closest('form').find('.eventMessage').val();

    if (!confirmAction(idForm, message))
      return false;
  });

  // Valider confirmation
  $(document).on('click', '#boutonAnnuler', function()
  {
    var actionForm = $('#actionForm').val();

    executeAction(actionForm, 'cancel');
  });

  // Annuler confirmation
  $(document).on('click', '#boutonConfirmer', function()
  {
    var actionForm = $('#actionForm').val();

    executeAction(actionForm, 'validate');
  });

  // Ferme le contenu Celsius
  $('#closeCelsius').click(function()
  {
    afficherMasquerIdWithDelay('contenuCelsius');
  });

  // Efface la zone de recherche au clic sur la croix
  $('#reset_recherche_live').click(function()
  {
    resetLiveSearch();
  });

  /*** Actions du clavier ***/
  // Filtre la recherche
  $('#recherche_live').keyup(function()
	{
    var inputContent = $.trim($(this).val());

    liveSearch(inputContent);
  });

  /*** Actions sur mobile ***/
  // Positionnement top début maintien clic et positions initiales
  $('.celsius').on('touchstart', function(e)
  {
    e.preventDefault();

    // Initialisation Celsius
    touchStartCelsius($(this), e);
  });

  // Positionnement top fin maintien clic
  $('.celsius').on('touchend',function(e)
  {
    e.preventDefault();

    // Fin Celsius
    touchEndCelsius($(this));
  });

  // Déplacement du bloc
  $('.celsius').on('touchmove', function(e)
  {
    e.preventDefault();

    // Déplacement Celsius
    touchMoveCelsius($(this), e);
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Remplacement du chargement par le contenu
  endLoading();
});

// Remise en place Celsius au changement d'orientation
$(window).on('orientationchange', function(e)
{
  // Forçage taille écran (viewport)
  if (e.orientation == 'landscape')
    fixViewport();

  // Réinitialsiation position Celsius
  initPositionCelsius();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Fige la taille de l'écran
function fixViewport()
{
  var viewHeight;
  var viewWidth;

  // On inverse hauteur et largeur si on change l'orientation de l'écran pour conserver des proportions identiques
  if ($(window).height() < $(window).width())
  {
    viewHeight = $(window).width();
    viewWidth  = $(window).height();
  }
  else
  {
    viewHeight = $(window).height();
    viewWidth  = $(window).width();
  }

  var viewport = document.querySelector('meta[name=viewport]');

  viewport.setAttribute('content', 'height=' + viewHeight + 'px, width=' + viewWidth + 'px, initial-scale=1.0');
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

// Positionnement du scroll vertical en fonction de l'id et de l'offset (en % de la hauteur <=> vh)
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
    var posScroll = posY - $(window).height() * offset;

    // On lance l'animation
    $('html, body').animate({scrollTop: posScroll}, speed);

    // Affichage d'une ombre pour un id "#zone_shadow_id"
    if (shadow == true)
    {
      // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
      $('#zone_shadow_' + id).css('box-shadow', '0 0 1vh #262626');

      setTimeout(function()
      {
        $('#zone_shadow_' + id).css('box-shadow', '0 0 0.5vh #7c7c7c');
        $('#zone_shadow_' + id).css({transition : 'box-shadow ease 0.2s'});
      }, 5000);
    }
  }
}

// Insère une prévisualisation de l'image sur la zone correspondante
function loadFile(event, id, rotation)
{
  var output = $('#' + id)[0];
  output.src = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  if (rotation == true)
  {
    EXIF.getData(event.target.files[0], function()
    {
      var orientation = EXIF.getTag(this, 'Orientation');
      var degrees;

      switch (orientation)
      {
        case 3:
          degrees = 180;
          break;

        case 6:
          degrees = -90;
          break;

        case 8:
          degrees = 90;
          break;

        case 1:
        default:
          degrees = 0;
          break;
      }

      output.setAttribute('style', 'transform: rotate(' + degrees + 'deg)');
    });
  }
};

// Initialisation de la position Celsius
function initPositionCelsius()
{
  $('.celsius').css('top', 'calc(100% - 16vh)');
  $('.celsius').css('left', 'calc(100% - 9vh)');
}

// Détermination partie de l'écran
function isUpScreen()
{
  var isUpScreen        = true;
  var celsiusTopPosY    = $('.celsius').position().top;
  var halfHeightCelsius = $('.celsius').height() / 2;

  var celsiusCenterPosY = celsiusTopPosY + halfHeightCelsius;
  var halfHeightScreen  = $(window).height() / 2;

  if (celsiusCenterPosY > halfHeightScreen)
    isUpScreen = false;

  return isUpScreen;
}

// Initialisations Celsius au clic
function touchStartCelsius(celsius, e)
{
  // Top début maintien clic
  celsius.data('touchstart', true);

  // Détermination clic simple
  setTimeout(function()
  {
    // Si clic simple alors on affiche le contenu
    afficherMasquerContenuCelsius();
  }, 200);

  // Positions initiales élément et souris
  var celsiusPosX = celsius.offset().left;
  var celsiusPosY = celsius.offset().top;
  var touchPosX   = e.originalEvent.touches[0].pageX;
  var touchPosY   = e.originalEvent.touches[0].pageY;

  celsius.data('initCelsiusPosX', celsiusPosX);
  celsius.data('initCelsiusPosY', celsiusPosY);
  celsius.data('initTouchPosX', touchPosX);
  celsius.data('initTouchPosY', touchPosY);

  // Animation taille élément
  celsius.css('transform', 'scale(0.8)');
  celsius.css('transition', 'transform 0.2s ease');
}

// Mouvement Celsius
function touchMoveCelsius(celsius, e)
{
  // Si le clic est maintenu on bouge l'élément
  if (celsius.data('touchstart') == true)
  {
    // Marges minimales à respecter
    var paddingScreen = $(window).height() * (2 / 100);
    var minScreenX    = paddingScreen;
    var minScreenY    = paddingScreen;
    var maxScreenX    = $(window).width() - (paddingScreen + celsius.width());
    var maxScreenY    = $(window).height() - (paddingScreen + celsius.height());

    // Récupération de la position courante de la souris
    var touchPosX = e.originalEvent.touches[0].pageX;
    var touchPosY = e.originalEvent.touches[0].pageY;

    // Calcul de la différence de position de la souris par rapport à la position initiale
    var ecartTouchPosX = touchPosX - celsius.data('initTouchPosX');
    var ecartTouchPosY = touchPosY - celsius.data('initTouchPosY');
    var scrollPosY     = $(window).scrollTop();

    // Calcul de la nouvelle position
    var newPosX = celsius.data('initCelsiusPosX') + ecartTouchPosX;
    var newPosY = celsius.data('initCelsiusPosY') + ecartTouchPosY - scrollPosY;

    // Limites la position sur l'écran
    if (newPosX < minScreenX)
      newPosX = minScreenX;

    if (newPosY < minScreenY)
      newPosY = minScreenY;

    if (newPosX > maxScreenX)
      newPosX = maxScreenX;

    if (newPosY > maxScreenY)
      newPosY = maxScreenY;

    // Applique la position
    celsius.css('left', newPosX);
    celsius.css('top', newPosY);

    // On cache le contenu
    if ($('#contenuCelsius').css('display') != 'none')
      afficherMasquerIdWithDelay('contenuCelsius');
  }
}

// Fin Celsius au relâchement
function touchEndCelsius(celsius)
{
  // Top fin maintien clic
  celsius.data('touchstart', false);

  // Animation taille élément
  celsius.css('transform', 'scale(1)');
  celsius.css('transition', 'transform 0.2s ease');
}

// Affichage contenu Celsius
function afficherMasquerContenuCelsius()
{
  // Affichage seulement si clic relâché
  if ($('.celsius').data('touchstart') == false)
  {
    // Détermination position écran
    var isUp = isUpScreen();

    if (isUp == true)
    {
      $('.zone_contenu_celsius').css('bottom', '2vh');
      $('.zone_contenu_celsius').css('top', 'auto');
    }
    else
    {
      $('.zone_contenu_celsius').css('bottom', 'auto');
      $('.zone_contenu_celsius').css('top', '2vh');
    }

    // Affichage contenu
    afficherMasquerIdWithDelay('contenuCelsius');
  }
}

// Animation chargement de la page en boucle
function loadingPage()
{
  if ($('.zone_loading_image').length)
  {
    var angle;

    // Calcul de l'angle courant
    var matrix = $('#loading_image').css('transform');

    if (matrix !== 'none')
    {
      var values = matrix.split('(')[1].split(')')[0].split(',');
      var a      = values[0];
      var b      = values[1];
      angle      = Math.round(Math.atan2(b, a) * (180 / Math.PI));

      if (angle < 0)
        angle += 360;
    }
    else
      angle = 0;

    // On rajoute 45 degrés
    angle += 45;

    // On applique la transformation
    $('#loading_image').css('transform', 'rotate(' + angle + 'deg)');
  }
}

// Termine le chargement
function endLoading()
{
  // Suppression de la barre de chargement de la page
  $('.zone_loading_image').remove();

  // Affichage du contenu
  $('article').css('display', 'block');

  // Arrêt de la répétition
  if (typeof loadPage !== 'undefined')
    clearInterval(loadPage);
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerIdNoDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Affiche ou masque un élément (délai 200ms)
function afficherMasquerIdWithDelay(id)
{
  if ($('#' + id).css('display') == 'none')
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Masque et supprime un élément (délai 200ms)
function masquerSupprimerIdWithDelay(id)
{
  $('#' + id).fadeOut(200, function()
  {
    $(this).remove();
  });
}

// Ouvre une zone sous un titre
function openSection(titre, zone, forcage)
{
  var angle;

  // Calcul de l'angle
  var fleche = titre.children('.fleche_titre_section');
  var matrix = fleche.css('transform');

  if (matrix !== 'none')
  {
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var a      = values[0];
    var b      = values[1];
    angle      = Math.round(Math.atan2(b, a) * (180 / Math.PI));

    if (angle < 0)
      angle += 360;
  }
  else
    angle = 0;

  fleche.css('transition', 'all ease 0.2s');

  // Gestion de l'affichage en fonction du forçage
  switch (forcage)
  {
    case 'open':
      if (angle != 0)
      {
        fleche.css('transform', 'rotate(0deg)');

        // Affichage ou masquage de la zone
        afficherMasquerIdNoDelay(zone);
      }
      break;

    case 'close':
      if (angle == 0)
      {
        fleche.css('transform', 'rotate(-90deg)');

        // Affichage ou masquage de la zone
        afficherMasquerIdNoDelay(zone);
      }
      break;

    default:
      if (angle == 0)
        fleche.css('transform', 'rotate(-90deg)');
      else
        fleche.css('transform', 'rotate(0deg)');

      // Affichage ou masquage de la zone
      afficherMasquerIdWithDelay(zone);
      break;
  }
}

// Exécute le script php de mise à jour du ping
function updatePing()
{
  $.post('/inside/includes/functions/script_commun.php', {function: 'updatePing'}, function(data)
  {
    // Récupération des données
    var userConnected = JSON.parse(data);

    // Arrêt du script si pas d'utilisateur connecté
    if (userConnected == false)
      clearInterval(majPing);
  });
}

// Déploie le menu latéral gauche
function deployerMenuPortail()
{
  if ($('.aside_portail').css('left') == '0px')
    $('.aside_portail').css('left', ('-80%'));
  else
    $('.aside_portail').css('left', ('0%'));

  $('.aside_portail').css('transition', 'left ease 0.3s');
}

// Déploie le menu latéral gauche
function deployerMenuUser()
{
  if ($('.aside_user').css('right') == '0px')
    $('.aside_user').css('right', ('-80%'));
  else
    $('.aside_user').css('right', ('0%'));

  $('.aside_user').css('transition', 'right ease 0.3s');
}

// Ouvre une fenêtre de confirmation
function confirmAction(form, message)
{
  // Suppression fenêtre éventuellement existante
  if ($('#confirmBox').length)
    $('#confirmBox').remove();

  // Génération nouvelle fenêtre de confirmation
  var html = '';

  html += '<div class="fond_alerte" id="confirmBox" style="display: none;">';
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

  // Affichage de la fenêtre de confirmation
  afficherMasquerIdWithDelay('confirmBox');
}

// Ferme la fenêtre ou execute le formulaire
function executeAction(form, action)
{
  if (action == 'cancel')
    masquerSupprimerIdWithDelay('confirmBox');
  else
    $('#' + form).submit();
}

// Réinitialise la zone de recherche saisie
function resetLiveSearch()
{
  // On vide la saisie
  $('#recherche_live').val('');

  // On cache le message vide
  $('.empty_recherche_live').hide();

  // Affiche tous les lieux par défaut
  $('.zone_recherche_conteneur').show();

  // Affiche tous les restaurants par défaut
  $('.zone_recherche_item').show();
}

// Filtre la zone de recherche en fonction de la saisie
function liveSearch(input)
{
  // Déplie toutes les zones de recherche
  $('.zone_recherche_conteneur > .titre_section').each(function()
  {
    var idZone = $(this).attr('id').replace('titre_', 'afficher_');

    openSection($(this), idZone, 'open');
  });

  // Si zone vide, on fait tout apparaitre
  if (!input)
  {
    // Affiche tous les lieux par défaut
    $('.zone_recherche_conteneur').show();

    // Affiche tous les restaurants par défaut
    $('.zone_recherche_item').show();

    // On cache le message vide
    $('.empty_recherche_live').hide();
  }
  // Sinon on filtre
  else
  {
    // Affiche tous les lieux par défaut
    $('.zone_recherche_conteneur').show();

    // Cache les restaurants qui ne correspondent pas
    $('.zone_recherche_item').show().not(':containsCaseInsensitive(' + input + ')').hide();

    // Cache une zone qui ne contient pas de restaurant qui corresponde
    $('.zone_recherche_contenu').show().not(':containsCaseInsensitive(' + input + ')').parent().hide();

    // Filtrage de l'affichage
    if (!$('.zone_recherche_item').is(':visible'))
      $('.zone_recherche_conteneur').hide();

    // Affichage / masquage message vide
    if ($('.zone_recherche_conteneur').is(':visible'))
      $('.empty_recherche_live').hide();
    else
      $('.empty_recherche_live').show();
  }
}

// Rend la recherche insensible à la casse
$.expr[':'].containsCaseInsensitive = $.expr.createPseudo(function(arg)
{
  return function(elem)
  {
    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
  };
});

// Formate une date pour affichage
function formatDateForDisplay(date)
{
  var dateFormatted;

  if (date.length == 8)
    dateFormatted = date.substr(6, 2) + '/' + date.substr(4, 2) + '/' + date.substr(0, 4);
  else
    dateFormatted = date;

  // Retour
  return dateFormatted;
}

// Formate un montant pour affichage
function formatAmountForDisplay(amount, withCurrency)
{
  // Initialisations
  amountFormatted = '';

  if (amount != '')
  {
    var currency;

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
  }

  // Retour
  return amountFormatted;
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
      level = '..';
      break;

    case 2:
      level = '../..';
      break;

    case 0:
    default:
      level = '/inside';
      break;
  }

  // Chemin
  if (avatar != '' && avatar != undefined)
    path = level + '/includes/images/profil/avatars/' + avatar;
  else
    path = level + '/includes/icons/common/default.png';

  // Pseudo
  pseudo = formatUnknownUser(pseudo, true, false);

  // Formatage
  var formattedAvatar = {'path' : path, 'alt' : alt, 'title' : pseudo};

  // Retour
  return formattedAvatar;
}

// Formate le pseudo utilisateur désinscrit
function formatUnknownUser(pseudo, majuscule, italique)
{
  if (pseudo == '')
  {
    if (majuscule == true)
    {
      if (italique == true)
        pseudo = '<i>Un ancien utilisateur</i>';
      else
        pseudo = 'Un ancien utilisateur';
    }
    else
    {
      if (italique == true)
        pseudo = '<i>un ancien utilisateur</i>';
      else
        pseudo = 'un ancien utilisateur';
    }
  }

  // Retour
  return pseudo;
}

// Formate une chaîne de caractères en longueur
function formatString(string, limit)
{
  if (string.length > limit)
    string = string.substr(0, limit) + '...';

  // Retour
  return string;
}

// Prend en compte les sauts de ligne
function nl2br(chaine)
{
  // Remplacement des sauts de ligne
  var nl2br = chaine.replace(/(\r\n|\n\r|\r|\n)/g, '<br />');

  // Retour
  return nl2br;
}
