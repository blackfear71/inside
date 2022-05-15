/***************/
/*** Actions ***/
/***************/



// TODO : remanier le chat web comme le mobile
// TODO : tester la position de la saisie par rapport au clavier sur mobile : forcer l'affichage sur mozilla pour tester, pour l'instant ça ne fonctionne pas
//        les focusin / focusout ne conviennent pas complètement : par exemple au resize, si le focus est sur la saisie alors remettre la taille en plus du focusout
// TODO : A la connexion le chat s'ouvre parfois ? à initialiser à false par défaut ?
// TODO : Au changement d'orientation (paysage), ça n'est pas utilisable





// Initialisations variables globales
var refreshChat;
var refreshUsers;
var cookieIdentifiant;
var cookieShowChat;
var cookieWindowChat;
var intervalRefreshChat  = 4000;
var intervalOldMessages  = 1000;
var intervalRefreshUsers = 30000;
var maximumCountMessages = 100;
var initialHeight        = window.innerHeight;  

// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche ou ferme le chat au clic sur le bouton
  $('#zone_bouton_chat, #zone_fermer_chat').click(function()
  {
    // Affichage ou masquage du chat
    afficherMasquerChat();
  });

  // Ferme le chat au clic sur le fond
  $(document).on('click', function(event)
  {
    // Ferme la zone de chat
    if ($(event.target).attr('class') == 'fond_chat')
      afficherMasquerChat();
  });

  // Rafraichissement chat (au clic sur le titre)
  $('#onglet_chat').click(function()
  {
    rafraichirOngletChat();
  });

  // Rafraichissement utilisateurs (au clic sur le titre)
  $('#onglet_users').click(function()
  {
    rafraichirOngletUtilisateurs();
  });

  // Affichage des anciens messages au clic sur le bouton
  $('#fenetres_chat').on('click', '#afficher_anciens_messages', afficherAnciensMessages);

  // Envoi de message au clic sur le bouton
  $('#fenetres_chat').on('click', '#send_message_chat', envoyerMessage);

  // Afficher/masquer la fenêtre d'insertion de smileys au clic
  $('#fenetres_chat').on('click', '#insert_smiley', function()
  {
    if ($('.zone_insert_smiley').css('display') == 'none')
    {
      $('.zone_insert_smiley').css('display', 'block');
      $('.triangle_chat_smileys').css('display', 'block');
    }
    else
    {
      $('.zone_insert_smiley').css('display', 'none');
      $('.triangle_chat_smileys').css('display', 'none');
    }
  });

  // Repli de la zone d'insertion de smileys au clic en dehors
  $('#fenetres_chat').on('click', '#scroll_conversation, #message_chat, #send_message_chat', function()
  {
    if ($('.zone_insert_smiley').css('display') == 'block')
    {
      $('.zone_insert_smiley').css('display', 'none');
      $('.triangle_chat_smileys').css('display', 'none');
    }
  });

  // Insertion smiley au clic
  $('#fenetres_chat').on('click', '.click_smiley', function()
  {
    insertSmiley($(this));
  });

  /*** Actions au scroll ***/
  // Affichage des anciens messages au scroll vers le haut (ne fonctionne que si la fenêtre est ouverte au chargement de la page)
  $('#scroll_conversation').scroll(function()
  {
    // Récupération de la position du scroll
    var scrollPosition = $(this).scrollTop();

    // Si le scroll est en haut, recherche des messages précédents
    if ($('.zone_anciens_messages_chat').length && scrollPosition == 0)
    {
      setTimeout(function()
      {
        afficherAnciensMessages();
      }, intervalOldMessages);
    }
  });

  /*** Actions à l'appui d'une touche ***/
  // Envoi de message sur appui de la touche "Entrée"
  $('#fenetres_chat').on('keypress', '#message_chat', function(e)
  {
    if (e.which == 13)
    {
      envoyerMessage();

      // Eventuel repli de la zone d'insertion de smileys
      if ($('.zone_insert_smiley').css('display') == 'block')
      {
        $('.zone_insert_smiley').css('display', 'none');
        $('.triangle_chat_smileys').css('display', 'none');
      }

      return false;
    }
  });

  /*** Actions au focus ***/
  // Diminution de la taille des messages à la saisie
  $(document).on('focusin', '#message_chat', function()
  {
    $('.zone_onglet_chat').css('height', '30vh');
    $('.contenu_onglet_chat').css('height', '30vh');

    setScrollbarDown();
  });

  // Réinitialisation de la taille des messages à la fin de la saisie
  $(document).on('focusout', '#message_chat', function()
  {
    $('.zone_onglet_chat').css('height', '77vh');
    $('.contenu_onglet_chat').css('height', '77vh');

    setScrollbarDown();
  });
});

