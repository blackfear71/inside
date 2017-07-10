<?php
	session_start();

	include('../../includes/appel_bdd.php');

	$_SESSION['bug_submitted'] = false;

	if (isset($_POST['report']))
	{
		// On récupère le sujet, le contenu et la date
		$sujet = htmlspecialchars($_POST['subject']);
		$contenu = htmlspecialchars($_POST['contenu_bug']);
		$date = date("mdY");
		$auteur = $_SESSION['identifiant'];
		$type = $_POST['type_bug'];
		$resolved = "N";

		//Stockage de l'enregistrement en table
		$req = $bdd->prepare('INSERT INTO bugs(subject, date, author, content, type, resolved) VALUES(:subject, :date, :author, :content, :type, :resolved)');
		$req->execute(array(
			'subject' => $sujet,
			'date' => $date,
			'author' => $auteur,
			'content' => $contenu,
			'type' => $type,
			'resolved' => $resolved
				));
		$req->closeCursor();

		$_SESSION['bug_submitted'] = true;

		header('location: ../bug.php');
	}
?>
