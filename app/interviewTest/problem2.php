<?php

function problem2 ($args) 
{
	// $A=[1,1,0,1,0,0];
  
	$A=[1,0]; 
  
	echo "<br>solution: " . problem2solution($A);
}

function problem2solution(&$A) 
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
