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

  // METIER : Inseère un message dans le fichier XML
  // RETOUR : Aucun
  function submitChat($post)
  {
    // Contrôle existence fichier chat
    if (!file_exists('content_chat.xml'))
    {
      // Création du fichier s'il n'existe pas
      $file    = fopen('content_chat.xml', 'a+');
      $balises =
'<?xml version="1.0" encoding="UTF-8"?>
<INSIDERoom>
</INSIDERoom>
';
      fputs($file, $balises);
      fclose($file);
    }

    // Récupération des données
    $nom     = $post['identifiant'];
    $message = $post['message'];

    // Ajout du message au fichier XML
    if (!empty($nom) AND !empty($message))
    {
      // Création des éléments
      $dom = new DOMDocument();
      $dom->load('content_chat.xml');
      $fragment = $dom->createDocumentFragment();

      // Formatage du message
      $ligne =
'  <message>
    <identifiant>' . $nom . '</identifiant>
    <text>' . $message . '</text>
    <date>' . date('Ymd') . '</date>
    <time>' . date('His') . '</time>
  </message>
';

      // Insersion dans le noeud puis le fichier
      $fragment->appendXML($ligne);
      $dom->documentElement->appendChild($fragment);
      $dom->save('content_chat.xml');
    }
  }
?>
