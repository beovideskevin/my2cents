<?php

function problem3 ($args) 
{
	// you can write to stdout for debugging purposes, e.g.
	// print "this is a debug message\n";

	$T = [];
	$T[0] = 2;
	$T[1] = 1;
	$T[2] = 1;
	$T[3] = 2;
	$T[4] = 3;

	problem3_2solution($T);
}

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
		print_r($cities);
		
		echo "<br>";
		
		$cities = findPaths_1($cities, $organized);
		
		$paths[$i++] = count($cities);
	}
	
	echo "results: ";
	print_r($paths);
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


function problem3_2solution($T) 
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
	
	echo "results: ";
	print_r($paths);
}

function findPath_2($c, &$t) 
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