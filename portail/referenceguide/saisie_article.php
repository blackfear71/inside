<?php
	session_start();
	
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: ../../administration/administration.php');
	
	if ($_SESSION['connected'] == false)
		header('location: ../../index.php');
?>

<!DOCTYPE html>
<html>

    <head>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="../../favicon.png" />
		<link rel="stylesheet" href="../../style.css" />
        <title>Inside CGI - RG</title>
		<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
    </head>
	
	<body>	
		
		<header onclick="document.getElementById('menu').style.display='none';"> 
			<?php include('../../includes/onglets.php') ; ?>
		</header>
		
		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$menu_rg = true;
					$back = true;
					$ideas = true;
					$bug = true;
					
					include('../../includes/aside.php');
				?>
			</aside>
		
			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';">
				<?php					
					echo '<form method="post" action="scan_article.php" enctype="multipart/form-data">';
						if (isset($_SESSION['title_article']))
							echo '<input type="text" name="title" value="' . $_SESSION['title_article'] . '" placeholder="Titre" maxlength="255" class="saisie_titre" required />';
						else
							echo '<input type="text" name="title" placeholder="Titre" maxlength="255" class="saisie_titre" required />';
						
						echo '<p class="saisie_date">' . date("d/m/Y") . '</p>';
						
						echo '<div class="trait"></div>';
						
						if (isset($_SESSION['category_article']) AND !empty($_SESSION['category_article']))
							$_SESSION['univers'] = $_SESSION['category_article'];
						
						echo '<select name="category" class="saisie_categorie">';
							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "rdz")
								echo '<option value="rdz" selected>RDZ</option>';
							else
								echo '<option value="rdz">RDZ</option>';
							
							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "tso")
								echo '<option value="tso" selected>TSO</option>';
							else
								echo '<option value="tso">TSO</option>';

							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "ims")
								echo '<option value="ims" selected>IMS</option>';
							else
								echo '<option value="ims">IMS</option>';
							
							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "micrortc")
								echo '<option value="micrortc" selected>Micro/RTC</option>';
							else
								echo '<option value="micrortc">Micro/RTC</option>';
							
							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "portaileid")
								echo '<option value="portaileid" selected>Portail EID</option>';
							else
								echo '<option value="portaileid">Portail EID</option>';

							if (isset($_SESSION['univers']) AND $_SESSION['univers'] == "glossaire")
								echo '<option value="glossaire" selected>Glossaire</option>';
							else
								echo '<option value="glossaire">Glossaire</option>';
						echo '</select>';
						
						echo '<p class="saisie_auteur"><i>Par ' . $_SESSION['full_name'] . '</i></p>';
						
						echo '<textarea placeholder="Contenu de l\'article" class="saisie_contenu" name="contenu" id="textarea">';
						if (isset($_SESSION['save_content_article']))
							echo $_SESSION['save_content_article'];
						echo '</textarea>';
						
						echo '<div class="trait"></div>';
						
						echo '<input type="submit" name="validate_text" value="Valider" class="saisie_valider" />';
					echo '</form>';
				?>
				
				<div class="boutons_texte">
					<input type="submit" value="Image" onclick="insertTag('\r\r<image>', '</image>\r', 'textarea');" class="bouton_insertion" />
					<input type="submit" value="Lien" onclick="insertTag('<lien>','</lien>','textarea');" class="bouton_insertion" />
					<input type="submit" value="Rouge" onclick="insertTag(' <rouge>','</rouge> ','textarea');" class="bouton_insertion" />
					<input type="submit" value="Vert" onclick="insertTag(' <vert>','</vert> ','textarea');" class="bouton_insertion" />
					<input type="submit" value="Gras" onclick="insertTag(' <gras>','</gras> ','textarea');" class="bouton_insertion" />
					<input type="submit" value="Italique" onclick="insertTag(' <italique>','</italique> ','textarea');" class="bouton_insertion" />
					<input type="submit" value="Souligné" onclick="insertTag(' <souligne>','</souligne> ','textarea');" class="bouton_insertion" />					
					<input type="submit" value="Surligné" onclick="insertTag(' <surligne>','</surligne> ','textarea');" class="bouton_insertion" />					

					<!--<input type="submit" value="Image" onclick="insert_style('image')" class="bouton_insertion" />
					<input type="submit" value="Lien" onclick="insert_style('lien')" class="bouton_insertion" />
					<input type="submit" value="Rouge" onclick="insert_style('rouge')" class="bouton_insertion" />
					<input type="submit" value="Vert" onclick="insert_style('vert')" class="bouton_insertion" />
					<input type="submit" value="Gras" onclick="insert_style('gras')" class="bouton_insertion" />
					<input type="submit" value="Italique" onclick="insert_style('italique')" class="bouton_insertion" />
					<input type="submit" value="Souligné" onclick="insert_style('souligné')" class="bouton_insertion" />
					<input type="submit" value="Surligné" onclick="insert_style('surligné')" class="bouton_insertion" />-->
				</div>
			</article>
		</section>
		
		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>
		
		<script type="text/javascript">
			function afficherMasquer(id)
			{
				if (document.getElementById(id).style.display == "none")
					document.getElementById(id).style.display = "block";
				else
					document.getElementById(id).style.display = "none";
			}

			function insertTag(startTag, endTag, textareaId, tagType) 
			{
				var field  = document.getElementById(textareaId); 
				var scroll = field.scrollTop;
				field.focus();
					
				if (window.ActiveXObject) // C'est IE
				{ 
					var textRange = document.selection.createRange();            
					var currentSelection = textRange.text;
							
					textRange.text = startTag + currentSelection + endTag;
					textRange.moveStart("character", -endTag.length - currentSelection.length);
					textRange.moveEnd("character", -endTag.length);
					textRange.select();     
				} 
				else // Ce n'est pas IE
				{ 
					var startSelection   = field.value.substring(0, field.selectionStart);
					var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
					var endSelection     = field.value.substring(field.selectionEnd);
							
					field.value = startSelection + startTag + currentSelection + endTag + endSelection;
					field.focus();
					field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
				} 

				field.scrollTop = scroll; // et on redéfinit le scroll.
			}

			function insert_style(style)
			{
				if (style == "image")
					insertion = '\r<image>TITRE_DE_L_IMAGE_ICI</image>\r';
					//insertion = '\r<img src="NOM_DU_FICHIER_AVEC_EXTENSION" alt="%AUTOMATIQUE%" title="%AUTOMATIQUE2%" class="image_article" />\r';
				else if (style == "lien")
					insertion = '<a href="LIEN_ICI" target="_blank" class="lien">TITRE_DU_LIEN_ICI</a>';
				else if (style == "rouge")
					insertion = '<span class="rouge">TEXTE_EN_ROUGE_ICI</span>';
				else if (style == "vert")
					insertion = '<span class="vert">TEXTE_EN_VERT_ICI</span>';
				else if (style == "gras")
					insertion = '<span class="gras">TEXTE_EN_GRAS_ICI</span>';
				else if (style == "italique")
					insertion = '<span class="italique">TEXTE_EN_ITALIQUE_ICI</span>';
				else if (style == "souligné")
					insertion = '<span class="souligné">TEXTE_SOULIGNÉ_ICI</span>';
				else if (style == "surligné")
					insertion = '<span class="surligné">TEXTE_SURLIGNÉ_ICI</span>';
				
				//Emplacement
				var where = document.getElementsByName("contenu")[0];
							
				//Texte à insérer + espace
				if (style != "image")
					var phrase = " " + insertion + " ";
				else
					var phrase = insertion;
					
				//Contenu déjà présent + Texte à insérer
				where.value += phrase;
							
				//Positionnement du curseur
				where.focus();
			}
		</script>
    </body>
	
</html>