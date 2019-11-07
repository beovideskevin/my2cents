<?php

/***********************
* ROUTES EXAMPLES
************************/

// paths: /example1 /example3/doThis
function doThis() 
{
	return $results = ["OUTPUT" => "doThis"];
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
************************/
function fullExample ($args) 
{
	global $_;

	if (isset($args['lan']) && $args['lan']) {
		$_SESSION['LANGUAGE_IN_USE'] = $args['lan'];
		$_("setlang: " . $args['lan']);
	}

	$oldValues = "";
	$rates = $_("assoc: SELECT * FROM rates ORDER BY id DESC");

	if ($rates) {
		$rate = new Rate();
		
		// You can just assign the vlaues
		$rate->assign($rates);
		
		// Get the values and make an HTML table
		$oldValues = $rate->getFormatedValue();
		
		// You can sync with the database
		$rate->sync($rates['id']);
		
		// This is how you would update
		$rate->save(["from_currency" => "popo", "exchange_rate" => 1]);
		
		// This sends an email 
		$email = new Email();
		$email->sendEmail("beovideskevin@gmail.com", "Exchange rate", ["RESULT" => $oldValues]);
	}

	$results = [
		"SEO_AUTHOR"      => "",
		"SEO_DESCRIPTION" => "",
		"SEO_KEYWORDS"    => "",
		"TITLE"           => "My2Cents",
		"SEO_TITLE"       => "Full Example",
		"MAIN_STYLE"      => $_("inject: app/assets/example.css"),
		"HEADER"          => $_("getlang: HEADER_ALT"),
		"OUTPUT"          => $_("inject: app/assets/example.html"),
		"CONTENT"         => '<span id="result">'.$oldValues.'</span>',
		"MAIN_SCRIPT"     => $_("inject: app/assets/example.js")
	];
	return $results;
}

/***********************
* THREE PROBLEMS
************************/

function problem1 ($args) 
{
	$A = "13A";
	$B = "22J";
	
	return ["OUTPUT" => problem1solution ($A, $B)];
}

function problem1solution ($A, $B) 
{
	for ($index = 0, $victories = 0; $index < strlen($A); $index++) 
		if (whoWon($A[$index], $B[$index])) 
			$victories++;
	
	return $victories;
}

function whoWon ($card1, $card2) 
{
	$alec = translateToNumber($card1);
	$bob = translateToNumber($card2);
	
	if ($alec > $bob) 
		return true;
	else 
		return false;
}

function translateToNumber ($value) 
{
	switch ($value) {
		case 'A': return 14;
		case 'K': return 13;
		case 'Q': return 12;
		case 'J': return 11;
		case 'T': return 10;
		default: return (int) $value;
	}
}

/***************/

function problem2 ($args) 
{
	// $A=[1,0]; 
	$A=[1,1,0,1,0,0];
  
	return ["OUTPUT" => problem2solution($A)];
}

function problem2solution (&$A) 
{
    $n = sizeof($A);
    $result = 0;
    for ($i = 0; $i < $n - 1; $i++) {
        if ($A[$i] == $A[$i + 1]) {
			$result = $result + 1; 
		}
    }
    $r = -1;
    for ($i = 0; $i < $n; $i++) {
        $count = 0;
        if ($i > 0) {
            if ($A[$i - 1] != $A[$i]) {
                $count = $count + 1;
			}
			else {
                $count = $count - 1;
			}
        }
        if ($i < $n - 1) {
            if ($A[$i + 1] != $A[$i]) {
                $count = $count + 1;
			}
			else {
                $count = $count - 1;
			}
        }
        $r = max($r, $count);
    }
	
    return $result + $r;
}

/***************/

function problem3 ($args) 
{
	$T = [];
	$T[0] = 2;
	$T[1] = 1;
	$T[2] = 1;
	$T[3] = 2;
	$T[4] = 3;

	return ["OUTPUT" => problem3_2solution($T)];
}

/* This solution works but it is slow
function problem3_1solution($T) 
{
	$organized = [];
	$paths = array_fill(0, count($T) - 1, 0);
	$cities = [];
	$capital = 0;
	
	for ($index = 0; $index < count($T); $index++) {
		if ($T[$index] == $index) 
			$capital = $index;
		else
			$organized[$T[$index]][] = $index;
	}
	
	$cities = [$capital];
	$i = 0;
	while (! empty($cities)) {
		$cities = findPaths_1($cities, $organized);
		
		$paths[$i++] = count($cities);
	}
	
	return print_r($paths, true);
}

function findPaths_1($c, $t) 
{
	$result = [];
	for ($index = 0; $index < count($c); $index++) {
		if (empty($t[$c[$index]])) 
			continue;
		$result = array_merge($result, $t[$c[$index]]);
	}
	return $result;
}
*/

function problem3_2solution ($T) 
{
	$paths = array_fill(0, count($T) - 1, 0);
	$cities = [];
	
    for ($i=0; $i < count($T); $i++) {
		if ($T[$i] == $i) {
			$cities = [$i];
			unset($T[$i]);
			break;
		}
	}
	
	$i = 0;
	while (! empty($T)) {
		$cities = findPath_2($cities, $T);
		$paths[$i++] = count($cities);
	}
	
	return print_r($paths, true);
}

function findPath_2 ($c, &$t) 
{
	$result = [];
	
	foreach ($t as $i => $val) {
		if (in_array($val, $c)) {
			$result[] = $i;
			unset($t[$i]);
		}
	}
	
	return $result;
}