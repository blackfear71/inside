<?php
  /**************/
  /* Pagination */
  /**************/
  if ($nbPages > 1)
  {
    $previousPoints = false;
    $nextPoints     = false;
    $limitInf       = $_GET['page'] - 1;
    $limitSup       = $_GET['page'] + 1;

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
          if ($i < $limitInf AND $i > 1 AND $previousPoints != true)
          {
            echo '<div class="points">...</div>';
            $previousPoints = true;
          }

          if ($i >= $limitInf AND $i <= $limitSup)
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

          if ($i > $limitSup AND $i < $nbPages AND $nextPoints != true)
          {
            echo '<div class="points">...</div>';
            $nextPoints = true;
          }
        }
      }
    echo '</div>';
  }
?>