// Au chargement du document complet
$(window).on('load', function()
{
  // Initialisation des cookies
  initCookies();

  // Initialisation de la position du chat
  initPositionChat();

  // Affichage du chat si besoin
  if (cookieShowChat == 'true')
  {
    // Affichage du chat
    afficherMasquerIdWithDelay('zoom_chat');

    // Masquage du bouton d'affichage
    afficherMasquerIdWithDelay('zone_bouton_chat');
    
    // Initialisation de la vue du chat
    initView(cookieShowChat, cookieWindowChat);
  }

  // On lance le rafraichissement des messages toujours après l'affichage des zones
  if (cookieShowChat == 'true' && cookieWindowChat == '1')
  {
    // Lecture initiale des messages
    initMessagesChat();

    // Arrêt du rafraichissement des utilisateurs
    stopTimerRefresh(refreshUsers);
  }
  else if ((cookieShowChat == 'true' && cookieWindowChat == '2') || cookieShowChat == 'false')
  {
    // Rafraichissement des utilisateurs
    refreshUsers = startTimerRefresh(rafraichirUtilisateurs, refreshUsers, intervalRefreshUsers);

    // Arrêt du rafraichissement du chat
    stopTimerRefresh(refreshChat);
  }
});

// Au scroll du document
$(window).scroll(function()
{
  // Positionnement de la fenêtre de chat en fonction du scroll
  initPositionChat();
});

// Au redimensionnement de la fenêtre
$(window).resize(function()
{
  // Positionnement de la fenêtre de chat en fonction du redimensionnement de la fenêtre
  initPositionChat();




  if (initialHeight <= window.innerHeight && $('#message_chat').is(':focus'))
  {
    $('.zone_onglet_chat').css('height', '77vh');
    $('.contenu_onglet_chat').css('height', '77vh');

    setScrollbarDown();
  }





});

// Au changement d'orientation
$(window).on('orientationchange', function(e)
{
  initPositionChat();
});

/*****************/
/*** Fonctions ***/
/*****************/
// Fonction d'affichage / masquage du chat
function afficherMasquerChat()
{
  // Si le chat est ouvert, on le ferme
  if (cookieShowChat == 'true')
  {
    // Arrêt du rafraichissement du chat
    stopTimerRefresh(refreshChat);

    // Redéfinition des cookies
    setCookie('chat[showChat]', false);
    setCookie('chat[windowChat]', '3');

    // Récupération des cookies
    cookieShowChat   = getCookie('chat[showChat]');
    cookieWindowChat = getCookie('chat[windowChat]');

    // Masquage du chat
    afficherMasquerIdWithDelay('zoom_chat');
    
    // Affichage du bouton d'affichage
    afficherMasquerIdWithDelay('zone_bouton_chat');

    // Initialisation de la vue
    initView(cookieShowChat, cookieWindowChat);
  }
  else
  {
    // Redéfinition des cookies
    setCookie('chat[showChat]', true);
    setCookie('chat[windowChat]', '1');

    // Récupération des cookies
    cookieShowChat   = getCookie('chat[showChat]');
    cookieWindowChat = getCookie('chat[windowChat]');

    // Affichage du chat
    afficherMasquerIdWithDelay('zoom_chat');

    // Masquage du bouton d'affichage
    afficherMasquerIdWithDelay('zone_bouton_chat');
    
    // Initialisation de la vue
    initView(cookieShowChat, cookieWindowChat);

    // Lecture initiale des messages
    initMessagesChat();
  }
}

// Fonction de rafraichissement de l'onglet chat
function rafraichirOngletChat()
{
  if (cookieWindowChat != '3')
  {
    // Arrêt du rafraichissement des utilisateurs
    stopTimerRefresh(refreshUsers);

    // Redéfinition des cookies
    setCookie('chat[windowChat]', '1');

    // Récupération des cookies
    cookieWindowChat = getCookie('chat[windowChat]');

    // Initialisation de la vue
    initView(cookieShowChat, cookieWindowChat);

    // Lecture initiale des messages
    initMessagesChat();
  }
}

// Fonction de rafraichissement de l'onglet utilisateurs
function rafraichirOngletUtilisateurs()
{
  // Arrêt du rafraichissement du chat
  stopTimerRefresh(refreshChat);

  // Redéfinition des cookies
  setCookie('chat[windowChat]', '2');

  // Récupération des cookies
  cookieWindowChat = getCookie('chat[windowChat]');

  // Initialisation de la vue
  initView(cookieShowChat, cookieWindowChat);

  // Relance de la mise à jour des utilisateurs
  refreshUsers = startTimerRefresh(rafraichirUtilisateurs, refreshUsers, intervalRefreshUsers);
}

