<?php
  /**************/
  /* Pagination */
  /**************/
  if ($nbPages > 1)
  {
    $prev_points = false;
    $next_points = false;
    $limit_inf   = $_GET['page'] - 1;
    $limit_sup   = $_GET['page'] + 1;

    echo '<div class="zone_pagination">';
      for ($i = 1; $i <= $nbPages; $i++)
      {
        if ($i == 1 OR $i == $nbPages)
        {
          if ($i == $_GET['page'])
            echo '<div class="numero_page_active">' . $i . '</div>';
          else
          {
            echo '<div class="numero_page_inactive">';
              echo '<a href="ideas.php?view=' . $_GET['view'] . '&action=goConsulter&page=' . $i . '" class="lien_pagination">' . $i . '</a>';
            echo '</div>';
          }
        }
        else
        {
          if ($i < $limit_inf AND $i > 1 AND $prev_points != true)
          {
            echo '<div class="points">...</div>';
            $prev_points = true;
          }

          if ($i >= $limit_inf AND $i <= $limit_sup)
          {
            if ($i == $_GET['page'])
              echo '<div class="numero_page_active">' . $i . '</div>';
            else
            {
              echo '<div class="numero_page_inactive">';
                echo '<a href="ideas.php?view=' . $_GET['view'] . '&action=goConsulter&page=' . $i . '" class="lien_pagination">' . $i . '</a>';
              echo '</div>';
            }
          }

          if ($i > $limit_sup AND $i < $nbPages AND $next_points != true)
          {
            echo '<div class="points">...</div>';
            $next_points = true;
          }
        }
      }
    echo '</div>';
  }
?>
