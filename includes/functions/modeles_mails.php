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
?>
