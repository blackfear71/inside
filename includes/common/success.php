<?php
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/success.php');

  // Initialisation des succès à afficher
  $unlocked = array();

  if (isset($_SESSION['success'])AND !empty($_SESSION['success']))
  {
    // Récupération des succès
    foreach ($_SESSION['success'] as $reference => $success)
    {
      // Nettoyage session
      if (empty($success))
        unset($_SESSION['success'][$reference]);
      // Lecture des succès
      else
      {
        // On ajoute la ligne au tableau (si le succès est défini)
        $reponse = $bdd->query('SELECT * FROM success WHERE reference = "' . $reference . '"');
        $donnees = $reponse->fetch();

        $ligneUnlocked = Success::withData($donnees);

        if ($ligneUnlocked->getDefined() == 'Y')
          array_push($unlocked, $ligneUnlocked);

        $reponse->closeCursor();

        // Nettoyage session
        unset($_SESSION['success'][$reference]);
      }
    }
  }

  // Affichage des succès débloqués
  if (!empty($unlocked))
  {
    echo '<div id="zoom_succes" class="fond_zoom_succes">';
      echo '<div class="zone_success_zoom">';
        // Titre
        if (count($unlocked) > 1)
          echo '<div class="titre_zone_succes_zoom">Succès débloqués !</div>';
        else
          echo '<div class="titre_zone_succes_zoom">Succès débloqué !</div>';

        // Affichage des succès
        foreach ($unlocked as $ligneUnlocked)
        {
          // Succès
          echo '<div class="zone_succes_zoom">';
            // Titre du succès
            echo '<div class="titre_succes_zoom">' . $ligneUnlocked->getTitle() . '</div>';

            // Logo du succès
            echo '<img src="/inside/includes/images/profil/success/' . $ligneUnlocked->getReference() . '.png" alt="' . $ligneUnlocked->getReference() . '" class="logo_succes_zoom" />';

            // Description du succès
            echo '<div class="description_succes_zoom">' . $ligneUnlocked->getDescription() . '</div>';

            // Explications du succès
            echo '<div class="explications_succes_zoom">' . formatExplanation($ligneUnlocked->getExplanation(), $ligneUnlocked->getLimit_success(), '%limit%') . '</div>';
          echo '</div>';
        }

        // Boutons
        echo '<div class="zone_boutons_succes_zoom">';
          // Bouton fermeture
          echo '<a id="closeZoomSuccess" class="bouton_succes_zoom">Trop bien !</a>';

          // Bouton redirection
          echo '<a href="/inside/portail/profil/profil.php?view=success&action=goConsulter" class="bouton_succes_zoom">Voir mes succès</a>';
        echo '</div>';
      echo '</div>';
    echo '</div>';

    // Nettoyage des succès
    $unlocked = array();
  }
?>
