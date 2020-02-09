<?php

/***********************
* ROUTES EXAMPLES
************************/

// paths: /example1 /example3/doThis
function hello() 
{
	return $results = ["OUTPUT" => "Hello, world!"];
}

// paths: /example5 /example6
function arguments ($args)
{
	return $results = ["OUTPUT" => print_r($args, true)];
}

// path: /example2
class ExampleClass 
{
	public function exampleMethod() 
	{
		return $results = ["OUTPUT" => "exampleMethod"];
	}
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
function BTCFullExample ($args) 
{
	global $_;

	if (isset($args['lan']) && $args['lan']) {
		$_SESSION['LANGUAGE_IN_USE'] = $args['lan'];
		$_("setlang: " . $args['lan']);
	}

	$oldValues = "";
	$rates = $_("assoc: SELECT * FROM btcexample ORDER BY id DESC");

	if ($rates) {
		$rate = new Rate();
		
		// You can just assign the vlaues
		$rate->assign($rates);
		
		// Get the values and make an HTML table
		$oldValues = $rate->getFormatedValue();
		
		// You can sync with the database
		$rate->sync($rates['id']);
		
		// This is how you would update
		$rate->save(["from_currency" => "btc", "exchange_rate" => 1]);
	}

	$results = [
		"SEO_TITLE"       => "| BTC Example",
		"MAIN_STYLE"      => $_("inject: app/assets/example.css"),
		"HEADER"          => $_("getlang: HEADER_ALT"),
		"OUTPUT"          => $_("inject: app/assets/example.html"),
		"CONTENT"         => '<span id="result">'.$oldValues.'</span>',
		"MAIN_SCRIPT"     => $_("inject: app/assets/example.js"),
        "REFRESH"         => !isset($_SESSION['LANGUAGE_IN_USE']) || $_SESSION['LANGUAGE_IN_USE'] == "en"
                                ? "Refresh" : "Refrescar"
	];
	return $results;
}

/**
 * This method is usually commented. Just in case you want to use it, uncomment it, 
 * and add a route to the config.json 
 */
 /*function php_info() {
	 phpinfo();
 }*/
