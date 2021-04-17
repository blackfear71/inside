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
        .zone_mail
        {
          margin-left: auto;
          margin-right: auto;
          width: 800px;
          min-height: 500px;
          text-align: center;
          border-radius: 2px;
          overflow: hidden;
          background-color: #e3e3e3;
        }

        .entete_mail
        {
          background-color: #ff1937;
          height: 100px;
        }

        .logo_inside_mail
        {
          height: 100%;
          margin-top: 3px;
        }

        .mask_mail
        {
          height: 15px;
          width: 100%;
        }

        .triangle_mail
        {
          width: 100%;
          height: 100%;
          left: 0px;
          top: 0px;
          background: linear-gradient(to right bottom, #ff1937 50%, transparent 50%);
        }

        .corps_mail
        {
          width: 100%;
          margin-top: 20px;
        }

        .corps_mail_left
        {
          float: left;
          width: 60%;
          height: 100%;
        }

        .text_mail
        {
          text-align: justify;
          padding: 20px;
          font-family: robotolight, Calibri, Verdana, sans-serif;
          color: #262626;
        }

        .destinataires_mail
        {
        	margin: 10px 0 10px 30px;
        }

        .avatar_dest_mail
        {
          display: inline-block;
        	border-radius: 50%;
        	width: 40px;
        	background-color: #262626;
        	box-shadow: 0 0 3px #7c7c7c;
        	vertical-align: middle;
        	margin-right: 10px;
        	vertical-align: middle;
        }

        .pseudo_dest_mail
        {
        	width: calc(100% - 50px);
        	display: inline-block;
        	word-break: break-word;
        	font-size: 80%;
        	vertical-align: middle;
        }

        .participants_mail
        {
          line-height: 25px;
          margin: 0;
          margin-left: 30px;
        }

        .corps_mail_right
        {
          float: right;
          width: calc(40% - 20px);
          height: 100%;
          padding-right: 20px;
          padding-bottom: 15px;
        }

        .poster_mail
        {
          max-width: 100%;
          border-radius: 2px;
        }

        .footer_mail
        {
          clear: both;
        }

        .logo_inside_mini_mail
        {
          width: 50px;
        }
        ';
        $modele .= '</style>';
      $modele .= '</head>';

      // HTML + données
      $modele .= '<body>';
        $modele .= '<div class="zone_mail">';
          $modele .= '<div class="entete_mail">';
            $modele .= '<img src="../../includes/icons/common/inside.png" alt="inside" class="logo_inside_mail" />';
          $modele .= '</div>';

          $modele .= '<div class="mask_mail">';
            $modele .= '<div class="triangle_mail"></div>';
          $modele .= '</div>';

          $modele .= '<div class="corps_mail">';
            $modele .= '<div class="corps_mail_left">';
              $modele .= '<div class="text_mail">';
                $modele .= 'Bonjour,';
                $modele .= '<br /><br /><br /><br />';
                $modele .= 'Vous avez stipulé être intéréssé(e) par le film : <strong>' . $details->getFilm() . '</strong>';
                $modele .= '<br /><br />';
                if (!empty($details->getDoodle()))
                  $modele .= 'Vous recevez ce mail contenant le lien doodle à renseigner pour donner votre disponibilité : <a href="' . $details->getDoodle() . '" target="_blank">Doodle</a>';
                else
                  $modele .= 'Aucun Doodle n\'a encore été créé pour ce film. Si vous êtes intéressé(e), veuillez le mettre en place.';
                $modele .= '<br /><br />';
                $modele .= 'Les personnes intéressées sont :<br />';
                foreach ($participants as $participant)
                {
                  $modele .= '<div class="destinataires_mail">';
                    $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                    $modele .= '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_dest_mail" />';
                    $modele .= '<div class="pseudo_dest_mail">' . $participant->getPseudo() . '</div>';
                  $modele .= '</div>';
                }
                $modele .= '<br /><br /><br />';
                $modele .= 'Cordialement,';
                $modele .= '<br /><br /><br /><br />';
                $modele .= 'Mail envoyé depuis INSIDE.';
              $modele .= '</div>';
            $modele .= '</div>';

            $modele .= '<div class="corps_mail_right">';
              if (!empty($details->getPoster()))
                $modele .= '<img src="' . $details->getPoster() . '" alt="poster" title="' . $details->getFilm() . '" class="poster_mail" />';
              else
                $modele .= '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $details->getFilm() . '" class="poster_mail" />';
            $modele .= '</div>';
          $modele .= '</div>';

          $modele .= '<div class="footer_mail">';
            $modele .= '<img src="../../includes/icons/common/inside_mini.png" alt="inside" class="logo_inside_mini_mail" />';
          $modele .= '</div>';
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
        	height: 25px;
        	width: 25px;
        	vertical-align: top;
        	margin-top: 5px;
        }

        .texte_titre_section_mail
        {
          display: inline-block;
        	width: calc(100% - 35px);
        	margin-left: 10px;
        	vertical-align: middle;
        }

        .zone_contenu_mail
        {
          display: block;
          width: 100%;
          text-align: center;
          margin-bottom: -20px;
        }

        .zone_nombre_demandes_mail
        {
          display: inline-block;
          width: 200px;
          height: 200px;
          border-radius: 2px;
          overflow: hidden;
          margin: 0 20px 20px 20px;
        }

        .titre_demandes_mail
        {
          display: block;
          width: calc(100% - 20px);
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
              $modele .= '<a href="http://77.150.63.94/inside/index.php?action=goConsulter">';
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
            // Gestion des utilisateurs
            $modele .= '<div class="titre_section_mail"><img src="../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section_mail" /><div class="texte_titre_section_mail">Gestion des utilisateurs</div></div>';

            $modele .= '<div class="zone_contenu_mail">';
              // Demandes de changemement de mot de passe
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Demandes de mot de passe';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_requetes_mot_de_passe'];
                $modele .= '</div>';
              $modele .= '</div>';

              // Demandes d'inscription
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Demandes d\'inscription';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_requetes_inscription'];
                $modele .= '</div>';
              $modele .= '</div>';

              // Demandes de désinscription
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Demandes de désinscription';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_requetes_desinscription'];
                $modele .= '</div>';
              $modele .= '</div>';
            $modele .= '</div>';

            // Gestion du contenu
            $modele .= '<div class="titre_section_mail"><img src="../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section_mail" /><div class="texte_titre_section_mail">Gestion du contenu</div></div>';

            $modele .= '<div class="zone_contenu_mail">';
              // Suppressions de films
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Suppressions de films';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_demandes_suppressions_films'];
                $modele .= '</div>';
              $modele .= '</div>';

              // Suppressions de calendriers
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Suppressions de calendriers';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_demandes_suppressions_calendriers'];
                $modele .= '</div>';
              $modele .= '</div>';

              // Suppressions d'annexes
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Suppressions d\'annexes';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_demandes_suppressions_annexes'];
                $modele .= '</div>';
              $modele .= '</div>';
            $modele .= '</div>';

            // Maintenance du site
            $modele .= '<div class="titre_section_mail"><img src="../includes/icons/admin/settings_grey.png" alt="settings_grey" class="logo_titre_section_mail" /><div class="texte_titre_section_mail">Maintenance du site</div></div>';

            $modele .= '<div class="zone_contenu_mail">';
              // Bugs en cours
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Bugs en cours';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_bugs_en_cours'];
                $modele .= '</div>';
              $modele .= '</div>';

              // Evolutions en cours
              $modele .= '<div class="zone_nombre_demandes_mail">';
                // Titre
                $modele .= '<div class="titre_demandes_mail">';
                  $modele .= 'Evolutions en cours';
                $modele .= '</div>';

                // Valeur
                $modele .= '<div class="valeur_demandes_mail">';
                  $modele .= $demandes['nombre_evolutions_en_cours'];
                $modele .= '</div>';
              $modele .= '</div>';
            $modele .= '</div>';
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
