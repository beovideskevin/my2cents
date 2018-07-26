<?php

function aleph () {
$all = $_("assoclist: SELECT * FROM aleph ");

foreach ($all as $a) {	
	$lines = explode ("\n", $a['text']);
	$text = '';
	$title = '';
	$author = '';

	foreach ($lines as $line) {
		$line = preg_replace("/\n+/","\n", $line);
		$line = preg_replace("/\r+/","", $line);
		$line = trim($line);
		
		if (empty($line) || $line == "\n") 
			continue;
		elseif (empty($title)) 
			$title = trim($line);
		elseif (empty($author))
			$author = trim($line);
		else 
			$text .= $line . "\n";
	}
	
	$_(": UPDATE aleph SET author = '" . queryOut($author) . "', title = '" . queryOut($title) . "', text = '" . queryOut($text) . "' WHERE id = {$a['id']}");
}

}