<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function phd($args) 
{
	global $_;
	
	$html = '<form id="mainForm" name="mainForm" method="POST" action="/phd"><input type="text" id="topic" name="topic" value=""><br><input type="number" id="number" name="number" value="0"><br><input type="submit" name="submit" id="submit" value="Generar"></form>';
	$final = '';
	
	if ( ! empty($args['topic'])) {
		$allPar = [];
		
		// get all the articles that talk about the topic
		$all = $_("assoclist: SELECT * FROM `aleph` WHERE MATCH(`title`) AGAINST('?' IN NATURAL LANGUAGE MODE) ORDER BY id ASC", [$args['topic']]);
	
		if (! empty($all)) {
			// for each article
			foreach ($all as $one) {
				// get all the lines (\n)
				$lines = explode("\n", $one['text']);

				//for each line 
				foreach ($lines as $l) {
					// get all the sentences
					$paragraph = explode(".", $l);

					// check if each sentence is big enough (we don't want small talk :)
					for ($index = 0; $index < count($paragraph); $index++) {
						error_log("cant " . str_word_count($paragraph[$index]));

						if (str_word_count($paragraph[$index]) < 10) {
							// this sentence is too small, remove it
							array_splice($paragraph, $index, 1);
						}
					}

					// put all the sentences in a general array
					$allPar = array_merge($allPar, $paragraph);
				}
			}

			// how many sentences does the article need
			$count = empty($args['number']) || ! is_numeric($args['number']) ? 10 : (int) $args['number'];

			// generate the article by using random sentences
			for ($index = 0; $index < $count && count($allPar) > 0; $index++) {
				$i = rand (0, count($allPar));

				$final .= $allPar[$i] . "<br><br>";

				array_splice($allPar, $i, 1);
			}
			
			error_log($args['topic']);
			error_log($args['number']);
			error_log($count);
			error_log($index);
			error_log(count($allPar));
		}
		else {
			$final = 'Por favor, sea un poco más explícito. Inténtelo de nuevo.';
		}
	}
	
	$results = [
				"HEADER" => 'Cual es el tema?', 
				"OUTPUT" => $html,
				"CONTENT" => $final
			];
	
	$_("render", $results);
}