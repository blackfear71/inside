$(function()
{
  /***************************/
  /***   Initialisations   ***/
  /***************************/
  var showChat   = initCookieChat();
  var windowChat = initWindowChat();
  var refresh_chat;
  var refresh_users;
  var intervalRefreshChat  = 4000;
  var intervalRefreshUsers = 30000;

  initPositionChat();
  initView(showChat, windowChat);

  // On lance le rafraichissement des messages toujours après l'affichage des zones
  if (showChat == "true" && windowChat == "1")
  {
    refresh_chat = startTimerRefresh(rafraichirConversation, refresh_chat, intervalRefreshChat, true, false);
    stopTimerRefresh(refresh_users);
  }
  else if ((showChat == "true" && windowChat == "2") || showChat == "false")
  {
    refresh_users = startTimerRefresh(rafraichirUtilisateurs, refresh_users, intervalRefreshUsers);
    stopTimerRefresh(refresh_chat);
  }

  /*******************/
  /***   Actions   ***/
  /*******************/
  // Positionnement de la fenêtre de chat en fonction du scroll ou du redimensionnement de la fenêtre
  $(window).scroll(initPositionChat);
  $(window).resize(initPositionChat);

  // Afficher/masquer la fenêtre de chat au clic
  $('#hide_chat').click(function()
  {
    // Si le chat est ouvert, on le ferme
    if (showChat == "true")
    {
      stopTimerRefresh(refresh_chat);
      setCookie("showChat", false);
      setCookie("windowChat", "3");
      showChat   = getCookie("showChat");
      windowChat = getCookie("windowChat");
      initView(showChat, windowChat);
    }
    // Sinon on l'ouvre sur la fenêtre de chat
    else
    {
      setCookie("showChat", true);
      setCookie("windowChat", "1");
      showChat   = getCookie("showChat");
      windowChat = getCookie("windowChat");
      initView(showChat, windowChat);
      refresh_chat = startTimerRefresh(rafraichirConversation, refresh_chat, intervalRefreshChat, true, false);
    }
  });

  // Rafraichissement chat (au clic sur le titre)
  $('#onglet_chat').click(function()
  {
    if (windowChat != "3")
    {
      stopTimerRefresh(refresh_users);
      setCookie("windowChat", "1");
      windowChat = getCookie("windowChat");
      initView(showChat, windowChat);
      refresh_chat = startTimerRefresh(rafraichirConversation, refresh_chat, intervalRefreshChat, true, false);
    }
  });

  // Rafraichissement utilisateurs (au clic sur le titre)
  $('#onglet_users').click(function()
  {
    stopTimerRefresh(refresh_chat);
    setCookie("windowChat", "2");
    windowChat = getCookie("windowChat");
    initView(showChat, windowChat);
    refresh_users = startTimerRefresh(rafraichirUtilisateurs, refresh_users, intervalRefreshUsers);
  });

  // Envoi de message au clic sur le bouton
  $('#fenetres_chat').on('click', '#send_message_chat', envoyerMessage);

  // Envoi de message sur appui de la touche "Entrée"
  $('#fenetres_chat').on('keypress', '#message_chat', function(e)
  {
    if (e.which == 13)
    {
      envoyerMessage();

      // Eventuel repli de la zone d'insertion de smileys
      if ($('.zone_insert_smiley').css('display') == "block")
      {
        $('.zone_insert_smiley').css('display', 'none');
        $('.triangle_chat_smileys').css('display', 'none');
      }
      return false;
    }
  });

  // Survol des onglets
  $('.titre_onglet').hover(function(e)
  {
    if (windowChat == "1" && $('#onglet_users').is(':hover'))
      $('#onglet_users').css('background-color', e.type === 'mouseenter' ? '#c81932' : '#ff1937');
    else if (windowChat == "2" && $('#onglet_chat').is(':hover'))
      $('#onglet_chat').css('background-color', e.type === 'mouseenter' ? '#c81932' : '#ff1937');
  });

  // Afficher/masquer la fenêtre d'insertion de smileys au clic
  $('#fenetres_chat').on('click', '#insert_smiley', function()
  {
    if ($('.zone_insert_smiley').css('display') == "none")
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
    if ($('.zone_insert_smiley').css('display') == "block")
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

  /*********************/
  /***   Fonctions   ***/
  /*********************/
  // Fonction mise en place intervalle rafraichissement chat
  function startTimerRefresh(func, interval, time, param_func, param_interval)
  {
    // On stoppe toujours un éventuel interval déjà en route
    stopTimerRefresh(interval);

    // Après avoir passé une fois TRUE en paramètre, on passe toujours FALSE ensuite à rafraichirConversation()
    func(param_func);
    var newInterval = setInterval(func, time, param_interval);

    return newInterval;
  }

  // Fonction arrêt intervalle rafraichissement chat
  function stopTimerRefresh(interval)
  {
    clearInterval(interval);
  }

  // Fonction initialisation cookie (affichage chat)
  function initCookieChat()
  {
    cookie = getCookie("showChat");

    // Initialisation cookie état Chat
    if (cookie == null)
    {
      setCookie("showChat", true);
      cookie = getCookie("showChat");
    }

    return cookie;
  }

  // Fonction initialisation cookie (fenêtre chat)
  function initWindowChat()
  {
    cookie = getCookie("windowChat");

    // Initialisation cookie état Chat
    if (cookie == null)
    {
      setCookie("windowChat", "1");
      cookie = getCookie("windowChat");
    }

    return cookie;
  }

  // Fonction initialisation position chat
  function initPositionChat()
  {
    var total_height = $('body')[0].scrollHeight - $(window).height();
    var difference   = $('footer').height() - (total_height - $(window).scrollTop());

    if (difference > 0)
      $('#zone_chat_position').css('bottom', difference + 'px');
    else
      $('#zone_chat_position').css('bottom', '0px');
  }

  // Fonction mise à jour de la vue
  function initView(showChat, windowChat)
  {
    //console.log('showChat : ' + showChat);
    //console.log('windowChat : ' + windowChat);

    var html;
    var hide_chat_css;
    var onglet_chat_css;
    var onglet_users_css;
    var cursor_css;

    if (showChat == "true" && windowChat == "1")
    {
      // Initialisation des affichages
      hide_chat_css    = '-';
      onglet_chat_css  = '#c81932';
      onglet_users_css = '#ff1937';
      cursor_css       = 'pointer';

      $('#onglet_users').show();

      // On construit la fenêtre de chat
      // Messages
      html = '<div id="scroll_conversation" class="zone_onglet_chat">';
        html += '<div id="conversation_chat" class="contenu_onglet_chat"></div>';
      html += '</div>';

      // Saisie
      html += '<form action="#" method="post" id="form_chat" class="form_saisie_chat">';
        html += '<div class="zone_insert_smiley">';
          html += '<a id="smiley_1" class="click_smiley"><img src="/inside/includes/icons/smileys/1.png" alt="smiley" title=":)" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_2" class="click_smiley"><img src="/inside/includes/icons/smileys/2.png" alt="smiley" title=";)" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_3" class="click_smiley"><img src="/inside/includes/icons/smileys/3.png" alt="smiley" title=":(" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_4" class="click_smiley"><img src="/inside/includes/icons/smileys/4.png" alt="smiley" title=":|" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_5" class="click_smiley"><img src="/inside/includes/icons/smileys/5.png" alt="smiley" title=":D" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_6" class="click_smiley"><img src="/inside/includes/icons/smileys/6.png" alt="smiley" title=":O" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_7" class="click_smiley"><img src="/inside/includes/icons/smileys/7.png" alt="smiley" title=":P" class="insert_smiley_chat" /></a>';
          html += '<a id="smiley_8" class="click_smiley"><img src="/inside/includes/icons/smileys/8.png" alt="smiley" title=":facepalm:" class="insert_smiley_chat" /></a>';
        html += '</div>';
        html += '<div class="triangle_chat_smileys"></div>';

        html += '<input type="hidden" id="identifiant_chat" value="' + currentUser + '" />';

        html += '<a id="insert_smiley" class="inserer_smiley">';
          html += '<img src="/inside/includes/icons/smileys.png" alt="smileys" title="Insérer un smiley" class="smileys" />';
        html += '</a>';

        html += '<input type="text" id="message_chat" name="message_chat" placeholder="Saisir un message..." autocomplete="off" class="saisie_chat" />';

        html += '<button type="button" id="send_message_chat" title="Envoyer" class="bouton_chat"></button>';
      html += '</form>';
    }
    else if (showChat == "true" && windowChat == "2")
    {
      // Initialisation des affichages
      hide_chat_css    = '-';
      onglet_chat_css  = '#ff1937';
      onglet_users_css = '#c81932';
      cursor_css       = 'pointer';

      // On construit la fenêtre des utilisateurs
      html = '<div id="scroll_conversation" class="zone_onglet_users">';
        html += '<div id="utilisateurs_chat" class="contenu_onglet_users"></div>';
      html += '</div>';
    }
    else if (showChat == "false")
    {
      // Initialisation des affichages
      hide_chat_css    = '+';
      onglet_chat_css  = '#ff1937';
      onglet_users_css = '#ff1937';
      cursor_css       = 'default';

      html = '';

      $('#onglet_users').hide();
    }

    $('#hide_chat').html(hide_chat_css);
    $('#onglet_chat').css('background-color', onglet_chat_css);
    $('#onglet_users').css('background-color', onglet_users_css);
    $('#onglet_chat').css('border-top-left-radius', '2px');
    $('.titre_onglet').css('cursor', cursor_css);
    $('#fenetres_chat').html(html);
  }

  // Fonction de rafraichissement du contenu & formatage des messages
  function rafraichirConversation(scrollUpdate)
  {
    //console.log('refresh_chat');

    // Si la scrollbar est déjà en bas on va quand même la remettre en bas en cas d'arrivée de nouveau messages
    var scrollDown = isScrollbarDown();

    if (scrollDown == true)
      scrollUpdate = true;

    // Gestion de l'affichage (on utilise $.post plutôt que $.get car le GET met en cache le fichier XML)
    $.post('/inside/includes/chat/content_chat.xml', function(display)
    {
      $('#conversation_chat').html('');

      // Affichage et formatage de tous les messages
      $(display).find('message').each(function()
      {
        var $message    = $(this);
        var identifiant = $message.find('identifiant').text();
        var text        = changeSmileys(decodeHtml($message.find('text').text()));
        var date        = $message.find('date').text();
        var time        = $message.find('time').text();
        var pseudo      = "Un ancien utilisateur";
        var avatar;
        var html;

        if (identifiant != "" && text != "")
        {
          // Formatage pseudo à partir du tableau php récupéré
          $.each(listUsers, function(key, value)
          {
            if (identifiant == value.identifiant)
            {
              pseudo = value.pseudo;
              avatar = value.avatar;
              return false;
            }
          });

          // Formatage du message complet
          if (currentUser == identifiant)
          {
            html = '<div class="zone_chat_user">';
              if (avatar != "" && avatar != undefined)
                html += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
              else
                html += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
              html += '<div class="triangle_chat_user"></div>';
              html += '<div class="text_chat_user">' + text + '</div>';
            html += '</div>';
          }
          else
          {
            html = '<div class="zone_chat_other">';
              if (avatar != "" && avatar != undefined)
                html += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
              else
                html += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
              html += '<div class="triangle_chat_other"></div>';
              html += '<div class="text_chat_other">' + text + '</div>';
            html += '</div>';
          }

          // Insertion dans la zone
          $('#conversation_chat').append($(html));
        }
      });

      // On repositionne le scroll en bas si on a saisi un message ou que la page s'initialise
      if (scrollUpdate == true)
        setScrollbarDown();
    });
  }

  // Fonction de rafraichissement des utilisateurs
  function rafraichirUtilisateurs()
  {
    //console.log('refresh_users');

    // Lecture des utilisateurs et du statut de connexion
    $.post('/inside/includes/ping.php', {function: 'getPings'}, function(users)
    {
      $('#utilisateurs_chat').html('');
      var offline = false;
      var html;

      // Séparation des utilisateur connectés (toujours au minimum l'utilisateur en cours)
      html = '<div class="online">En ligne</div>';
      $('#utilisateurs_chat').append($(html));

      $.each(JSON.parse(users), function(key, value)
      {
        var pseudo    = value.pseudo;
        var avatar    = value.avatar;
        var connected = value.connected;

        // On va afficher la séparation des utilisateurs hors ligne à partir du premier
        if (offline != true && connected == false)
        {
          html = '<div class="offline">Hors ligne</div>';
          $('#utilisateurs_chat').append($(html));
          offline = true;
        }

        html = '<div class="zone_chat_connected">';
          if (avatar != "" && avatar != undefined)
            html += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_connected" />';
          else
            html += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_connected" />';

          if (connected == true)
            html += '<div class="user_chat_online"></div>';
          else
            html += '<div class="user_chat_offline"></div>';

          html += '<div class="text_chat_connected">' + pseudo + '</div>';
        html += '</div>';

        // Insertion dans la zone
        $('#utilisateurs_chat').append($(html));
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
      $.post('/inside/includes/chat/submit_chat.php', {'identifiant': identifiant, 'message': message}, afficheConversation);
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
    setTimeout(rafraichirConversation, 500, true);

    // On positionne le curseur dans la zone de saisie
    $('#message_chat').val('');
    $('#message_chat').focus();
  }

  // Positionne la scrollbar en bas en cas d'initialisation de l'écran ou d'envoi de messages
  function setScrollbarDown()
  {
    var height = $('#scroll_conversation')[0].scrollHeight;
    $('#scroll_conversation').scrollTop(height);
  }

  // Détermine si la scrollabr est en bas
  function isScrollbarDown()
  {
    var isScrollBottom = $('#scroll_conversation').scrollTop() + $('#scroll_conversation').innerHeight() >= $('#scroll_conversation')[0].scrollHeight;

    return isScrollBottom;
  }

  // Encodage des caractères spéciaux
  function escapeHtml(str)
  {
    var map =
    {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };

    replace = str.replace(/[&<>"']/g, function(m)
    {
      return map[m];
    });

    return replace;
  }

  // Décodage des caractères spéciaux
  function decodeHtml(str)
  {
    var map =
    {
      '&amp;' : '&',
      '&lt;'  : '<',
      '&gt;'  : '>',
      '&quot;': '"',
      '&#039;': "'"
    };

    replace = str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m)
    {
      return map[m];
    });

    return replace;
  }

  // Remplace les smileys
  function changeSmileys(text)
  {
    var emoticons =
    {
      ':)'         : '<img src="/inside/includes/icons/smileys/1.png" alt=":)" class="smiley_chat" />',
      ':-)'        : '<img src="/inside/includes/icons/smileys/1.png" alt=":)" class="smiley_chat" />',
      ';)'         : '<img src="/inside/includes/icons/smileys/2.png" alt=":)" class="smiley_chat" />',
      ';-)'        : '<img src="/inside/includes/icons/smileys/2.png" alt=":)" class="smiley_chat" />',
      ':('         : '<img src="/inside/includes/icons/smileys/3.png" alt=":)" class="smiley_chat" />',
      ':-('        : '<img src="/inside/includes/icons/smileys/3.png" alt=":)" class="smiley_chat" />',
      ':|'         : '<img src="/inside/includes/icons/smileys/4.png" alt=":)" class="smiley_chat" />',
      ':-|'        : '<img src="/inside/includes/icons/smileys/4.png" alt=":)" class="smiley_chat" />',
      ':D'         : '<img src="/inside/includes/icons/smileys/5.png" alt=":)" class="smiley_chat" />',
      ':-D'        : '<img src="/inside/includes/icons/smileys/5.png" alt=":)" class="smiley_chat" />',
      ':O'         : '<img src="/inside/includes/icons/smileys/6.png" alt=":)" class="smiley_chat" />',
      ':-O'        : '<img src="/inside/includes/icons/smileys/6.png" alt=":)" class="smiley_chat" />',
      ':P'         : '<img src="/inside/includes/icons/smileys/7.png" alt=":P" class="smiley_chat" />',
      ':-P'        : '<img src="/inside/includes/icons/smileys/7.png" alt=":P" class="smiley_chat" />',
      ':facepalm:' : '<img src="/inside/includes/icons/smileys/8.png" alt=":facepalm:" class="smiley_chat" />'
    };

    var patterns = [];
    var metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;

    // On définit un modèle pattern pour chaque propriété
    for (var i in emoticons)
    {
      // On échappe les metachars
      if (emoticons.hasOwnProperty(i))
        patterns.push('(' + i.replace(metachars, "\\$&") + ')');
    }

    // On construit l'expression régulière et on remplace
    return text.replace(new RegExp(patterns.join('|'),'g'), function (match)
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

    $('#message_chat').val($('#message_chat').val() + transco[object.attr("id")]);
    $('#message_chat').focus();

    // Repli de la zone d'insertion de smiley
    if ($('.zone_insert_smiley').css('display') == "block")
    {
      $('.zone_insert_smiley').css('display', 'none');
      $('.triangle_chat_smileys').css('display', 'none');
    }
  }

  /******************/
  /***   Debugg   ***/
  /******************/
  //deleteCookie("showChat");
  //console.log('cookies : ' + document.cookie);
});
