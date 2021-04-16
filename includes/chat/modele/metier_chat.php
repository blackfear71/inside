<?php
  // METIER : Lecture liste des utilisateurs pour le chat
  // RETOUR : Liste des utilisateurs
  function getUsersChat()
  {
    // Récupération de la liste des utilisateurs
    $listeUsersChat = physiqueUsersChat();

    // Retour
    return $listeUsersChat;
  }
?>
