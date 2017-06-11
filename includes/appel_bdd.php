<?php
	// Alterner entre les lignes, la première fait appel à la BDD en local, la deuxième à celle en ligne
	try
	{
		// En local
		$bdd = new PDO('mysql:host=localhost; dbname=inside_cgi; charset=utf8',
					     'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
?>
