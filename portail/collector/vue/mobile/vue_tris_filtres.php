<?php
    // Tris
    echo '<select id="applySort" class="listbox_tri_filtre">';
        foreach ($ordersAndFilters['tris'] as $tri)
        {
            if ($_GET['sort'] == $tri['value'])
                echo '<option value="' . $tri['value'] . '" selected>' . $tri['label'] . '</option>';
            else
                echo '<option value="' . $tri['value'] . '">' . $tri['label'] . '</option>';
        }
    echo '</select>';

    // Filtres
    echo '<select id="applyFilter" class="listbox_tri_filtre">';
        foreach ($ordersAndFilters['filtres'] as $filtre)
        {
            if ($_GET['filter'] == $filtre['value'])
                echo '<option value="' . $filtre['value'] . '" selected>' . $filtre['label'] . '</option>';
            else
                echo '<option value="' . $filtre['value'] . '">' . $filtre['label'] . '</option>';
        }
    echo '</select>';
?>