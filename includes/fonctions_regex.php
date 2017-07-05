<?php
/////////////////////////////////////////////////////////////////
	// Fonction d'extraction de l'id d'une vidéo à partir de l'url //
	/////////////////////////////////////////////////////////////////
	function extract_url ($adress)
	{
		// Initialisation
		$url = "";
			
		//DAILYMOTION	
		preg_match('#&lt;object[^&gt;]+&gt;.+?http://www.dailymotion.com/swf/video/([A-Za-z0-9]+).+?&lt;/object&gt;#s', $adress, $matches);

		if (!isset($matches[1])) 
		{
			preg_match('#http://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $adress, $matches);
			if (!isset($matches[1])) 
			{
				preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $adress, $matches);
				if (!isset($matches[1])) 
				{
					$url = "";
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
			
		//YOUTUBE
		if (preg_match('#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $adress, $videoid))
		{
			if (strlen($videoid[0])) 
			{ 
				$url = 'youtube:_:' . $videoid[0]; 
			}
		}

		//VIMEO
		if (preg_match('#(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*#', $adress, $videoid))
		{
			if (strlen($videoid[5])) 
			{ 
				$url = 'vimeo:_:' . $videoid[5]; 
			}
		}

		return $url;
	}

?>