// Fonction mise en place interval rafraichissement chat
function startTimerRefresh(nomFonction, nomVariableInterval, dureeInterval, parametreFonctionAppelee, parametreInterval)
{
  // On stoppe toujours un éventuel interval déjà en route
  stopTimerRefresh(nomVariableInterval);

  // Appel de la fonction avec son éventuel paramètre
  nomFonction(parametreFonctionAppelee);

  // Dans le cas de l'appel à rafraichirConversation(), après avoir passé une fois TRUE en paramètre, on passe toujours FALSE grâce au 3ème paramètre du setInterval(), ce qui permet d'éviter de redescendre le scroll
  var nouvelInterval = setInterval(nomFonction, dureeInterval, parametreInterval);

  // Retour
  return nouvelInterval;
}

// Fonction arrêt interval rafraichissement chat
function stopTimerRefresh(nomVariableInterval)
{
  clearInterval(nomVariableInterval);
}

// Fonction d'initialisation des cookies
function initCookies()
{
  // Initialisation ou récupération des cookies si existants
  cookieIdentifiant = getCookie('chat[identifiant]');
  cookieShowChat    = getCookie('chat[showChat]');
  cookieWindowChat  = getCookie('chat[windowChat]');

  // Initialisation cookie identifiant
  initCookieIdentifiant();

  // Initialisation cookie affichage chat
  initCookieShowChat();

  // Initialisation cookie fenêtre affichée
  initCookieWindowChat();
}

// Fonction initialisation cookie (identifiant)
function initCookieIdentifiant()
{
  // Initialisation cookie identifiant
  if (cookieIdentifiant == null)
  {
    setCookie('chat[identifiant]', currentUser);
    cookieIdentifiant = getCookie('chat[identifiant]');
  }

  // Si le cookie ne correspond pas à l'utilisateur, on détruit tous les cookies
  if (cookieIdentifiant != currentUser)
  {
    deleteCookie('chat[identifiant]');
    deleteCookie('chat[showChat]');
    deleteCookie('chat[windowChat]');
    setCookie('chat[identifiant]', currentUser);
    cookieIdentifiant = getCookie('chat[identifiant]');
  }
}

// Fonction initialisation cookie (affichage chat)
function initCookieShowChat()
{
  // Initialisation cookie état Chat par défaut
  if (cookieShowChat == null)
  {
    setCookie('chat[showChat]', true);
    cookieShowChat = getCookie('chat[showChat]');
  }
}

// Fonction initialisation cookie (fenêtre chat)
function initCookieWindowChat()
{
  // Initialisation cookie état Chat par défaut
  if (cookieWindowChat == null)
  {
    setCookie('chat[windowChat]', '1');
    cookieWindowChat = getCookie('chat[windowChat]');
  }
}

// Fonction initialisation position chat (aussi présente dans scriptMH.js)
function initPositionChat()
{
  var totalHeight = $('body')[0].scrollHeight - window.innerHeight;
  var difference  = $('footer').height() - (totalHeight - $(window).scrollTop());

  if (difference > 0)
    $('#zone_bouton_chat').css('bottom', difference + 'px');
  else
    $('#zone_bouton_chat').css('bottom', '0px');
}

