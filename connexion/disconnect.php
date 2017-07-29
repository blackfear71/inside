<?php
	// Démarrer la session
	session_start();

	// Détruire les variables de la session
	session_unset();

	// Détruit toute la session (pas bon dans ce cas sinon la page index ne trouve plus les variables dont elle a besoin et affiche très rapidement un message d'erreur)
	//session_destroy();

	// Après avoir détruit la variable, on la réinitialise pour éviter les erreurs au retour sur index.html
	$_SESSION['connected'] = false;

	// Retour sur index.php
	header('location: /inside/index.php');

	exit;
?>
