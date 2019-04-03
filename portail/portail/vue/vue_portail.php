<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Portail";
      $style_head   = "stylePortail.css";
      $script_head  = "scriptPortail.js";
      $chat_head    = true;
      $masonry_head = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Portail";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
					$disconnect  = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<article>
				<?php
          echo '<div class="zone_portail_left">';
            echo '<div class="titre_section"><img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_titre_section" />INSIDE News</div>';

            foreach ($news as $messageNews)
            {
              echo '<a href="' . $messageNews->getLink() . '" class="zone_news">';
                echo '<img src="/inside/includes/icons/common/' . $messageNews->getLogo() . '.png" alt="' . $messageNews->getLogo() . '" class="logo_news" />';

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
              echo '</a>';
            }
          echo '</div>';

          echo '<div class="zone_portail_right">';
            echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />Portail</div>';

            echo '<div class="menu_portail">';
              // Liens des catégories
              foreach ($portail as $lienPortail)
              {
                echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail">';
                  echo '<div class="text_portail">' . $lienPortail['categorie'] . '</div>';
                  echo '<div class="fond_lien_portail">';
                    echo '<img src="' . $lienPortail['image'] . '" alt="' . $lienPortail['alt'] . '" class="img_lien_portail" />';
                  echo '</div>';
                echo '</a>';
              }
            echo '</div>';
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
