<?php
  session_start();

  if (isset($_POST['ask_inscription']))
  {
    include('../includes/appel_bdd.php');

    // Récupération des champs saisis et initialisations utilisateur
    $trigramme        = htmlspecialchars(strtoupper($_POST['trigramme']));
    $pseudo           = htmlspecialchars($_POST['pseudo']);
    $salt             = rand();
    $password         = htmlspecialchars(hash('sha1', $_POST['password'] . $salt));
    $confirm_password = htmlspecialchars(hash('sha1', $_POST['confirm_password'] . $salt));
    $reset            = "I";
    $avatar           = "";

    // Initialisations préférences
    $view_movie_house  = "H";
    $categories_home   = "NN";
    $today_movie_house = "N";
    $view_the_box      = "P";

    // Contrôle trigramme déjà pris
    $reponse = $bdd->query('SELECT id, identifiant FROM users');
    while ($donnees = $reponse->fetch())
    {
      if ($donnees['identifiant'] == $trigramme)
      {
        $_SESSION['already_exist'] = true;
        break;
      }
      else
      {
        $_SESSION['already_exist'] = false;
      }
    }
    $reponse->closeCursor();

    // Contrôle confirmation mot de passe
    if ($_SESSION['already_exist'] == false)
    {
      if ($password == $confirm_password)
      {
        // On créé l'utilisateur
        $req = $bdd->prepare('INSERT INTO users(identifiant, salt, mot_de_passe, reset, full_name, avatar) VALUES(:identifiant, :salt, :mot_de_passe, :reset, :full_name, :avatar)');
				$req->execute(array(
					'identifiant'  => $trigramme,
          'salt'         => $salt,
          'mot_de_passe' => $password,
          'reset'        => $reset,
					'full_name'    => $pseudo,
					'avatar'       => $avatar
					));
				$req->closeCursor();

        // On créé les préférences
        $req = $bdd->prepare('INSERT INTO preferences(identifiant, view_movie_house, categories_home, today_movie_house, view_the_box) VALUES(:identifiant, :view_movie_house, :categories_home, :today_movie_house, :view_the_box)');
        $req->execute(array(
          'identifiant'       => $trigramme,
          'view_movie_house'  => $view_movie_house,
          'categories_home'   => $categories_home,
          'today_movie_house' => $today_movie_house,
          'view_the_box'      => $view_the_box
          ));
        $req->closeCursor();

        $_SESSION['ask_inscription'] = true;
        $_SESSION['wrong_confirm'] = false;
      }
      else
      {
        $_SESSION['ask_inscription'] = false;
        $_SESSION['wrong_confirm'] = true;
      }
    }

    // Redirection
    //header('location: inscription.php');
  }
  elseif (isset($_POST['ask_desinscription']))
  {
    include('../includes/appel_bdd.php');

    $reset = "D";
    $_SESSION['ask_desinscription'] = false;

    $req = $bdd->prepare('UPDATE users SET reset=:reset WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();

    $_SESSION['ask_desinscription'] = true;

    header('location: ../profil/profil.php?user=' . $_SESSION['identifiant']);
  }
?>
