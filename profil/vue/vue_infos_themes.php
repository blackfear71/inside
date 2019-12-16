<?php
  echo '<div class="titre_section"><img src="../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Pour profiter de vos thèmes</div></div>';

  echo '<div class="texte_themes">';
    echo 'Vous pouvez modifier ici votre thème qui sera <strong>appliqué à l\'ensemble du site.</strong>';
  echo '</div>';

  echo '<div class="texte_themes">';
    echo 'Vous avez le choix de sélectionner soit un thème débloqué par votre niveau <span class="number_exp">' . convertExperience($profil->getExperience()) . '</span> en accumulant de l\'expérience, soit un thème utilisé lors d\'une mission passée.';
  echo '</div>';

  echo '<div class="texte_themes italique">';
    echo 'Le meilleur moyen d\'accumuler de l\'expérience reste de faire vivre le site pour tous !';
  echo '</div>';

  echo '<div class="texte_themes">';
    echo 'Par défaut, si un thème a été défini sur une période donnée par l\'administrateur, <strong>celui-ci prévaudra</strong> sur votre préférence.';
  echo '</div>';

  echo '<div class="texte_themes">';
    echo 'Vous pouvez également désactiver le thème courant (<strong>hors mission en cours</strong>) en cliquant sur ce bouton :';

    echo '<form method="post" action="profil.php?action=doSupprimerTheme">';
      echo '<input type="submit" name="delete_theme" value="Désactiver le thème" class="bouton_validation margin_top" />';
    echo '</form>';
  echo '</div>';
?>
