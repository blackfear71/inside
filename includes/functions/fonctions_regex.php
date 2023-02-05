<?php
	// REGEX : Extraction de l'id d'une vidéo à partir de l'url
	// RETOUR : Url
	function extractUrl($adress)
	{
		// Initialisations
		$url = '';

		// DAILYMOTION
		preg_match('#&lt;object[^&gt;]+&gt;.+?https://www.dailymotion.com/swf/video/([A-Za-z0-9]+).+?&lt;/object&gt;#s', $adress, $matches);

		if (!isset($matches[1]))
		{
			preg_match('#https://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $adress, $matches);

			if (!isset($matches[1]))
			{
				preg_match('#https://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $adress, $matches);

				if (!isset($matches[1]))
					$url = '';
				elseif (strlen($matches[1]))
					$url = 'dailymotion:_:' . $matches[1];
			}
			elseif (strlen($matches[1]))
				$url = 'dailymotion:_:' . $matches[1];
		}
		elseif (strlen($matches[1]))
		{
			if (strlen($matches[1]))
				$url = 'dailymotion:_:' . $matches[1];
		}

		// YOUTUBE
		if (preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $adress, $videoid))
		{
			if (strlen($videoid[0]))
				$url = 'youtube:_:' . $videoid[0];
		}

		// VIMEO
		if (preg_match('#(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*#', $adress, $videoid))
		{
			if (strlen($videoid[5]))
				$url = 'vimeo:_:' . $videoid[5];
		}

		// Retour
		return $url;
	}

	// REGEX : Extraction de liens
	// RETOUR : Lien
	function extractLink($texte)
	{
		// Recherche des liens
		$lien = preg_replace('#(?:https?://|ftp://|www.)(?:[\w%?=,:;+\#@./-]|&amp;)+#u', '<a href="$0" target="_blank" title="Lien" class="lien_commentaire"></a>', $texte);

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

		// Récupération de l'image à partir du texte
		$smiley = str_replace($in, $out, $texte);

		// Retour
		return $smiley;
	}

	// REGEX : Formatage pour messages d'alertes avec caractères spéciaux
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
			$montantFormat = round($montantFormat, 2, PHP_ROUND_HALF_DOWN);
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

	// METIER : Suppression des caractères ASCII invisibles
  // RETOUR : Chaîne nettoyée
  function deleteInvisible($phrase)
  {
		// Filtrage des caractères invisibles
    $cleaned = preg_replace('[\xE2\x80\x8E]', '', $phrase);

		// Retour
    return $cleaned;
  }

	// METIER : Formatage phrases cultes
	// RETOUR : Chaîne formatée
	function formatCollector($collector)
	{
		// Filtrage des caractères
		$search    = array('[', ']');
		$replace   = array('<strong>', '</strong>');
		$formatted = str_replace($search, $replace, $collector);

		// Retour
		return $formatted;
	}

	// METIER : Dé-formatage phrases cultes
	// RETOUR : Chaîne dé-formatée
	function unformatCollector($collector)
	{
		// Filtrage des caractères
		$search      = array('[', ']');
		$replace     = array('', '');
		$unformatted = str_replace($search, $replace, $collector);

		// Retour
		return $unformatted;
	}

	// METIER : Formatage Id
	// RETOUR : Id formaté
	function formatId($id)
	{
		// Transforme les caractères accentués en entités HTML
		$formatted = htmlentities($id, ENT_NOQUOTES, 'utf-8');

		// Remplace les entités HTML pour avoir juste le premier caractères non accentué
		$formatted = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $formatted);

		// Remplace les ligatures tel que : œ, Æ ...
		$formatted = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $formatted);

		// Supprime tout le reste
		$formatted = preg_replace('#&[^;]+;#', '', $formatted);

		// Remplace les caractères inutiles
		$search    = array(' ', ',', '?', ';', '.', ':', '/', '!');
		$replace   = array('_', '_', '', '_', '_', '', '', '_');
		$formatted = str_replace($search, $replace, $formatted);

		// Passe en minuscule
		$formatted = strtolower($formatted);

		// Retour
		return $formatted;
	}

	// METIER : Formatage du numéro de téléphone
	// RETOUR : Numéro formaté
	function formatPhoneNumber($phone)
	{
		// Formatage du numéro de téléphone
		if (!empty($phone))
			$formattedPhone = substr($phone, 0, 2) . '.' . substr($phone, 2, 2) . '.' . substr($phone, 4, 2) . '.' . substr($phone, 6, 2) . '.' . substr($phone, 8, 2);
		else
			$formattedPhone = '';

		// Retour
		return $formattedPhone;
	}

	// METIER : Encode certains caractères
	// RETOUR : Chaîne encodée
	function encodeStringForInsert($chaine)
	{
		// Remplacement des caractères
		$search  = array('&', ';', '"', "'", '<', '>');
		$replace = array('et', '', '', '', '', '');
		$chaine  = trim(str_replace($search, $replace, $chaine));

		// Retour
		return $chaine;
	}

	// METIER : Décode certains caractères
	// RETOUR : Chaîne décodée
	function decodeStringForDisplay($chaine)
	{
		// Remplacement des caractères
		$search  = array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;');
		$replace = array('et', '', '', '', '');
		$chaine  = str_replace($search, $replace, $chaine);

		// Retour
		return $chaine;
	}
?>
