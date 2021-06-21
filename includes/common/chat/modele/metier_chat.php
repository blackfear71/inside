<?php
  // METIER : Lecture liste des utilisateurs pour le chat
  // RETOUR : Liste des utilisateurs
  function getUsersChat($equipe)
  {
    // Récupération de la liste des utilisateurs
    $listeUsersChat = physiqueUsersChat($equipe);

    // Retour
    return $listeUsersChat;
  }

  // METIER : Inseère un message dans le fichier XML
  // RETOUR : Aucun
  function submitChat($post)
  {
    // Récupération des données
    $identifiant = $post['identifiant'];
    $equipe      = $post['equipe'];
    $message     = $post['message'];

    // On vérifie la présence du dossier, sinon on le créé de manière récursive
    $folder = 'conversations';

    if (!is_dir($folder))
      mkdir($folder, 0777, true);

    // Contrôle existence fichier chat
    if (!file_exists($folder . '/content_chat_' . $equipe . '.xml'))
    {
      // Création du fichier s'il n'existe pas
      $file    = fopen($folder . '/content_chat_' . $equipe . '.xml', 'a+');
      $balises =
'<?xml version="1.0" encoding="UTF-8"?>
<INSIDERoom>
</INSIDERoom>';

      fputs($file, $balises);
      fclose($file);
    }

    // Ajout du message au fichier XML
    if (!empty($identifiant) AND !empty($message))
    {
      // Création des éléments
      $dom = new DOMDocument();
      $dom->load($folder . '/content_chat_' . $equipe . '.xml');
      $fragment = $dom->createDocumentFragment();

      // Formatage du message
      $ligne =
'  <message>
    <identifiant>' . $identifiant . '</identifiant>
    <text>' . $message . '</text>
    <date>' . date('Ymd') . '</date>
    <time>' . date('His') . '</time>
  </message>
';

      // Insersion dans le noeud puis le fichier
      $fragment->appendXML($ligne);
      $dom->documentElement->appendChild($fragment);
      $dom->save($folder . '/content_chat_' . $equipe . '.xml');
    }
  }
?>
