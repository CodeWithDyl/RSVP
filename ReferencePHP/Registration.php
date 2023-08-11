<?php
include_once("../dbaccess_NewDB.php");

# Make sure there is a record that corresponds to the RecordID. If not, stop the script.
if (empty($_REQUEST['id'])) {
	die('<div style="width:35%; margin:0 auto;"><p style= "background: black; text-align:center; font-size: 4em; color: white;">The record doesn\'t exist.</p></div>');
}

# Find the record using getRecordByID using the id passed in the URL
$record = $fm->getRecordById('PHP Events', $_REQUEST['id']);
$InviteCount = $record->getField('events_ENTITIESEVENTSJOIN::s_InvitedCount');
$Deadline = $record->getField('Deadline');
$RegistrationTemplate = $record->getField('RegistrationTemplate');
$RegistrationLimit = $record->getField('RegistrationLimit');

# Check for invite count limit reached and deadline passed
if (date("y-m-d", strtotime($Deadline)) < date("y-m-d") || $Deadline == "") {
	header('location: https://president-events.louisville.edu/oop/Events/RegistrationDeadline.php?id=' . $_REQUEST['id'] . '&type=deadline');
} else if (!empty($RegistrationLimit) && $InviteCount >= $RegistrationLimit) {
	header('location: https://president-events.louisville.edu/oop/Events/RegistrationDeadline.php?id=' . $_REQUEST['id'] . '&type=full');
}

$RecordID = $record->getField('RecordID');
$EventName = $record->getField('EventName');
$EventDate = $record->getField('EventDate');
$EventDate = (strtotime($EventDate) == false ? '' : date('l, F jS, Y', strtotime($EventDate)));
$StartTime = $record->getField('EventStartTime');
$StartTime = (strtotime($StartTime) == false ? '' : date('g:i A', strtotime($StartTime)));
$EndTime = $record->getField('EventEndTime');
$EndTime = (strtotime($EndTime) == false ? '' : date('g:i A', strtotime($EndTime)));
if ($EndTime != "") {
	$EventTime = $StartTime . " - " . $EndTime;
} else {
	$EventTime = $StartTime;
}

$EventLoc = $record->getField('Location');
$Max_RSVP = $record->getField('MaxRSVP');

switch ($RegistrationTemplate){
	case "Commencement":
		include ("./Registration_Commencement.php");
		break;
		
	default:
		include ("./Registration_NewDB.php");
		break;
}
?>