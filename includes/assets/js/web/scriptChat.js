/***********************/
/*** Initialisations ***/
/***********************/
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

/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au clic ***/
    // Afficher/masquer la fenêtre de chat au clic
    $('#zone_hide_chat').click(function ()
    {
        // Affichage ou masquage du chat
        afficherMasquerChat();
    });

    // Rafraichissement chat (au clic sur le titre)
    $('#onglet_chat').click(function ()
    {
        rafraichirOngletChat();
    });

    // Rafraichissement utilisateurs (au clic sur le titre)
    $('#onglet_users').click(function ()
    {
        rafraichirOngletUtilisateurs();
    });

    // Affichage des anciens messages au clic sur le bouton
    $('#fenetres_chat').on('click', '#afficher_anciens_messages', afficherAnciensMessages);

    // Envoi de message au clic sur le bouton
    $('#fenetres_chat').on('click', '#send_message_chat', envoyerMessage);

    // Afficher/masquer la fenêtre d'insertion de smileys au clic
    $('#fenetres_chat').on('click', '#insert_smiley', function ()
    {
        afficherMasquerInsertionSmiley(false);
    });

    // Repli de la zone d'insertion de smileys au clic en dehors
    $('#fenetres_chat').on('click', '#scroll_conversation, #message_chat, #send_message_chat', function ()
    {
        afficherMasquerInsertionSmiley(true);
    });

    // Insertion smiley au clic
    $('#fenetres_chat').on('click', '.click_smiley', function ()
    {
        insertSmileyChat($(this));
    });

    /*** Actions au survol ***/
    // Survol des onglets
    $('#onglet_users').hover(function (e)
    {
        if (cookieWindowChat == '1')
            $('#onglet_users').css('background-color', e.type === 'mouseenter' ? '#c81932' : '#ff1937');
    });

    $('#onglet_chat').hover(function (e)
    {
        if (cookieWindowChat == '2')
            $('#onglet_chat').css('background-color', e.type === 'mouseenter' ? '#c81932' : '#ff1937');
    });

    /*** Actions au scroll ***/
    // Affichage des anciens messages au scroll vers le haut (ne fonctionne que si la fenêtre est ouverte au chargement de la page)
    $('#scroll_conversation').scroll(function ()
    {
        // Récupération de la position du scroll
        var scrollPosition = $(this).scrollTop();

        // Si le scroll est en haut, recherche des messages précédents
        if ($('.zone_anciens_messages_chat').length && scrollPosition == 0)
        {
            setTimeout(function ()
            {
                afficherAnciensMessages();
            }, intervalOldMessages);
        }
    });

    /*** Actions à l'appui d'une touche ***/
    // Envoi de message sur appui de la touche "Entrée"
    $('#fenetres_chat').on('keypress', '#message_chat', function (e)
    {
        if (e.which == 13)
        {
            // Envoi du message
            envoyerMessage();

            // Eventuel repli de la zone d'insertion de smileys
            afficherMasquerInsertionSmiley(true);

            return false;
        }
    });
});

