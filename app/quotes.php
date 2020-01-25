<?php

// This namespace groups all the method for the Quotes app
namespace Quotes {
	/**
	 * This method show the quotes one by one
	 */
	function showQuotes() {		
		global $_;

		return [
			"MAIN_CONTENT" => $_("inject: app/assets/quotes/show.html"),
			"TEXT" => "Quote text",
			"IMAGE" => "Quote image"
		];
	}

	/**
	 * View with the login form
	 */
	function signInQuotes() {
		global $_;

		return ["MAIN_CONTENT" => "signInQuotes"];
	}

	/**
	 * View for new user registration
	 */
	function signupQuotes() {
		global $_;

		return ["MAIN_CONTENT" => "signupQuotes"];
	}

	/**
	 * Logout
	 */
	function logoutQuotes() {
		// Destroy the variables an session cookies and later the session itself
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
		header("Location: /my2cents/quotes/");
	}

	/**
	 * Enforces security
	 */
	function enforceQuotes() {
		if (empty($_SESSION['api_key'])) {
			header("Location: /my2cents/quotes/signin");
		}
	}

	/**
	 * View with the list of quotes
	 */
	function dashboardQuotes() {
		global $_;

		return ["MAIN_CONTENT" => "dashboardQuotes"];
	}

	/**
	 * View to edit a quote
	 */
	function editQuotes() {
		global $_;

		return ["MAIN_CONTENT" => "editQuotes"];
	}

	/**
	 * View to add a quote
	 */
	function newQuotes() {
		global $_;
		
		return ["MAIN_CONTENT" => "newQuotes"];
	}
}