<?php

function testMethod () 
{	
	return "testMethod";
}

function testMethod2 ($args) 
{
	return "testMethod2 " . $args[0];
}

class TestModel extends Model 
{
	protected function validate () {
		error_log("Validate function Test model");
		return true;
	}
	
	protected function relate () {
		error_log("Relate function Test model");
		return true;
	}
	
	protected function cascade () {
		error_log("Cascade function Test model");
		return true;
	}
	
	protected function beforeSave () {
		error_log("BeforeSave function Test model");
		return true;
	}
	
	protected function afterSave () {
		error_log("AfterSave function Test model");
		return true;
	}
	
	protected function beforeDelete () {
		error_log("BeforeDelete function Test model");
		return true;
	}
	
	protected function afterDelete () {
		error_log("AfterDelete function Test model");
		return true;
	}
}
