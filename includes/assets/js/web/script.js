/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /*** Actions au chargement ***/
    // Applique le thème utilisateur si présent
    if (themeUser != null)
        changeTheme(themeUser.background, themeUser.header, themeUser.footer, themeUser.logo);

    // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
    updatePing();
    majPing = setInterval(updatePing, 60000);

    // Mise à jour du compteur des notifications toutes les 60 secondes
    updateNotifications();
    majNotifications = setInterval(updateNotifications, 60000);

    // Mise à jour du compteur des bugs / évolutions toutes les 60 secondes
    updateBugs();
    majBugs = setInterval(updateBugs, 60000);

    // Animation symbole chargement de la page
    if ($('.zone_loading_page').length)
        loadingPage();

    // Affichage des alertes
    if ($('#alerte').length)
        afficherMasquerPopUp('alerte', false);

    // Affichage de l'expérience
    if ($('.experience_header').length)
    {
        $('.experience_header').each(function ()
        {
            afficherExperienceHeader($(this).attr('id'));
        });
    }

    // Affichage des succès
    if ($('#zoom_succes').length)
        afficherMasquerIdWithDelay('zoom_succes');

    // Positionnement du nom de l'équipe
    if ($('.separation_nav').length)
        $('.zone_equipe_bandeau').css('top', '83px');
    else
        $('.zone_equipe_bandeau').css('top', '80px');

    /*** Actions au clic ***/
    // Referme la barre de recherche quand on clique n'importe où sur le body
    $('body').click(function ()
    {
        // Barre de recherche
        if ($('#resizeBar') != null && $('#color_search') != null)
        {
            $('#resizeBar').css('width', '300px');
            $('#resizeBar').css('transition', 'width ease 0.4s');
            $('#color_search').css('background-color', '#e3e3e3');
            $('#color_search').css('transition', 'background-color ease 0.4s');
        }
    });

    // Redimensionne la zone de recherche quand sélectionnée et gère le changement de couleur
    $('#color_search').click(function (event)
    {
        if ($('#resizeBar') != null && $('#color_search') != null)
        {
            $('#resizeBar').css('width', '100%');
            $('#resizeBar').css('transition', 'width ease 0.4s');
            $('#color_search').css('background-color', 'white');
            $('#color_search').css('transition', 'background-color ease 0.4s');

            event.stopPropagation();
        }
    });

    // Ferme un élément au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme une zone de saisie
        // Ferme une zone de détails
        if ($(event.target).attr('class') == 'fond_saisie'
        ||  $(event.target).attr('class') == 'fond_details')

            afficherMasquerIdWithDelay(event.target.id);

        // Ferme une zone de saisie (préférence film)
        // Ferme le zoom d'un succès
        // Ferme le zoom d'une image
        if ($(event.target).attr('class') == 'fond_saisie_preference'
        ||  $(event.target).attr('class') == 'fond_zoom_succes'
        ||  $(event.target).attr('class') == 'fond_zoom_image'
        ||  $(event.target).attr('class') == 'fond_details_admin')
            masquerSupprimerIdWithDelay(event.target.id);
    });

    // Bouton fermer alerte
    $('#fermerAlerte').click(function ()
    {
        afficherMasquerPopUp('alerte', false);
    });

    // Messages de confirmation
    $(document).on('click', '.eventConfirm', function ()
    {
        var idForm  = $(this).closest('form').attr('id');
        var message = $(this).closest('form').find('.eventMessage').val();

        if (!confirmAction(idForm, message))
            return false;
    });

    // Valider confirmation
    $(document).on('click', '#boutonAnnuler', function ()
    {
        var action_form = $('#actionForm').val();

        executeAction(action_form, 'cancel');
    });

    // Annuler confirmation
    $(document).on('click', '#boutonConfirmer', function ()
    {
        var action_form = $('#actionForm').val();

        executeAction(action_form, 'validate');
    });

    // Fermeture zoom succès
    $(document).on('click', '#closeZoomSuccess', function ()
    {
        masquerSupprimerIdWithDelay('zoom_succes');
    });

    /*** Actions au passage de la souris ***/
    // Changement couleur barre de recherche (entrée)
    $('#color_search').mouseover(function ()
    {
        changeColorToWhite('color_search');
    });

    // Changement couleur barre de recherche (sortie)
    $('#color_search').mouseout(function ()
    {
        changeColorToGrey('color_search', 'resizeBar');
    });

    // Affichage détail notifications (entrée)
    $('#afficherDetailNotifications').mouseover(function ()
    {
        afficherDetailsNotifications();
    });

    // Affichage détail notifications (sortie)
    $('#afficherDetailNotifications').mouseout(function ()
    {
        masquerDetailsNotifications();
    });
});