// Fonction mise à jour de la vue
function initView(cookieShowChat, cookieWindowChat)
{
  var html = '';
  var ongletChatCss;
  var ongletUsersCss;

  if (cookieShowChat == 'true' && cookieWindowChat == '1')
  {
    // Initialisation des affichages
    ongletChatCss  = '#c81932';
    ongletUsersCss = '#ff1937';

    $('#onglet_users').show();

    // On construit la fenêtre de chat
    // Messages
    html += '<div id="scroll_conversation" class="zone_onglet_chat">';
      html += '<div id="conversation_chat" class="contenu_onglet_chat"></div>';
    html += '</div>';

    // Saisie
    html += '<form action="#" method="post" id="form_chat" class="form_saisie_chat">';
      html += '<div class="zone_insert_smiley">';
        html += '<a id="smiley_1" class="click_smiley"><img src="/inside/includes/icons/common/smileys/1.png" alt="smiley" title=":)" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_2" class="click_smiley"><img src="/inside/includes/icons/common/smileys/2.png" alt="smiley" title=";)" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_3" class="click_smiley"><img src="/inside/includes/icons/common/smileys/3.png" alt="smiley" title=":(" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_4" class="click_smiley"><img src="/inside/includes/icons/common/smileys/4.png" alt="smiley" title=":|" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_5" class="click_smiley"><img src="/inside/includes/icons/common/smileys/5.png" alt="smiley" title=":D" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_6" class="click_smiley"><img src="/inside/includes/icons/common/smileys/6.png" alt="smiley" title=":O" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_7" class="click_smiley"><img src="/inside/includes/icons/common/smileys/7.png" alt="smiley" title=":P" class="insert_smiley_chat" /></a>';
        html += '<a id="smiley_8" class="click_smiley"><img src="/inside/includes/icons/common/smileys/8.png" alt="smiley" title=":facepalm:" class="insert_smiley_chat" /></a>';
      html += '</div>';
      html += '<div class="triangle_chat_smileys"></div>';

      html += '<input type="hidden" id="identifiant_chat" value="' + currentUser + '" />';

      html += '<a id="insert_smiley" class="inserer_smiley">';
        html += '<img src="/inside/includes/icons/common/smileys.png" alt="smileys" title="Insérer un smiley" class="smileys" />';
      html += '</a>';

      html += '<input type="text" id="message_chat" name="message_chat" placeholder="Saisir un message..." autocomplete="off" class="saisie_chat" />';

      html += '<button type="button" id="send_message_chat" title="Envoyer" class="bouton_chat"></button>';
    html += '</form>';
  }
  else if (cookieShowChat == 'true' && cookieWindowChat == '2')
  {
    // Initialisation des affichages
    ongletChatCss  = '#ff1937';
    ongletUsersCss = '#c81932';

    // On construit la fenêtre des utilisateurs
    html += '<div id="scroll_conversation" class="zone_onglet_users">';
      html += '<div id="utilisateurs_chat" class="contenu_onglet_users"></div>';
    html += '</div>';
  }
  else if (cookieShowChat == 'false' || cookieWindowChat == '3')
  {
    // Initialisation des affichages
    ongletChatCss  = '#ff1937';
    ongletUsersCss = '#ff1937';

    $('#onglet_users').hide();
  }

  $('#onglet_chat').css('background-color', ongletChatCss);
  $('#onglet_users').css('background-color', ongletUsersCss);
  $('#onglet_chat').css('border-top-left-radius', '2px');
  $('#fenetres_chat').html(html);
}

// Fonction de lecture des derniers messages et du lancement du rafraichissement
function initMessagesChat()
{
  // Lecture initiale des messages les plus récents
  lectureInitialeConversation();

  // Rafraichissement du chat
  setTimeout(function()
  {
    refreshChat = startTimerRefresh(rafraichirConversation, refreshChat, intervalRefreshChat, false, false);
  }, intervalRefreshChat);
}

// Fonction de lecture des derniers messages
function lectureInitialeConversation()
{
  // Gestion de l'affichage (on utilise $.post plutôt que $.get car le GET met en cache le fichier XML)
  $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function(display)
  {
    // Initialisations
    var previousDate  = '';
    var countMessages = 0;

    // Initialisation de la zone d'affichage
    $('#conversation_chat').html('');

    // Affichage et formatage des messages
    $($(display).find('message').get().reverse()).each(function()
    {
      var message     = $(this);
      var identifiant = message.find('identifiant').text();
      var text        = changeSmileys(decodeHtml(message.find('text').text()));
      var date        = message.find('date').text();
      var time        = message.find('time').text();
      var html        = '';

      if (identifiant != '' && text != '')
      {
        countMessages++;

        // Formatage pseudo à partir du tableau php récupéré
        $.each(listeUsersChat, function(key, value)
        {
          if (identifiant == value.identifiant)
          {
            pseudo = decodeHtml(value.pseudo);
            avatar = value.avatar;

            return false;
          }
        });

        // Affichage de la date pour le message le plus ancien
        if (countMessages >= maximumCountMessages || message.index() == 0)
        {
          // Formatage de la date
          if (date != '')
            html += formatDatesChat(date);
        }

        // Formatage du message complet
        html += formatMessagesChat(currentUser, identifiant, text, date, time);

        // Affichage de la date précédente si différente de la date courante
        if (previousDate != '' && date != previousDate)
          html += formatDatesChat(previousDate);

        // Sauvegarde de la date courante pour le message suivant
        previousDate = date;

        // Insertion dans la zone
        $('#conversation_chat').prepend(html);

        // Arrêt de la récupération des messages à la limite définie
        if (countMessages >= maximumCountMessages)
        {
          // Bouton d'affichage des anciens messages seulement s'il y en a
          if (message.index() != 0)
          {
            var boutonAnciensMessages = '<div class="zone_anciens_messages_chat"><a id="afficher_anciens_messages" class="anciens_messages_chat">Afficher les anciens messages</a></div>';

            // Insertion dans la zone
            $('#conversation_chat').prepend(boutonAnciensMessages);
          }

          return false;
        }
      }
    });

    // Adaptation des séparations (dates)
    adaptSeparationDate();

    // On positionne le scroll en bas lorsque la page s'initialise
    setScrollbarDown();
  });
}

