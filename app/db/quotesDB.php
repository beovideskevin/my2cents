<?php

class Quote extends Table
{
	function __construct () 
	{
		parent::__construct("quotes", "id");
	}
}
