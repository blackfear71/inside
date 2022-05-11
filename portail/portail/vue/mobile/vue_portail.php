<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Portail';
      $styleHead       = 'stylePortail.css';
      $scriptHead      = 'scriptPortail.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/mobile/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'portail';
        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /********/
          /* News */
          /********/
          // Titre
          echo '<div id="titre_news_portail" class="titre_section">';
            echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">INSIDE News<div class="count_news">' . count($news) . '</div></div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          // Boutons news
          echo '<div id="afficher_news_portail" style="display: none;">';
            echo '<div class="zone_boutons_news">';
              $categorieNewsPrecedente = '';

              foreach ($news as $messageNews)
              {
                if ($messageNews->getLogo() != $categorieNewsPrecedente)
                {
                  echo '<a id="bouton_' . $messageNews->getLogo() . '" class="zone_logo_news">';
                    echo '<img src="../../includes/icons/common/' . $messageNews->getLogo() . '.png" alt="' . $messageNews->getLogo() . '" class="logo_news" />';
                  echo '</a>';

                  $categorieNewsPrecedente = $messageNews->getLogo();
                }
              }
            echo '</div>';

            // News
            echo '<div id="zone_affichage_news" style="display: none;">';
              foreach ($news as $messageNews)
              {
                if (empty($messageNews->getLink()))
                  echo '<div class="zone_news news_' . $messageNews->getLogo() . '" style="display: none;">';
                else
                  echo '<a href="' . $messageNews->getLink() . '" class="zone_news news_' . $messageNews->getLogo() . '" style="display: none;">';

                echo '<img src="../../includes/icons/common/' . $messageNews->getLogo() . '.png" alt="' . $messageNews->getLogo() . '" class="logo_news" />';

                echo '<div class="zone_contenu_news">';
                  echo '<div class="titre_news">' . $messageNews->getTitle() . '</div>';
                  echo '<div class="contenu_news">' . $messageNews->getContent() . '</div>';
                echo '</div>';

                if (!empty($messageNews->getDetails()))
                {
                  echo '<div class="zone_details_news">';
                    echo $messageNews->getDetails();
                  echo '</div>';
                }

                if (empty($messageNews->getLink()))
                  echo '</div>';
                else
                  echo '</a>';
              }
            echo '</div>';
          echo '</div>';

          /***********/
          /* Portail */
          /***********/
          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Portail</div>';
          echo '</div>';

          // Portail
          $i          = 0;
          $keyPortail = array_keys($portail);
          $lastKey    = end($keyPortail);

          echo '<div class="zone_portail">';
            foreach ($portail as $lienPortail)
            {
              if ($lienPortail['mobile'] == 'Y')
              {
                if ($i % 2 == 0)
                {
                  if ($i == $lastKey)
                    echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail ">';
                  else
                    echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail margin_right_0_5vh">';
                }
                else
                  echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail margin_left_0_5vh">';
                  // Logo
                  echo '<img src="' . $lienPortail['image'] . '" alt="' . $lienPortail['alt'] . '" class="icone_lien_portail" />';

                  // Texte
                  echo '<div class="zone_texte_lien_portail">';
                    echo '<div class="texte_lien_portail">' . $lienPortail['categorie'] . '</div>';
                  echo '</div>';
                echo '</a>';

                $i++;
              }
            }
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/mobile/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
