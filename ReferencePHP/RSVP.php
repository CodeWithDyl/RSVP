<?php
	if($_SERVER["HTTPS"] != "on")	//Force redirect to HTTPS
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
	
    include ("./dbaccess_NewDB.php");
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    
    $layout = &$fm -> getLayout('PHP Guest List');

	# Make sure there is a record that corresponds to the RecordID. If not, stop the script.
	if (empty($_REQUEST['id'])) {
		//include ("./RSVPs/Basketball.php");
		die('<div style="width:35%; margin:0 auto;"><p style= "background: black; text-align:center; font-size: 4em; color: white;">The record doesn\'t exist.</p></div>');
	}
	
	# Find the record using getRecordByID using the id passed in the URL
	$record = $fm -> getRecordById('PHP Guest List', $_REQUEST['id']);

    $Deadline = $record -> getField('enevjoin_EVENTS::Deadline');
	if (date("y-m-d", strtotime($Deadline)) < date("y-m-d") || $Deadline == "") {
		//include ("./RSVPs/Basketball.php");
		//die('<div style="width:35%; margin:0 auto;"><p style= "background: black; text-align:center; font-size: 4em; color: white;">The deadline has passed.</p></div>');
		header('location: https://president-events.louisville.edu/oop/Events/ErrorPage.php');
	}
    
	$EventName = $record -> getField('enevjoin_EVENTS::EventName');
	$EventType = $record -> getField('enevjoin_EVENTS::EventType');
	$EventDate = $record -> getField('enevjoin_EVENTS::EventDate');
	$EventDate = (strtotime($EventDate) == false ? '' : date('l, F jS, Y', strtotime($EventDate)));
	$EventTime = $record -> getField('enevjoin_EVENTS::EventStartTime');
	$EventTime = (strtotime($EventTime) == false ? '' : date('g:i A', strtotime($EventTime)));
	$EventLoc = $record -> getField('enevjoin_EVENTS::Location');
	$Pre_game = $record -> getField('enevjoin_EVENTS::Pregame');
	$Pre_game = (strtotime($Pre_game) == false ? 'TBA' : date('g:i A', strtotime($Pre_game)));
	$Max_RSVP = $record -> getField('enevjoin_EVENTS::MaxRSVP');
	$Template = $record->getField('enevjoin_EVENTS::InviteTemplate');

	# Create variables to hold record information from the Guest
	$rsvp = $record -> getField('RSVP');
	$LastName = $record -> getField('LastName');
	$FirstName = $record -> getField('FirstName');
	$Guest = $record -> getField('GuestName');
	$Email = $record -> getField('Email');
	$PartyInvite = $record -> getField('PartyInvite');
	$Party = $record -> getField('Party');
	$InvitedVia = $record -> getField('InvitedVia');
	$InviteOptions = $record -> getField('InviteOptions');

	$formAction = "https://president-events.louisville.edu/oop/Events/Confirmation.php";

	switch ($EventType){
		case "Basketball":
			include ("./RSVPs/Basketball.php");
            break;
        case "Football":
			include ("./RSVPs/Football.php");
			break;
		case "Special":
			if($Template == "Commencement"){
				if($_REQUEST['id'] == ""){
					include ("./RSVPs/Commencementtest.php");
				}
				else{
					include ("./RSVPs/Commencement.php");
				}
				
				break;
			}
			else if($Template == "Registration"){
				include ("./RSVPs/Registration.php");
			}
			else{
				include ("./RSVPs/Special.php");
				break;
			}
	}
?>