// Au chargement du document complet
$(window).on('load', function ()
{
    // Suppression de la barre de chargement de la page
    $('.zone_loading_page').remove();

    // Initialisation de la position Celsius
    setTimeout(function ()
    {
        initPositionCelsius();
    }, 100);
});

// Au scroll du document
$(window).scroll(function ()
{
    // Positionnement de Celsius en fonction du scroll
    initPositionCelsius();
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Positionnement de Celsius en fonction du redimensionnement de la fenêtre
    setTimeout(function ()
    {
        initPositionCelsius();
    }, 100);
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
    $('#' + id).fadeOut(200, function ()
    {
        $(this).remove();
    });
}

// Affiche ou masque une pop-up
function afficherMasquerPopUp(id, forceClose)
{
    // Affichage en tenant compte du forçage
    if ($('#' + id).css('display') == 'none' && forceClose != true)
    {
        // Intialisation de l'animation
        $('#' + id).css('transform', 'scale(0) translate(-50%, -50%)');
        $('#' + id).css('transform-origin', 'left top');
        $('#' + id).css('top', '50%');
        $('#' + id).css('left', '50%');
        $('#' + id).css('transition', 'transform 0.2s ease');

        // Affichage du fond
        $('#' + id).parent().fadeIn({ queue: false, duration: 300 });

        // Animation de l'échelle
        setTimeout(function ()
        {
            $('#' + id).css('transform', 'scale(1.2) translate(-50%, -50%)');
            $('#' + id).css('transform-origin', 'left top');
            $('#' + id).css('top', '50%');
            $('#' + id).css('left', '50%');
            $('#' + id).css('transition', 'transform 0.2s ease');

            // Apparition progressive de la zone
            $('#' + id).fadeIn({ queue: false, duration: 300 });

            setTimeout(function ()
            {
                $('#' + id).css('transform', 'scale(1) translate(-50%, -50%)');
                $('#' + id).css('transform-origin', 'left top');
                $('#' + id).css('top', '50%');
                $('#' + id).css('left', '50%');
                $('#' + id).css('transition', 'transform 0.2s ease');
            }, 200);
        }, 100);
    }
    else
    {
        // Masquage en tenant compte du forçage
        if ($('#' + id).css('display') != 'none')
        {
            // Intialisation de l'animation
            $('#' + id).css('transform', 'scale(1) translate(-50%, -50%)');
            $('#' + id).css('transform-origin', 'left top');
            $('#' + id).css('top', '50%');
            $('#' + id).css('left', '50%');
            $('#' + id).css('transition', 'transform 0.2s ease');

            // Masquage du fond
            $('#' + id).parent().fadeOut({ queue: false, duration: 300 });

            // Animation de l'échelle
            setTimeout(function ()
            {
                $('#' + id).css('transform', 'scale(1.2) translate(-50%, -50%)');
                $('#' + id).css('transform-origin', 'left top');
                $('#' + id).css('top', '50%');
                $('#' + id).css('left', '50%');
                $('#' + id).css('transition', 'transform 0.2s ease');

                // Disparition progressive de la zone
                $('#' + id).fadeOut({ queue: false, duration: 400 });

                setTimeout(function ()
                {
                    $('#' + id).css('transform', 'scale(0) translate(-50%, -50%)');
                    $('#' + id).css('transform-origin', 'left top');
                    $('#' + id).css('top', '50%');
                    $('#' + id).css('left', '50%');
                    $('#' + id).css('transition', 'transform 0.2s ease');
                }, 200);
            }, 100);
        }
    }
}

// Affichage de l'expérience dans l'entête
function afficherExperienceHeader(id)
{
    // Initialisations
    var rayonArc       = 32;
    var epaisseurLigne = 4;
    var abcisseCentre  = rayonArc + epaisseurLigne;
    var ordonneeCentre = rayonArc + epaisseurLigne;

    // Récupération des données
    var pourcentage = id.replace('canvas_header_', '');
    var canvas      = $('#' + id)[0];
    var context     = canvas.getContext("2d");

    // Calcul du début et de la fin de l'arc
    var debutArc = Math.PI / 2;
    var finArc   = -1 * pourcentage * Math.PI / 50 + debutArc;

    // Suppression du dessin précédent
    context.clearRect(0, 0, canvas.width, canvas.height);

    // Début de la ligne
    context.beginPath();

    // Epaisseur de la ligne
    context.lineWidth = epaisseurLigne;

    // Couleur de la ligne
    if (pourcentage == 100)
        context.strokeStyle = 'white';
    else
        context.strokeStyle = '#ff1937';

    // Lissage du contour
    context.imageSmoothingEnabled = true;

    // Définition de l'arc de cercle (dans le sens inverse des aiguilles d'une montre avec true)
    context.arc(abcisseCentre, ordonneeCentre, rayonArc, debutArc, finArc, true);

    // Création de la ligne
    context.stroke();
}

// Fonction initialisation position Celsius
function initPositionCelsius()
{
    var totalHeight = $('body')[0].scrollHeight - $(window).height();
    var difference  = $('footer').height() - (totalHeight - $(window).scrollTop()) + 20;

    if (difference > 0)
        $('.zone_celsius').css('bottom', difference + 'px');
    else
        $('.zone_celsius').css('bottom', '20px');
}

// Changement thème
function changeTheme(background, header, footer, logo)
{
    if (background != null)
    {
        $('section').css('background-image', 'url(' + background + ')');
        $('section').css('background-repeat', 'repeat-y');
        $('section').css('background-size', '100%');
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

    replace = str.replace(/[&<>"']/g, function (m)
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

    replace = str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function (m)
    {
        return map[m];
    });

    return replace;
}

// Gestion des cookies
function setCookie(cookieName, cookieValue)
{
    // Date expiration cookie (1 an)
    var today   = new Date();
    var expires = new Date();

    expires.setTime(today.getTime() + (60 * 60 * 24 * 365));

    // Cookie global (path=/)
    if (location.host.toLowerCase().includes('localhost'))
        document.cookie = cookieName + '=' + encodeURIComponent(cookieValue) + ';expires=' + expires.toGMTString() + ';path=/;SameSite=Lax';
    else
        document.cookie = cookieName + '=' + encodeURIComponent(cookieValue) + ';expires=' + expires.toGMTString() + ';path=/;domain=' + location.host + ';SameSite=Lax';
}

function getCookie(cookieName)
{
    var name          = cookieName + '=';
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
    if (location.host.toLowerCase().includes('localhost'))
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
    else
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=' + location.host;
}

// Exécute le script php de mise à jour du ping
function updatePing()
{
    $.post('/inside/includes/functions/script_commun.php', { function: 'updatePing' }, function (data)
    {
        // Récupération des données
        var userConnected = JSON.parse(data);

        // Arrêt du script si pas d'utilisateur connecté
        if (userConnected == false)
            clearInterval(majPing);
    });
}

// Exécute le script php de mise à jour du compteur de notifications
function updateNotifications()
{
    $.get('/inside/includes/functions/script_commun.php', { function: 'countNotifications' }, function (data)
    {
        var identifiant             = data.identifiant;
        var nombreNotificationsJour = data.nombreNotificationsJour;
        var view                    = data.view;
        var page                    = data.page;
        var html                    = '';

        // On n'exécute de manière récurrente que si on n'est pas l'admin
        if (identifiant != 'admin')
        {
            // La première fois on génère la zone
            if (!$('.link_notifications').length)
            {
                html += '<a href="/inside/portail/notifications/notifications.php?view=all&action=goConsulter&page=1" class="link_notifications">';
                    if (nombreNotificationsJour > 0)
                        html += '<img src="/inside/includes/icons/common/notifications.png" alt="notifications" class="icon_notifications" />';
                    else
                        html += '<img src="/inside/includes/icons/common/notifications_blue.png" alt="notifications" class="icon_notifications" />';

                    html += '<div class="number_notifications"></div>';
                html += '</a>';

                $('.zone_notifications_bandeau').html(html);
            }

            // On met à jour le contenu
            if (nombreNotificationsJour > 0)
            {
                $('.link_notifications').attr('href', '/inside/portail/notifications/notifications.php?view=' + view + '&action=goConsulter' + page)
                $('.icon_notifications').attr('src', '/inside/includes/icons/common/notifications_blue.png');

                if (nombreNotificationsJour <= 9)
                {
                    $('.number_notifications').html(nombreNotificationsJour);
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
    }, 'json');
}

// Exécute le script php de mise à jour du compteur de bugs / évolutions
function updateBugs()
{
    $.get('/inside/includes/functions/script_commun.php', { function: 'countBugs' }, function (data)
    {
        var identifiant = data.identifiant;
        var nombreBugs  = data.nombreBugs;
        var html        = '';

        // On n'exécute de manière récurrente que si on n'est pas l'admin
        if (identifiant != 'admin')
        {
            if (nombreBugs > 0)
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
                $('.number_bugs').html(nombreBugs);
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
    }, 'json');
}

// Fonction équivalente au $_GET en php
function $_GET(param)
{
    var vars = {};

    window.location.href.replace(location.hash, '').replace(
        // Expression régulière
        /[?&]+([^=&]+)=?([^&]*)?/gi,

        // Fonction retour
        function (m, key, value)
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
        $('html, body').animate({ scrollTop: posScroll }, speed);

        // Affichage d'une ombre pour un id "#zone_shadow_id"
        if (shadow == true)
        {
            // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
            $('#zone_shadow_' + id).css('box-shadow', '0 3px 10px #262626');

            setTimeout(function ()
            {
                $('#zone_shadow_' + id).css('box-shadow', '0 0 3px #7c7c7c');
                $('#zone_shadow_' + id).css({ transition: 'box-shadow ease 0.2s' });
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
        EXIF.getData(event.target.files[0], function ()
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
}

// Insère un nom de document dans la zone correspondante
function loadDocument(event, id)
{
    $('#' + id).text(event.target.files[0].name);
}

// Ouvre une fenêtre de confirmation
function confirmAction(form, message)
{
    // Suppression fenêtre éventuellement existante
    if ($('.fond_alerte').length)
        $('.fond_alerte').remove();

    // Génération nouvelle fenêtre de confirmation
    var html = '';

    html += '<div class="fond_alerte">';
        html += '<div class="zone_affichage_alerte" id="confirmBox">';
            html += '<input type="hidden" id="actionForm" value="' + form + '" />';

            // Titre
            html += '<div class="zone_titre_alerte">';
                html += '<img src="/inside/includes/icons/common/inside_grey.png" alt="inside_grey" class="image_alerte" />';
                html += '<div class="titre_alerte">Inside - Confirmation</div>';
            html += '</div>';

            // Affichage du message
            html += '<div class="zone_alertes">';
                html += '<div class="zone_texte_alerte">';
                    html += '<img src="/inside/includes/icons/common/question_grey.png" alt="question_grey" title="Confirmer ?" class="logo_alerte" />';

                    html += '<div class="texte_alerte">';
                        html += message;
                    html += '</div>';
                html += '</div>';
            html += '</div>';

            // Boutons
            html += '<a id="boutonAnnuler" class="bouton_alerte">Annuler</a>';
            html += '<a id="boutonConfirmer" class="bouton_alerte">Oui</a>';
        html += '</div>';
    html += '</div>';

    // Ajout à la page
    $('body').append(html);

    // Affichage de la fenêtre de confirmation
    afficherMasquerPopUp('confirmBox');
}

// Ferme la fenêtre ou execute le formulaire
function executeAction(form, action)
{
    if (action == 'cancel')
        afficherMasquerPopUp('confirmBox');
    else
        $('#' + form).submit();
}

// Animation chargement de la page en boucle
function loadingPage()
{
    $('.zone_loading_page').css('padding-top', '40px');
    $('.zone_loading_page').css('padding-bottom', '40px');
    $('#loading_page').css('height', '5px');
    $('#loading_page').css('margin-left', 0);
    $('#loading_page').css('opacity', 1);

    $('#loading_page').animate(
    {
        width: '+=100%',
        marginLeft: '0%'
    }, 800, 'easeInOutCubic', function ()
    {
        $('#loading_page').animate(
        {
            width: '-=100%',
            marginLeft: '100%'
        }, 800, 'easeInOutCubic', function ()
        {
            $('#loading_page').css('opacity', 0);

            setTimeout(function ()
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
    form.find('input, textarea, select').each(function ()
    {
        // Contrôle champ requis
        if ($(this).prop('required') == true && ($(this).val() == '' || $(this).val() == null))
        {
            hideButton = false;
            return false;
        }

        // Contrôle format numérique
        if ($(this).attr('type') == 'number' && $(this).val() != '' && $(this).val() != null)
        {
            if (!$.isNumeric($(this).val())
                || ($(this).val().attr('min') != '' && $(this).val() < $(this).val().attr('min'))
                || ($(this).val().attr('max') != '' && $(this).val() > $(this).val().attr('max')))
            {
                hideButton = false;
                return false;
            }
        }
    });

    if (hideButton == true)
    {
        // On fait disparaitre le bouton
        button.css('display', 'none');

        // On bloque les saisies
        form.find('input, textarea, select, label').each(function ()
        {
            $(this).prop('readonly', true);
            $(this).css('pointer-events', 'none');
            $(this).css('color', '#a3a3a3');
        });

        // Blocage des saisies spécifiques à partir d'un tableau
        if (tabBlock != null)
        {
            $.each(tabBlock, function ()
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

    $('.' + classZone + ' .loading_form').css('height', '5px');
    $('.' + classZone + ' .loading_form').css('margin-left', 0);
    $('.' + classZone + ' .loading_form').css('opacity', 1);

    $('.' + classZone + ' .loading_form').animate(
    {
        width: '+=100%',
        marginLeft: '0%'
    }, 800, 'easeInOutCubic', function ()
    {
        $('.' + classZone + ' .loading_form').animate(
        {
            width: '-=100%',
            marginLeft: '100%'
        }, 800, 'easeInOutCubic', function ()
        {
            $('.' + classZone + ' .loading_form').css('opacity', 0);

            setTimeout(function ()
            {
                loadingForm(zone);
            }, 200);
        });
    });
}

// Affiche ou masque une section
function afficherMasquerSection(lien, zone, forcage)
{
    var angle;

    // Calcul de l'angle
    var fleche = lien.children('.fleche_bouton_fold');
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

// Affiche le détail des notifications
function afficherDetailsNotifications()
{
    $.get('/inside/includes/functions/script_commun.php', { function: 'getDetailsNotifications' }, function (data)
    {
        var identifiant                = data.identifiant;
        var nombreNotificationsJour    = data.nombreNotificationsJour;
        var nombreNotificationsSemaine = data.nombreNotificationsSemaine;

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
                        if (nombreNotificationsJour == 1)
                            html += '<div class="number_notifications_details">' + nombreNotificationsJour + '</div> notification aujourd\'hui';
                        else
                            html += '<div class="number_notifications_details">' + nombreNotificationsJour + '</div> notifications aujourd\'hui';
                    html += '</div>';

                    // Notifications de la semaine
                    html += '<div class="ligne_details_notifications">';
                        if (nombreNotificationsSemaine == 1)
                            html += '<div class="number_notifications_details">' + nombreNotificationsSemaine + '</div> notification cette semaine';
                        else
                            html += '<div class="number_notifications_details">' + nombreNotificationsSemaine + '</div> notifications cette semaine';
                    html += '</div>';
                html += '</div>';
            html += '</div>';

            $('#afficherDetailNotifications').append(html);
        }
    }, 'json');
}

// Cache le nombre de notifications
function masquerDetailsNotifications()
{
    $('.zone_details_notifications').remove();
}

// Formate une date pour affichage (AAAAMMJJ -> JJ/MM/AAAA)
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

// Formate une date pour affichage (AAAA-MM-JJ -> JJ/MM/AAAA)
function formatDateForDisplayCsv(date)
{
    var dateFormatted;

    if (date.length == 10)
        dateFormatted = date.substr(8, 2) + '/' + date.substr(5, 2) + '/' + date.substr(0, 4);
    else
        dateFormatted = date;

    // Retour
    return dateFormatted;
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
function formatTimeForDisplayLight(time)
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

        // Calcul arrondi inférieur
        var amountRounded = Math.round(amountNumeric * 100) / 100;

        // Formatage en chaîne avec 2 chiffres après la virgule
        var amountFormatted = amountRounded.toFixed(2).toString().replace('.', ',') + currency;
    }

    // Retour
    return amountFormatted;
}

// Formate une numérique pour affichage
function formatNumericForDisplay(numeric)
{
    // Formatage
    var numericFormatted = numeric.replace('.', ',');

    // Retour
    return numericFormatted;
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
    var formattedAvatar = { 'path': path, 'alt': alt, 'title': pseudo };

    return formattedAvatar;
}

// Formate une chaîne de caractères en longueur
function formatString(string, limit)
{
    if (string.length > limit)
        string = string.substr(0, limit) + '...';

    return string;
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