// Fonction de rafraichissement des nouveaux messages
function rafraichirConversation(scrollUpdate)
{
  // Si la scrollbar est déjà en bas on va quand même la remettre en bas en cas d'arrivée de nouveau messages
  var scrollDown = isScrollbarDown();

  if (scrollDown == true)
    scrollUpdate = true;

  // Récupération de la date et l'heure du message le plus récent
  var lastDate = $('#conversation_chat .date_chat').last().text();
  var lastTime = $('#conversation_chat .time_chat').last().text();

  // Recherche de messages plus récents et affichage
  $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function(display)
  {
    // Initialisations
    var previousDate = '';
    var newMessages  = '';

    // Affichage et formatage des messages
    $($(display).find('message').get().reverse()).each(function()
    {
      var message     = $(this);
      var identifiant = message.find('identifiant').text();
      var text        = changeSmileys(decodeHtml(message.find('text').text()));
      var date        = message.find('date').text();
      var time        = message.find('time').text();
      var html        = '';

      if (date > lastDate || (date == lastDate && time > lastTime))
      {
        if (identifiant != '' && text != '')
        {
          // Formatage pseudo à partir du tableau php récupéré
          $.each(listeUsersChat, function(key, value)
          {
            if (identifiant == value.identifiant)
            {
              pseudo = decodeHtml(value.pseudo);
              avatar = value.avatar;

              return false;
            }
          });

          // Affichage de la date pour le message le plus ancien
          if (message.index() == 0 && date != '')
            html += formatDatesChat(date);

          // Formatage du message complet
          html += formatMessagesChat(currentUser, identifiant, text, date, time);

          // Affichage de la date précédente si différente de la date courante
          if (previousDate != '' && date != previousDate && date != lastDate)
            html += formatDatesChat(previousDate);

          // Sauvegarde de la date courante pour le message suivant
          previousDate = date;

          // Ajout à la variable des nouveaux messages
          newMessages = html + newMessages;
        }
      }
      else
      {
        // Affichage de la date précédente si différente de la date courante
        if (previousDate != '' && previousDate != lastDate)
        {
          // Formatage de la date
          html += formatDatesChat(previousDate);

          // Ajout à la variable des nouveaux messages
          newMessages = html + newMessages;
        }

        return false;
      }
    });

    // Insertion dans la zone
    $('#conversation_chat').append(newMessages);

    // Adaptation des séparations (dates)
    adaptSeparationDate();

    // On repositionne le scroll en bas si on a saisi un message ou que la page s'initialise
    if (scrollUpdate == true)
      setScrollbarDown();
  });
}

// Fonction d'affichage des anciens messages
function afficherAnciensMessages()
{
  // Récupération de la date et l'heure du message le plus ancien
  var firstDate = $('#conversation_chat .date_chat').first().text();
  var firstTime = $('#conversation_chat .time_chat').first().text();

  // Récupération des données de repositionnement du scroll
  var scrollDown = isScrollbarDown();

  // Gestion de l'affichage (on utilise $.post plutôt que $.get car le GET met en cache le fichier XML)
  $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function(display)
  {
    // Initialisations
    var previousDate     = '';
    var countMessages    = 0;
    var firstDateDeleted = false;

    // Récupération de la position initiale du scroll
    var oldHeight = $('#scroll_conversation')[0].scrollHeight;

    // Affichage et formatage des messages
    $($(display).find('message').get().reverse()).each(function()
    {
      var message     = $(this);
      var identifiant = message.find('identifiant').text();
      var text        = changeSmileys(decodeHtml(message.find('text').text()));
      var date        = message.find('date').text();
      var time        = message.find('time').text();
      var html        = '';

      if (date < firstDate || (date == firstDate && time < firstTime))
      {
        if (identifiant != '' && text != '')
        {
          countMessages++;

          // Suppression de la première date affichée si identique à celle du premier ancien message lu
          if (firstDateDeleted == false && date == firstDate)
          {
            $('.zone_date_chat_messages').first().remove();
            firstDateDeleted = true;
          }

          // Affichage de la date pour le message le plus ancien
          if (countMessages >= maximumCountMessages || message.index() == 0)
          {
            // Formatage de la date
            if (date != '')
              html += formatDatesChat(date);
          }

          // Formatage du message complet
          html += formatMessagesChat(currentUser, identifiant, text, date, time);

          // Affichage de la date précédente si différente de la date courante
          if (previousDate != '' && date != previousDate)
            html += formatDatesChat(previousDate);

          // Sauvegarde de la date courante pour le message suivant
          previousDate = date;

          // Insertion dans la zone
          $('#conversation_chat').prepend(html);

          // Arrêt de la récupération des messages à la limite définie
          if (countMessages >= maximumCountMessages)
          {
            // Bouton d'affichage des anciens messages seulement s'il y en a
            if (message.index() != 0)
            {
              var boutonAnciensMessages = '<div class="zone_anciens_messages_chat"><a id="afficher_anciens_messages" class="anciens_messages_chat">Afficher les anciens messages</a></div>';

              // Insertion dans la zone
              $('#conversation_chat').prepend(boutonAnciensMessages);
            }

            return false;
          }
        }
      }
    });

    // Adaptation des séparations (dates)
    adaptSeparationDate();

    // Supression du bouton d'affichage des anciens messages
    $('.zone_anciens_messages_chat').last().remove();

    // Conserve la position de la scrollbar lors de l'affichage des anciens messages
    if (scrollDown)
      setScrollbarDown();
    else
      keepScrollbarPosition(oldHeight);
  });
}

