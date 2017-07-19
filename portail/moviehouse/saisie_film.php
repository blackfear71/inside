<?php
	session_start();

	include ('../../includes/appel_bdd.php');
	include ('../../includes/fonctions_regex.php');
	include ('../../includes/fonctions_dates.php');

	// Saisie rapide à partir du tableau des films
	if (isset($_POST['saisie_rapide']))
	{
		// Sauvegarde en session en cas d'erreur
		$_SESSION['nom_film_saisi'] = $_POST['nom_film'];
		$_SESSION['date_theater_saisie'] = $_POST['date_theater'];

		// Récupération des variables
		$nom_film = $_POST['nom_film'];
		$to_delete = "N";
		$date_theater = "";
		$date_release = "";
		$link = "";
		$poster = "";
		$trailer = "";
		$id_url = "";
		$doodle = "";
		$date_doodle = "";

		$date_a_verifier = $_POST['date_theater'];

		//SMI - déb
		//list($d, $m, $y) = explode('/', $date_a_verifier);

		// On vérifie le format de la date
		// if (checkdate($m, $d, $y))
		// {
		if (empty($date_a_verifier))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_theater = '1230' . date('Y');
			}
			else
			{
				$date_theater = '1231' . date('Y');
			}
		}
		else
		{
			// $date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
			$date_theater = substr($date_a_verifier, 3, 2) . substr($date_a_verifier, 0, 2) . substr($date_a_verifier, 6, 4);
		}
			//SMI - fin

			// Stockage de l'enregistrement en table
			$req = $bdd->prepare('INSERT INTO movie_house(film, to_delete, date_theater, date_release, link, poster, trailer, id_url, doodle, date_doodle) VALUES(:film, :to_delete, :date_theater, :date_release, :link, :poster, :trailer, :id_url, :doodle, :date_doodle)');
			$req->execute(array(
				'film' => $nom_film,
				'to_delete' => $to_delete,
				'date_theater' => $date_theater,
				'date_release' => $date_release,
				'link' => $link,
				'poster' => $poster,
				'trailer' => $trailer,
				'id_url' => $id_url,
				'doodle' => $doodle,
				'date_doodle' => $date_doodle
				));
			$req->closeCursor();

			$_SESSION['wrong_date'] = false;
			header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . substr($_POST['date_theater'], 6, 4));
		//SMI - déb
		// }
		// else
		// {
		// 	$_SESSION['wrong_date'] = true;
		// 	header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . date("Y"));
		// }
		//SMI - fin
	}
	// Saisie avancée à partir de l'écran dédié
	elseif (isset($_POST['saisie_avancee']))
	{
		// Sauvegarde en session en cas d'erreur
		$_SESSION['nom_film_saisi'] = $_POST['nom_film'];
		$_SESSION['date_theater_saisie'] = $_POST['date_theater'];
		$_SESSION['date_release_saisie'] = $_POST['date_release'];
		$_SESSION['trailer_saisi'] = $_POST['trailer'];
		$_SESSION['link_saisi'] = $_POST['link'];
		$_SESSION['poster_saisi'] = $_POST['poster'];
		$_SESSION['doodle_saisi'] = $_POST['doodle'];
		$_SESSION['date_doodle_saisie'] = $_POST['date_doodle'];

		// Récupération des variables
		$nom_film = $_POST['nom_film'];
		$to_delete = "N";
		$date_theater = "";
		$date_release = "";
		$link = $_POST['link'];
		$poster = $_POST['poster'];
		$trailer = $_POST['trailer'];
		$id_url = "";
		$doodle = $_POST['doodle'];
		$date_doodle = "";

		/*
		// Lien Youtube trailer
		$search = "watch?v=";
		$replace = "embed/";
		$trailer = str_replace($search, $replace, $_POST['trailer']);
		*/

		// Récupération ID vidéo
		$id_url = extract_url($trailer);

		// Récupération date sortie cinéma
		$date_a_verifier_1 = $_POST['date_theater'];

		//SMI - déb
		// list($d, $m, $y) = explode('/', $date_a_verifier_1);

		// On vérifie le format de la date 1 (date sortie cinéma)
		// if (checkdate($m, $d, $y))
		// {
			// $date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
			if (empty($date_a_verifier_1))
			{
				if (isLastDayOfYearWednesday(date('Y')))
				{
					$date_theater = '1230' . date('Y');
				}
				else
				{
					$date_theater = '1231' . date('Y');
				}
			}
			else
			{
				// $date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
				$date_theater = substr($date_a_verifier_1, 3, 2) . substr($date_a_verifier_1, 0, 2) . substr($date_a_verifier_1, 6, 4);
			}
			// $date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
			// SMI - fin
			$_SESSION['wrong_date'] = false;

			if (isset($_POST['date_release']) AND !empty($_POST['date_release']))
			{
				$date_a_verifier_2 = $_POST['date_release'];

				list($d, $m, $y) = explode('/', $date_a_verifier_2);

				// On vérifie le format de la date 2 (date sortie dvd/bluray)
				if (checkdate($m, $d, $y))
				{
					$date_release = substr($_POST['date_release'], 3, 2) . substr($_POST['date_release'], 0, 2) . substr($_POST['date_release'], 6, 4);
					$_SESSION['wrong_date'] = false;
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_film_plus.php');
				}
			}

			if (isset($_POST['date_doodle']) AND !empty($_POST['date_doodle']))
			{
				$date_a_verifier_3 = $_POST['date_doodle'];

				list($d, $m, $y) = explode('/', $date_a_verifier_3);

				// On vérifie le format de la date 3 (date proposée)
				if (checkdate($m, $d, $y))
				{
					$date_doodle = substr($_POST['date_doodle'], 3, 2) . substr($_POST['date_doodle'], 0, 2) . substr($_POST['date_doodle'], 6, 4);
					$_SESSION['wrong_date'] = false;
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_film_plus.php');
				}
			}

			if ($_SESSION['wrong_date'] == false)
			{
				// Stockage de l'enregistrement en table
				$req = $bdd->prepare('INSERT INTO movie_house(film, to_delete, date_theater, date_release, link, poster, trailer, id_url, doodle, date_doodle) VALUES(:film, :to_delete, :date_theater, :date_release, :link, :poster, :trailer, :id_url, :doodle, :date_doodle)');
				$req->execute(array(
					'film' => $nom_film,
					'to_delete' => $to_delete,
					'date_theater' => $date_theater,
					'date_release' => $date_release,
					'link' => $link,
					'poster' => $poster,
					'trailer' => $trailer,
					'id_url' => $id_url,
					'doodle' => $doodle,
					'date_doodle' => $date_doodle
					));
				$req->closeCursor();

				switch ($_SESSION['view_movie_house'])
				{
					case "D":
						header('location: ../moviehouse.php?view=user&year=' . substr($_POST['date_theater'], 6, 4));
						break;

					case "S":
					default:
						header('location: ../moviehouse.php?view=main&year=' . substr($_POST['date_theater'], 6, 4));
						break;
				}
			}
			//SMI - déb
		// }
		// else
		// {
		// 	$_SESSION['wrong_date'] = true;
		// 	header('location: saisie_film_plus.php');
		// }
		//SMI - fin
	}
	// Modification à partir de l'écran de saisie avancée
	elseif (isset($_POST['modification_avancee']))
	{
		// Sauvegarde en session en cas d'erreur
		$_SESSION['nom_film_saisi'] = $_POST['nom_film'];
		$_SESSION['date_theater_saisie'] = $_POST['date_theater'];
		$_SESSION['date_release_saisie'] = $_POST['date_release'];
		$_SESSION['trailer_saisi'] = $_POST['trailer'];
		$_SESSION['link_saisi'] = $_POST['link'];
		$_SESSION['poster_saisi'] = $_POST['poster'];
		$_SESSION['doodle_saisi'] = $_POST['doodle'];
		$_SESSION['date_doodle_saisie'] = $_POST['date_doodle'];

		// Récupération des variables
		$id_film = $_GET['modify_id'];
		$nom_film = $_POST['nom_film'];
		$date_theater = "";
		$date_release = "";
		$link = $_POST['link'];
		$poster = $_POST['poster'];
		$trailer = $_POST['trailer'];
		$id_url = "";
		$doodle = $_POST['doodle'];
		$date_doodle = "";

		/*
		// Lien Youtube trailer
		$search = "watch?v=";
		$replace = "embed/";
		$trailer = str_replace($search, $replace, $_POST['trailer']);
		*/

		// Récupération ID vidéo
		$id_url = extract_url($trailer);

		// Récupération date sortie cinéma
		$date_a_verifier_1 = $_POST['date_theater'];

		list($d, $m, $y) = explode('/', $date_a_verifier_1);

		// On vérifie le format de la date 1 (date sortie cinéma)
		// SMI - déb
		// if (checkdate($m, $d, $y))
		// {
		// 	$date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
			if (empty($date_a_verifier_1))
			{
				if (isLastDayOfYearWednesday(date('Y')))
				{
					$date_theater = '1230' . date('Y');
				}
				else
				{
					$date_theater = '1231' . date('Y');
				}
			}
			else
			{
				// $date_theater = substr($_POST['date_theater'], 3, 2) . substr($_POST['date_theater'], 0, 2) . substr($_POST['date_theater'], 6, 4);
				$date_theater = substr($date_a_verifier_1, 3, 2) . substr($date_a_verifier_1, 0, 2) . substr($date_a_verifier_1, 6, 4);
			}
			//SMI - fin

			$_SESSION['wrong_date'] = false;

			if (isset($_POST['date_release']) AND !empty($_POST['date_release']))
			{
				$date_a_verifier_2 = $_POST['date_release'];

				list($d, $m, $y) = explode('/', $date_a_verifier_2);

				// On vérifie le format de la date 2 (date sortie dvd/bluray)
				if (checkdate($m, $d, $y))
				{
					$date_release = substr($_POST['date_release'], 3, 2) . substr($_POST['date_release'], 0, 2) . substr($_POST['date_release'], 6, 4);
					$_SESSION['wrong_date'] = false;
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_film_plus.php?modify_id=' . $id_film);
				}
			}

			if (isset($_POST['date_doodle']) AND !empty($_POST['date_doodle']))
			{
				$date_a_verifier_3 = $_POST['date_doodle'];

				list($d, $m, $y) = explode('/', $date_a_verifier_3);

				// On vérifie le format de la date 3 (date proposée)
				if (checkdate($m, $d, $y))
				{
					$date_doodle = substr($_POST['date_doodle'], 3, 2) . substr($_POST['date_doodle'], 0, 2) . substr($_POST['date_doodle'], 6, 4);
					$_SESSION['wrong_date'] = false;
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_film_plus.php?modify_id=' . $id_film);
				}
			}

			if ($_SESSION['wrong_date'] == false)
			{
				// Modification de l'enregistrement en table
				$req = $bdd->prepare('UPDATE movie_house SET film = :film,
															 date_theater = :date_theater,
															 date_release = :date_release,
															 link = :link,
															 poster = :poster,
															 trailer = :trailer,
															 id_url = :id_url,
															 doodle = :doodle,
															 date_doodle = :date_doodle
														 WHERE id = ' . $id_film);
				$req->execute(array(
					'film' => $nom_film,
					'date_theater' => $date_theater,
					'date_release' => $date_release,
					'link' => $link,
					'poster' => $poster,
					'trailer' => $trailer,
					'id_url' => $id_url,
					'doodle' => $doodle,
					'date_doodle' => $date_doodle
				));
				$req->closeCursor();

				header('location: details_film.php?id_film=' . $id_film);
			}
			//SMI - déb
		// }
		// else
		// {
		// 	$_SESSION['wrong_date'] = true;
		// 	header('location: saisie_film_plus.php?modify_id=' . $id_film);
		// }
		//SMI - fin
	}
	// Demande de suppression d'un film
	elseif (isset($_POST['delete_film']))
	{
		$id_film = $_GET['delete_id'];
		$to_delete = "Y";

		// Modification de l'enregistrement en table
		$req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete WHERE id = ' . $id_film);
		$req->execute(array(
			'to_delete' => $to_delete
		));
		$req->closeCursor();

		// Redirection
		switch ($_SESSION['view_movie_house'])
		{
			case "D":
				header('location: ../moviehouse.php?view=user&year=' . date("Y"));
				break;

			case "S":
			default:
				header('location: ../moviehouse.php?view=main&year=' . date("Y"));
				break;
		}
	}
?>
