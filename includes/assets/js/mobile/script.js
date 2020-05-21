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
  setInterval(updatePing, 60000);

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
  });

  // Bouton fermer alerte
  $('#boutonFermerAlerte').click(function()
  {
    masquerSupprimerIdWithDelay('alerte');
  });

  // Messages de confirmation
  $('.eventConfirm').click(function()
  {
    var idForm  = $(this).closest('form').attr('id');
    var message = $(this).closest('form').find('.eventMessage').val();

    if (!confirmAction(idForm, message))
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

  // Positionnement top début maintien clic et positions initiales
  $('.celsius').on('touchstart', function(e)
  {
    e.preventDefault();

    // Top début maintien clic
    $(this).data('touchstart', true);

    // Détermination clic simple
    setTimeout(function()
    {
      // Si clic simple alors on affiche le contenu
      afficherMasquerContenuCelsius();
    }, 200);

    // Positions initiales élément et souris
    celsiusPosX = $(this).offset().left;
    celsiusPosY = $(this).offset().top;
    touchPosX   = e.originalEvent.touches[0].pageX;
    touchPosY   = e.originalEvent.touches[0].pageY;

    $(this).data('initCelsiusPosX', celsiusPosX);
    $(this).data('initCelsiusPosY', celsiusPosY);
    $(this).data('initTouchPosX', touchPosX);
    $(this).data('initTouchPosY', touchPosY);

    // Animation taille élément
    $(this).css('transform', 'scale(0.8)');
    $(this).css('transition', 'transform 0.2s ease');
  });

  // Positionnement top fin maintien clic
  $('.celsius').on('touchend',function(e)
  {
    e.preventDefault();

    // Top fin maintien clic
    $('.celsius').data('touchstart', false);

    // Animation taille élément
    $('.celsius').css('transform', 'scale(1)');
    $('.celsius').css('transition', 'transform 0.2s ease');
  });

  // Déplacement du bloc
  $('.celsius').on('touchmove', function(e)
  {
    e.preventDefault();

    // Si le clic est maintenu on bouge l'élément
    if ($('.celsius').data('touchstart') == true)
    {
      // Marges minimales à respecter
      var paddingScreen = $(window).height() * (2 / 100);
      var minScreenX    = paddingScreen;
      var minScreenY    = paddingScreen;
      var maxScreenX    = $(window).width() - (paddingScreen + $('.celsius').width());
      var maxScreenY    = $(window).height() - (paddingScreen + $('.celsius').height());

      // Récupération de la position courante de la souris
      var touchPosX = e.originalEvent.touches[0].pageX;
      var touchPosY = e.originalEvent.touches[0].pageY;

      // Calcul de la différence de position de la souris par rapport à la position initiale
      var ecartTouchPosX = touchPosX - $('.celsius').data('initTouchPosX');
      var ecartTouchPosY = touchPosY - $('.celsius').data('initTouchPosY');
      var scrollPosY     = $(window).scrollTop();

      // Calcul de la nouvelle position
      var newPosX = $('.celsius').data('initCelsiusPosX') + ecartTouchPosX;
      var newPosY = $('.celsius').data('initCelsiusPosY') + ecartTouchPosY - scrollPosY;

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
      $('.celsius').css('left', newPosX);
      $('.celsius').css('top', newPosY);

      // On cache le contenu
      if ($('#contenuCelsius').css('display') != 'none')
        afficherMasquerIdWithDelay('contenuCelsius');
    }
  });

  // Ferme le contenu Celsius
  $('#closeCelsius').click(function()
  {
    afficherMasquerIdWithDelay('contenuCelsius');
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Remplacement du chargement par le contenu
  endLoading();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Fige la taille de l'écran
function fixViewport()
{
  var viewHeight = $(window).innerHeight();
  var viewWidth  = $(window).innerWidth();
  var viewport   = document.querySelector("meta[name=viewport]");

  viewport.setAttribute("content", "height=" + viewHeight + "px, width=" + viewWidth + "px, initial-scale=1.0");
}

// Initialisation de la position Celsius
function initPositionCelsius()
{
  $('.celsius').css('top', '84vh');
  $('.celsius').css('right', '2vh');
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
    // Calcul de l'angle courant
    var matrix = $('#loading_image').css('transform');

    if (matrix !== 'none')
    {
      var values = matrix.split('(')[1].split(')')[0].split(',');
      var a      = values[0];
      var b      = values[1];
      var angle  = Math.round(Math.atan2(b, a) * (180 / Math.PI));

      if (angle < 0)
        angle = angle + 360;
    }
    else
      var angle = 0;

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

// Exécute le script php de mise à jour du ping
function updatePing()
{
  $.post('/inside/includes/functions/ping.php', {function: 'updatePing'});
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
