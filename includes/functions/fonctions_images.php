<?php
    // IMAGE : Création miniature
    // RETOUR : Aucun
    function imageThumb($source, $destination = NULL, $maxSize = 100, $expand = false, $square = false)
    {
        // Traitement si l'image source existe
        if (file_exists($source))
        {
            // Récupèration des infos de l'image
            $fileinfo = getimagesize($source);

            // Traitement si les infos sont disponibles
            if ($fileinfo)
            {
                // Données de l'image
                $width    = $fileinfo[0];
                $height   = $fileinfo[1];
                $typeMime = $fileinfo['mime'];
                $type     = str_replace('image/', '', $typeMime);

                // Copie ou lecture du fichier d'origine s'il ne faut pas l'étendre
                if ($expand == false AND max($width, $height) <= $maxSize AND ($square == false OR ($square == true AND $width == $height)))
                {
                    // Si une destination est renseignée, on créé une copie du fichier, sinon on le lit
                    if ($destination != NULL)
                    {
                        return copy($source, $destination);
                    }
                    else
                    {
                        header('Content-Type: ' . $typeMime);
                        return (bool) readfile($source);
                    }
                }

                // Calcul des nouvelles dimensions
                $ratio = $width / $height;

                if ($square == true)
                {
                    $newWidth = $newHeight = $maxSize;

                    if ($ratio > 1)
                    {
                        // Paysage
                        $sourceY     = 0;
                        $sourceX     = round(($width - $height) / 2);
                        $sourceWidth = $sourceHeight = $height;
                    }
                    else
                    {
                        // Portrait
                        $sourceX     = 0;
                        $sourceY     = round(($height - $width) / 2);
                        $sourceWidth = $sourceHeight = $width;
                    }
                }
                else
                {
                    $sourceX      = $sourceY = 0;
                    $sourceWidth  = $width;
                    $sourceHeight = $height;

                    if ($ratio > 1)
                    {
                        // Paysage
                        $newWidth  = $maxSize;
                        $newHeight = round($maxSize / $ratio);
                    }
                    else
                    {
                        // Portrait
                        $newHeight = $maxSize;
                        $newWidth  = round($maxSize * $ratio);
                    }
                }

                // Détermination de la fonction à utiliser
                $fonction = 'imagecreatefrom' . $type;

                // Traitement de l'image si la fonction existe
                if (function_exists($fonction))
                {
                    // Ouverture de l'image originale
                    $source   = $fonction($source);
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);

                    if ($type == 'png')
                    {
                        // Gestion de la transparence pour les PNG
                        imagealphablending($newImage, false);

                        if (function_exists('imagesavealpha'))
                            imagesavealpha($newImage, true);
                    }
                    elseif ($type == 'gif' AND imagecolortransparent($source) >= 0)
                    {
                        // Gestion de la transparence pour les GIF
                        $transparentIndex = imagecolortransparent($source);
                        $transparentColor = imagecolorsforindex($source, $transparentIndex);
                        $transparentIndex = imagecolorallocate($newImage, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);

                        imagefill($newImage, 0, 0, $transparentIndex);
                        imagecolortransparent($newImage, $transparentIndex);
                    }

                    // Redimensionnement de l'image
                    imagecopyresampled($newImage, $source, 0, 0, $sourceX, $sourceY, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

                    // Enregistrement de l'image
                    $fonction = 'image' . $type;

                    if ($destination != NULL)
                    {
                        $fonction($newImage, $destination);
                    }
                    else
                    {
                        header('Content-Type: ' . $typeMime);
                        $fonction($newImage);
                    }

                    // Libération de la mémoire
                    imagedestroy($newImage);
                }
            }
        }
    }

    // IMAGE : Création image rognée
    // RETOUR : Aucun
    function imageTrim($maxWidth, $maxHeight, $source, $destination = NULL, $expand = false)
    {
        // Traitement si l'image source existe
        if (file_exists($source))
        {
            // Récupèration des infos de l'image
            $fileinfo = getimagesize($source);

            // Traitement si les infos sont disponibles
            if ($fileinfo)
            {
                // Données de l'image
                $width    = $fileinfo[0];
                $height   = $fileinfo[1];
                $typeMime = $fileinfo['mime'];
                $type     = str_replace('image/', '', $typeMime);

                // Copie ou lecture du fichier d'origine s'il ne faut pas l'étendre
                if ($expand == false AND ($width <= $maxWidth OR $height <= $maxHeight))
                {
                    // Si une destination est renseignée, on créé une copie du fichier, sinon on le lit
                    if ($destination != NULL)
                    {
                        return copy($source, $destination);
                    }
                    else
                    {
                        header('Content-Type: ' . $typeMime);
                        return (bool) readfile($source);
                    }
                }

                // Calcul des nouvelles dimensions
                $ratio    = $width / $height;
                $newRatio = $maxWidth / $maxHeight;

                if ($ratio > 1)
                {
                    // Paysage
                    if ($ratio > $newRatio)
                    {
                        // Si le ratio d'origine est supérieur au ratio de destination, il faut se positionner et rogner en largeur
                        $sourceX      = round(($width - ($height * $newRatio)) / 2);
                        $sourceY      = 0;
                        $sourceWidth  = $height * $newRatio;
                        $sourceHeight = $height;
                    }
                    else
                    {
                        // Si le ratio d'origine est inférieur au ratio de destination, il faut se positionner et rogner en hauteur
                        $sourceX      = 0;
                        $sourceY      = round(($height - ($width / $newRatio)) / 2);
                        $sourceWidth  = $width;
                        $sourceHeight = $width / $newRatio;
                    }
                }
                else
                {
                    // Portrait
                    if ($ratio > $newRatio)
                    {
                        // Si le ratio d'origine est supérieur au ratio de destination, il faut se positionner et rogner en largeur
                        $sourceX      = round(($width - ($height * $newRatio)) / 2);
                        $sourceY      = 0;
                        $sourceWidth  = $height * $newRatio;
                        $sourceHeight = $height;
                    }
                    else
                    {
                        // Si le ratio d'origine est inférieur au ratio de destination, il faut se positionner et rogner en hauteur
                        $sourceX      = 0;
                        $sourceY      = round(($height - ($width / $newRatio)) / 2);
                        $sourceWidth  = $width;
                        $sourceHeight = $width / $newRatio;
                    }
                }

                $newWidth  = $maxWidth;
                $newHeight = $maxHeight;

                // Détermination de la fonction à utiliser
                $fonction = 'imagecreatefrom' . $type;

                // Traitement de l'image si la fonction existe
                if (function_exists($fonction))
                {
                    $source   = $fonction($source);
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);

                    if ($type == 'png')
                    {
                        // Gestion de la transparence pour les PNG
                        imagealphablending($newImage, false);

                        if (function_exists('imagesavealpha'))
                            imagesavealpha($newImage, true);
                    }
                    elseif ($type == 'gif' AND imagecolortransparent($source) >= 0)
                    {
                        // Gestion de la transparence pour les gif
                        $transparentIndex = imagecolortransparent($source);
                        $transparentColor = imagecolorsforindex($source, $transparentIndex);
                        $transparentIndex = imagecolorallocate($newImage, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);

                        imagefill($newImage, 0, 0, $transparentIndex);
                        imagecolortransparent($newImage, $transparentIndex);
                    }

                    // Redimensionnement de l'image
                    imagecopyresampled($newImage, $source, 0, 0, $sourceX, $sourceY, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

                    // Enregistrement de l'image
                    $fonction = 'image' . $type;

                    if ($destination != NULL)
                    {
                        $fonction($newImage, $destination);
                    }
                    else
                    {
                        header('Content-Type: ' . $typeMime);
                        $fonction($newImage);
                    }

                    // Libération de la mémoire
                    imagedestroy($newImage);
                }
            }
        }
    }

    // IMAGE : Compression d'une image au format JPEG
    // RETOUR : Aucun
    function imageCompression($source, $destination, $qualite)
    {
        // Récupération des informations du fichier
        $info = getimagesize($source);

        // Création d'une nouvelle image en fonction du type
        switch ($info['mime'])
        {
            case 'image/bmp':
                $image = imagecreatefrombmp($source);
                break;

            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;

            case 'image/png':
                $image = imagecreatefrompng($source);
                break;

            case 'image/jpeg':
            default:
                $image = imagecreatefromjpeg($source);
                break;
        }

        // Sauvegarde de l'image
        imagejpeg($image, $destination, $qualite);
    }
?>