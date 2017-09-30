<?php
      $destinataire = "pierre.hamonic71@gmail.com";
      $sujet = "test mail";
			$jour  = date("d-m-Y");
			$heure = date("H:i");

			//Exception pour retour à la ligne (interprétations différentes en fonction des navigateurs)
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire))
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}

			// Contenu du message de l'email avec sa mise en forme en fonction de si on est connecté ou pas
			// e-mail personnalisable ici
			$message = 'test';

			// Pour envoyer un email HTML, l'en-tête Content-type doit être défini
			$headers= 'MIME-Version: 1.0' . $passage_ligne;
			$headers.= 'Content-type: text/html; charset=utf-8' . $passage_ligne;
			$headers.= 'Content-Transfer-Encoding: 8bit';

			// Fonction principale qui envoi l'email avec vérification
			imap_mail($destinataire, $sujet, $message, $headers);
?>
