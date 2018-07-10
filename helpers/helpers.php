<?php

/**
 * 404 page
 */
function notFound () 
{
	global $_;
	
	$results = ["OUTPUT" => "404"];
	
	$_("render", $results);
}

/**
 * Login 
 */
function login () 
{
	$_SESSION['id'] = 1;
	
	global $_;
	
	$results = ["OUTPUT" => "login"];
	
	$_("render", $results);
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
	
	$_("render", $results);
}

