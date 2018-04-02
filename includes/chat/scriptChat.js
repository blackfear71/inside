$(function()
{
  /************************************/
  /***   Initialisations / Appels   ***/
  /************************************/
  var showChat = initCookieChat();
  rafraichirConversation(true);
  setInterval(rafraichirConversation, 4000, false); // après avoir passé une fois TRUE en paramètre, on passe toujours FALSE ensuite à rafraichirConversation()

  /*******************/
  /***   Actions   ***/
  /*******************/
  // Afficher/masquer la fenêtre de chat au clic
  $('#hide_chat').click(afficherMasquerChat);

  // Envoi de message au clic sur le bouton
  $('#send_message_chat').click(envoyerMessage);

  // Envoi de message sur appui de la touche "Entrée"
  $('#message_chat').keypress(function(e)
  {
    if (e.which == 13)
    {
      envoyerMessage();
      return false;
    }
  });

  /*********************/
  /***   Fonctions   ***/
  /*********************/
  // Fonction initialisation cookie
  function initCookieChat()
  {
    cookie = getCookie("showChat");

    // Initialisation cookie état Chat
    if (cookie == null)
    {
      setCookie("showChat", true);
      cookie = getCookie("showChat");
    }

    // Initialisation affichage en fonction du cookie
    if (cookie == "true")
    {
      document.getElementById('hide_chat').innerHTML               = '-';
      document.getElementById('scroll_conversation').style.display = "block";
      document.getElementById('form_chat').style.display           = "block";
    }
    else
    {
      document.getElementById('hide_chat').innerHTML               = '+';
      document.getElementById('scroll_conversation').style.display = "none";
      document.getElementById('form_chat').style.display           = "none";
    }

    return cookie;
  }

  // Fonction affichage chat
  function afficherMasquerChat()
  {
    //console.log('showChat avant = ' + showChat);

    if (showChat == "true")
    {
      document.getElementById('hide_chat').innerHTML               = '+';
      document.getElementById('scroll_conversation').style.display = "none";
      document.getElementById('form_chat').style.display           = "none";
      setCookie("showChat", false);
    }
    else
    {
      document.getElementById('hide_chat').innerHTML               = '-';
      document.getElementById('scroll_conversation').style.display = "block";
      document.getElementById('form_chat').style.display           = "block";
      setCookie("showChat", true);
      setScrollbarDown();
    }

    showChat = getCookie("showChat");

    //console.log('showChat après = ' + showChat);
  }

  // Fonction de rafraichissement du contenu & formatage des messages
  function rafraichirConversation(scrollUpdate)
  {
    // Si la scrollbar est déjà en bas on va quand même la remettre en bas en cas d'arrivée de nouveau messages
    var scrollDown = isScrollbarDown();

    if (scrollDown == true)
      scrollUpdate = true;

    // Gestion de l'affichage (on utilise $.post plutôt que $.get car le GET met en cache le fichier XML)
    $.post('/inside/includes/chat/content_chat.xml', function(display)
    {
      $('#conversation').html('');

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
            html     = '<div class="zone_chat_user">';
            if (avatar != "" && avatar != undefined)
              html  += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
            else
              html  += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
            html    += '<div class="triangle_chat_user"></div>';
            html    += '<div class="text_chat_user">' + text + '</div>';
            html    += '</div>';
          }
          else
          {
            html     = '<div class="zone_chat_other">';
            if (avatar != "" && avatar != undefined)
              html  += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
            else
              html  += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
            html    += '<div class="triangle_chat_other"></div>';
            html    += '<div class="text_chat_other">' + text + '</div>';
            html    += '</div>';
          }

          // Insertion dans la zone
          $('#conversation').append($(html));
        }
      });

      // On repositionne le scroll en bas si on a saisi un message ou que la page s'initialise
      if (scrollUpdate == true)
        setScrollbarDown();
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

  /******************/
  /***   Debugg   ***/
  /******************/
  /*deleteCookie("showChat");

  //console.log('cookies : ' + document.cookie);

  // Debugg liste utilisateurs
  var listUsers = <?php //echo $listUsersJson; ?>;
  listUsers.forEach(function(user)
  {
    test = afficherProps(user, "user");
    console.log(test);
  });

  function afficherProps(obj, nomObjet)
  {
    var resultat = "";
    for (var i in obj)
    {
      if (obj.hasOwnProperty(i))
          resultat += nomObjet + "." + i + " = " + obj[i] + "\n";
    }
    return resultat;
  }

  //console.log('cookie : ' + showChat);*/

  /****************************/
  /***   Saisie dynamique   ***/
  /****************************/
  /*var map =
  {
    //"<3": "\u2764\uFE0F",
    //"</3": "\uD83D\uDC94",
    //":D": "\uD83D\uDE00",
    //":)": "\uD83D\uDE03",
    //";)": "\uD83D\uDE09",
    //":(": "\uD83D\uDE12",
    //":p": "\uD83D\uDE1B",
    //";p": "\uD83D\uDE1C",
    //":'(": "\uD83D\uDE22"

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

  function escapeSpecialChars(regex)
  {
    return regex.replace(/([()[{*+.$^\\|?])/g, '\\$1');
  }

  document.getElementById('message_chat').oninput = function()
  {
    for (var i in map)
    {
      var regex = new RegExp(escapeSpecialChars(i), 'gim');
      this.value = this.value = this.value.replace(regex, map[i]);
    }
  };*/
});
