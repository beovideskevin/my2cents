<?php

// This namespace groups all the method for the Quotes app
namespace Quotes {
    use Email;

    /**
	 * This method show the quotes one by one
	 */
	function showQuotes($args) {
		global $_;

        // Get the external files
		$css = $_("inject: app/assets/quotes/show.css");
		$html = $_("inject: app/assets/quotes/show.html");

		// Get the quotes
		$quotes = $_("assoclist: SELECT * FROM quotes WHERE status = 'active'");
        $selected = array_rand ($quotes);

		return [
            "MAIN_STYLE" => $css,
            "MAIN_CONTENT" => $html,
			"TEXT" => $quotes[$selected]['quote'],
			"IMAGE" => 'uploaded/' . $quotes[$selected]['image'],
            "MAIN_SCRIPT" => '$(document).ready(function () {
                $("#home").addClass("active");
            });'
		];
	}

	function contactQuotes($args)
    {
        global $_;

        if (isset($args['g-recaptcha-response']) && $args['g-recaptcha-response'] &&
            isset($args['subject']) && $args['subject'] &&
            isset($args['message']) && $args['message'] &&
            isset($args['email']) && $args['email'])
        {
            $secret = $_("getConfig: secretKey");
            $output = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".
                                                    $secret . "&response=" . $args['g-recaptcha-response']), true);
            if (isset($output['success']) && $output['success'] == true) {
                $_("email: contact@eldiletante.com", $args['subject'], ["OUTPUT" => $args['message'] . "<br>" . $args['email']]);
            }
        }

        // Get the external files
        $contact = $_("inject: app/assets/quotes/contact.html");
        $javascript = $_("inject: app/assets/quotes/contact.js");

        return [
            "MAIN_CONTENT" => $contact,
            "MAIN_SCRIPT" => $javascript
        ];
    }
}