// Fonction commune de formatage des messages
function formatMessagesChat(currentUser, identifiant, text, date, time)
{
  // Initialisations
  var html = '';
  var avatar;

  // Formatage pseudo à partir du tableau php récupéré
  var pseudo = formatUnknownUser('', true, false);

  $.each(listeUsersChat, function(key, value)
  {
    if (identifiant == value.identifiant)
    {
      pseudo = decodeHtml(value.pseudo);
      avatar = value.avatar;

      return false;
    }
  });

  // Formatage de l'avatar
  var avatarFormatted = formatAvatar(avatar, pseudo, 0, 'avatar');

  // Formatage du message selon l'utilisateur
  if (currentUser == identifiant)
  {
    html += '<div class="zone_chat_user">';
      html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_chat_user" />';
      html += '<div class="triangle_chat_user"></div>';
      html += '<div class="time_chat_user">' + formatTimeForDisplayChat(time) + '</div>';
      html += '<div class="text_chat_user">' + text + '</div>';
      html += '<div class="date_chat">' + date + '</div>';
      html += '<div class="time_chat">' + time + '</div>';
    html += '</div>';
  }
  else
  {
    html += '<div class="zone_chat_other">';
      html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_chat_other" />';
      html += '<div class="triangle_chat_other"></div>';
      html += '<div class="text_chat_other">' + text + '</div>';
      html += '<div class="time_chat_other">' + formatTimeForDisplayChat(time) + '</div>';
      html += '<div class="date_chat">' + date + '</div>';
      html += '<div class="time_chat">' + time + '</div>';
    html += '</div>';
  }

  return html;
}

// Fonction commune de formatage des dates
function formatDatesChat(dateToFormat)
{
  // Initialisations
  var html = '';

  html += '<div class="zone_date_chat_messages">';
    html += '<div class="trait_chat_gauche"></div>';
    html += '<div class="date_chat_messages">' + formatDateForDisplayChat(dateToFormat) + '</div>';
    html += '<div class="trait_chat_droit"></div>';
  html += '</div>';

  return html;
}

// Formate une date pour affichage (version texte)
function formatDateForDisplayChat(date)
{
  var dateFormatted;

  if (date.length == 8)
  {
    // Liste des jours et mois
    var days   = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    var months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    // Récupération de la date
    var jour  = date.substr(6, 2);
    var mois  = date.substr(4, 2) - 1;
    var annee = date.substr(0, 4);

    // Conversion au format JS
    var jsDate = new Date(annee, mois, jour);

    // Formatage
    dateFormatted = days[jsDate.getDay()] + ' ' + jour + ' ' + months[jsDate.getMonth()] + ' ' + annee;
  }
  else
    dateFormatted = date;

  // Retour
  return dateFormatted;
}

// Formate une heure pour affichage
function formatTimeForDisplayChat(time)
{
  var timeFormatted;

  // Formatage de l'heure
  if (time.length == 6 || time.length == 4)
    timeFormatted = time.substr(0, 2) + ':' + time.substr(2, 2);
  else
    timeFormatted = time;

  // Retour
  return timeFormatted;
}

