<?php

function problem1 ($args) 
{
	// you can write to stdout for debugging purposes, e.g.
	// print "this is a debug message\n";

	// debug
	$A = "";
	$B = "";
	problem1solution ($A, $B);
}

function problem1solution($A, $B) 
{
	for ($index = 0, $victories = 0; $index < strlen($A); $index++) 
		if (whoWon($A[$index], $B[$index])) 
			$victories++;
	
	// debug
	echo $victories;
	
	return $victories;
}

function whoWon($card1, $card2) 
{
	$alec = translateToNumber($card1);
	$bob = translateToNumber($card2);
	
	if ($alec > $bob) 
		return true;
	else 
		return false;
}

function translateToNumber($value) 
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

