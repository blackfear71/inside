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

  // Recherche des utilisateurs si pas déjà faite
  if (!isset($_SESSION['chat']['users']) OR empty($_SESSION['chat']['users']))
    $_SESSION['chat']['users'] = getUsersChat();

  $_SESSION['chat']['current'] = $_SESSION['user']['identifiant'];

  // On transforme les objets en tableau pour envoyer au Javascript
  $listUsers = array();

  foreach ($_SESSION['chat']['users'] as $user)
  {
    $user_chat = array('identifiant' => htmlspecialchars($user->getIdentifiant()),
                       'pseudo'      => htmlspecialchars($user->getPseudo()),
                       'avatar'      => htmlspecialchars($user->getAvatar())
                      );
    array_push($listUsers, $user_chat);
  }

  // On formate les données au format JSON
  $listUsersJson   = json_encode($listUsers);
  $currentUserJson = json_encode($_SESSION['chat']['current']);
  $initChat        = json_encode($_SESSION['chat']['show_chat']);

  // Suppression session préférence chat après connexion
  $_SESSION['chat']['show_chat'] = NULL;
?>

<script>
  // Récupération liste utilisateurs, identifiant & initialisation chat pour le script
  var listUsers   = <?php echo $listUsersJson; ?>;
  var currentUser = <?php echo $currentUserJson; ?>;
  var initChat    = <?php echo $initChat; ?>;
</script>
