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
	global $_;
	
	$res = $_("assoc: SELECT COUNT(*) as c FROM counter WHERE `date` > '?' AND `ip` like '?'", ['2015-1-1 12:00:00', '77.%']);
	
	$results = ["OUTPUT" => $res["c"]];
	
	$_("render", $results);
}

function ajax ($args) 
{
	die("ajax");
}
