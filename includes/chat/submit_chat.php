<?php
  // Création des éléments
  $dom = new DOMDocument();
  $dom->load('content_chat.xml');
  $fragment = $dom->createDocumentFragment();

  // Récupération des données
  $nom      = $_POST['identifiant'];
  $message  = $_POST['message'];

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

  /* Ancienne version
  // Récupération des données
  $nom      = $_POST['nom'];
  $message  = $_POST['message'];

  // Formatage du message
  $ligne    = '<message>';
  $ligne   .= '<identifiant>' . $_POST['nom'] . '</identifiant>';
  $ligne   .= '<text>' . $_POST['message'] . '</text>';
  $ligne   .= '<date>' . date('d/m/Y') . '</date>';
  $ligne   .= '<time>' . date('H:m') . '</time>';
  $ligne   .= '</message>';

  // On lit le fichier content_chat.xml et on stocke la réponse dans une variable (de type tableau)
  $new_file = file('content_chat.xml');

  // On insère dans le message le texte puis un saut de ligne et on écrit le fichier
  array_push($new_file, $ligne);
  array_push($new_file, "\n");
  file_put_contents('content_chat.xml', $new_file);*/
?>
