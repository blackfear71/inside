<?php
	// Initialisation des variables de session correspondant à la création d'un article (évite de conserver des données quand on sort de l'environnement de création)
	$_SESSION['title_article'] = "";
	$_SESSION['category_article'] = "";
	$_SESSION['content_article'] = "";
	$_SESSION['save_content_article'] = "";
	$_SESSION['tableau_images'] = array();
	$_SESSION['tableau_liens'] = array();
	$_SESSION['tableau_noms_images'] = array();
	$_SESSION['tableau_noms_liens'] = array();
	$_SESSION['nouveau_texte'] = "";
	$_SESSION['top_images'] = false;
	$_SESSION['top_liens'] = false;
?>