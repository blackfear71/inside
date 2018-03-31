<script type="text/javascript" src="/inside/includes/chat/scriptChat.js"></script>

<?php
  // Zone de chat à intégrer
  echo '<div class="zone_chat">';
    // Titre et repli
    echo '<div class="zone_titre_chat">';
      echo '<div class="titre_chat">INSIDE Room [Bêta]</div>';
      echo '<a id="hide_chat" class="reduire_chat">-</a>';
    echo '</div>';

    // Messages
    echo '<div id="scroll_conversation" class="zone_conversation">';
      echo '<div id="conversation" class="contenu_chat"></div>';
    echo '</div>';

    // Saisie
    echo '<form action="#" method="post" id="form_chat" class="form_saisie_chat">';
      echo '<input type="hidden" id="identifiant_chat" value="' . $_SESSION['user']['identifiant'] . '" />';
      echo '<input type="text" id="message_chat" name="message_chat" placeholder="Saisir un message..." autocomplete="off" class="saisie_chat" />';
      echo '<button type="button" id="send_message_chat" title="Envoyer" class="bouton_chat"></button>';
    echo '</form>';
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
