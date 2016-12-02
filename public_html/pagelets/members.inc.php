<div class="results">
<?php 
if ($rank >= MEMBER_RANK) {
    

//sorting not implemented (might do it later)
if (isset($_GET['orderby'])) {
    $orderby = escape_data($_GET['orderby']);
} else {
    $orderby = "displayname";
}

//get members from db
$result = getMembers($orderby);
$numrows = mysqli_num_rows($result);
if ($numrows > 0) {     ?>
    <table>
        <tr>
            <th><?php echo "<a href='?page=$page&amp;orderby=displayname'>Member</a>"; ?></th>
            <th><?php echo "<a href='?page=$page&amp;orderby=rolename'>Role</a>"; ?></th>
            <?php if ($rank >= ADMIN_RANK) { echo "<th></th>"; } ?>
        </tr>
        <?php
        $bg = 'whitebg'; //for alternating bg color
        //display members
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $bg = ($bg =='ltgreybg' ? 'whitebg' : 'ltgreybg');
            echo '<tr class="' . $bg . '"><td>'
                . '<a href="?page=profile&amp;mid='
                . $row['profileID'] 
                . '">'
                . $row['displayname']
                . "</a>"
                . "</td><td>"
                . $row['rolename']
                . "</td>";
            //admins can edit member role
            if ($rank >= ADMIN_RANK) {
                //show edit role button
                echo '<td><a class="button" href="?page=editmemberaccess&amp;mid='
                . $row['profileID']
                . '">Edit Role</a></td>';
            }
            echo "</tr>";
        }
        ?>
    </table>
    <?php
} else {
    echo "<p>No members are currently registered.</p>";

}
} else {
    echo "<p>You must be signed in to view members.</p>";
}
?>
  
</div>
