<?php

// JUST FOR DEBUGGING
// error_reporting (E_ALL);

// JUST FOR THE LOCALHOST	
// date_default_timezone_set('America/Los_Angeles');

session_start();

require_once('$_.php');

$_("init");

function index ($args)
{
	/* 
	 * Example of using the index file
	
	global $_;
	
	$res = $_("assoc: SELECT COUNT(*) as c FROM counter WHERE `date` > '?' AND `ip` like '?'", ['2015-1-1 12:00:00', '77.%']);
	
	$results = ["OUTPUT" => $res["c"]];
	
	$_("render", $results);

	*/
	
	
	$a = ["a", "b", "c"];
	$b = $a;
	$b[0] = "d";
	
	var_dump($a);
	var_dump($b);
	
	
}

function ajax ($args) 
{
	die("ajax");
}


/****/

function try1 ($args) 
{	
	// $jsonData = json_decode(file_get_contents('http://dimecubawow.loc/async/'), true);

	// print_r($jsonData);
	
	// $d = date("Y-m-d H:i:s ", 1527191984);
	// echo $d . "<br>";
	// echo date_default_timezone_get();

	phpinfo();
} 

function try2 ($args) 
{
	echo sha1("a714c884522f639b" . sha1("12345678"));

	// yamel
	// id 401
	// old 
	// bb97c5d5fcd6adf08b4a61fcce357a2e255db263
	// new (123456)
	// 9e21e031d73307bc426965562f4cfa40accf12f8

	// ezequiel
	// id 530
	// old
	// 9f4592d1d0b490516e284d92424c096035abbfd7
	// new (12345678)
	// 62e6eee237d72ce927941b0df9b5f103621f686d
}