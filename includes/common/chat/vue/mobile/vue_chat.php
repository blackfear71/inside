<?php
    // Bouton d'affichage
    echo '<a id="zone_bouton_chat" title="INSIDE Room" class="lien_affichage_chat">';
        echo '<img src="/includes/icons/common/comments.png" alt="comments" class="logo_inside_room" />';
    echo '</a>';

    // Chat
    echo '<div id="zoom_chat" class="fond_chat" style="display: none;">';
        echo '<div class="zone_chat">';
            // Onglets
            echo '<div class="zone_onglets_chat">';
                echo '<a id="onglet_chat" class="titre_onglet">INSIDE Room</a>';

                echo '<a id="onglet_users" class="titre_onglet">Connectés</a>';

                echo '<a id="zone_fermer_chat" class="lien_fermer_chat">';
                    echo '<img src="/includes/icons/common/close.png" alt="close" class="fermer_chat" />';
                echo '</a>';
            echo '</div>';

            // Contenu
            echo '<div id="fenetres_chat"></div>';
        echo '</div>';
    echo '</div>';
?>

<!-- Données JSON -->
<script>
    // Récupération liste utilisateurs, identifiant & équipe pour le script
    var listeUsersChat = <?php echo $listeUsersChatJson; ?>;
    var currentUser    = <?php echo $currentUserJson; ?>;
    var teamUser       = <?php echo $teamUserJson; ?>;
</script>