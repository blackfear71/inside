/***********************/
/*** Initialisations ***/
/***********************/
// Initialisations variables globales
var initialHeight = window.innerHeight;

/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function ()
{
    /** Actions au chargement ***/
    // Forçage taille écran (viewport)
    fixViewport();

    // Applique le thème utilisateur si présent
    if (themeUser != null)
        changeTheme(themeUser.background, themeUser.header, themeUser.footer, themeUser.logo);

    // Initialisation de la position Celsius si présente
    if ($('.celsius').length)
        initPositionCelsius();

    // Animation symbole chargement de la page
    loadingPage('zone_loading_image', 'loading_image');
    loadPage = setInterval(function ()
    {
        loadingPage('zone_loading_image', 'loading_image');
    }, 100);

    // Mise à jour du ping à chaque chargement de page et toutes 60 secondes
    updatePing();
    majPing = setInterval(updatePing, 60000);

    // Mise à jour du compteur des notifications toutes les 60 secondes
    updateNotifications();
    majNotifications = setInterval(updateNotifications, 60000);

    // Mise à jour du compteur des bugs / évolutions toutes les 60 secondes
    updateBugs();
    majBugs = setInterval(updateBugs, 60000);

    // Affichage des alertes
    if ($('#alerte').length)
        afficherMasquerPopUp('alerte', false);

    /*** Actions au clic ***/
    // Ouverture barre de recherche
    $('#afficherBarreRecherche, #masquerBarreRecherche').click(function ()
    {
        afficherMasquerIdWithDelay('searchBar');
        $('#searchFocus').focus();
    });

    // Ouverture menu latéral gauche
    $('#deployAsidePortail').click(function ()
    {
        deployerMenuPortail();
    });

    // Ouverture menu latéral droit
    $('#deployAsideUser').click(function ()
    {
        deployerMenuUser();
    });

    // Déplie une zone en cliquant sur le titre
    $('.titre_section').click(function ()
    {
        var idZone = $(this).attr('id').replace('titre_', 'afficher_');

        openSection($(this), idZone, '');
    });

    // Ferme un menu au clic sur le fond
    $(document).on('click', function (event)
    {
        // Ferme la zone de recherche
        if ($(event.target).attr('class') == 'fond_recherche')
            afficherMasquerIdWithDelay('searchBar');

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
        &&  $(event.target).attr('class') != 'fond_experience_aside'
        &&  $(event.target).attr('class') != 'experience_aside'
        &&  $(event.target).attr('class') != 'niveau_aside'
        &&  $('.aside_user').css('right') == '0px')
            deployerMenuUser();

        // Ferme le contenu Celsius
        if ($(event.target).attr('class')       != 'zone_contenu_celsius'
        &&  $(event.target).attr('class')       != 'titre_contenu_celsius'
        &&  $(event.target).attr('class')       != 'zone_texte_celsius'
        &&  $(event.target).attr('class')       != 'texte_contenu_celsius'
        &&  $(event.target).attr('class')       != 'zone_boutons_celsius'
        &&  $(event.target).attr('class')       != 'bouton_celsius_left'
        &&  $(event.target).attr('class')       != 'bouton_celsius_right'
        &&  $(event.target).attr('class')       != 'celsius'
        &&  $('#contenuCelsius').css('display') != 'none')
            afficherMasquerIdWithDelay('contenuCelsius');

        // Ferme une zone de saisie ou de détails
        if ($(event.target).attr('class') == 'fond_saisie'
        ||  $(event.target).attr('class') == 'fond_details')
            afficherMasquerIdWithDelay(event.target.id);

        // Ferme une image
        if ($(event.target).attr('class') == 'fond_zoom_image')
            masquerSupprimerIdWithDelay('zoom_image');

        // Ferme une zone de succès
        if ($(event.target).attr('class') == 'fond_zoom_succes')
            masquerSupprimerIdWithDelay('zoom_succes');
    });

    // Bouton fermer alerte
    $('#fermerAlerte').click(function ()
    {
        afficherMasquerPopUp('alerte', false);
    });

    // Messages de confirmation
    $('.eventConfirm').click(function ()
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

    // Annuler confirmation
    $(document).on('click', '#boutonAnnuler', function ()
    {
        var actionForm = $('#actionForm').val();

        executeAction(actionForm, 'cancel');
    });

    // Valider confirmation
    $(document).on('click', '#boutonConfirmer', function ()
    {
        var actionForm = $('#actionForm').val();

        executeAction(actionForm, 'validate');
    });

    // Réinitialise la position de Celsius
    $('#resetCelsius').click(function ()
    {
        resetCelsius();
    });

    // Ferme le contenu Celsius
    $('#closeCelsius').click(function ()
    {
        afficherMasquerIdWithDelay('contenuCelsius');
    });

    // Fermeture zoom succès
    $(document).on('click', '#closeZoomSuccess', function ()
    {
        masquerSupprimerIdWithDelay('zoom_succes');
    });

    /*** Actions sur mobile ***/
    // Positionnement top début maintien clic et positions initiales
    $('.celsius').on('touchstart', function (e)
    {
        e.preventDefault();

        // Initialisation Celsius
        touchStartCelsius($(this), e);
    });

    // Positionnement top fin maintien clic
    $('.celsius').on('touchend', function (e)
    {
        e.preventDefault();

        // Fin Celsius
        touchEndCelsius($(this));
    });

    // Déplacement du bloc
    $('.celsius').on('touchmove', function (e)
    {
        e.preventDefault();

        // Déplacement Celsius
        touchMoveCelsius($(this), e);
    });
});

