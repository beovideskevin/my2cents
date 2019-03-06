<?php
// paths: /example1 /example3/doThis
function doThis() 
{
	global $_;
	
	$results = ["OUTPUT" => "doThis"];
	
	echo $_("render", $results);
}

// paths: /example5 /example6
function arguments ($args)
{
	global $_;
	
	$results = ["OUTPUT" => print_r($args, true)];
	
	echo $_("render", $results);
}

// path: /example2
class ExampleClass 
{
	/*
	 * Note that the method is static
	 */
	public function exampleMethod() 
	{
		global $_;
	
		$results = ["OUTPUT" => "exampleMethod"];

		echo $_("render", $results);
	}	
}


// path: /example4
function publicArea () 
{
	global $_;
	
	$results = ["OUTPUT" => "public"];
	
	echo $_("render", $results);
}

// path: /exmaple4/private 
function privateArea () 
{
	global $_;
	
	$results = ["OUTPUT" => "private"];
	
	echo $_("render", $results);
}

// path: /show
function show ($args) 
{
	global $_;
	
	$results = ["OUTPUT" => "hello, world!"];

	echo $_("render", $results);
}

