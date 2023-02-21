<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture liste des thèmes
    // RETOUR : Liste des thèmes
    function physiqueThemes($typeTheme)
    {
        // Initialisations
        $listeThemes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM themes
                            WHERE type = "' . $typeTheme . '"
                            ORDER BY date_deb DESC, CAST(level AS UNSIGNED) ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Theme à partir des données remontées de la bdd
            $theme = Theme::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeThemes, $theme);
        }

        $req->closeCursor();

        // Retour
        return $listeThemes;
    }

    // PHYSIQUE : Lecture du nombre de références existantes
    // RETOUR : Booléen
    function physiqueReferenceUnique($reference)
    {
        // Initialisations
        $isUnique = true;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreReferences
                            FROM themes
                            WHERE reference = "' . $reference . '"');

        $data = $req->fetch();

        if ($data['nombreReferences'] > 0)
            $isUnique = false;

        $req->closeCursor();

        // Retour
        return $isUnique;
    }

    // PHYSIQUE : Lecture dates thèmes
    // RETOUR : Booléen
    function physiqueSuperpositionDates($dateDeb, $dateFin, $idTheme)
    {
        // Initialisations
        $isOver = false;

        // Requête
        global $bdd;

        if (!empty($idTheme))
        {
            $req = $bdd->query('SELECT *
                                FROM themes
                                WHERE id != ' . $idTheme . ' AND type = "M"
                                ORDER BY date_deb DESC ');
        }
        else
        {
            $req = $bdd->query('SELECT *
                                FROM themes
                                WHERE type = "M"
                                ORDER BY date_deb DESC');
        }

        while ($data = $req->fetch())
        {
            if (($dateDeb >= $data['date_deb'] AND $dateDeb <= $data['date_fin'])
            OR  ($dateFin >= $data['date_deb'] AND $dateFin <= $data['date_fin'])
            OR  ($dateDeb <= $data['date_deb'] AND $dateFin >= $data['date_fin']))
            {
                $isOver = true;
                break;
            }
        }

        $req->closeCursor();

        // Retour
        return $isOver;
    }

    // PHYSIQUE : Lecture thème
    // RETOUR : Objet thème
    function physiqueTheme($idTheme)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM themes
                            WHERE id = ' . $idTheme);

        $data = $req->fetch();

        // Instanciation d'un objet Theme à partir des données remontées de la bdd
        $theme = Theme::withData($data);

        $req->closeCursor();

        // Retour
        return $theme;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau thème
    // RETOUR : Id thème
    function physiqueInsertionTheme($theme)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO themes(reference,
                                                 name,
                                                 type,
                                                 level,
                                                 logo,
                                                 date_deb,
                                                 date_fin)
                                         VALUES(:reference,
                                                :name,
                                                :type,
                                                :level,
                                                :logo,
                                                :date_deb,
                                                :date_fin)');

        $req->execute($theme);

        $req->closeCursor();

        $newId = $bdd->lastInsertId();

        // Retour
        return $newId;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour thème existant
    // RETOUR : Aucun
    function physiqueUpdateTheme($idTheme, $theme)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE themes
                              SET name     = :name,
                                  type     = :type,
                                  level    = :level,
                                  date_deb = :date_deb,
                                  date_fin = :date_fin
                              WHERE id = ' . $idTheme);

        $req->execute($theme);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour préférence utilisateur
    // RETOUR : Aucun
    function physiqueUpdateThemeUsers($reference)
    {
        // Initialisations
        $newReference = '';

        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE preferences
                              SET ref_theme = :ref_theme
                              WHERE ref_theme = "' . $reference . '"');

        $req->execute(array(
            'ref_theme' => $newReference
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression thème
    // RETOUR : Aucun
    function physiqueDeleteTheme($idTheme)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM themes
                           WHERE id = ' . $idTheme);
    }
?>