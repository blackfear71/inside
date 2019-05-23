<?php
  ////////////////////////////////////////////////////////
  // Fonction d'édition de modèle mail pour sortie film //
  ////////////////////////////////////////////////////////
  function getModeleFilm($details, $participants)
  {
    $modele = '';

    $modele = '<html lang="fr">';
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
          border-radius: 5px;
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
        	margin-left: 30px;
        	margin-bottom: 10px;
        	margin-top: 10px;
          word-break: break-word;
        }

        .avatar_dest_mail
        {
        	border-radius: 50%;
        	width: 40px;
          box-shadow: 0 0 3px #7c7c7c;
        	background-color: #262626;
        	vertical-align: middle;
        	margin-right: 10px;
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
          width: 38%;
          height: 100%;
          padding-right: 2%;
          padding-bottom: 1.5%;
        }

        .poster_mail
        {
          max-width: 100%;
          border-radius: 5px;
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
                  //$modele .= '<p class="participants_mail">- ' . $participant->getPseudo() . '</p>';
                  $modele .= '<p class="destinataires_mail">';
                    if (!empty($participant->getAvatar()))
                      $modele .= '<img src="../../includes/images/profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest_mail" />';
                    else
                      $modele .= '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest_mail" />';

                    $modele .= $participant->getPseudo();
                  $modele .= '</p>';
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

    return $modele;
  }
?>
