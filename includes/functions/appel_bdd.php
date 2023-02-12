<?php
    // Appel à la base de donnée (méthode PDO)
    try
    {
        $bdd = new PDO('mysql:host=localhost; dbname=inside; charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
?>