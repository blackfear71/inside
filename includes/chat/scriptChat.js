$(function()
{
  /***************************/
  /***   Initialisations   ***/
  /***************************/
  var showChat = initCookieChat();
  setInterval(rafraichirConversation, 3000, false); // après avoir passé "init" en paramètre, on passe toujours false ensuite à rafraichirConversation()

  /******************/
  /***   Appels   ***/
  /******************/
  rafraichirConversation("init");

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
    }

    showChat = getCookie("showChat");

    //console.log('showChat après = ' + showChat);
  }

  // Fonction de rafraichissement du contenu & formatage des messages
  function rafraichirConversation(scrollUpdate)
  {
    //$('#conversation').load('/inside/includes/chat/content_chat.xml');
    $.get('/inside/includes/chat/content_chat.xml', function(display)
    {
      $('#conversation').html('');

      // Affichage et formatage de tous les messages
      $(display).find('message').each(function()
      {
        var $message    = $(this);
        var identifiant = $message.find('identifiant').text();
        var text        = decodeHtml($message.find('text').text());
        var date        = $message.find('date').text();
        var time        = $message.find('time').text();
        var pseudo      = "Un ancien utilisateur";
        var avatar;
        var html;

        if (identifiant != "" && text != "")
        {
          // Formatage pseudo à partir du tableau php récupéré
          listUsers.forEach(function(user)
          {
            if (identifiant == user.identifiant)
            {
              pseudo = user.pseudo;
              avatar = user.avatar;
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
      if (scrollUpdate == "init" || scrollUpdate == true)
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
    // On met à jour la conversation après un petit temps afin de laisser le temps d'enregistrer le message pour le rafraichir
    setTimeout(rafraichirConversation, 500, true);
    setTimeout(setScrollbarDown, 500);

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
    return str.replace(/[&<>"']/g, function(m) {return map[m];});
  }

  // Décodage des caractères spéciaux
  function decodeHtml(str)
  {
    var map =
    {
      '&amp;': '&',
      '&lt;': '<',
      '&gt;': '>',
      '&quot;': '"',
      '&#039;': "'"
    };
    return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
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
});
