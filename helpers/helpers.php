<?php

/**
 * 404 page
 */
function notFound () 
{
	global $_;
	
	$results = ["OUTPUT" => "404"];
	
	echo $_("render", $results);
}

/**
 * Login 
 */
function login () 
{
	$_SESSION['id'] = 1;
	
	global $_;
	
	$results = ["OUTPUT" => "login"];
	
	echo $_("render", $results);
}

/**
 * Enforce the login
 */
function enforce () 
{
	if (empty($_SESSION['id'])) {
		die("nop");
	}
}

/**
 * Log out
 */
function logout () 
{
	$_SESSION['id'] = 0;
	
	global $_;
	
	$results = ["OUTPUT" => "logout"];
	
	echo $_("render", $results);
}
