<?php

/**
 * 404 page
 */
function notFound () 
{
	global $_;
	
	return $results = ["OUTPUT" => "404"];	
}

/**
 * Login 
 */
function login () 
{
	$_SESSION['id'] = 1;
	
	return $results = ["OUTPUT" => "login"];
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
	
	return $results = ["OUTPUT" => "logout"];
}
