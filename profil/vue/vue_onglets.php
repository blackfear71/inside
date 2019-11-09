<?php
  // Onglets
  echo '<div class="titre_section"><img src="../includes/icons/profil/user_grey.png" alt="user_grey" class="logo_titre_section" /><div class="texte_titre_section">Mon profil</div></div>';

  $i         = 0;
  $listeVues = array('profile'  => 'Profil',
                     'settings' => 'Paramètres',
                     'success'  => 'Succès',
                     'ranking'  => 'Classement',
                     'themes'   => 'Thèmes'
                    );

  foreach ($listeVues as $view => $vue)
  {
    if ($i % 2 == 0)
    {
      if ($_GET['view'] == $view)
        echo '<span class="view active margin_right">' . $vue . '</span>';
      else
        echo '<a href="profil.php?view=' . $view . '&action=goConsulter" class="view inactive margin_right">' . $vue . '</a>';
    }
    else
    {
      if ($_GET['view'] == $view)
        echo '<span class="view active">' . $vue . '</span>';
      else
        echo '<a href="profil.php?view=' . $view . '&action=goConsulter" class="view inactive">' . $vue . '</a>';
    }

    $i++;
  }
?>
