<?php
	session_start();

	if (isset($_POST['prendre_en_charge']) OR isset($_POST['cloturer']) OR isset($_POST['remettre_en_cours']))
	{
		include('../../includes/appel_bdd.php');
		
		if (isset($_POST['prendre_en_charge']))
		{
			$status = "P";
			$developper = $_SESSION['identifiant'];
		}
		elseif(isset($_POST['cloturer']))
		{
			$status = "D";
			$developper = $_SESSION['identifiant'];
		}
		elseif(isset($_POST['remettre_en_cours']))
		{
			$status = "P";
			$developper = "";
		}
		
		$req = $bdd->prepare('UPDATE ideas SET status=:status, developper=:developper WHERE id=' . $_GET['id']);
		$req->execute(array(
			'status' => $status,
			'developper' => $developper
		));
		$req->closeCursor();
		
		header('location: ../ideas.php?view=' . $_GET['view']);
	}
	elseif (isset($_POST['new_idea']))
	{
		include('../../includes/appel_bdd.php');
		
		$_SESSION['idea_submited'] = false;
		
		$subject = htmlspecialchars($_POST['subject_idea']);
		$date = date("mdY");
		$author = $_SESSION['identifiant'];
		$content = htmlspecialchars($_POST['content_idea']);
		$status = "P";
		$developper = "";
		
		//Stockage de l'enregistrement en table
		$req = $bdd->prepare('INSERT INTO ideas(subject, date, author, content, status, developper) VALUES(:subject, :date, :author, :content, :status, :developper)');
		$req->execute(array(
			'subject' => $subject,
			'date' => $date,
			'author' => $author,
			'content' => $content,
			'status' => $status,
			'developper' => $developper
				));
		$req->closeCursor();
		
		$_SESSION['idea_submited'] = true;
		
		header ('location: ../ideas.php?view=' . $_GET['view']);		
	}
	else
		header ('location: ../ideas.php?view=inprogress');
?>