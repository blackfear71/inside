<?php
	if (isset($_GET['univers']))
	{
		switch ($_GET['univers'])
		{
			case "rdz":
				echo '<img src="banniere_rdz.png" alt="banniere_rdz" class="banniere" />';	
				break;
				
			case "tso":
				echo '<img src="banniere_tso.png" alt="banniere_tso" class="banniere" />';	
				break;	

			case "ims":
				echo '<img src="banniere_ims.png" alt="banniere_ims" class="banniere" />';	
				break;

			case "micrortc":
				echo '<img src="banniere_micrortc.png" alt="banniere_micrortc" class="banniere" />';	
				break;

			case "portaileid":
				echo '<img src="banniere_portaileid.png" alt="banniere_portaileid" class="banniere" />';	
				break;

			case "glossaire":
				echo '<img src="banniere_glossaire.png" alt="banniere_glossaire" class="banniere" />';	
				break;				
				
			default:
				break;
		}
	}
?>