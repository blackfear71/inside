<?php
	session_start();
	
	$_SESSION['search'] = $_POST['search_content'];
	
	if (isset($_POST['search']))
	{
		header('location: liste_articles.php?search=yes');
	}
	elseif (isset($_POST['search_rdz']))
	{		
		header('location: liste_articles.php?univers=rdz&search=yes');
	}
	elseif (isset($_POST['search_tso']))
	{		
		header('location: liste_articles.php?univers=tso&search=yes');
	}
	elseif (isset($_POST['search_ims']))
	{		
		header('location: liste_articles.php?univers=ims&search=yes');
	}
	elseif (isset($_POST['search_micrortc']))
	{		
		header('location: liste_articles.php?univers=micrortc&search=yes');
	}
	elseif (isset($_POST['search_portaileid']))
	{		
		header('location: liste_articles.php?univers=portaileid&search=yes');
	}
	elseif (isset($_POST['search_glossaire']))
	{		
		header('location: liste_articles.php?univers=glossaire&search=yes');
	}
?>