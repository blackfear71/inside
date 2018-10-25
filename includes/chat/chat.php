<?php
  // Zone de chat à intégrer
  echo '<div id="zone_chat_position" class="zone_chat">';
    // Titre et repli
    echo '<div class="zone_titre_chat">';
      echo '<a id="onglet_chat" class="titre_onglet">';
        echo '<img src="/inside/includes/icons/comments.png" alt="comments" title="Inside Room" class="logo_inside_room" />';
        echo 'INSIDE Room';
      echo '</a>';
      echo '<a id="onglet_users" class="titre_onglet">Connectés</a>';
      echo '<a id="hide_chat" class="reduire_chat"></a>';
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
    $user_chat = array('identifiant' => $user->getIdentifiant(),
                       'pseudo'      => $user->getPseudo(),
                       'avatar'      => $user->getAvatar()
                      );
    array_push($listUsers, $user_chat);
  }

  // On formate le tableau au format JSON
  $listUsersJson   = json_encode($listUsers);
  $currentUserJson = json_encode($_SESSION['chat']['current']);
?>

<script type="text/javascript">
  // Récupération liste utilisateurs & identifiant pour le script
  var listUsers   = <?php echo $listUsersJson; ?>;
  var currentUser = <?php echo $currentUserJson; ?>;
</script>
