<?php

class Rate extends Table
{
	function __construct () 
	{
		parent::__construct("btcexample", "id");
	}

	function getFormatedValue () 
	{
		return "<div class='container'>
					<div class='row'>
						<div class='six columns'>From Currency Code:</div>
						<div class='six columns'>{$this->from_currency}</div>
					</div>
					<div class='row'>
						<div class='six columns'>To Currency Code:</div>
						<div class='six columns'>{$this->to_currency}</div>
					</div>
					<div class='row'>
						<div class='six columns'>Exchange Rate:</div>
						<div class='six columns'>{$this->exchange_rate}</div>
					</div>
					<div class='row'>
						<div class='six columns'>Last Refreshed:</div>
						<div class='six columns'>" . 
							substr($this->last_refreshed, 0, 4) . "-" . 
							substr($this->last_refreshed, 4, 2) . "-" . 
							substr($this->last_refreshed, 6, 2) . "</div>
					</div>
				</div>";
	}
}
