<?php

function index ($args)
{
	$results = ["OUTPUT" => 'Hello, world!'];

	return $results;
}

class urldecodeClass {
	function urldecodeAction() {
		$str = "INVESTIGATE%5BPROCESS_ID%5D=p1_ent_5dc044b05c18ada662af8e0&ENTITY%5BCOMPANY%5D=Sample+Entity+Processor+2+name%27s&ENTITY%5BVAT_ID%5D=F87654321&ENTITY%5BREGISTER_DATE%5D=2010-01-01&ENTITY%5BAML%5D=1&ADDRESS%5B0%5D%5BFULLSTREET%5D=123+North+St&ADDRESS%5B0%5D%5BZIPCODE%5D=12345&ADDRESS%5B0%5D%5BCITY%5D=New+York&ADDRESS%5B0%5D%5BCOUNTY%5D=NY&ADDRESS%5B0%5D%5BCOUNTRY%5D=USA&ADDRESS%5B0%5D%5BOWNER%5D=%40&CONTACT%5B0%5D%5BCONTACT%5D=0111234567891&CONTACT%5B0%5D%5BTYPE%5D=PHONE&CONTACT%5B0%5D%5BOWNER%5D=%40&WEBSITE%5B0%5D%5BURL%5D=http%3A%2F%2Ftest2.com&WEBSITE%5B0%5D%5BMCC%5D=3000&WEBSITE%5B0%5D%5BMCCDETECT%5D=1&WEBSITE%5B0%5D%5BOWNER%5D=%40&DIRECTOR%5B0%5D%5BSURNAME%5D=Test+1&DIRECTOR%5B0%5D%5BNAME%5D=Member&DIRECTOR%5B0%5D%5BDOB%5D=1970-01-01&DIRECTOR%5B0%5D%5BADDRESS%5D=123+North+St+Ste+1&DIRECTOR%5B0%5D%5BAML%5D=1&DIRECTOR%5B0%5D%5BOWNER%5D=%40";
		
		parse_str ($str, $result);
		
		$results = ["RESULT" => "<pre>" . print_r($result, true) . "</pre>"];

		return $results;
	}
}