// Au chargement du document complet
$(window).on('load', function ()
{
    // Remplacement du chargement par le contenu
    endLoading();
});

// Au redimensionnement de la fenêtre
$(window).resize(function ()
{
    // Adaptation de la taille d'une saisie à l'affichage / masquage du clavier
    adaptSaisieClavier();
});

// Au changement d'orientation
$(window).on('orientationchange', function (e)
{
    // Forçage taille écran (viewport)
    if (e.orientation == 'landscape')
        fixViewport();

    // Adaptation de la taille d'une saisie au changement d'orientation
    adaptSaisieOrientation();

    // Réinitialisation positionnement de Celsius (avec un délai pour éviter les erreurs)
    setTimeout(function ()
    {
        if ($('.celsius').length)
        {
            // Réinitialisation de la position de Celsius
            resetCelsius();
        }
    }, 200);
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
    if (screen.height < screen.width)
    {
        viewHeight = window.innerWidth;
        viewWidth  = window.innerHeight;
    }
    else
    {
        viewHeight = window.innerHeight;
        viewWidth  = window.innerWidth;
    }

    var viewport = document.querySelector('meta[name=viewport]');

    viewport.setAttribute('content', 'height=' + viewHeight + 'px, width=' + viewWidth + 'px, initial-scale=1.0, minimum-scale=1, maximum-scale=1.0, user-scalable=no');
}

// Adaptation de la saisie à l'affichage du clavier
function adaptSaisieClavier()
{
    // Vérification saisie affichée
    var saisieAffichee = false;
    var saisie;

    $.each($('.fond_saisie'), function ()
    {
        if ($(this).css('display') != 'none' && ($(this).find('.form_saisie').length || $(this).find('.div_saisie').length))
        {
            saisieAffichee = true;
            saisie         = $(this);

            return false;
        }
    });

    // On adapte la saisie concernée
    if (saisieAffichee)
    {
        if (initialHeight > window.innerHeight)
        {
            if (saisie.find('.zone_contenu_saisie').length)
                saisie.find('.zone_contenu_saisie').css('max-height', '25vh');
            else if (saisie.find('.zone_contenu_saisie_live').length)
                saisie.find('.zone_contenu_saisie_live').css('max-height', '20vh');
        }
        else
        {
            if (saisie.find('.zone_contenu_saisie').length)
            {
                if (window.innerHeight <= window.innerWidth)
                    saisie.find('.zone_contenu_saisie').css('max-height', '25vh');
                else
                    saisie.find('.zone_contenu_saisie').css('max-height', '65.7vh');
            }
            else if (saisie.find('.zone_contenu_saisie_live').length)
            {
                if (window.innerHeight <= window.innerWidth)
                    saisie.find('.zone_contenu_saisie_live').css('max-height', '20vh');
                else
                    saisie.find('.zone_contenu_saisie_live').css('max-height', '59.7vh');
            }
        }
    }
}

// Adaptation de la saisie selon l'orientation
function adaptSaisieOrientation()
{
    // Vérification saisie affichée
    var saisieAffichee = false;
    var saisie;

    $.each($('.fond_saisie'), function ()
    {
        if ($(this).css('display') != 'none' && ($(this).find('.form_saisie').length || $(this).find('.div_saisie').length))
        {
            saisieAffichee = true;
            saisie         = $(this);

            return false;
        }
    });

    // On adapte la saisie concernée
    if (saisieAffichee)
    {
        // Si la hauteur est inférieure ou égale à la largeur, alors on est en paysage
        setTimeout(function ()
        {
            if (saisie.find('.zone_contenu_saisie').length)
            {
                if (window.innerHeight <= window.innerWidth)
                    saisie.find('.zone_contenu_saisie').css('max-height', '25vh');
                else
                    saisie.find('.zone_contenu_saisie').css('max-height', '65.7vh');
            }
            else if (saisie.find('.zone_contenu_saisie_live').length)
            {
                if (window.innerHeight <= window.innerWidth)
                    saisie.find('.zone_contenu_saisie_live').css('max-height', '20vh');
                else
                    saisie.find('.zone_contenu_saisie_live').css('max-height', '59.7vh');
            }
        }, 350);
    }
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
        $('.zone_bandeau').css('background-size', 'auto 100%');
        $('.zone_bandeau').css('background-position', 'center');
    }

    if (footer != null)
    {
        $('footer').css('background-image', 'url(' + footer + ')');
        $('footer').css('background-repeat', 'repeat-x');
        $('footer').css('background-size', 'auto 100%');
        $('footer').css('background-position', 'center');
    }

    if (logo != null)
        $('#logo_inside_header').attr('src', logo);
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
        $('html, body').animate({ scrollTop: posScroll }, speed);

        // Affichage d'une ombre pour un id "#zone_shadow_id"
        if (shadow == true)
        {
            // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
            $('#zone_shadow_' + id).css('box-shadow', '0 0 1vh #262626');

            setTimeout(function ()
            {
                $('#zone_shadow_' + id).css('box-shadow', '0 0 0.5vh #7c7c7c');
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

// Initialisation de la position Celsius
function initPositionCelsius()
{
    var cookieCelsiusPositionX = getCookie('celsius[positionX]');
    var cookieCelsiusPositionY = getCookie('celsius[positionY]');

    if (cookieCelsiusPositionX == null || cookieCelsiusPositionX == 'undefined' || cookieCelsiusPositionX == ''
    ||  cookieCelsiusPositionY == null || cookieCelsiusPositionY == 'undefined' || cookieCelsiusPositionY == '')
    {
        // Positionnement en fonction de l'orientation
        if (screen.height > screen.width)
            $('.celsius').css('top', 'calc(100% - 16vh)');
        else
            $('.celsius').css('top', 'calc(100% - 9vh)');

        $('.celsius').css('left', 'calc(100% - 9vh)');

        // Définition des cookies
        setCookie('celsius[positionX]', $('.celsius').offset().left);
        setCookie('celsius[positionY]', $('.celsius').offset().top);
    }
    else
    {
        // Positionnement
        $('.celsius').css('top', cookieCelsiusPositionY + 'px');
        $('.celsius').css('left', cookieCelsiusPositionX + 'px');
    }
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

// Réinitialisation de la position de Celsius
function resetCelsius()
{
    // Réinitialisation des cookies de position Celsius
    deleteCookie('celsius[positionX]');
    deleteCookie('celsius[positionY]');

    // Fermeture du contenu
    if ($('#contenuCelsius').css('display') != 'none')
        afficherMasquerIdWithDelay('contenuCelsius');

    // Animation de la réinitialisation (délai de 0.2s entre chaque animation + prise en compte de la variation d'échelle dans le positionnement de Celsius)
    $('.celsius').css('transform', 'scale(1.2)');
    $('.celsius').css('transition', 'transform 0.2s ease');

    setTimeout(function ()
    {
        $('.celsius').css('transform', 'scale(0)');
        $('.celsius').css('transition', 'transform 0.2s ease');

        setTimeout(function ()
        {
            // Prise en compte de la variation d'échelle : on remet Celsius à la bonne taille de manière cachée pour calculer ensuite les coordonnées correctes pour les cookies
            $('.celsius').css('visibility', 'hidden');
            $('.celsius').css('transform', 'scale(1)');

            setTimeout(function ()
            {
                // Réinitialsiation position Celsius
                initPositionCelsius();

                $('.celsius').css('transform', 'scale(0)');

                setTimeout(function ()
                {
                    $('.celsius').css('visibility', 'visible');
                    $('.celsius').css('transform', 'scale(1.2)');
                    $('.celsius').css('transition', 'visibility 0, transform 0.2s ease');

                    setTimeout(function ()
                    {
                        $('.celsius').css('visibility', 'visible');
                        $('.celsius').css('transform', 'scale(1)');
                        $('.celsius').css('transition', 'transform 0.2s ease');
                    }, 200);
                }, 100);
            }, 100);
        }, 10);
    }, 100);
}

// Initialisations Celsius au clic
function touchStartCelsius(celsius, e)
{
    // Top début maintien clic
    celsius.data('touchstart', true);

    // Détermination clic simple
    setTimeout(function ()
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

    // Définition des cookies (en laissant le temps à l'icône de reprendre sa taille)
    setTimeout(function ()
    {
        var celsiusPosX = celsius.offset().left;
        var celsiusPosY = celsius.offset().top;

        setCookie('celsius[positionX]', celsiusPosX);
        setCookie('celsius[positionY]', celsiusPosY);
    }, 200);
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
function loadingPage(zone, image)
{
    if ($('.' + zone).length)
    {
        var angle;

        // Calcul de l'angle courant
        var matrix = $('#' + image).css('transform');

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
        $('#' + image).css('transform', 'rotate(' + angle + 'deg)');
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

// Bloque une saisie en cas de soumission de formulaire
function blockValidationSubmission(form, zoneForm, zoneContenuForm, rechercheLive = false)
{
    // On vérifie chaque saisie obligatoire
    var hideForm = true;

    form.find('input, textarea, select').each(function ()
    {
        // Contrôle champ requis
        if ($(this).prop('required') == true && ($(this).val() == '' || $(this).val() == null))
        {
            hideForm = false;
            return false;
        }

        // Contrôle format numérique
        if ($(this).attr('type') == 'number')
        {
            if (!$.isNumeric($(this).val())
                || ($(this).val().attr('min') != '' && $(this).val() < $(this).val().attr('min'))
                || ($(this).val().attr('max') != '' && $(this).val() > $(this).val().attr('max')))
            {
                hideForm = false;
                return false;
            }
        }
    });

    if (hideForm == true)
    {
        // Masquage du formulaire
        form.find('.' + zoneContenuForm).css('display', 'none');
        form.find('.zone_boutons_saisie').css('display', 'none');

        // Masquage de la barre de recherche live
        if (rechercheLive == true)
            form.find('.zone_recherche_live').css('display', 'none');

        // Génération du symbole de chargement
        var loading = '';

        loading += '<div class="zone_loading_image_form">';
            loading += '<img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image_form" class="loading_image_form" />';
        loading += '</div>';

        form.find('.' + zoneForm).append(loading);

        // Animation du symbole de chargement
        loadingPage('zone_loading_image_form', 'loading_image_form');
        setInterval(function ()
        {
            loadingPage('zone_loading_image_form', 'loading_image_form');
        }, 100);
    }
}

// Bloque la page en cas de soumission de formulaire
function blockValidationSubmissionPage(form)
{
    // On vérifie chaque saisie obligatoire
    var hideForm = true;

    form.find('input, textarea, select').each(function ()
    {
        // Contrôle champ requis
        if ($(this).prop('required') == true && ($(this).val() == '' || $(this).val() == null))
        {
            hideForm = false;
            return false;
        }

        // Contrôle format numérique
        if ($(this).attr('type') == 'number')
        {
            if (!$.isNumeric($(this).val())
                || ($(this).val().attr('min') != '' && $(this).val() < $(this).val().attr('min'))
                || ($(this).val().attr('max') != '' && $(this).val() > $(this).val().attr('max')))
            {
                hideForm = false;
                return false;
            }
        }
    });

    if (hideForm == true)
    {
        // Masquage du formulaire
        $('article').css('display', 'none');

        // Génération du symbole de chargement
        var loading = '';

        loading += '<div class="zone_loading_image">';
            loading += '<img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />';
        loading += '</div>';

        $('aside').after(loading);

        // Animation du symbole de chargement
        loadingPage('zone_loading_image', 'loading_image');
        setInterval(function ()
        {
            loadingPage('zone_loading_image', 'loading_image');
        }, 100);
    }
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
    {
        // Affichage de la zone
        $('#' + id).fadeIn(200);

        // Dans le cas d'une saisie, adaptation selon l'orientation
        adaptSaisieOrientation();
    }
    else
        $('#' + id).fadeOut(200);
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
                html += '<a href="/inside/portail/notifications/notifications.php?view=all&action=goConsulter&page=1" title="Notifications" class="link_notifications">';
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

// Formate une date pour affichage saisie sur mobile
function formatDateForDisplayMobile(date)
{
    var dateFormatted;

    if (date.length == 8)
        dateFormatted = date.substr(0, 4) + '-' + date.substr(4, 2) + '-' + date.substr(6, 2);
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

// Formate un numérique pour affichage
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

// Converti une chaîne en texte
function decodeText(chaine)
{
    var decoded = $('<div />').html(chaine).text();

    return decoded;
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

// Définition d'un cookie
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

// Lecture d'un cookie
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

// Suppression d'un cookie
function deleteCookie(cookieName)
{
    if (location.host.toLowerCase().includes('localhost'))
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
    else
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=' + location.host;
}