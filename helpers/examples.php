<?php
// paths: /example1 /example3/donotredirect
function doThis() 
{
	global $_;
	
	$results = ["OUTPUT" => "doThis"];
	
	$_("render", $results);
}

// path: /example2
class ExampleClass 
{
	/*
	 * Note that the method is static
	 */
	public static function exampleMethod() 
	{
		global $_;
	
		$results = ["OUTPUT" => "exampleMethod"];

		$_("render", $results);
	}	
}


// path: /example4
function publicArea () 
{
	global $_;
	
	$results = ["OUTPUT" => "public"];
	
	$_("render", $results);
}

// path: /exmaple4/private 
function privateArea () 
{
	global $_;
	
	$results = ["OUTPUT" => "private"];
	
	$_("render", $results);
}
