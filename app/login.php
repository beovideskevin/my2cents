<?php

function login () {
	if (! empty($_SESSION['in'])) {
		header("Location: /seo");
		die();
	}
	
	getSEO ();
	
	View::setValue ("TITLE", "Login");
	View::setValue ("MAIN_TITLE", "Login");
	View::setValue ("MAIN_CONTENT",  injectCode('app/assets/html/login.html'));
	View::setValue ("MAIN_SCRIPT",  injectCode('app/assets/js/login.js'));
}

function enter ($args) {
	global $_;
	
	if (! empty($args['username']) && ! empty($args['password']) && empty($args['nono'])) {
		// $recaptcha_response = $args['g-recaptcha-response'];
		// $response = file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response );
		// $g_response = json_decode( $response );
		// if ( $g_response->success === true ) {
			
			$res = $_("assoc: 
						SELECT * 
						FROM bhm_users 
						WHERE username = '" . queryOut(trimlower($args['username'])) . "' 
						AND password = '" . sha1($args['password']) . "'");
						
			if ( ! empty($res)) 
				$_SESSION['in'] = $res['id'];
			
		// }
	}
	
	login();
}