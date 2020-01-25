<?php

// This namspace groups all the ajax methods for the Quotes app
namespace Quotes\Ajax {
	/**
	 * Return all the quotes
	 */
	function getQuotes($args) {
		global $_;

		return ["OUTPUT" => "getQuotes"];
	}

	/**
	 * Add a new quote
	 */
	function addQuotes($args) {
		global $_;

		return ["OUTPUT" => "addQuotes"];
	}

	/**
	 * Remove a quote
	 */
	function delQuotes($args) {
		global $_;
		
		return ["OUTPUT" => "delQuotes"];
	}

	/**
	 * Enforces security on the ajax calls
	 */
	function enforceAjaxQuotes() {
		if (empty($_SESSION['api_key'])) {
			die();
		}
	}
}