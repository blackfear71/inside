<?php
  switch ($modele)
  {
    case "film":
    $message = '<html>';
      $message .= '<head>';
        // CSS
        $message .= '<style type="text/css">';
        $message .= '
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
          	line-height: 110px;
          	font-family: robotothin, Verdana, sans-serif;
          	font-size: 50px;
          	color: white;
          	background-color: rgb(255, 25, 55);
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
            background: linear-gradient(to right bottom, rgb(255, 25, 55) 50%, transparent 50%);
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
          	font-family: Calibri, Verdana, sans-serif;
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

          .trait_mail_1
          {
          	float: left;
          	height: 1px;
          	width: 30%;
          	margin-top: 25px;
          	margin-left: 120px;
          	background-color: rgb(255, 25, 55);
          }

          .trait_mail_2
          {
          	float: right;
          	height: 1px;
          	width: 30%;
          	margin-top: 25px;
          	margin-right: 120px;
          	background-color: rgb(255, 25, 55);
          }
          ';
          $message .= '</style>';
        $message .= '</head>';

        // HTML + données
        $message .= '<body>';
          $message .= '<div class="zone_mail">';
            $message .= '<div class="entete_mail">';
              $message .= '<img src="../../includes/icons/inside.png" alt="inside" class="logo_inside_mail" />';
            $message .= '</div>';

            $message .= '<div class="mask_mail">';
              $message .= '<div class="triangle_mail"></div>';
            $message .= '</div>';

            $message .= '<div class="corps_mail">';
              $message .= '<div class="corps_mail_left">';
                $message .= '<div class="text_mail">';
                  $message .= 'Bonjour,';
                  $message .= '<br /><br /><br /><br />';
                  $message .= 'Vous avez stipulé être intéréssé(e) par le film : <strong>' . $details->getFilm() . '</strong>';
                  $message .= '<br /><br />';
                  $message .= 'Vous recevez ce mail contenant le lien doodle à renseigner pour donner votre disponibilité : <a href="' . $details->getDoodle() . '" target="_blank">Doodle</a>';
                  $message .= '<br /><br />';
                  $message .= 'Les personnes intéressées sont :<br />';
                  foreach ($participants as $participant)
                  {
                    $message .= '<p class="participants_mail">- ' . $participant->getPseudo() . '</p>';
                  }
                  $message .= '<br /><br /><br />';
                  $message .= 'Cordialement,';
                  $message .= '<br /><br /><br /><br />';
                  $message .= 'Mail envoyé depuis INSIDE.';
                $message .= '</div>';
              $message .= '</div>';

              $message .= '<div class="corps_mail_right">';
                if (!empty($details->getPoster()))
                  $message .= '<img src="' . $details->getPoster() . '" alt="poster" title="' . $details->getFilm() . '" class="poster_mail"/>';
                else
                  $message .= '<img src="images/cinema.jpg" alt="poster" title="' . $details->getFilm() . '" class="poster_mail"/>';
              $message .= '</div>';
            $message .= '</div>';

            $message .= '<div class="footer_mail">';
              $message .= '<div class="trait_mail_1"></div>';
              $message .= '<img src="../../includes/icons/inside_mini.png" alt="inside" class="logo_inside_mini_mail" />';
              $message .= '<div class="trait_mail_2"></div>';
            $message .= '</div>';
          $message .= '</div>';
        $message .= '</body>';
      $message .= '</html>';
      break;

    default:
      break;
  }
?>
