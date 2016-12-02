<?php 
if ($rank >= ADMIN_RANK) { ?>

    <div class="results">
        <p><a class="button" href="?page=addlocation">Add New Location</a></p>
    
<?php 

    //get sort order (not implemented)
    if (isset($_GET['orderby'])) {
        $orderby = escape_data($_GET['orderby']);
    } else {
        $orderby = "locationname";
    }
    //get locations from db
    //locations can be added but not deleted so that past event still show where they were held
    //locations can be set to inactive so that new events can't be created there
    $result = getLocations($orderby);
    $numrows = mysqli_num_rows($result);
    if ($numrows > 0) {
        ?>
          <table>
              <tr>
                  <th><?php echo "<a href='?page=$page&amp;orderby=locationname'>Venue Name</a>"; ?></th>
                  <th><?php echo "<a href='?page=$page&amp;orderby=city'>City</a>"; ?></th>
                  <th><?php echo "<a href='?page=$page&amp;orderby=state'>State</a>"; ?></th>
                  <th><?php echo "<a href='?page=$page&amp;orderby=allownewevents'>Active</a>"; ?></th>
                  <th></th>
                  <th></th>
              </tr>
            <?php
            $bg = 'whitebg'; //for alternating bg color
            //show all locations
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $bg = ($bg =='ltgreybg' ? 'whitebg' : 'ltgreybg');
                $active = $row['allownewevents'] == 1 ? "Yes" : "No";
                echo '<tr class="' . $bg . '"><td>'
                    . $row['locationname']
                    . "</td><td>"
                    . $row['city']
                    . "</td><td>"
                    . $row['state']
                    . "</td><td>"
                    . $active
                    . "</td><td>"
                    . '<a class="button" href="?page=addlocation&amp;lid='
                    . $row['locationid']
                    . '">Edit</a>'
                    . "</td>";
                if ($row['allownewevents'] == 1) {
                    echo '<td><a class="button" href="responders/allowlocation.php?allow=0&amp;lid='
                    . $row['locationid']
                    . '">Deactivate</a></td>';
                } else {
                    echo '<td><a class="button" href="responders/allowlocation.php?allow=1&amp;lid='
                    . $row['locationid']
                    . '">Reactivate</a></td>';
                }
                echo "</tr>";
            }
            ?>
        </table>
        <?php
    } else {
        echo "<p>No locations found.</p>";

    }
} else {
    echo "<p>You are not authorized to perform this function.</p>";
}

?>
  
</div>

