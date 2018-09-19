<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function phd($args) 
{
	global $_;
	
	$small_array = ["a", "ante", "bajo", "cabe", "con", "contra", "de", "desde", "durante", "en", "entre", "hacia", "hasta", 
			"mediante", "para", "por", "segun", "sin", "so", "sobre", "tras", "versus", "via", "y", "ni", "tanto", "como",
			"cuanto", "igual", "que", "tambien", "ya", "pero", "sino", "porque", "por que", "aunque", "luego", "que", "conque", 
			"de", "el", "ella", "ellos", "ello", "la", "y", "e", "asi", "mas", "empero", "mientras", "o", "u", "ya", "sea", "vos", 
			"nos", "nosotros", "ud", "ustedes", "yo", "tu"];
	
	$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
	
	$html = '<form id="mainForm" name="mainForm" method="POST" action="/phd"><input type="text" id="topic" name="topic" value="' . (! empty($args['topic']) ? $args['topic'] : '' ) . '"><br><input type="number" id="number" name="number" value="' . (! empty($args['number']) ? $args['number'] : '' ) . '"><br><input type="submit" name="submit" id="submit" value="Generar"></form>';
	$final = '';
	
	if ( ! empty($args['topic'])) {
		$allPar = [];
		
		$str = strtr($args['topic'], $unwanted_array);
		
		$words = explode(" ", $str);
		$query = "";
		
		for ($index = 0; $index < count($words); $index++) {
			if (in_array($words[$index], $small_array) || strlen($words[$index]) < 4) {
				array_splice($words, $index, 1);
				continue;
			}
			
			if (! empty($query)) {
				$query .= " OR ";
			} 
			
			$query .= " `text` LIKE '%?%' ";
		}
		
		if (! empty($words)) {
			// get all the articles that talk about the topic
			$all = $_("assoclist: SELECT * FROM `aleph` WHERE " . $query . " ORDER BY id ASC", $words);

			if (! empty($all)) {
				// for each article
				foreach ($all as $one) {
					// get all the lines (\n)
					$lines = explode("\n", $one['text']);

					//for each line 
					foreach ($lines as $l) {
						if (empty($l)) {
							continue;
						}
						
						// get all the sentences
						$paragraph = explode(".", $l);

						// check if each sentence is big enough (we don't want small talk :)
						for ($index = 0; $index < count($paragraph); $index++) {
							error_log("cant " . str_word_count($paragraph[$index]));

							if (str_word_count($paragraph[$index]) < 10 || strlen($paragraph[$index]) < 30) {
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
				for ($index = 0, $cut = 0; $index < $count && count($allPar) > 0; $index++) {
					
					if ($cut == 0) {
						$cut = rand (2, 6);
					}
					
					$i = rand (0, count($allPar));

					$final .= $allPar[$i];
					
					$final .= ". ";
					
					if ($index % $cut == 0) {
						$final .= "<br><br>";
						$cut = 0;
					} 

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