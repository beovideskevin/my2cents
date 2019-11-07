<?php

function ajax ($args) 
{
	die();
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

	return ["RESULT" => $result['result']];
}

function saveBTCPrice ($args) 
{
	if (isset($args['Realtime_Currency_Exchange_Rate'])) {
		$data = $args['Realtime_Currency_Exchange_Rate'];
		$rate = new Rate();
		$return = $rate->save([
			"from_currency"  => $data['1. From_Currency Code'],
			"to_currency"    => $data['3. To_Currency Code'],
			"exchange_rate"  => $data['5. Exchange Rate'],
			"last_refreshed" => date("Ymd", strtotime($data['6. Last Refreshed']))
		]);
	}

	return ["RESULT" => $return];
}