<?php 
//get past or upcoming variable from url
if (isset($_GET['past'])) {
    $past = (bool)(escape_data($_GET['past']));
} else {
    $past = false;
}

$pastlink = $past ? "&amp;past=true" : "";

//orderby for sorting (not yet implmented)
if (isset($_GET['orderby'])) {
    $orderby = escape_data($_GET['orderby']);
} else {
    $orderby = '`start`';
}

//get events from database
$result = getEventList($past, $orderby);
$numrows = mysqli_num_rows($result);
?>

<div class="results">
    
    <?php 
    if ($numrows > 0) {
        ?>
    <table>
        <tr>
            <th><?php echo "<a href='?page=$page&amp;past=$past&amp;orderby=title'>Event</a>"; ?></th>
            <th><?php echo "<a href='?page=$page&amp;past=$past&amp;orderby=start'>When</a>"; ?></th>
            <th>RSVPs</th>
            <th><?php echo "<a href='?page=$page&amp;past=$past&amp;orderby=locationname'>Location</a>"; ?></th>
            <?php if ($rank >= COORDINATOR_RANK) { echo "<th></th><th></th>"; } ?>
        </tr>
            <?php
            $canceltext = $past ? "Delete" : "Cancel";
            $bg = 'whitebg'; //for alternating bg color
            //display events
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $bg = ($bg =='ltgreybg' ? 'whitebg' : 'ltgreybg');
                $eventid = $row['eventID'];
                $going = countGuests($eventid, "YES");
                $going = $going > 0 ? $going : 0;
                $maybe = countGuests($eventid, "MAYBE");
                $maybe = $maybe > 0 ? $maybe : 0;
                $no = countGuests($eventid, "NO");
                $no = $no > 0 ? $no : 0;
                echo '<tr class="' . $bg . '"><td>'
                    . '<a href="?page=event&amp;id='
                    . $eventid 
                    . $pastlink
                    . '">'
                    . $row['title']
                    . "</a>"
                    . "</td><td>"
                    . verbose_date($row['start'])
                    . "</td><td>";
                echo "$going yes, $maybe maybe, $no no.</td><td>";
                if ($rank > 1) {  //members+ can see the location
                    echo $row['locationname']; 
                } else if ($rank < 1) {  
                    echo "Banned! You may not view locations.";
                } else {
                    echo "Sign in to see location.";
                }
                    echo "</td>";
                if ($rank >= COORDINATOR_RANK) {  //coordinators can see the edit and cancel buttons
                    if (!$past) {
                        echo "<td><a class='button' href='?page=createevent&amp;id=";
                        echo $row['eventID'];
                        echo "'>Edit</a></td>";
                    }
                    echo "<td><a class='button' href='?page=cancelevent&amp;id=";
                    echo $row['eventID'];
                    echo "'>$canceltext</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    } else {
        echo "<p>No events to list.</p>";
    }

?>
    
</div>