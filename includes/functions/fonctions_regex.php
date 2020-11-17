<?php
	// REGEX : Extraction de l'id d'une vidéo à partir de l'url
	// RETOUR : Url
	function extractUrl($adress)
	{
		// Initialisations
		$url = '';

		// DAILYMOTION
		preg_match('#&lt;object[^&gt;]+&gt;.+?http://www.dailymotion.com/swf/video/([A-Za-z0-9]+).+?&lt;/object&gt;#s', $adress, $matches);

		if (!isset($matches[1]))
		{
			preg_match('#http://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $adress, $matches);
			if (!isset($matches[1]))
			{
				preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $adress, $matches);
				if (!isset($matches[1]))
				{
					$url = '';
				}
				elseif (strlen($matches[1]))
				{
					$url = 'dailymotion:_:' . $matches[1];
				}
			}
			elseif (strlen($matches[1]))
			{
				$url = 'dailymotion:_:' . $matches[1];
			}
		}
		elseif (strlen($matches[1]))
		{
			if (strlen($matches[1]))
			{
				$url = 'dailymotion:_:' . $matches[1];
			}
		}

		// YOUTUBE
		if (preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $adress, $videoid))
		{
			if (strlen($videoid[0]))
			{
				$url = 'youtube:_:' . $videoid[0];
			}
		}

		// VIMEO
		if (preg_match('#(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*#', $adress, $videoid))
		{
			if (strlen($videoid[5]))
			{
				$url = 'vimeo:_:' . $videoid[5];
			}
		}

		// Retour
		return $url;
	}

	// REGEX : Extraction de liens
	// RETOUR : Lien
	function extractLink($texte)
	{
		// On cherche les liens
		$lien = preg_replace('#(?:https?://|ftp://|www.)(?:[\w%?=,:;+\#@./-]|&amp;)+#u', '<a href="$0" target="_blank" title="Lien" class="link_comment"></a>', $texte);

		// Remplacement des débuts d'url
		$search  = array('www.', 'https://https://', 'http://https://');
		$replace = array('https://www.', 'https://', 'http://');
		$lien    = str_replace($search, $replace, $lien);

		// Retour
		return $lien;
	}

	// REGEX : Extraction de smileys
	// RETOUR : Smiley
	function extractSmiley($texte)
	{
		// Tableaux de conversion
		$in = array(htmlspecialchars(":)"),
								htmlspecialchars(":-)"),
								htmlspecialchars(";)"),
								htmlspecialchars(";-)"),
								htmlspecialchars(":("),
								htmlspecialchars(":-("),
								htmlspecialchars(":|"),
								htmlspecialchars(":-|"),
								htmlspecialchars(":D"),
								htmlspecialchars(":-D"),
								htmlspecialchars(":O"),
								htmlspecialchars(":-O"),
								htmlspecialchars(":P"),
								htmlspecialchars(":-P"),
								htmlspecialchars(":facepalm:")
							 );

		$out = array('<img src="/inside/includes/icons/common/smileys/1.png" alt=":)" class="smiley" />',
							 	 '<img src="/inside/includes/icons/common/smileys/1.png" alt=":-)" class="smiley" />',
							 	 '<img src="/inside/includes/icons/common/smileys/2.png" alt=";)" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/2.png" alt=";-)" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/3.png" alt=":(" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/3.png" alt=":-(" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/4.png" alt=":|" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/4.png" alt=":-|" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/5.png" alt=":D" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/5.png" alt=":-D" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/6.png" alt=":O" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/6.png" alt=":-O" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/7.png" alt=":P" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/7.png" alt=":-P" class="smiley" />',
								 '<img src="/inside/includes/icons/common/smileys/8.png" alt=":facepalm:" class="smiley" />'
								);

		// Récupération de l'image
		$smiley = str_replace($in, $out, $texte);

		// Retour
		return $smiley;
	}

	// REGEX : Formatage pour Onclick
	// RETOUR : Texte formaté
	function formatOnclick($texte)
	{
		// Tableaux de conversion
		$in  = array("'", '"', 'é', 'è', 'ê', 'ë', 'à', 'ç', 'ô', 'û');
		$out = array('&rsquo;', '&quot;', '&eacute;', '&egrave;', '&ecirc;', '&euml;', '&agrave;', '&ccedil;', '&ocirc;', '&ucirc;');

		// Remplacement des caractères
		$texteFormat = str_replace($in, $out, $texte);

		// Retour
		return $texteFormat;
	}

	// REGEX : Formatage montant pour affichage
	// RETOUR : Montant formaté
	function formatAmountForDisplay($montant)
	{
		// Remplacement des caractères spéciaux
		$montantFormat = str_replace(',', '.', htmlspecialchars($montant));

		// Formatage
		if (!empty($montantFormat) AND is_numeric($montantFormat))
			$montantFormat = str_replace('.', ',', number_format(round($montantFormat, 2), 2, ',', '')) . ' €';
		else
			$montantFormat = '0,00 €';

		// Retour
		return $montantFormat;
	}

	// REGEX : Formatage montant pour insertion
	// RETOUR : Montant formaté
	function formatAmountForInsert($montant)
	{
		// Remplacement des caractères spéciaux
		$montantFormat = str_replace(',', '.', htmlspecialchars($montant));

		// Formatage
		if (is_numeric($montantFormat))
		{
			if ($montantFormat >= 0)
				$montantFormat = floor($montantFormat * 100) / 100;
			else
				$montantFormat = ceil($montantFormat * 100) / 100;
		}
		else
      $montantFormat = '';

		// Retour
		return $montantFormat;
	}

	// REGEX : Formatage distance pour affichage
	// RETOUR : Distance formatée
	function formatDistanceForDisplay($distance)
	{
		// Formatage
		$distanceFormat = str_replace('.', ',', $distance) . ' km';

		// Retour
		return $distanceFormat;
	}
?>
