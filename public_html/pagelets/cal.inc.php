<?php
//get current date
$monthname = date('F');
$year = date('Y');
$monthnum = date('m');
$day = date('d');

//check for $_GET month and year
if (isset($_GET['year'])) {
    $year = (int)escape_data($_GET['year']);
}
if (isset($_GET['month'])) {
    $monthnum = (int)escape_data($_GET['month']);
    $monthname = date("F", mktime(0, 0, 0, $monthnum, 1, $year));
}

//num of days in month
$numdays = cal_days_in_month(CAL_GREGORIAN, $monthnum, $year);
//day of the week the first of the month lands on
$monthstart = mktime(0,0,0, $monthnum, 1, $year);
$firstdayweek = date('w', $monthstart);
//maybe on some planets the weeks are longer?
$daysperweek = 7;

//how many blank days at start of month
$daystostart = $firstdayweek;

//make link values to previous and next month
if ($monthnum > 1) {
    $ltmonth = $monthnum - 1;
    $ltyear = $year;
} else {
    $ltmonth = 12;
    $ltyear = $year - 1;
}
if ($monthnum > 11) {
    $gtmonth = 1;
    $gtyear = $year + 1;
} else {
    $gtmonth = $monthnum + 1;
    $gtyear = $year;
}

//get the events for the month from the database and store in an array
$start = toDateTime("$monthnum/01/$year", "00", "00", "AM");
$startdate = $start->format('Y-m-d H:i:s');
$end = toDateTime("$monthnum/$numdays/$year", "11", "59", "PM");
$enddate = $end->format('Y-m-d H:i:s');
$result = getEventsBetween($startdate, $enddate);
$eventarray = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $evdate = date('j', strtotime($row['start']));
    $evid = $row['eventID'];
    $eventarray[$evdate][$evid] = $row;
}

//start displaying calendar
?>

<div class="calendar">
    <h3>
        <?php 
        //display links to < or > and month for calendar
        echo "<span class='huge rpadbig'><a href='?page=cal&amp;month=$ltmonth&amp;year=$ltyear'>&lt;</a></span> ";
        echo $monthname . ' ' . $year; 
        echo " <span class='huge lpadbig'><a href='?page=cal&amp;month=$gtmonth&amp;year=$gtyear'>&gt;</a></span>";
    
    ?></h3>
    <table class="calendar">
        <tr>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        <tr>
        <?php 
        $weekdaycount = 1;  //counter for day of week
        $monthday = 1; //start at day 1
        //blank days at start of calendar
        while ($daystostart > 0) {
            echo "<td></td>";
            $daystostart--;
            $weekdaycount++;
        }
        //create the calendar days
        while ($monthday <= $numdays) {
            echo "<td> ";
            echo "$monthday ";
            //check for events on each day
            if (array_key_exists($monthday, $eventarray)) {
                foreach ($eventarray[$monthday] as $eventid => $event) {
                    //display events on the day
                    $eventname = $event['title'];
                    $eventtime = date('g:ia', strtotime($event['start']));
                    echo "<p>$eventtime <a href='?page=event&amp;id=$eventid'>$eventname</a><p>";
                }
            }
            echo "</td>";
            $monthday++;
            $weekdaycount++;
            //move to new line if it's saturday
            if ($weekdaycount > $daysperweek) {
                echo "</tr><tr>";
                $weekdaycount = 1;
            }
        }
        //blank days at end of calendar
        while ($weekdaycount > 1 && $weekdaycount <= $daysperweek) {
            echo "<td></td>";
            $weekdaycount++;
        }
        ?>
        </tr>
    </table>

</div>