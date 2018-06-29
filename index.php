<?php

session_start();

require_once('$_.php');

// JUST FOR TESTING	
date_default_timezone_set('America/Los_Angeles');

// THE MAIN MVC CLASS
class MVC extends Controller {
	/*
	* Set the language from the REQUEST
	*/
	public static function preProcess() {
		// if (! empty($_REQUEST['lan']) && in_array($_REQUEST['lan'], ['en', 'es'])) 
		//	$_SESSION['LANGUAGE_IN_USE'] = $_REQUEST['lan'];
	}
}

// PREVIOUS PROCESSING
MVC::preProcess();

// LOAD THE CONFIGURATION
MVC::config('../config.json');

// ROUTE THE PAGE
MVC::route();

// CHECK IF THE LAYOUT IS ENABLED
if (View::isEnabled()) {
	// APPLY THE VALUES TO THE LAYOUT
	View::applyLayout();

	// SHOW THE RESULT
	View::echoLayout();
}