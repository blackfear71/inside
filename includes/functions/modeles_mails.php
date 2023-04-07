<?php
    // MODELE : Edition modèle de mail pour sortie film
    // RETOUR : Modèle de mail
    function getModeleMailFilm($details, $participants)
    {
        // Initialisations
        $modele = '';

        // Contenu
        $modele .= '<html lang="fr">';
            $modele .= '<head>';
                // CSS
                $modele .= '<style type="text/css">';
                    $modele .= '
                        .zone_bandeau_mail
                        {
                            background-color: #262626;
                            width: 100%;
                            min-height: 80px;
                            overflow: hidden;
                        }

                        .zone_bandeau_mail_left
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: 80px;
                            height: 80px;
                        }

                        .logo_bandeau_mail
                        {
                            display: block;
                            height: 70px;
                            margin: 5px;
                        }

                        .zone_bandeau_mail_right
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: calc(100% - 100px);
                            min-height: 80px;
                            padding-right: 20px;
                        }

                        .zone_bandeau_mail_right_top
                        {
                            display: block;
                            margin-top: 10px;
                            min-height: 30px;
                            line-height: 30px;
                            font-family: Calibri, Verdana, sans-serif;
                            font-size: 150%;
                            color: white;
                            text-align: right;
                        }

                        .zone_bandeau_mail_right_bottom
                        {
                            display: block;
                            min-height: 30px;
                            line-height: 30px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 110%;
                            color: white;
                            text-align: right;
                        }

                        .article
                        {
                            padding: 20px;
                            width: calc(100% - 40px);
                            background-color: #fbfbfb;
                        }

                        .zone_contenu_mail
                        {
                            display: block;
                            width: 100%;
                            text-align: justify;
                        }

                        .zone_contenu_mail_left
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: calc(60% - 20px);
                            margin-right: 20px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 120%;
                        }

                        .participant_mail
                        {
                            display: block;
                            margin-bottom: 10px;
                        }

                        .avatar_mail
                        {
                            display: inline-block;
                            border-radius: 50%;
                            width: 40px;
                            background-color: #262626;
                            vertical-align: middle;
                            margin-right: 10px;
                        }

                        .pseudo_mail
                        {
                            width: calc(100% - 50px);
                            display: inline-block;
                            word-break: break-word;
                            font-size: 80%;
                            font-weight: bold;
                            vertical-align: middle;
                        }

                        .zone_contenu_mail_right
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: 40%;
                        }

                        .zone_poster_mail
                        {
                            display: block;
                            border-radius: 2px;
                            overflow: hidden;
                        }

                        .poster_mail
                        {
                            display: block;
                            max-width: 100%;
                        }

                        .footer
                        {
                            display: block;
                            width: calc(100% - 20px);
                            background-color: #262626;
                            height: 50px;
                            line-height: 50px;
                            padding-left: 10px;
                            padding-right: 10px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 110%;
                            color: white;
                            text-align: right;
                        }
                        ';
                $modele .= '</style>';
            $modele .= '</head>';

            // Affichage du contenu
            $modele .= '<body>';
                // Entête
                $modele .= '<div>';
                    $modele .= '<div class="zone_bandeau_mail">';
                        // Logo et lien
                        $modele .= '<div class="zone_bandeau_mail_left">';
                            $modele .= '<a href="https://inside.ddns.net/inside/index.php?action=goConsulter">';
                                $modele .= '<img src="../../includes/icons/common/inside.png" alt="inside" class="logo_bandeau_mail" />';
                            $modele .= '</a>';
                        $modele .= '</div>';

                        // Titre et semaine
                        $modele .= '<div class="zone_bandeau_mail_right">';
                            // Titre
                            $modele .= '<div class="zone_bandeau_mail_right_top">' . $details->getFilm() . '</div>';

                            // Semaine
                            $modele .= '<div class="zone_bandeau_mail_right_bottom">';
                                if (!empty($details->getDate_doodle()))
                                    $modele .= 'Sortie organisée le ' . formatDateForDisplay($details->getDate_doodle());
                                else
                                    $modele .= 'Sortie à organiser';
                            $modele .= '</div>';
                        $modele .= '</div>';
                    $modele .= '</div>';
                $modele .= '</div>';

                // Contenu
                $modele .= '<div>';
                    $modele .= '<div class="article">';
                        $modele .= '<div class="zone_contenu_mail">';
                            // Corps du mail
                            $modele .= '<div class="zone_contenu_mail_left">';
                                $modele .= 'Bonjour,';
                                $modele .= '<br /><br /><br /><br />';
                                $modele .= 'Vous avez stipulé être intéréssé(e) par le film : <strong>' . $details->getFilm() . '</strong>';
                                $modele .= '<br /><br />';

                                if (!empty($details->getDoodle()))
                                    $modele .= 'Vous recevez ce mail contenant le lien doodle à renseigner pour donner votre disponibilité : <a href="' . $details->getDoodle() . '" target="_blank">Doodle</a>';
                                else
                                    $modele .= 'Aucun Doodle n\'a encore été créé pour ce film. Si vous êtes intéressé(e), veuillez le mettre en place.';

                                $modele .= '<br /><br />';
                                $modele .= 'Les personnes intéressées sont :';
                                $modele .= '<br /><br />';

                                foreach ($participants as $participant)
                                {
                                    $modele .= '<div class="participant_mail">';
                                        $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                                        $modele .= '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_mail" />';
                                        $modele .= '<div class="pseudo_mail">' . $participant->getPseudo() . '</div>';
                                    $modele .= '</div>';
                                }

                                $modele .= '<br /><br /><br />';
                                $modele .= 'Cordialement,';
                            $modele .= '</div>';

                            // Poster du film
                            $modele .= '<div class="zone_contenu_mail_right">';
                                $modele .= '<div class="zone_poster_mail">';
                                    if (!empty($details->getPoster()))
                                        $modele .= '<img src="' . $details->getPoster() . '" alt="poster" title="' . $details->getFilm() . '" class="poster_mail" />';
                                    else
                                        $modele .= '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $details->getFilm() . '" class="poster_mail" />';
                                $modele .= '</div>';
                            $modele .= '</div>';
                        $modele .= '</div>';
                    $modele .= '</div>';
                $modele .= '</div>';

                // Pied de page
                $modele .= '<div class="footer">';
                    $modele .= '© 2017-' . date('Y') . ' Inside';
                $modele .= '</div>';
            $modele .= '</body>';
        $modele .= '</html>';

        // Retour
        return $modele;
    }

    // MODELE : Edition modèle de mail pour gestion du site
    // RETOUR : Modèle de mail
    function getModeleMailAdministration($demandes)
    {
        // Initialisations
        $modele = '';

        // Contenu
        $modele .= '<html lang="fr">';
            $modele .= '<head>';
                // CSS
                $modele .= '<style type="text/css">';
                    $modele .= '
                        .zone_bandeau_mail
                        {
                            background-color: #262626;
                            width: 100%;
                            height: 80px;
                            overflow: hidden;
                        }

                        .zone_bandeau_mail_left
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: 80px;
                            height: 80px;
                        }

                        .logo_bandeau_mail
                        {
                            display: block;
                            height: 70px;
                            margin: 5px;
                        }

                        .zone_bandeau_mail_right
                        {
                            display: inline-block;
                            vertical-align: top;
                            width: calc(100% - 100px);
                            height: 80px;
                            padding-right: 20px;
                        }

                        .zone_bandeau_mail_right_top
                        {
                            display: block;
                            margin-top: 10px;
                            height: 30px;
                            line-height: 30px;
                            font-family: Calibri, Verdana, sans-serif;
                            font-size: 150%;
                            color: white;
                            text-align: right;
                        }

                        .zone_bandeau_mail_right_bottom
                        {
                            display: block;
                            height: 30px;
                            line-height: 30px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 110%;
                            color: white;
                            text-align: right;
                        }

                        article
                        {
                            padding-left: 20px;
                            padding-right: 20px;
                            width: calc(100% - 40px);
                            background-color: #fbfbfb;
                        }

                        .titre_section_mail
                        {
                            display: block;
                            width: 100%;
                            font-family: Calibri, Verdana, sans-serif;
                            font-size: 150%;
                            font-weight: bold;
                            border-bottom: solid 1px #b3b3b3;
                            line-height: 30px;
                            margin-bottom: 20px;
                            padding-top: 10px;
                            padding-bottom: 10px;
                            color: #262626;
                            text-align: left;
                            word-break: break-word;
                        }

                        .logo_titre_section_mail
                        {
                            display: inline-block;
                            vertical-align: middle;
                            height: 25px;
                            width: 25px;
                        }

                        .texte_titre_section_mail
                        {
                            display: inline-block;
                            vertical-align: middle;
                            width: calc(100% - 35px);
                            margin-left: 10px;
                        }

                        .zone_contenu_mail
                        {
                            display: block;
                            width: 100%;
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        .zone_nombre_demandes_mail
                        {
                            display: inline-block;
                            width: 250px;
                            height: 200px;
                            border-radius: 2px;
                            overflow: hidden;
                            margin: 0 10px 10px 10px;
                            border: solid 1px #d3d3d3;
                        }

                        .titre_demandes_mail
                        {
                            display: block;
                            width: calc(100% - 20px);
                            height: 40px;
                            line-height: 40px;
                            font-family: Calibri, Verdana, sans-serif;
                            font-size: 120%;
                            padding: 10px;
                            color: white;
                            text-align: center;
                            word-break: break-word;
                            background-color: #ff1937;
                        }

                        .valeur_demandes_mail
                        {
                            display: block;
                            width: 100%;
                            height: 140px;
                            line-height: 140px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 600%;
                            color: #262626;
                            text-align: center;
                            background-color: white;
                        }

                        footer
                        {
                            display: block;
                            width: calc(100% - 20px);
                            background-color: #262626;
                            height: 50px;
                            line-height: 50px;
                            padding-left: 10px;
                            padding-right: 10px;
                            font-family: Calibri Light, Verdana, sans-serif;
                            font-size: 110%;
                            color: white;
                            text-align: right;
                        }
                        ';
                $modele .= '</style>';
            $modele .= '</head>';

            // Affichage du contenu
            $modele .= '<body>';
                // Entête
                $modele .= '<header>';
                    $modele .= '<div class="zone_bandeau_mail">';
                        // Logo et lien
                        $modele .= '<div class="zone_bandeau_mail_left">';
                            $modele .= '<a href="https://inside.ddns.net/inside/index.php?action=goConsulter">';
                                $modele .= '<img src="../includes/icons/common/inside.png" alt="inside" class="logo_bandeau_mail" />';
                            $modele .= '</a>';
                        $modele .= '</div>';

                        // Titre et semaine
                        $modele .= '<div class="zone_bandeau_mail_right">';
                            // Titre
                            $modele .= '<div class="zone_bandeau_mail_right_top">';
                                $modele .= 'GESTION DU SITE';
                            $modele .= '</div>';

                            // Semaine
                            $modele .= '<div class="zone_bandeau_mail_right_bottom">';
                                $modele .= date('Y') . ' - Semaine ' . date('W');
                            $modele .= '</div>';
                        $modele .= '</div>';
                    $modele .= '</div>';
                $modele .= '</header>';

                // Contenu
                $modele .= '<section>';
                    $modele .= '<article>';
                        foreach ($demandes as $demande)
                        {
                            // Titre
                            $modele .= '<div class="titre_section_mail"><img src="../includes/icons/admin/' . $demande['icone'] . '.png" alt="' . $demande['icone'] . '" class="logo_titre_section_mail" /><div class="texte_titre_section_mail">' . $demande['titre'] . '</div></div>';

                            // Contenu
                            $modele .= '<div class="zone_contenu_mail">';
                                foreach ($demande['contenu'] as $contenu)
                                {
                                    $modele .= '<div class="zone_nombre_demandes_mail">';
                                        // Titre
                                        $modele .= '<div class="titre_demandes_mail">';
                                            $modele .= $contenu[0];
                                        $modele .= '</div>';

                                        // Valeur
                                        $modele .= '<div class="valeur_demandes_mail">';
                                            $modele .= $contenu[1];
                                        $modele .= '</div>';
                                    $modele .= '</div>';
                                }
                            $modele .= '</div>';
                        }
                    $modele .= '</article>';
                $modele .= '</section>';

                // Pied de page
                $modele .= '<footer>';
                    $modele .= '© 2017-' . date('Y') . ' Inside';
                $modele .= '</footer>';
            $modele .= '</body>';
        $modele .= '</html>';

        // Retour
        return $modele;
    }
?>