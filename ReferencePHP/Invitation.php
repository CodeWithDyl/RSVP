<?php
	include ("./dbaccess_NewDB.php");

	$message = "";

	if (empty($_GET['id'])) {
		die('The record no longer exists.');
	}

	$record = $fm -> getRecordById('PHP Guest List', $_GET['id']);
	if(FileMaker::isError($record)){
		die('Error - ' . $record->getCode() . ' ' . $record->getMessage());
	}

    # Get data for Formal, Informal, and Standard greetings for them
	$Formal = ($record -> getField('enevjoin_ENTITIES::FormalSalutation') != '' ? $record -> getField('enevjoin_ENTITIES::FormalSalutation') : 'you');
	$Standard = ($record -> getField('enevjoin_ENTITIES::StandardSalutation') != '' ? $record -> getField('enevjoin_ENTITIES::StandardSalutation') : 'you');
	$Informal = ($record -> getField('enevjoin_ENTITIES::InformalSalutation') != '' ? $record -> getField('enevjoin_ENTITIES::InformalSalutation') : 'you');

	$EventName = $record -> getField('enevjoin_EVENTS::EventName');
	$EventType = $record -> getField('enevjoin_EVENTS::EventType');
	$EventDate = $record -> getField('enevjoin_EVENTS::EventDate');
	$EventDate = (strtotime($EventDate) == false ? '' : date('l, F jS, Y', strtotime($EventDate)));
	$EventTime = $record -> getField('enevjoin_EVENTS::EventStartTime');
	$EventTime = (strtotime($EventTime) == false ? '' : date('g:i A', strtotime($EventTime)));
	$EventLocation = $record -> getField('enevjoin_EVENTS::Location');
	$Deadline = $record -> getField('enevjoin_EVENTS::Deadline');
	$Deadline = (strtotime($Deadline) == false ? '' : date('F jS', strtotime($Deadline)));
	$Pre_game = $record -> getField('enevjoin_EVENTS::Pregame');
	$Pre_game = (strtotime($Pre_game) == false ? 'TBA' : date('g:i A', strtotime($Pre_game)));
	$InviteTemplate = $record -> getField('enevjoin_EVENTS::InviteTemplate');

	# Create variables to hold record information from the Guest
	$LastName = $record -> getField('LastName');
	$FirstName = $record -> getField('FirstName');
	$RecordID = $record -> getField('RecordID');
	$PartyInvite = $record -> getField('PartyInvite');
	$InviteOptions = $record -> getField('InviteOptions');
	$InvitedVia = $record -> getField('InvitedVia');

    # Block variables are used for the content of the invitation so that content can be changed from within FileMaker
    $InviteCSS = $record -> getField('enevjoin_EVENTS::InviteCSS');
	$BlockHeader = ($record -> getField('enevjoin_EVENTS::BlockHeader') != '' ? $record -> getField('enevjoin_EVENTS::BlockHeader') : "");
	$BlockInviteFrom = ($record -> getField('enevjoin_EVENTS::BlockInviteFrom') != '' ? $record -> getField('enevjoin_EVENTS::BlockInviteFrom') : "");
	$BlockEvent = ($record -> getField('enevjoin_EVENTS::BlockEvent') != '' ? $record -> getField('enevjoin_EVENTS::BlockEvent') : "");
	$BlockDate = ($record -> getField('enevjoin_EVENTS::BlockDate') != '' ? $record -> getField('enevjoin_EVENTS::BlockDate') : "");
	$BlockTime = ($record -> getField('enevjoin_EVENTS::BlockTime') != '' ? $record -> getField('enevjoin_EVENTS::BlockTime') : "");
	$BlockPreGame = ($record -> getField('enevjoin_EVENTS::BlockPreGame') != '' ? $record -> getField('enevjoin_EVENTS::BlockPreGame') : "");
	$BlockLocation = ($record -> getField('enevjoin_EVENTS::BlockLocation') != '' ? $record -> getField('enevjoin_EVENTS::BlockLocation') : "");
	$BlockRSVP = ($record -> getField('enevjoin_EVENTS::BlockRSVP') != '' ? $record -> getField('enevjoin_EVENTS::BlockRSVP') : "");
	$BlockAttire = ($record -> getField('enevjoin_EVENTS::BlockAttire') != '' ?$record -> getField('enevjoin_EVENTS::BlockAttire') : "");

    $replace = array('$FirstName' => $FirstName, '$LastName' => $LastName, '$EventName' => $EventName, '$EventType' => $EventType, '$EventLocation' => $EventLocation, '$EventTime' => 
    $EventTime, '$Pre_game' => $Pre_game, '$Deadline' => $Deadline, '$EventDate' => $EventDate, '$Formal' => $Formal, '$Informal' => $Informal, '$Standard' => $Standard, '$RecordID' => $_GET['id']);	

    function strReplaceAssoc(array $replace, $subject) { 
		return str_replace(array_keys($replace), array_values($replace), $subject);
	}

	switch ($InviteTemplate){
		case "Standard":
			include ("./Invites/Standard.php");
			break;
		case "Special":
			include ("./Invites/Special.php");
			break;
		case "Standard Sample":
			include ("./Invites/StandardSample.php");
			break;
		case "Special Sample":
			include ("./Invites/SpecialSample.php");
			break;
		case "Registration":
			include("./Invites/Registration.php");
			break;
		case "Commencement":
			include("./Invites/Commencement.php");
			break;
		case "Amelia Place":
			include("./Invites/Special.php");
			break;
	}


?>