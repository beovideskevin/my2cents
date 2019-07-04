<?php 

// JUST FOR DEBUGGING
// error_reporting (E_ALL);

// JUST FOR THE LOCALHOST	
// date_default_timezone_set('America/Los_Angeles');

session_start();

require_once('$_.php');

/*************
 * controller
 *************/
$controller = new Controller();

if ($controller->getAction('test') != 'test') 
	die ('getAction 1');

if ($controller->getAction('test:') != 'test:') 
	die ('getAction 2');

if ($controller->getQuery('test: query1 query2') != 'query1 query2')
	die ('getQuery');

$controller->config('test/test');
if (!$controller::$config) 
	die ('config');

if ($controller->route() != 'testMethod')
	die ('route 1');


/*************
 * view 
 *************/
$view = new View();

$lang = $view->setLang();
if (empty($lang['HEADER']) || $lang['HEADER'] != 'result:')
	die ('language 1');

if ($view->getLang('HEADER') != 'result:')
	die ('language 2');

$lang = $view->getLang();
if (empty($lang['HEADER']) || $lang['HEADER'] != 'result:')
	die ('language 3');

if ($view->inject('test/test.html', ['OUTPUT' => 'output'], true) != 'output')
	die ('inject 1');

if ($view->render(['OUTPUT' => 'output']) != 'result:output')
	die ('render 1');


/*************
 * database
 *************/
$database = new Database();
$database->connect();

if ($database->sanitize("'this'") != "\'this\'")
	die ('sanitize 1');

if (! $database->query("INSERT INTO test (searchKey, searchValue) VALUES ('?', '?')", ["firstKey", "firstValue"])) 
	die ('query 1');

if (! $database->query("SELECT * FROM test")) 
	die ('query 2');

$result = $database->result('assoc');

if ($result['searchKey'] != 'firstKey' || $result['searchValue'] != 'firstValue')
	die ('query 3');


/*************
 * model
 *************/
$test = new TestModel('test', 'id');

$test->load($result['id']);

if ($test->searchKey != 'firstKey' || $test->searchValue != 'firstValue')
	die ('load');

if ($test->count('id = ?', [$result['id']]) != 1)
	die ('count');

$result2 = $test->find('id = ?', [$result['id']]);
	
if ($result2[0]['id'] != $result['id'])
	die ('find');

if (! $test->save(['searchKey' => 'lastKey']))
	die ('save 1');

$result2 = $test->find('id = ?', [$result['id']]);

if ($result2[0]['searchKey'] != 'lastKey')
	die('save 2');

if (! $test->delete())
	die ('delete 1');

$result2 = $test->find('id = ?', [$result['id']]);

if ($result2)
	die ('delete 2');


/**************
 * email
 **************/
$email = new Email();
if (! $email->sendEmail('beovideskevin@gmail.com', 'testing my2cents', 'this is a test of my2cents'))
	die ('email 1');


/*************
 * $_
 *************/ 
if ($_('route', 'test', ['argument']) != 'testMethod2 argument') 
	die ('route 2');
	
if ($_('route: test', ['argument']) != 'testMethod2 argument') 
	die ('route 3');
	
$result3 = $_('setlang: test/test');
if ($result3['HEADER'] != 'result:')
	die ('language 4');

$result3 = $_('setlang', 'test/test');
if ($result3['HEADER'] != 'result:')
	die ('language 5');

if ($_('inject: test/test.html', ['HEADER' => 'result:', 'OUTPUT' => 'show']) != 'result:show')
	die ('inject 2');

if ($_('render', ['HEADER' => 'result:', 'OUTPUT' => 'show']) != 'result:show')
	die ('render 2');

$_('connect');

if ($_('sanitize', "'this'") != "\'this\'")
	die ('sanitize 2');

if (! $_(": INSERT INTO test (searchKey, searchValue) VALUES ('?', '?')", ["secondKey", "secondValue"])) 
	die ('query 4');

$result4 = $_("assoc: SELECT * FROM test");

if ($result4['searchKey'] != 'secondKey' || $result4['searchValue'] != 'secondValue')
	die ('query 5');

if (! $_('mail: beovideskevin@gmail.com', 'testing my2cents', 'this is a test of my2cents'))
	die ('email 2');

die ('All is working!');
