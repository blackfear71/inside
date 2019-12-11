<?php
  echo '<div class="zone_ideas_right">';
    switch ($_GET['view'])
    {
      case 'inprogress':
        echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées proposées</div></div>';
        break;

      case 'mine':
        echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées que j\'ai en charge</div></div>';
        break;

      case 'done':
        echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées terminées ou rejetées</div></div>';
        break;

      case 'all':
      default:
        echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Toutes les idées</div></div>';
        break;
    }

    if (!empty($listeIdeas))
    {
      echo '<div class="zone_ideas">';
        foreach ($listeIdeas as $idea)
        {
          echo '<div class="zone_idea">';
            echo '<div id="zone_shadow_' . $idea->getId() . '" class="zone_shadow">';
              // Titre
              echo '<div class="zone_idea_top" id="' . $idea->getId() . '">';
                echo '<div class="zone_idea_titre">';
                  echo $idea->getSubject();
                echo '</div>';
              echo '</div>';

              // Infos
              echo '<div class="zone_idea_middle">';
                // Avatar
                $avatarFormatted = formatAvatar($idea->getAvatar_a(), $idea->getPseudo_a(), 2, "avatar");

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_idea" />';

                // Pseudo
                if ($idea->getPseudo_a() == "Un ancien utilisateur")
                  echo '<div class="pseudo_idea"><i>' . $idea->getPseudo_a() . '</i></div>';
                else
                  echo '<div class="pseudo_idea">' . $idea->getPseudo_a() . '</div>';

                // Date
                echo '<div class="date_idea">';
                  echo '<img src="../../includes/icons/ideas/date.png" alt="date" class="icone_idea" />';
                  echo formatDateForDisplay($idea->getDate());
                echo '</div>';

                switch ($idea->getStatus())
                {
                  // Prise en charge
                  case 'C':
                    echo '<div class="idea_status idea_in_charge">Prise en charge</div>';
                    break;

                  // En progrès
                  case 'P':
                    echo '<div class="idea_status idea_in_progress">En cours de développement</div>';
                    break;

                  // Terminée
                  case 'D':
                    echo '<div class="idea_status idea_ended">Terminée</div>';
                    break;

                  // Rejetée
                  case 'R':
                    echo '<div class="idea_status idea_rejected">Rejetée</div>';
                    break;

                  // Ouverte
                  case 'O':
                  default:
                    echo '<div class="idea_status idea_proposed">Proposée</div>';
                    break;
                }
              echo '</div>';

              // Développeur
              if (!empty($idea->getDevelopper()))
              {
                switch ($idea->getStatus())
                {
                  // Prise en charge
                  case 'C':
                    echo '<div class="zone_idea_dev idea_in_charge">';
                    break;

                  // En progrès
                  case 'P':
                    echo '<div class="zone_idea_dev idea_in_progress">';
                    break;

                  // Terminée
                  case 'D':
                    echo '<div class="zone_idea_dev idea_ended">';
                    break;

                  // Rejetée
                  case 'R':
                    echo '<div class="zone_idea_dev idea_rejected">';
                    break;

                  // Ouverte
                  case 'O':
                  default:
                    echo '<div class="zone_idea_dev idea_proposed">';
                    break;
                }

                  // Avatar
                  $avatarFormatted = formatAvatar($idea->getAvatar_d(), $idea->getPseudo_d(), 2, "avatar");

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_idea" />';

                  // Pseudo
                  if ($idea->getPseudo_d() == "Un ancien utilisateur")
                    echo '<div class="pseudo_idea white"><i>' . $idea->getPseudo_d() . '</i></div>';
                  else
                    echo '<div class="pseudo_idea white">' . $idea->getPseudo_d() . '</div>';
                echo '</div>';
              }

              // Contenu
              echo '<div class="zone_idea_bottom">';
                echo '<div class="content_idea">' . nl2br($idea->getContent()) . '</div>';
              echo '</div>';

              // Actions
              if ( empty($idea->getDevelopper())
              OR (!empty($idea->getDevelopper()) AND $idea->getDevelopper() == $_SESSION['user']['identifiant'])
              OR (!empty($idea->getDevelopper()) AND $idea->getPseudo_d()   == "Un ancien utilisateur")
              OR  $idea->getStatus() == "D"
              OR  $idea->getStatus() == "R")
              {
                echo '<div class="zone_idea_actions">';
                // Boutons de prise en charge (disponibles si personne n'a pris en charge OU si le développeur est sur la page OU si l'idée est terminée / rejetée)
                  echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doChangerStatut" class="form_manage_idea">';
                    echo '<input type="hidden" name="id_idea" value="' . $idea->getId() . '" />';

                    switch ($idea->getStatus())
                    {
                      // Ouverte
                      case "O":
                        echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="saisie_bouton margin_button" />';
                        break;

                      // Prise en charge
                      case "C":
                        echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';

                        if ($idea->getPseudo_d() != "Un ancien utilisateur")
                        {
                          echo '<input type="submit" name="developp" value="Développer" title="Commencer les développements" class="saisie_bouton margin_button" />';
                          echo '<input type="submit" name="reject" value="Rejeter" title="Annuler l\'idée" class="saisie_bouton margin_button" />';
                        }
                        break;

                      // En progrès
                      case "P":
                        echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';

                        if ($idea->getPseudo_d() != "Un ancien utilisateur")
                        {
                          echo '<input type="submit" name="take" value="Remise à prise en charge" title="Remettre à prise en charge" class="saisie_bouton margin_button" />';
                          echo '<input type="submit" name="end" value="Terminer" title="Finaliser l\'idée" class="saisie_bouton margin_button" />';
                        }
                        break;

                      // Terminée
                      case "D":
                        echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';
                        break;

                      // Rejetée
                      case "R":
                        echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';
                        break;

                      default:
                        break;
                    }
                  echo '</form>';
                echo '</div>';
              }
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
    {
      switch ($_GET['view'])
      {
        case 'inprogress':
          echo '<div class="empty">Pas d\'idées proposées</div>';
          break;

        case 'mine':
          echo '<div class="empty">Pas d\'idées en charge</div>';
          break;

        case 'done':
          echo '<div class="empty">Pas d\'idées terminées ou rejetées</div>';
          break;

        case 'all':
        default:
          echo '<div class="empty">Pas d\'idées proposées</div>';
          break;
      }
    }
  echo '</div>';
?>