// Fonction de rafraichissement des utilisateurs
function rafraichirUtilisateurs()
{
  // Lecture des utilisateurs et du statut de connexion
  $.post('/inside/includes/functions/script_commun.php', {function: 'getPings'}, function(users)
  {
    $('#utilisateurs_chat').html('');
    var offline = false;
    var html    = '';

    // Séparation des utilisateur connectés (toujours au minimum l'utilisateur en cours)
    html = '<div class="zone_statut_chat_users">';
      html += '<div class="trait_chat_gauche trait_online"></div>';
      html += '<div class="online">En ligne</div>';
      html += '<div class="trait_chat_droit trait_online"></div>';
    html += '</div>';

    $('#utilisateurs_chat').append(html);

    $.each(JSON.parse(users), function(key, value)
    {
      var pseudo             = value.pseudo;
      var avatar             = value.avatar;
      var connected          = value.connected;
      var dateLastConnection = value.date_last_connection;
      var hourLastConnection = value.hour_last_connection;

      // On va afficher la séparation des utilisateurs hors ligne à partir du premier
      if (offline != true && connected == false)
      {
        html = '<div class="zone_statut_chat_users">';
          html += '<div class="trait_chat_gauche trait_offline"></div>';
          html += '<div class="offline">Hors ligne</div>';
          html += '<div class="trait_chat_droit trait_offline"></div>';
        html += '</div>';

        $('#utilisateurs_chat').append(html);
        offline = true;
      }

      // Zone utilisateur
      html = '<div class="zone_chat_connected">';
        // Avatar
        var avatarFormatted = formatAvatar(avatar, pseudo, 0, 'avatar');

        html += '<img src="' + avatarFormatted['path'] + '" alt="' + avatarFormatted['alt'] + '" title="' + avatarFormatted['title'] + '" class="avatar_chat_connected" />';

        // Indicateur connexion
        if (connected == true)
        {
          html += '<div class="zone_indicateur" title="Connecté">';
            html += '<div class="user_chat_online"></div>';
          html += '</div>';
        }
        else
        {
          if (dateLastConnection != '' && hourLastConnection != '')
          {
            html += '<div class="zone_indicateur" title="Dernière connexion le ' + dateLastConnection + ' à ' + hourLastConnection + '">';
              html += '<div class="user_chat_offline"></div>';
            html += '</div>';
          }
          else
          {
            html += '<div class="zone_indicateur" title="Pas de connexion récente">';
              html += '<div class="user_chat_offline"></div>';
            html += '</div>';
          }
        }

        html += '<div class="text_chat_connected">' + formatString(pseudo, 30) + '</div>';
      html += '</div>';

      // Insertion dans la zone
      $('#utilisateurs_chat').append(html);

      // Adaptation des séparations (statut)
      adaptSeparationStatut();
    });
  });
}

// Fonction envoi de message
function envoyerMessage()
{
  var identifiant = $('#identifiant_chat').val();
  var message     = escapeHtml($('#message_chat').val());

  // Envoi du message si renseignée et non vide
  if (!$.isEmptyObject($.trim(message)) && !$.isEmptyObject(identifiant))
    $.post('/inside/includes/common/chat/chat.php?action=doSubmit', {'identifiant': identifiant, 'equipe': teamUser, 'message': message}, afficheConversation);
  else
  {
    $('#message_chat').val('');
    $('#message_chat').focus();
  }
}

// Fonction de rafraichissement après saisie message et repositionnement zone de saisie
function afficheConversation()
{
  // On met à jour la conversation après un petit temps
  rafraichirConversation(true);

  // On positionne le curseur dans la zone de saisie
  $('#message_chat').val('');
  $('#message_chat').focus();
}

// Positionne la scrollbar en bas en cas d'initialisation de l'écran ou d'envoi de messages
function setScrollbarDown()
{
  if ($('#scroll_conversation').length)
  {
    var height = $('#scroll_conversation')[0].scrollHeight;
    $('#scroll_conversation').scrollTop(height);
  }
}

// Conserve la position de la scrollbar à l'affichage d'anciens messages
function keepScrollbarPosition(oldHeight)
{
  if ($('#scroll_conversation').length)
  {
    var newHeight = $('#scroll_conversation')[0].scrollHeight;
    $('#scroll_conversation').scrollTop(newHeight - oldHeight);
  }
}

// Détermine si la scrollabr est en bas
function isScrollbarDown()
{
  var isScrollBottom = true;

  if ($('#scroll_conversation').length)
    isScrollBottom = $('#scroll_conversation').scrollTop() + Math.ceil($('#scroll_conversation').innerHeight()) >= $('#scroll_conversation')[0].scrollHeight;

  return isScrollBottom;
}

