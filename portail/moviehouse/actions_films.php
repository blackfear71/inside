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
		$date_add = date("mdY");
		$date_theater = "";
		$date_release = "";
		$link = "";
		$poster = "";
		$trailer = "";
		$id_url = "";
		$doodle = "";
		$date_doodle = "";
		$time_doodle = "";
		$restaurant = "N";
		$place = "";

		$date_a_verifier = $_POST['date_theater'];

		// Contrôle date à vide
		//SMI - déb
		if (empty($date_a_verifier))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_a_verifier = '30/12/' . date('Y');
				$date_theater = '1230' . date('Y');
			}
			else
			{
				$date_a_verifier = '31/12/' . date('Y');
				$date_theater = '1231' . date('Y');
			}
		}
		else
		{
			$date_theater = formatDateForInsert($date_a_verifier);
		}
		//SMI - fin

		// On décompose la date à contrôler
		list($d, $m, $y) = explode('/', $date_a_verifier);

		// On vérifie le format de la date
		if (checkdate($m, $d, $y))
		{
			// Stockage de l'enregistrement en table
			$req = $bdd->prepare('INSERT INTO movie_house(film,
																										to_delete,
																										date_add,
																										date_theater,
																										date_release,
																										link,
																										poster,
																										trailer,
																										id_url,
																										doodle,
																										date_doodle,
																										time_doodle,
																										restaurant,
																										place)
																						VALUES(:film,
																									 :to_delete,
																									 :date_add,
																									 :date_theater,
																									 :date_release,
																									 :link,
																									 :poster,
																									 :trailer,
																									 :id_url,
																									 :doodle,
																									 :date_doodle,
																									 :time_doodle,
																									 :restaurant,
																									 :place)');
			$req->execute(array(
				'film' => $nom_film,
				'to_delete' => $to_delete,
				'date_add' => $date_add,
				'date_theater' => $date_theater,
				'date_release' => $date_release,
				'link' => $link,
				'poster' => $poster,
				'trailer' => $trailer,
				'id_url' => $id_url,
				'doodle' => $doodle,
				'date_doodle' => $date_doodle,
				'time_doodle' => $time_doodle,
				'restaurant' => $restaurant,
				'place' => $place
				));
			$req->closeCursor();

			$_SESSION['film_added'] = true;

			header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . substr($date_a_verifier, 6, 4));
		}
		else
		{
			$_SESSION['wrong_date'] = true;

			header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . date("Y"));
		}
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

		if (isset($_POST['hours_doodle']))
			$_SESSION['hours_doodle_saisies'] = $_POST['hours_doodle'];
		else
			$_SESSION['hours_doodle_saisies'] = "";

		if (isset($_POST['minutes_doodle']))
			$_SESSION['minutes_doodle_saisies'] = $_POST['minutes_doodle'];
		else
			$_SESSION['minutes_doodle_saisies'] = "";

		$_SESSION['restaurant_saisi'] = $_POST['restaurant'];
		$_SESSION['place_saisie'] = $_POST['place'];

		// Récupération des variables
		$nom_film = $_POST['nom_film'];
		$to_delete = "N";
		$date_add = date("mdY");
		$date_theater = "";
		$date_release = "";
		$link = $_POST['link'];
		$poster = $_POST['poster'];
		$trailer = $_POST['trailer'];
		$id_url = "";
		$doodle = $_POST['doodle'];
		$date_doodle = "";

		if (!empty($_POST['date_doodle']) AND isset($_POST['hours_doodle']) AND isset($_POST['minutes_doodle']))
			$time_doodle = $_POST['hours_doodle'] . $_POST['minutes_doodle'];
		else
			$time_doodle = "";

		$restaurant = $_POST['restaurant'];
		$place = $_POST['place'];

		//PHA - déb
		// Lien Youtube trailer
		/*$search = "watch?v=";
		$replace = "embed/";
		$trailer = str_replace($search, $replace, $_POST['trailer']);*/
		//PHA - fin

		// Récupération ID vidéo
		$id_url = extract_url($trailer);

		// Récupération date sortie cinéma
		$date_a_verifier_1 = $_POST['date_theater'];

		// Contrôle date à vide
		//SMI - déb
		if (empty($date_a_verifier_1))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_a_verifier_1 = '30/12/' . date('Y');
				$date_theater = '1230' . date('Y');
			}
			else
			{
				$date_a_verifier_1 = '31/12/' . date('Y');
				$date_theater = '1231' . date('Y');
			}
		}
		else
		{
			$date_theater = formatDateForInsert($date_a_verifier_1);
		}
		// SMI - fin

		// On décompose la date à contrôler
		list($d, $m, $y) = explode('/', $date_a_verifier_1);

		// On vérifie le format de la date
		if (checkdate($m, $d, $y))
		{
			if (isset($_POST['date_release']) AND !empty($_POST['date_release']))
			{
				$date_a_verifier_2 = $_POST['date_release'];

				list($d, $m, $y) = explode('/', $date_a_verifier_2);

				// On vérifie le format de la date 2 (date sortie dvd/bluray)
				if (checkdate($m, $d, $y))
				{
					$date_release = formatDateForInsert($_POST['date_release']);
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_avancee.php');
				}
			}

			if (isset($_POST['date_doodle']) AND !empty($_POST['date_doodle']))
			{
				$date_a_verifier_3 = $_POST['date_doodle'];

				list($d, $m, $y) = explode('/', $date_a_verifier_3);

				// On vérifie le format de la date 3 (date proposée)
				if (checkdate($m, $d, $y))
				{
					$date_doodle = formatDateForInsert($_POST['date_doodle']);
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_avancee.php');
				}
			}

			if ($_SESSION['wrong_date'] != true)
			{
				// Stockage de l'enregistrement en table
				$req = $bdd->prepare('INSERT INTO movie_house(film,
																											to_delete,
																											date_add,
																											date_theater,
																											date_release,
																											link, poster,
																											trailer,
																											id_url,
																											doodle,
																											date_doodle,
																											time_doodle,
																											restaurant,
																											place)
																							VALUES(:film,
																										 :to_delete,
																										 :date_add,
																										 :date_theater,
																										 :date_release,
																										 :link, :poster,
																										 :trailer,
																										 :id_url,
																										 :doodle,
																										 :date_doodle,
																									   :time_doodle,
																										 :restaurant,
																										 :place)');
				$req->execute(array(
					'film' => $nom_film,
					'to_delete' => $to_delete,
					'date_add' => $date_add,
					'date_theater' => $date_theater,
					'date_release' => $date_release,
					'link' => $link,
					'poster' => $poster,
					'trailer' => $trailer,
					'id_url' => $id_url,
					'doodle' => $doodle,
					'date_doodle' => $date_doodle,
					'time_doodle' => $time_doodle,
					'restaurant' => $restaurant,
					'place' => $place
					));
				$req->closeCursor();

				$_SESSION['film_added'] = true;

				switch ($_SESSION['view_movie_house'])
				{
					case "S":
						header('location: ../moviehouse.php?view=main&year=' . substr($date_a_verifier_1, 6, 4));
						break;

					case "D":
						header('location: ../moviehouse.php?view=user&year=' . substr($date_a_verifier_1, 6, 4));
						break;

					case "H":
					default:
						header('location: ../moviehouse.php?view=home&year=' . substr($date_a_verifier_1, 6, 4));
						break;
				}
			}
		}
		else
		{
			$_SESSION['wrong_date'] = true;
			header('location: saisie_avancee.php');
		}
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

		if (isset($_POST['hours_doodle']))
			$_SESSION['hours_doodle_saisies'] = $_POST['hours_doodle'];
		else
			$_SESSION['hours_doodle_saisies'] = "";

		if (isset($_POST['minutes_doodle']))
			$_SESSION['minutes_doodle_saisies'] = $_POST['minutes_doodle'];
		else
			$_SESSION['minutes_doodle_saisies'] = "";

		$_SESSION['restaurant_saisi'] = $_POST['restaurant'];
		$_SESSION['place_saisie'] = $_POST['place'];

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

		if (!empty($_POST['date_doodle']) AND isset($_POST['hours_doodle']) AND isset($_POST['minutes_doodle']))
			$time_doodle = $_POST['hours_doodle'] . $_POST['minutes_doodle'];
		else
			$time_doodle = "";

		$restaurant = $_POST['restaurant'];
		$place = $_POST['place'];

		//PHA - déb
		// Lien Youtube trailer
		/*$search = "watch?v=";
		$replace = "embed/";
		$trailer = str_replace($search, $replace, $_POST['trailer']);*/
		//PHA - fin

		// Récupération ID vidéo
		$id_url = extract_url($trailer);

		// Récupération date sortie cinéma
		$date_a_verifier_1 = $_POST['date_theater'];

		// Contrôle date à vide
		//SMI - déb
		if (empty($date_a_verifier_1))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_a_verifier_1 = '30/12/' . date('Y');
				$date_theater = '1230' . date('Y');
			}
			else
			{
				$date_a_verifier_1 = '31/12/' . date('Y');
				$date_theater = '1231' . date('Y');
			}
		}
		else
		{
			$date_theater = formatDateForInsert($date_a_verifier_1);
		}
		// SMI - fin

		// On décompose la date à contrôler
		list($d, $m, $y) = explode('/', $date_a_verifier_1);

		// On vérifie le format de la date
		if (checkdate($m, $d, $y))
		{
			if (isset($_POST['date_release']) AND !empty($_POST['date_release']))
			{
				$date_a_verifier_2 = $_POST['date_release'];

				list($d, $m, $y) = explode('/', $date_a_verifier_2);

				// On vérifie le format de la date 2 (date sortie dvd/bluray)
				if (checkdate($m, $d, $y))
				{
					$date_release = formatDateForInsert($_POST['date_release']);
					$_SESSION['wrong_date'] = false;
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_avancee.php?modify_id=' . $id_film);
				}
			}

			if (isset($_POST['date_doodle']) AND !empty($_POST['date_doodle']))
			{
				$date_a_verifier_3 = $_POST['date_doodle'];

				list($d, $m, $y) = explode('/', $date_a_verifier_3);

				// On vérifie le format de la date 3 (date proposée)
				if (checkdate($m, $d, $y))
				{
					$date_doodle = formatDateForInsert($_POST['date_doodle']);
				}
				else
				{
					$_SESSION['wrong_date'] = true;
					header('location: saisie_avancee.php?modify_id=' . $id_film);
				}
			}

			if ($_SESSION['wrong_date'] != true)
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
															 date_doodle = :date_doodle,
															 time_doodle = :time_doodle,
															 restaurant = :restaurant,
															 place = :place
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
					'date_doodle' => $date_doodle,
					'time_doodle' => $time_doodle,
					'restaurant' => $restaurant,
					'place' => $place
				));
				$req->closeCursor();

				$_SESSION['film_modified'] = true;
				header('location: details_film.php?id_film=' . $id_film);
			}
		}
		else
		{
			$_SESSION['wrong_date'] = true;
			header('location: saisie_avancee.php?modify_id=' . $id_film);
		}
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

		$_SESSION['film_removed'] = true;

		// Redirection
		switch ($_SESSION['view_movie_house'])
		{
			case "S":
				header('location: ../moviehouse.php?view=main&year=' . date("Y"));
				break;
				
			case "D":
				header('location: ../moviehouse.php?view=user&year=' . date("Y"));
				break;

			case "H":
			default:
				header('location: ../moviehouse.php?view=home&year=' . date("Y"));
				break;
		}
	}
?>
