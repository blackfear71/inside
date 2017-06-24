<?php
  session_start();

  if (isset($_POST['ask_inscription']))
  {
    include('../includes/appel_bdd.php');

    $trigramme = strtoupper($_POST['trigramme']);
    $pseudo = $_POST['pseudo'];
    $salt = rand();
    $password = htmlspecialchars(hash('sha1', $_POST['password'] . $salt));
    $confirm_password = htmlspecialchars(hash('sha1', $_POST['confirm_password'] . $salt));
    $reset = "I";
    $avatar = "";

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
        $req = $bdd->prepare('INSERT INTO users(identifiant, salt, mot_de_passe, reset, full_name, avatar) VALUES(:identifiant, :salt, :mot_de_passe, :reset, :full_name, :avatar)');
				$req->execute(array(
					'identifiant' => $trigramme,
          'salt' => $salt,
          'mot_de_passe' => $password,
          'reset' => $reset,
					'full_name' => $pseudo,
					'avatar' => $avatar
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
    header('location: inscription.php');
  }
?>
