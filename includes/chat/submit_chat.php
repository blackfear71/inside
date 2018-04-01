<?php
  // Contrôle existence fichier conversations
  if (!file_exists('content_chat.xml'))
  {
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
  $nom      = $_POST['identifiant'];
  $message  = $_POST['message'];

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
?>