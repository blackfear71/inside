<?php
    /***************/
    /* Calendriers */
    /***************/
    echo '<div class="zone_calendars">';
        // Titre
        echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les calendriers</div>';
        echo '</div>';

        if (!empty($calendriers))
        {
            $i = 0;

            echo '<div class="zone_calendriers">';
                foreach ($calendriers as $calendrier)
                {
                    if ($i % 2 == 0)
                        echo '<div class="zone_calendrier zone_calendrier_margin">';
                    else
                        echo '<div class="zone_calendrier">';
                        // Image
                        echo '<img src="../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="' . $calendrier->getTitle() . '" title="' . $calendrier->getTitle() . '" class="calendrier" />';

                        // Nom
                        echo '<div class="titre_calendrier">' . $calendrier->getTitle() . '</div>';

                        // Boutons
                        echo '<div class="zone_boutons">';
                            // Télécharger
                            echo '<a href="../../includes/images/calendars/' . $calendrier->getYear() . '/' . $calendrier->getCalendar() . '" class="download_calendar" download><img src="../../includes/icons/calendars/download_grey.png" alt="download_grey" title="Télécharger" class="download_icon" /></a>';

                            // Supprimer
                            if ($preferences->getManage_calendars() == 'Y')
                            {
                                echo '<form id="delete_calendar_' . $calendrier->getId() . '" method="post" action="calendars.php?year=' . $_GET['year'] . '&action=doSupprimerCalendrier" class="delete_calendar">';
                                    echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
                                    echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
                                    echo '<input type="submit" name="delete_calendar" value="" title="Supprimer le calendrier" class="delete_calendar_icon eventConfirm" />';
                                    echo '<input type="hidden" value="Demander la suppression de ce calendrier ?" class="eventMessage" />';
                                echo '</form>';
                            }
                        echo '</div>';
                    echo '</div>';

                    $i++;
                }
            echo '</div>';
        }
        else
            echo '<div class="empty">Pas de calendriers pour cette année...</div>';
    echo '</div>';
?>