<?php

// This namespace groups all the method for the Quotes app
namespace Quotes {
	/**
	 * This method show the quotes one by one
	 */
	function showQuotes($args) {
		global $_;

        if (isset($args['g-recaptcha-response'])) {
            $output = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdgjdIUAAAAAPrA3yoyaZivGerl5a_0tA-59KvG&response=" . $args['g-recaptcha-response']), true);
            error_log(print_r($output, true));
            if (isset($output['success']) && $output['success'] == true) {
                error_log("yep");
            }
        }

		$css = $_("inject: app/assets/quotes/quotes.css");
        $navbar = $_("inject: app/assets/quotes/navbar.html");
		$show = $_("inject: app/assets/quotes/show.html");
		$github = $_("inject: app/assets/quotes/github.html");
        $contact = $_("inject: app/assets/quotes/contact.html");
		$javascript = $_("inject: app/assets/quotes/checks.js");

		$quotes = $_("assoclist: SELECT * FROM quotes WHERE user = 1");

		error_log(print_r($quotes, true));

        $selected = array_rand ($quotes);

        error_log($selected);

		return [
            "MAIN_STYLE" => $css,
			"MAIN_NAVBAR" => $navbar,
            "MAIN_SHOW" => $show,
            "MAIN_GITHUB" => $github,
            "MAIN_CONTACT" => $contact,
            "MAIN_SCRIPT" => $javascript,
			"TEXT" => $quotes[$selected]['quote'],
			"IMAGE" => 'uploaded/' . $quotes[$selected]['image']
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