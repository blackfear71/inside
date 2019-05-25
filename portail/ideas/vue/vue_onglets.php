<?php
  echo '<div class="zone_vues">';
    echo '<div class="titre_section"><img src="../../includes/icons/ideas/view_grey.png" alt="view_grey" class="logo_titre_section" />Vues</div>';

    $listeVues = array('all'        => 'Toutes',
                       'inprogress' => 'En cours',
                       'mine'       => 'En charge',
                       'done'       => 'Terminées'
                      );

    foreach ($listeVues as $view => $vue)
    {
      if ($_GET['view'] == $view)
        echo '<span class="view active">' . $vue . '</span>';
      else
        echo '<a href="ideas.php?view=' . $view . '&action=goConsulter&page=1" class="view inactive">' . $vue . '</a>';
    }
  echo '</div>';
?>
