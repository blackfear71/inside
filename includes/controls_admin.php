<?php
  // Lancement de la session
  if (empty(session_id()))
	 session_start();

  // Contrôle utilisateur normal
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: /inside/portail/portail.php');

  // Contrôle utilisateur connecté
	if ($_SESSION['connected'] == false)
		header('location: /inside/index.php');
?>
