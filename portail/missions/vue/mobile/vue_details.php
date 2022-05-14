<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Missions : Insider';
      $styleHead       = 'styleMI.css';
      $scriptHead      = '';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/mobile/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'missions';
        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /*******************/
          /* Détails mission */
          /*******************/
          if ($missionExistante == true)
          {
            echo '<div class="zone_details_mission">';
              // Titre
              switch ($detailsMission->getStatut())
              {
                case 'V':
                  echo '<div class="titre_section">';
                    echo '<img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section">' . $detailsMission->getMission() . '</div>';
                  echo '</div>';
                  break;

                case 'C':
                  echo '<div class="titre_section">';
                    echo '<img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section">' . $detailsMission->getMission() . '</div>';
                  echo '</div>';
                  break;

                case 'A':
                default:
                  echo '<div class="titre_section">';
                    echo '<img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section">' . $detailsMission->getMission() . '</div>';
                  echo '</div>';
                  break;
              }

              // Image
              echo '<img src="../../includes/images/missions/banners/' . $detailsMission->getReference() . '.png" alt="' . $detailsMission->getReference() . '" title="' . $detailsMission->getMission() . '" class="image_details_mission" />';
            echo '</div>';

            echo '<div class="zone_details_mission">';
              // Histoire
              echo '<div id="titre_histoire_mission" class="titre_section">';
                echo '<img src="../../includes/icons/missions/story_grey.png" alt="story_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fleche">Il était une fois...</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
              echo '</div>';

              echo '<div id="afficher_histoire_mission">';
                echo '<div class="texte_details_mission">';
                  echo nl2br($detailsMission->getDescription());
                echo '</div>';
              echo '</div>';
            echo '</div>';

            // Conclusion
            if (date('Ymd') > $detailsMission->getDate_fin())
            {
              echo '<div class="zone_details_mission">';
                echo '<div id="titre_conclusion_mission" class="titre_section">';
                  echo '<img src="../../includes/icons/missions/end_grey.png" alt="end_grey" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Le fin mot de l\'histoire</div>';
                  echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                echo '</div>';

                echo '<div id="afficher_conclusion_mission">';
                  echo '<div class="texte_details_mission">';
                    echo nl2br($detailsMission->getConclusion());
                  echo '</div>';

                  echo '<div class="zone_images_conclusion">';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_g.png" alt="' . $detailsMission->getReference() . '_g" class="img_conclusion_mission_g" />';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_m.png" alt="' . $detailsMission->getReference() . '_m" class="img_conclusion_mission_m" />';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_d.png" alt="' . $detailsMission->getReference() . '_d" class="img_conclusion_mission_d" />';
                  echo '</div>';
                echo '</div>';
              echo '</div>';
            }

            // Classement et succès
            if (date('Ymd') > $detailsMission->getDate_fin())
            {              
              // Classement
              echo '<div class="zone_details_mission">';
                // Titre
                echo '<div id="titre_classement_mission" class="titre_section">';
                  echo '<img src="../../includes/icons/missions/podium_grey.png" alt="podium_grey" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Classement</div>';
                  echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                echo '</div>';

                echo '<div id="afficher_classement_mission">';
                  // Classement des utilisateurs
                  if (!empty($ranking))
                  {
                    $rank = 0;
                    $finGagnants = false;

                    echo '<div class="zone_classement">';
                      foreach ($ranking as $keyRank => $rankUser)
                      {
                        if ($rank == 0)
                        {
                          echo '<div class="zone_gagnants">';
                            echo '<div class="zone_titre_gagnants">Les gagnants</div>';
                        }

                        if ($rankUser->getRank() != $rank)
                        {
                          // Médailles
                          if ($rankUser->getRank() <= 3)
                          {
                            switch ($rankUser->getRank())
                            {
                              case 1:
                                echo '<img src="../../includes/icons/common/medals/or.png" alt="or" class="medal_rank" />';
                                break;

                              case 2:
                                echo '<img src="../../includes/icons/common/medals/argent.png" alt="argent" class="medal_rank" />';
                                break;

                              case 3:
                                echo '<img src="../../includes/icons/common/medals/bronze.png" alt="bronze" class="medal_rank" />';
                                break;

                              default:
                                break;
                            }
                          }
                          else
                          {
                            // Fin zone gagnants si rang > 3 et début zone participants restants
                            if ($finGagnants == false)
                            {
                              echo '</div>';
                              $finGagnants = true;

                              echo '<div class="zone_participants">';
                                echo '<div class="zone_titre_gagnants">Les autres participants</div>';
                            }
                          }

                          $rank = $rankUser->getRank();

                          if ($rank > 3)
                            echo '<div class="score_classement score_classement_margin">' . $rankUser->getTotal() . '</div>';
                          else
                            echo '<div class="score_classement">' . $rankUser->getTotal() . '</div>';

                          echo '<div class="zone_medals">';
                        }

                        // Avatar
                        $avatarFormatted = formatAvatar($rankUser->getAvatar(), $rankUser->getPseudo(), 2, 'avatar');

                        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_classement" />';

                        // Fin zone gagnants si moins de 3 médailles présentes
                        if ($rank <= 3 AND $finGagnants == false AND !isset($ranking[$keyRank + 1]))
                        {
                          echo '</div>';
                          $finGagnants = true;
                        }

                        // Fin zone gagnants si exactement 3 médailles présentes ou si il reste des participants
                        if (!isset($ranking[$keyRank + 1]) OR $rankUser->getRank() != $ranking[$keyRank + 1]->getRank())
                          echo '</div>';

                        // Fin zone participants si rang > 3
                        if (!isset($ranking[$keyRank + 1]) AND $rank > 3)
                          echo '</div>';
                      }
                    echo '</div>';
                  }
                  else
                    echo '<div class="empty">Personne n\'a été classé sur cette mission...</div>';
                echo '</div>';
              echo '</div>';

              // Succès
              echo '<div class="zone_details_mission">';
                // Titre
                echo '<div id="titre_succes_mission" class="titre_section">';
                  echo '<img src="../../includes/icons/missions/success_grey.png" alt="success_grey" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Succès débloqués</div>';
                  echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                echo '</div>';

                echo '<div id="afficher_succes_mission">';
                  // Succès débloqués
                  if (!empty($succesMission))
                  {
                    echo '<div class="zone_succes_mission">';
                      $i         = 0;
                      $keySucces = array_keys($succesMission);
                      $lastKey   = end($keySucces);

                      foreach ($succesMission as $succes)
                      {
                        // Logo succès
                        if ($succes->getValue_user() >= $succes->getLimit_success())
                        {
                          if ($i % 2 == 0)
                          {
                            if ($i == $lastKey)
                              echo '<div class="succes_mission succes_mission_yellow" title="' . $succes->getTitle() . '">';
                            else
                              echo '<div class="succes_mission succes_mission_yellow margin_right_1vh" title="' . $succes->getTitle() . '">';
                          }
                          else
                            echo '<div class="succes_mission succes_mission_yellow margin_left_1vh" title="' . $succes->getTitle() . '">';

                            echo '<img src="../../includes/images/profil/success/' . $succes->getReference() . '.png" alt="' . $succes->getReference() . '" class="logo_succes_unlocked" />';
                          echo '</div>';
                        }
                        else
                        {
                          if ($i % 2 == 0)
                          {
                            if ($i == $lastKey)
                              echo '<div class="succes_mission" title="Succès non débloqué">';
                            else
                              echo '<div class="succes_mission margin_right_1vh" title="Succès non débloqué">';
                          }
                          else
                            echo '<div class="succes_mission margin_left_1vh" title="Succès non débloqué">';

                            echo '<img src="../../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_succes_locked" />';
                          echo '</div>';
                        }

                        $i++;
                      }
                    echo '</div>';
                  }
                  else
                    echo '<div class="empty">Aucun succès associé à cette mission</div>';

                  // Lien vers les succès
                  echo '<a href="../profil/profil.php?view=success&action=goConsulter" class="lien_succes">Voir mes succès</a>';
                echo '</div>';
              echo '</div>';
            }

            // Informations mission
            echo '<div class="zone_details_mission">';
              // Titre
              echo '<div id="titre_infos_mission" class="titre_section">';
                echo '<img src="../../includes/icons/missions/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fleche">Informations</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
              echo '</div>';

              echo '<div id="afficher_infos_mission">';
                // Dates et horaires
                echo '<div class="zone_info_details_mission">';
                  echo '<img src="../../includes/icons/missions/date_grey.png" alt="date_grey" class="logo_details_mission" />';

                  echo '<div class="texte_info_details_mission">';
                    if ($detailsMission->getDate_deb() == $detailsMission->getDate_fin())
                      echo 'Evènement le <strong>' . formatDateForDisplay($detailsMission->getDate_deb()) . '</strong> à partir de <strong>' . formatTimeForDisplayLight($detailsMission->getHeure()) . '</strong>.';
                    else
                      echo 'Evènement du <strong>' . formatDateForDisplay($detailsMission->getDate_deb()) . '</strong> au <strong>' . formatDateForDisplay($detailsMission->getDate_fin()) . '</strong>, chaque jour à partir de <strong>' . formatTimeForDisplayLight($detailsMission->getHeure()) . '</strong>.';
                  echo '</div>';
                echo '</div>';

                // Objectif mission
                echo '<div class="zone_info_details_mission">';
                  echo '<img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_details_mission" />';

                  echo '<div class="texte_info_details_mission">';
                    echo formatExplanation($detailsMission->getExplications(), $detailsMission->getObjectif(), '%objectif%');
                  echo '</div>';
                echo '</div>';

                // Objectif du jour
                if (date('Ymd') <= $detailsMission->getDate_fin())
                {
                  echo '<div class="zone_info_details_mission">';
                    echo '<img src="../../includes/icons/missions/progress_day.png" alt="progress_day" class="logo_details_mission" />';

                    echo '<div class="texte_info_details_mission">';
                      echo 'Progression du jour :';
                    echo '</div>';

                    echo '<div class="barre_info_details_mission">';
                      echo '<div class="fond_avancement_mission"><div class="avancement_mission" style="width: ' . $missionUser['daily_percent'] . '%;"></div></div>';
                      echo '<div class="score_avancement_mission">' . $missionUser['daily'] . '</div>';
                    echo '</div>';
                  echo '</div>';
                }

                // Participation hors classement
                if (date('Ymd') > $detailsMission->getDate_fin() AND isset($participationUserNoRanking) AND $participationUserNoRanking == true)
                {
                  echo '<div class="zone_info_details_mission">';
                    echo '<img src="../../includes/icons/missions/user_grey.png" alt="user_grey" class="logo_details_mission" />';

                    echo '<div class="texte_info_details_mission">';
                      echo 'Vous ne faites pas partie du classement car vous avez changé d\'équipe.';
                    echo '</div>';
                  echo '</div>';
                }

                // Objectif total
                echo '<div class="zone_info_details_mission">';
                  echo '<img src="../../includes/icons/missions/progress_mission.png" alt="progress_mission" class="logo_details_mission" />';

                  echo '<div class="texte_info_details_mission">';
                    echo 'Progression totale :';
                  echo '</div>';

                  echo '<div class="barre_info_details_mission">';
                    echo '<div class="fond_avancement_mission"><div class="avancement_mission" style="width: ' . $missionUser['event_percent'] . '%;"></div></div>';
                    echo '<div class="score_avancement_mission">' . $missionUser['event'] . '</div>';
                  echo '</div>';
                echo '</div>';
              echo '</div>';
            echo '</div>';

            // Participants
            if (date('Ymd') <= $detailsMission->getDate_fin())
            {
              echo '<div class="zone_details_mission">';
                // Titre
                echo '<div id="titre_participants_mission" class="titre_section">';
                  echo '<img src="../../includes/icons/missions/users_grey.png" alt="users_grey" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Participants</div>';
                  echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                echo '</div>';

                echo '<div id="afficher_participants_mission">';
                  if (!empty($participants))
                  {
                    echo '<div class="zone_participants_details_missions">';
                      foreach ($participants as $participant)
                      {
                        echo '<div class="zone_avatar_details_mission">';
                          // Avatar
                          $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                          echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_details_mission" />';

                          // Pseudo
                          echo '<div class="pseudo_details_mission">' . formatString($participant->getPseudo(), 15) . '</div>';
                        echo '</div>';
                      }
                    echo '</div>';
                  }
                else
                  echo '<div class="empty">Personne n\'a encore participé à cette mission...</div>';
              echo '</div>';
            }
          }
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>