<div id="menubar">
    <div id="menubutton">
        <img class="smallmenubtn" src="images/navbutton.png" alt="Navigation Button - Click here to reveal navigation." />
    </div>
    <div id="menucontent">
        <ul>
            <li><a href="?page=eventlist&amp;past=0"><?php echo constant("EVENTLIST"); ?></a></li>
            <li><a href="?page=eventlist&amp;past=1"><?php echo constant("PASTEVENTS"); ?></a></li>
            <li><a href="?page=cal"><?php echo constant("CAL"); ?></a></li>
            <li><a href="?page=members"><?php echo constant("MEMBERS"); ?></a></li>
            <?php 
            if ($loggedin) {
                if ($rank >= COORDINATOR_RANK) {
                   ?><li><a href="?page=createevent"><?php echo constant("ADDEVENT"); ?></a></li><?php
                }
                if ($rank >= ADMIN_RANK) {
                   ?><li><a href="?page=managelocations"><?php echo constant("MANAGELOCS"); ?></a></li><?php
                }
            }
            ?>
        </ul>
    </div>
</div>
