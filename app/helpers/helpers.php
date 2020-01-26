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
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

	return $results = ["OUTPUT" => "logout"];
}
