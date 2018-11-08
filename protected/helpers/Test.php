<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include "calendar.php";

$startDate =   $date = mktime(0, 0, 0, 9,30, date("Y"));
$startDate = date('d/m/Y', $startDate);
$endDate = $date = mktime(0, 0, 0, 10, 15, date("Y"));
$endDate = date('d/m/Y', $endDate);



Calendar::getLiveInMissionSlots($startDate, $endDate)

?>
