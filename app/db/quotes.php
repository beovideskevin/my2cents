<?php

class Quote extends Table
{
	function __construct () 
	{
		parent::__construct("quotes", "id");
	}
}

class QuoteUser extends Table
{
	function __construct () 
	{
		parent::__construct("quotes_user", "id");
	}
}
