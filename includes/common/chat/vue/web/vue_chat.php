<?php
  // Zone de chat à intégrer
  echo '<div id="zone_chat_position" class="zone_chat" style="display: none;">';
    // Titre et repli
    echo '<div class="zone_titre_chat">';
      echo '<a id="onglet_chat" class="titre_onglet">';
        echo '<img src="/inside/includes/icons/common/comments.png" alt="comments" title="Inside Room" class="logo_inside_room" />';
        echo 'INSIDE Room';
      echo '</a>';

      echo '<a id="onglet_users" class="titre_onglet">Connectés</a>';

      echo '<a id="zone_hide_chat" class="lien_reduire_chat">';
        echo '<div class="triangle_reduire_chat"></div>';
        echo '<div id="hide_chat" class="reduire_chat"></div>';
      echo '</a>';
    echo '</div>';

    // Fenêtres paramétrées (JS)
    echo '<div id="fenetres_chat"></div>';
  echo '</div>';
?>

<!-- Données JSON -->
<script>
  // Récupération liste utilisateurs, identifiant & initialisation chat pour le script
  var listeUsersChat = <?php echo $listeUsersChatJson; ?>;
  var currentUser    = <?php echo $currentUserJson; ?>;
  var teamUser       = <?php echo $teamUserJson; ?>;
  var initChat       = <?php echo $initChat; ?>;
</script>