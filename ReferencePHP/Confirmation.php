<?php
	if($_SERVER["HTTPS"] != "on")	//Force redirect to HTTPS
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
    include ("./dbaccess_NewDB.php");
    include ("./OSandBrowserDetection.php");
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    
    $message = "";


##########################################################################################################
###############     Update the guest's record based on their input from the RSVP page      ###############
##########################################################################################################
    if (isset($_POST['submit']) and $_POST['submit'] == 'Submit')
    {
        $edit = $fm->newEditCommand('PHP Guest List', $_REQUEST['id']);

        # Create TimeStamp to show when this RSVP was submitted
        $timestamp = new DateTime();
        $timestamp = $timestamp->format('m/d/Y g:i A');
        $edit->setField('TimeStamp_RSVP', $timestamp);

        # Set the fields with the values from the $_POST superglobal
        if(isset($_POST['rsvp'])) $edit->setField('RSVP' , $_POST['rsvp']);
        if(isset($_POST['Guest'])) $edit->setField('GuestName', $_POST['Guest']);
        if(isset($_POST['party'])) $edit->setField('Party', $_POST['party']);
        if(isset($_POST['transport']))  $edit->setField('Notes', $_POST['transport']);

        // For Commencement
        if(isset($_POST['attendance'])) $edit->setField('AttendCommencementOptions', $_POST['attendance']);
        if(isset($_POST['schoolCollege'])) $edit->setField('School', $_POST['schoolCollege']);
        if(isset($_POST['department'])) $edit->setField('Department', $_POST['department']);
        isset($_POST['apparel']) ? $edit->setField('CommencementApparelOrder', $_POST['apparel']) : $edit->setField('CommencementApparelOrder' , "");
        isset($_POST['height']) ? $edit->setField('Height', $_POST['height']) : $edit->setField('Height', "");
        isset($_POST['weight']) ? $edit->setField('Weight', $_POST['weight']) : $edit->setField('Weight', "");
        isset($_POST['degree']) ? $edit->setField('HighestDegree', $_POST['degree']) : $edit->setField('HighestDegree', "");
        isset($_POST['discipline']) ? $edit->setField('Discipline', $_POST['discipline']) : $edit->setField('Discipline', "");
        isset($_POST['institution']) ? $edit->setField('Institution', $_POST['institution']) : $edit->setField('Institution', "");
        isset($_POST['institutionAddress']) ? $edit->setField('InstitutionAddress', $_POST['institutionAddress']) : $edit->setField('InstitutionAddress', "");

        if(isset($user_os)) $edit->setField('OSversion', $user_os);
        if(isset($user_browser)) $edit->setField('Browser', $user_browser);
        if(isset($user_ip)) $edit->setField('IPAddress', $user_ip);
        $edit->setField('OSbrowser_Regex', $user_agent);

        # Execute the newEditCommand
        $result = $edit->execute();

// echo '<pre>';
// var_dump($result);
// echo '</pre>';

		if (FileMaker::isError($result)){

            // echo "Problem saving record";
            // echo "<p>Error" . $result->getMessage() . "</p>";

		}

        # Update the message variable to tell the user the record has been saved.
        $message = 'Your changes have been saved';
    }
##########################################################################################################

    # Make sure there is a record that corresponds to the RecordID. If not, stop the script.
    if (empty($_REQUEST['id'])) {
        die('The record no longer exists.');
    }

###############################################################################################
###############     Get Event and Guest info after updating the record      ###################
###############################################################################################
    $record = $fm->getRecordById('PHP Guest List', $_REQUEST['id']);

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
    $Deadline = $record->getField('enevjoin_EVENTS::Deadline');
    $Template = $record->getField('enevjoin_EVENTS::InviteTemplate');
    
    # Create variables to hold record information from the Guest
    $rsvp = $record -> getField('RSVP');
    $LastName = $record -> getField('LastName');
    $FirstName = $record -> getField('FirstName');
    $Guest = $record -> getField('GuestName');
    $Email = $record -> getField('Email');
    $PartyInvite = $record -> getField('PartyInvite');
    $Party = $record -> getField('Party');
    $QRcode = $record -> getField('QRcode');
    $Transportation = $record -> getField('Notes');
    $InvitedVia = $record -> getField('InvitedVia');
    $InviteOptions = $record->getField('InviteOptions');

###############################################################################################


    if (date("y-m-d", strtotime($Deadline)) < date("y-m-d") || $Deadline == "") {
        die('<div style="width:35%; margin:0 auto;"><p style= "background: black; text-align:center; font-size: 4em; color: white;">The deadline has passed.</p></div>');
    }

	switch ($EventType){
		case "Basketball":
			include ("./Confirmations/Basketball.php");
            break;
        case "Football":
			include ("./Confirmations/Football.php");
			break;
		case "Special":
            if($Template == "Commencement"){
                include ("./Confirmations/Commencement.php");
                break;
            }
            else if($Template == "Amelia Place"){
                include ("./Confirmations/AmeliaPlace.php");
                break;
            }
			else{
                include ("./Confirmations/Special.php");
                break;
            }
		case "BasketballTest":
			include ("./Confirmations/Basketball.php");
            break;
    	case "FootballTest":
			include ("./Confirmations/Football.php");
            break;
        case "SpecialTest":
			include ("./Confirmations/Special.php");
            break;
	}
?>