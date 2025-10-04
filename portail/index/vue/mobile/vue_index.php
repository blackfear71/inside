<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = '';
            $styleHead       = 'styleIndex.css';
            $scriptHead      = 'scriptIndex.js';
            $chatHead        = false;
            $datepickerHead  = false;
            $masonryHead     = false;
            $exifHead        = false;
            $html2canvasHead = false;
            $jqueryCsv       = false;

            include('includes/common/head.php');
        ?>
    </head>

    <body>
        <!-- Entête -->
        <header>
            <?php include('includes/common/mobile/header_mobile.php'); ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('includes/common/alerts.php'); ?>

            <!-- Chargement page -->
            <div class="zone_loading_image">
                <img src="includes/icons/common/loading.png" alt="" id="loading_image" class="loading_image" />
            </div>

            <!-- Contenu -->
            <article>
                <?php
                    // Message d'aide inscription
                    echo '<div class="fond_alerte">';
                        echo '<div id="aideInscription" class="zone_affichage_alerte">';
                            // Titre
                            echo '<div class="zone_titre_alerte">';
                                echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" class="image_alerte" />';
                                echo '<div class="titre_alerte">Pour votre inscription</div>';
                            echo '</div>';

                            // Affichage du message
                            echo '<div class="texte_aide_index">';
                            echo 'Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo, votre équipe ainsi qu\'un mot de passe.
                                  Celui-ci sera directement crypté afin de garantir la sécurité de l\'accès. Vous pouvez également suggérer la création d\'une nouvelle équipe si celle que vous
                                  souhaitez intégrer n\'est pas disponible. Une demande sera ensuite envoyée à l\'administrateur qui validera votre inscription dans les plus brefs délais.';
                            echo '</div>';

                            // Bouton
                            echo '<a id="fermerAideInscription" class="bouton_alerte">Fermer</a>';
                        echo '</div>';
                    echo '</div>';

                    // Message d'aide changement de mot de passe
                    echo '<div class="fond_alerte">';
                        echo '<div id="aidePassword" class="zone_affichage_alerte">';
                            // Titre
                            echo '<div class="zone_titre_alerte">';
                                echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" class="image_alerte" />';
                                echo '<div class="titre_alerte">Réinitialiser un mot de passe</div>';
                            echo '</div>';

                            // Affichage du message
                            echo '<div class="texte_aide_index">';
                                echo 'Si vous avez perdu votre mot de passe, vous pouvez effectuer une demande de réinitialisation du mot de passe à l\'administrateur via le formulaire ci-dessous.
                                      L\'administrateur est suceptible de vous contacter directement afin de vérifier votre demande. Il vous suffit de renseigner votre identifiant afin que celui-ci
                                      puisse procéder à la création d\'un nouveau mot de passe qu\'il vous communiquera par la suite.';
                            echo '</div>';

                            // Bouton
                            echo '<a id="fermerAidePassword" class="bouton_alerte">Fermer</a>';
                        echo '</div>';
                    echo '</div>';

                    // Formulaires
                    echo '<div class="zone_forms_index">';
                        echo '<div class="zone_form_index">';
                            // Connexion
                            if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == true OR $erreursIndex['erreurPassword'] == true))
                                echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index" style="display: none;">';
                            else
                                echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index">';
                                // Données utilisateur
                                echo '<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" id="focus_identifiant" required />';
                                echo '<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';

                                // Boutons
                                echo '<div class="zone_boutons_validation_index">';
                                    echo '<input type="submit" name="connect" value="CONNEXION" class="bouton_index" />';
                                echo '</div>';
                            echo '</form>';

                            // Inscription
                            if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == true AND $erreursIndex['erreurPassword'] == false))
                                echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index">';
                            else
                                echo '<form method="post" action="index.php?action=doDemanderInscription" id="formInscription" class="form_index" style="display: none;">';
                                // Données utilisateur
                                echo '<input type="text" name="trigramme" value="' . $_SESSION['save']['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index_inscription" id="focus_identifiant_2" required />';
                                echo '<input type="text" name="pseudo" value="' . $_SESSION['save']['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne_index_inscription" required />';

                                // Choix de l'équipe
                                echo '<select name="equipe" class="select_form_index" required>';
                                    echo '<option value="" hidden>Choisir une équipe</option>';

                                    foreach ($listeEquipes as $equipe)
                                    {
                                        if ($_SESSION['save']['equipe_saisie'] == $equipe->getReference())
                                            echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
                                        else
                                            echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
                                    }

                                    if ($_SESSION['save']['equipe_saisie'] == 'other')
                                        echo '<option value="other" selected>Créer une équipe</option>';
                                    else
                                        echo '<option value="other">Créer une équipe</option>';
                                echo '</select>';

                                // Saisie "Autre"
                                if ($_SESSION['save']['equipe_saisie'] == 'other')
                                    echo '<input type="text" name="autre_equipe" value="' . $_SESSION['save']['autre_equipe'] . '" placeholder="Nom de l\'équipe" id="autre_equipe" class="monoligne_index_inscription" />';
                                else
                                    echo '<input type="text" name="autre_equipe" value="' . $_SESSION['save']['autre_equipe'] . '" placeholder="Nom de l\'équipe" id="autre_equipe" class="monoligne_index_inscription" style="display: none;" />';

                                // Mot de passe
                                echo '<input type="password" name="password" value="' . $_SESSION['save']['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne_index_inscription" required />';
                                echo '<input type="password" name="confirm_password" value="' . $_SESSION['save']['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne_index_inscription" required />';

                                // Boutons
                                echo '<div class="zone_boutons_validation_index zone_boutons_inscription">';
                                    echo '<input type="submit" name="ask_inscription" value="INSCRIPTION" class="bouton_index bouton_index_short" />';

                                    echo '<a id="afficherAideInscription" class="lien_bouton_index">';
                                        echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" title="Aide" class="image_bouton_index" />';
                                    echo '</a>';
                                echo '</div>';
                            echo '</form>';

                            // Réinitialisation mot de passe
                            if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == true))
                                echo '<form method="post" action="index.php?action=doDemanderMotDePasse" id="formPassword" class="form_index">';
                            else
                                echo '<form method="post" action="index.php?action=doDemanderMotDePasse" id="formPassword" class="form_index" style="display: none;">';
                                // Données utilisateur
                                echo '<input type="text" name="login" value="' . $_SESSION['save']['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne_index" id="focus_identifiant_3" required />';

                                // Boutons
                                echo '<div class="zone_boutons_validation_index">';
                                    echo '<input type="submit" name="ask_password" value="MOT DE PASSE" class="bouton_index bouton_index_short" />';
                                    
                                    echo '<a id="afficherAidePassword" class="lien_bouton_index">';
                                        echo '<img src="includes/icons/common/question_grey.png" alt="question_grey" title="Aide" class="image_bouton_index" />';
                                    echo '</a>';
                                echo '</div>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';

                    // Boutons
                    echo '<div class="zone_boutons_index">';
                        // Lien connexion
                        if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == true OR $erreursIndex['erreurPassword'] == true))
                            echo '<a id="afficherConnexion" class="lien_index lien_index_margin_right">Se connecter</a>';
                        else
                            echo '<a id="afficherConnexion" class="lien_index lien_index_margin_right" style="display: none;">Se connecter</a>';

                        // Lien inscription
                        if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == false))
                            echo '<a id="afficherInscription" class="lien_index lien_index_margin_right">Inscription</a>';
                        else if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == true AND $erreursIndex['erreurPassword'] == false))
                            echo '<a id="afficherInscription" class="lien_index" style="display: none;">Inscription</a>';
                        else
                            echo '<a id="afficherInscription" class="lien_index">Inscription</a>';

                        // Lien mot de passe perdu
                        if (isset($erreursIndex) AND ($erreursIndex['erreurInscription'] == false AND $erreursIndex['erreurPassword'] == true))
                            echo '<a id="afficherPassword" class="lien_index" style="display: none;">Mot de passe oublié</a>';
                        else
                            echo '<a id="afficherPassword" class="lien_index">Mot de passe oublié</a>';
                    echo '</div>';
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('includes/common/mobile/footer_mobile.php'); ?>
        </footer>
    </body>
</html>