// Au chargement du document complet
$(window).on('load', function ()
{
    // Initialisation des cookies
    initCookies();

    // Initialisation de la position du chat
    initPositionChat();

    // Initialisation de la vue du chat
    initView(cookieShowChat, cookieWindowChat);

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
$(window).scroll(function ()
{
    // Positionnement de la fenêtre de chat en fonction du scroll
    initPositionChat();
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Positionnement de la fenêtre de chat en fonction du redimensionnement de la fenêtre
    setTimeout(function ()
    {
        initPositionChat();
    }, 350);
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

        // Initialisation de la vue
        initView(cookieShowChat, cookieWindowChat);
    }
    // Sinon on l'ouvre sur la fenêtre de chat
    else
    {
        // Redéfinition des cookies
        setCookie('chat[showChat]', true);
        setCookie('chat[windowChat]', '1');

        // Récupération des cookies
        cookieShowChat   = getCookie('chat[showChat]');
        cookieWindowChat = getCookie('chat[windowChat]');

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

    // On ne tient plus compte de la préférence utilisateur après la connexion
    if (initChat != null)
        initChat = null;
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
    if (initChat != null)
    {
        // Initialisation cookie état Chat en fonction de la préférence utilisateur
        setCookie('chat[showChat]', initChat);
        cookieShowChat = getCookie('chat[showChat]');
    }
    else
    {
        // Initialisation cookie état Chat par défaut
        if (cookieShowChat == null)
        {
            setCookie('chat[showChat]', true);
            cookieShowChat = getCookie('chat[showChat]');
        }
    }
}

// Fonction initialisation cookie (fenêtre chat)
function initCookieWindowChat()
{
    if (initChat != null)
    {
        // Initialisation cookie fenêtre Chat en fonction de la préférence utilisateur
        if (initChat == true)
            setCookie('chat[windowChat]', '1');
        else
            setCookie('chat[windowChat]', '3');

        cookieWindowChat = getCookie('chat[windowChat]');
    }
    else
    {
        // Initialisation cookie état Chat par défaut
        if (cookieWindowChat == null)
        {
            setCookie('chat[windowChat]', '1');
            cookieWindowChat = getCookie('chat[windowChat]');
        }
    }
}

// Fonction initialisation position chat (aussi utilisée dans scriptMH.js)
function initPositionChat()
{
    var totalHeight = $('body')[0].scrollHeight - $(window).height();
    var difference  = $('footer').height() - (totalHeight - $(window).scrollTop());

    /*console.log('$('body')[0].scrollHeight = ' + $('body')[0].scrollHeight);
    console.log('$(window).height() = ' + $(window).height());
    console.log('totalHeight = ' + totalHeight);
    console.log('$('footer').height() = ' + $('footer').height());
    console.log('$(window).scrollTop() = ' + $(window).scrollTop());
    console.log('difference = ' + difference);
    console.log('----------------------');*/

    $('#zone_chat_position').css('display', 'block');

    if (difference > 0)
        $('#zone_chat_position').css('bottom', difference + 'px');
    else
        $('#zone_chat_position').css('bottom', '0px');
}

// Fonction mise à jour de la vue
function initView(cookieShowChat, cookieWindowChat)
{
    /*console.log('cookieShowChat : ' + cookieShowChat);
    console.log('cookieWindowChat : ' + cookieWindowChat);*/

    var html = '';
    var hideChatRotateCss;
    var hideChatPaddingCss;
    var ongletChatCss;
    var ongletUsersCss;
    var cursorCss;

    if (cookieShowChat == 'true' && cookieWindowChat == '1')
    {
        // Initialisation des affichages
        hideChatRotateCss  = '180deg';
        hideChatPaddingCss = '12.5px 10px 12.5px 5px';
        ongletChatCss      = '#c81932';
        ongletUsersCss     = '#ff1937';
        cursorCss          = 'pointer';

        // On construit la fenêtre de chat
        $('#onglet_users').show();

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
        hideChatRotateCss  = '180deg';
        hideChatPaddingCss = '12.5px 10px 12.5px 5px';
        ongletChatCss      = '#ff1937';
        ongletUsersCss     = '#c81932';
        cursorCss          = 'pointer';

        // On construit la fenêtre des utilisateurs
        html += '<div id="scroll_conversation" class="zone_onglet_users">';
        html += '<div id="utilisateurs_chat" class="contenu_onglet_users"></div>';
        html += '</div>';
    }
    else if (cookieShowChat == 'false' || cookieWindowChat == '3')
    {
        // Initialisation des affichages
        hideChatRotateCss  = '0deg';
        hideChatPaddingCss = '12.5px 5px 12.5px 10px';
        ongletChatCss      = '#ff1937';
        ongletUsersCss     = '#ff1937';
        cursorCss          = 'default';

        $('#onglet_users').hide();
    }

    $('#hide_chat').css('rotate', hideChatRotateCss);
    $('#hide_chat').css('padding', hideChatPaddingCss);
    $('#onglet_chat').css('background-color', ongletChatCss);
    $('#onglet_users').css('background-color', ongletUsersCss);
    $('#onglet_chat').css('border-top-left-radius', '2px');
    $('.titre_onglet').css('cursor', cursorCss);
    $('#fenetres_chat').html(html);
}

// Fonction de lecture des derniers messages et du lancement du rafraichissement
function initMessagesChat()
{
    // Lecture initiale des messages les plus récents
    lectureInitialeConversation();

    // Rafraichissement du chat
    setTimeout(function ()
    {
        refreshChat = startTimerRefresh(rafraichirConversation, refreshChat, intervalRefreshChat, false, false);
    }, intervalRefreshChat);
}

// Fonction de lecture des derniers messages
function lectureInitialeConversation()
{
    // Gestion de l'affichage (on utilise $.post plutôt que $.get car le GET met en cache le fichier XML)
    $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function (display)
    {
        // Initialisations
        var previousDate  = '';
        var countMessages = 0;

        // Initialisation de la zone d'affichage
        $('#conversation_chat').html('');

        // Affichage et formatage des messages
        $($(display).find('message').get().reverse()).each(function ()
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
                $.each(listeUsersChat, function (key, value)
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
    $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function (display)
    {
        // Initialisations
        var previousDate = '';
        var newMessages  = '';

        // Affichage et formatage des messages
        $($(display).find('message').get().reverse()).each(function ()
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
                    $.each(listeUsersChat, function (key, value)
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
    $.post('/inside/includes/common/chat/conversations/content_chat_' + teamUser + '.xml', function (display)
    {
        // Initialisations
        var previousDate     = '';
        var countMessages    = 0;
        var firstDateDeleted = false;

        // Récupération de la position initiale du scroll
        var oldHeight = $('#scroll_conversation')[0].scrollHeight;

        // Affichage et formatage des messages
        $($(display).find('message').get().reverse()).each(function ()
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
    var html   = '';
    var avatar = '';

    // Formatage pseudo à partir du tableau php récupéré
    var pseudo = formatUnknownUser('', true, false);

    $.each(listeUsersChat, function (key, value)
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
            html += '<div class="time_chat_user">' + formatTimeForDisplayLight(time) + '</div>';
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
            html += '<div class="time_chat_other">' + formatTimeForDisplayLight(time) + '</div>';
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

// Fonction de rafraichissement des utilisateurs
function rafraichirUtilisateurs()
{
    // Lecture des utilisateurs et du statut de connexion
    $.post('/inside/includes/functions/script_commun.php', { function: 'getPings' }, function (users)
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

        $.each(JSON.parse(users), function (key, value)
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
        $.post('/inside/includes/common/chat/chat.php?action=doSubmit', { 'identifiant': identifiant, 'equipe': teamUser, 'message': message }, afficherConversation);
    else
    {
        $('#message_chat').val('');
        $('#message_chat').focus();
    }
}

// Affiche ou masque l'insertion de smileys
function afficherMasquerInsertionSmiley(forceClose)
{
    if (forceClose == true)
    {
        $('.zone_insert_smiley').css('display', 'none');
        $('.triangle_chat_smileys').css('display', 'none');
    }
    else
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
    }
}

// Fonction de rafraichissement après saisie message et repositionnement zone de saisie
function afficherConversation()
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
function insertSmileyChat(object)
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
    $('.zone_date_chat_messages').each(function ()
    {
        var tailleTexte = $(this).children('.date_chat_messages').width();
        var tailleTrait = (tailleTotale - tailleTexte - 30) / 2;

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