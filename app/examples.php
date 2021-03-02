<?php

/***********************
 * ROUTES EXAMPLES
 ************************/

// paths: /example1 /example2 /example2/hello
function hello() 
{
	return $results = ["OUTPUT" => "Hello, world!"];
}

// path: /example3
function arguments ($args)
{
	return $results = ["OUTPUT" => print_r($args, true)];
}

// path: /example4
function publicArea () 
{
	return $results = ["OUTPUT" => "public"];
}

// path: /exmaple4/private 
function privateArea () 
{
	return $results = ["OUTPUT" => "private"];
}

/***********************
 * FULL EXAMPLE
 *
 * TABLE FOR THE EXAMPLE
 *	CREATE TABLE public.rates (
 *		from_currency varchar(10) NULL,
 *		to_currency varchar(10) NULL,
 *		exchange_rate numeric(10) NULL,
 *		last_refreshed numeric(8) NULL,
 *		id bigserial NOT NULL
 *	);
 *
 ************************/

class BTC {
    
	function FullExample ($args) 
	{
		global $_;

		if (isset($args['lan']) && $args['lan']) {
			$_SESSION['LANGUAGE_IN_USE'] = $args['lan'];
			$_("setlang: " . $args['lan']);
		}

		$values = "";
		$rates = $_("assoc: SELECT * FROM btcexample ORDER BY id DESC");

		if ($rates) {
			$rate = new Rate();
			
			// You can just assign the vlaues
			$rate->assign($rates);
			
			// Get the values and make an HTML table
			$values = $rate->getFormatedValue();
		}

		$recaptcha = $_("getConfig: recaptcha");

		if (isset($args['g-recaptcha-response']) && $args['g-recaptcha-response'] &&
            isset($args['subject']) && $args['subject'] &&
            isset($args['message']) && $args['message'] &&
			isset($args['email']) && $args['email']) 
		{
            $output = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".
								  $recaptcha['secretKey'] . "&response=" . $args['g-recaptcha-response']), true);
            if (isset($output['success']) && $output['success'] == true) {
				$emailResult = $_("email: contact@eldiletante.com", $args['subject'], ["OUTPUT" => $args['message'] . "<br>" . $args['email']]);
				$emailMsg = $emailResult ? "EMAIL_MSG" : "EMAIL_ERROR";  
            }
        }

		$results = [
			"MAIN_STYLE"   => $_("inject: app/assets/example.css"),
			"BTN"          => $_("inject: app/assets/example.html"),
			"CONTENT"      => $values,
			"MAIN_SCRIPT"  => $_("inject: app/assets/example.js"),
			"SITE_KEY"     => $recaptcha['siteKey'],
			"EMAIL_RESULT" => isset($emailMsg) ? $_("getlang: {$emailMsg}") : ""
		];
		return $results;
    }
    
    function refreshBTCPrice ()
    {
        /**
         * https://www.alphavantage.co/documentation/
         * CURRENCY_EXCHANGE_RATE
         * https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=BTC&to_currency=USD&apikey=1DSRUMF11OD6D09C
         * API key: 1DSRUMF11OD6D09C
         */ 
        $curl = new Curl();
        $result = $curl->sendHttp(
            "GET", 
            "https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=BTC&to_currency=USD&apikey=1DSRUMF11OD6D09C",
            "",
            [],
            [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => true
            ]
		);

		if (isset($result['result'])) {
			$data = json_decode($result['result'], true);
			$rate = new Rate();
            $rate->save([
                "from_currency"  => $data['Realtime Currency Exchange Rate']['1. From_Currency Code'],
                "to_currency"    => $data['Realtime Currency Exchange Rate']['3. To_Currency Code'],
                "exchange_rate"  => $data['Realtime Currency Exchange Rate']['5. Exchange Rate'],
                "last_refreshed" => date("Ymd", strtotime($data['Realtime Currency Exchange Rate']['6. Last Refreshed']))
            ]);
		}

        return ["OUTPUT" => $result['result']];
    }
}