// Remplace les smileys
function changeSmileys(text)
{
  var emoticons =
  {
    ':)'         : '<img src="/inside/includes/icons/common/smileys/1.png" alt=":)" class="smiley_chat" />',
    ':-)'        : '<img src="/inside/includes/icons/common/smileys/1.png" alt=":)" class="smiley_chat" />',
    ';)'         : '<img src="/inside/includes/icons/common/smileys/2.png" alt=":)" class="smiley_chat" />',
    ';-)'        : '<img src="/inside/includes/icons/common/smileys/2.png" alt=":)" class="smiley_chat" />',
    ':('         : '<img src="/inside/includes/icons/common/smileys/3.png" alt=":)" class="smiley_chat" />',
    ':-('        : '<img src="/inside/includes/icons/common/smileys/3.png" alt=":)" class="smiley_chat" />',
    ':|'         : '<img src="/inside/includes/icons/common/smileys/4.png" alt=":)" class="smiley_chat" />',
    ':-|'        : '<img src="/inside/includes/icons/common/smileys/4.png" alt=":)" class="smiley_chat" />',
    ':D'         : '<img src="/inside/includes/icons/common/smileys/5.png" alt=":)" class="smiley_chat" />',
    ':-D'        : '<img src="/inside/includes/icons/common/smileys/5.png" alt=":)" class="smiley_chat" />',
    ':O'         : '<img src="/inside/includes/icons/common/smileys/6.png" alt=":)" class="smiley_chat" />',
    ':-O'        : '<img src="/inside/includes/icons/common/smileys/6.png" alt=":)" class="smiley_chat" />',
    ':P'         : '<img src="/inside/includes/icons/common/smileys/7.png" alt=":P" class="smiley_chat" />',
    ':-P'        : '<img src="/inside/includes/icons/common/smileys/7.png" alt=":P" class="smiley_chat" />',
    ':facepalm:' : '<img src="/inside/includes/icons/common/smileys/8.png" alt=":facepalm:" class="smiley_chat" />'
  };

  var patterns  = [];
  var metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;

  // On définit un modèle pattern pour chaque propriété
  for (var i in emoticons)
  {
    // On échappe les metachars
    if (emoticons.hasOwnProperty(i))
      patterns.push('(' + i.replace(metachars, "\\$&") + ')');
  }

  // On construit l'expression régulière et on remplace
  return text.replace(new RegExp(patterns.join('|'), 'g'), function (match)
  {
    return typeof emoticons[match] != 'undefined' ? emoticons[match] : match;
  });
}

// Insère un smiley dans la zone de saisie
function insertSmiley(object)
{
  var transco =
  {
    'smiley_1' : ' :) ',
    'smiley_2' : ' ;) ',
    'smiley_3' : ' :( ',
    'smiley_4' : ' :| ',
    'smiley_5' : ' :D ',
    'smiley_6' : ' :O ',
    'smiley_7' : ' :P ',
    'smiley_8' : ' :facepalm: '
  };

  $('#message_chat').val($('#message_chat').val() + transco[object.attr('id')]);
  $('#message_chat').focus();

  // Repli de la zone d'insertion de smiley
  if ($('.zone_insert_smiley').css('display') == 'block')
  {
    $('.zone_insert_smiley').css('display', 'none');
    $('.triangle_chat_smileys').css('display', 'none');
  }
}

// Adaptation des traits du chat (dates)
function adaptSeparationDate()
{
  var tailleTotale = $('.contenu_onglet_chat').width() - 90;

  // Calcul de la taille de chaque trait
  $('.zone_date_chat_messages').each(function()
  {
    var tailleTexte = $(this).children('.date_chat_messages').width();
    var tailleTrait = (tailleTotale - tailleTexte) / 2;

    $(this).children('.trait_chat_gauche').css('width', tailleTrait + 'px');
    $(this).children('.trait_chat_droit').css('width', tailleTrait + 'px');
  });
}

// Adaptation des traits du chat (statut)
function adaptSeparationStatut()
{
  var tailleTotale = $('.contenu_onglet_users').width() - 90;

  // Calcul de la taille de chaque trait
  var tailleTexteOnline  = $('.zone_statut_chat_users').children('.online').width();
  var tailleTexteOffline = $('.zone_statut_chat_users').children('.offline').width();
  var tailleTraitOnline  = (tailleTotale - tailleTexteOnline - 30) / 2;
  var tailleTraitOffline = (tailleTotale - tailleTexteOffline - 30) / 2;

  $('.zone_statut_chat_users').children('.trait_online').css('width', tailleTraitOnline + 'px');
  $('.zone_statut_chat_users').children('.trait_online').css('width', tailleTraitOnline + 'px');
  $('.zone_statut_chat_users').children('.trait_offline').css('width', tailleTraitOffline + 'px');
  $('.zone_statut_chat_users').children('.trait_offline').css('width', tailleTraitOffline + 'px');
}