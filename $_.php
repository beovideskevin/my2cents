<?php

/***********************************************************************************

	My two cents - https://github.com/beovideskevin/dollarlib 
	Copyright (c) 2016 Flow with the Code

	This file is part of Dollar Lib.

    My two cents is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    My two cents is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Dollar Lib.  If not, see <http://www.gnu.org/licenses/>.

************************************************************************************

	This is the main file, include it and you don't need to include anything else
		
************************************************************************************/

/**
 *
 */
class PostgreSQL_Adapter 
{
	public static $link = NULL, 
				  $result = NULL; 

	public function connect($server, $port, $user, $pass, $database) 
	{
		self::$link = pg_connect("host={$server} port={$port} dbname={$database} user={$user} password={$pass}");
		
		if (! self::$link) 
			die('Could not connect!');
	}
	
	protected function disconnect () 
	{
		pg_close(self::$link);
	}
	
	public function sanitize ($var) 
	{
		return pg_escape_string(self::$link, $var);
	}
	
	public function query ($query)
	{
		self::$result = pg_query($query);
		
		return self::$result;
	}
	
	public function result ($ret = '') 
	{
		$act = empty($ret) ? 'assoc' : trimLower($ret);
		
		switch ($act) {
			case 'insertid':
			case 'single':
				$tmp = pg_fetch_array(self::$result);
				return $tmp[0];
				
			case 'obj':
				return pg_fetch_object(self::$result);
				
			case 'assoclist':
				$rows = [];
				while($row = pg_fetch_assoc(self::$result))
					$rows[] = $row;

				return $rows;

			case 'assoc':
			default:
				return pg_fetch_assoc(self::$result);
		}
	}
}

/**
 *
 */
class MySQL_Adapter 
{
	public static $link = NULL, 
				  $result = NULL; 
	
	public function connect($server, $port, $user, $pass, $database) 
	{
		self::$link = new mysqli($server, $user, $pass, $database, $port);
		
		if (! self::$link) 
			die('Could not connect!');
	}
	
	public function disconnect () 
	{
		mysqli_close(self::$link);
	}
	
	public function sanitize ($var) 
	{	
		return mysqli_real_escape_string(self::$link, $var);
	}
	
	public function query ($query)  
	{
		self::$result = self::$link->query($query);
		
		return self::$result;
	}
	
	public function result ($ret = '') 
	{
		$act = empty($ret) ? 'assoc' : trimLower($ret);
		
		switch ($act) {
			case 'single':
				$tmp = self::$result->fetch_row();
				return $tmp[0];

			case 'insertid':
				return self::$link->insert_id;

			case 'obj':
				return self::$result->fetch_object();
				
			case 'assoclist':
				$rows = [];
				while($row = self::$result->fetch_array(MYSQLI_ASSOC))
					$rows[] = $row;

				return $rows;

			case 'assoc':
			default:
				return self::$result->fetch_assoc();
		}
	}
}

/**
 *
 */
class SQLite_Adapter 
{
	public function connect($server, $port, $user, $pass, $database) 
	{}
	
	public function disconnect () 
	{}
	
	public function sanitize ($var) 
	{}
	
	public function query ($query)  
	{}
	
	public function result ($ret = '') 
	{}
}

/**
 *
 */
class Database
{
	public static $driver = NULL, 
				  $adapter = '', 
				  $host = '', 
				  $port = 0, 
				  $database = '', 
				  $user = '', 
				  $password = '';

	public function connect () 
	{
		// if the link is not empty, abort the previous connection
		if (! empty(self::$driver)) {
			self::$driver->disconnect();
			self::$driver = NULL;
		}

		$server = self::$host;
		$port = self::$port;
		$user = self::$user;
		$pass = self::$password;
		$database = self::$database;
		
		if (trimLower(self::$adapter) == 'mysql')
			self::$driver = new MySQL_Adapter();
		elseif (trimLower(self::$adapter) == 'postgresql')
			self::$driver = new PostgreSQL_Adapter();
		else
			die ('No adapter for the database!');

		self::$driver->connect($server, $port, $user, $pass, $database);
	}
	
	public function disconnect () 
	{
		self::$driver->disconnect();
	}
	
	public function sanitize ($var) 
	{
		if (empty($var)) 
			return $var;

		if (is_array($var)) {
			foreach ($var as $key => $subvar) 
				$result[$key] = $this->sanitize($subvar);

			$var = $result;
		}
		else 
			$var = self::$driver->sanitize($var);
		
		return $var;
	}
		
	public function query ($query, $args = [], $ret = '') 
	{
		if (! empty($args)) {
			$args = $this->sanitize($args);
			$i = 0;
			while(($letter_pos = strpos($query, '?')) !== false) {
				$query = substr_replace($query, $args[$i], $letter_pos, 1);
				$i++;
				if ($i > count($args)) 
					break;
			}
		}
		
		if (! self::$driver->query($query)) {
			error_log('WOW (query): ' . $query);
			
			return false;
		}
		elseif (! empty($ret))
			return self::$driver->result($ret);

		return true;
	}
	
	public function result ($ret = '') 
	{
		return self::$driver->result($ret);
	}
}

/**
* To add functionality to this model class just implement some of these methods:
* 	protected function validate () {} This method is call for validate the values of the object
* 	protected function relate () {} This method is call to buil the external relationships
* 	protected function cascade () {} This method is called to delete the related records when deleting this one
* 	protected function beforeSave () {} This method is called before inserting or updating
* 	protected function afterSave () {} This method is called after inserting or updating
* 	protected function beforeDelete () {} This method is called before deleting
* 	protected function afterDelete () {} This method is called after deleting
*/

class Model 
{
	public $_table = '', 
		   $_id = '';

	function __construct ($table, $id = '', $properties = []) 
	{
		// create the internal data
		if (! $table) 
			throw new Exception('ERROR: TABLE');
		
		$this->_table = $table;
		$this->_id = $id;

		if ($properties) {
			// assign data if there is any
			$this->assign($properties);
			
			if (is_callable([$this, 'validate']) && ! $this->validate()) 
				throw new Exception('ERROR: VALIDATE');
						
			if (is_callable([$this, 'relate']) && ! $this->relate())
				throw new Exception('ERROR: RELATE');
		}
	}

	public function load ($id) 
	{
		if ($this->_id) {
			$result = $this->find("{$this->_id} = '?'", $id, ["LIMIT" => 1]);
			
			if (! $result)
				return false;
			
			$this->assign($result[0]);
			
			if (is_callable([$this, 'validate']) && ! $this->validate())
				return false;
						
			if (is_callable([$this, 'relate']) && ! $this->relate())
				return false;
			
			return true;
		}
		
		return false;
	}
	
	public function assign ($properties) 
	{
		foreach ($properties as $key => $value)
			$this->$key = $value;
	}
	
	public function count ($conditions = '', $values = [], $options = [])
	{
		return $this->find($conditions, $values, $options, 'single:', "count({$this->_id})");
	}
	
	public function find ($conditions = '', $values = [], $options = [], $type = '', $select = '')
	{
		global $_;
		
		if ($type) 
			$query = $type;
		else 
			$query = "assoclist:";

		$query .= " SELECT ";
		
		if ($select) 
			$query .= $select;
		else 
			$query .= "*";
		
		$query .= " FROM {$this->_table} ";
	
		if ($conditions) 
			$query .= " WHERE {$conditions} ";
		
		if (isset($options['ORDER'])) 
			$query .= " ORDER BY {$options['ORDER']}";
		
		if (isset($options['LIMIT'])) {
			$query .= " LIMIT {$options['LIMIT']}";
			if (isset($options['PAGE'])) 
				$query .= ",{$options['PAGE']}";
		}
		
		if (! is_array($values)) 
			$values = [$values];
	
		return $_($query, $values);
	}
	
	public function save ($properties = [])
	{
		global $_;
		
		if ($properties) 
			$this->assign($properties);
		else 
			$properties = get_object_vars($this);
		
		if (is_callable([$this, 'validate']) && ! $this->validate()) 
			return false;
		
		if (is_callable([$this, 'beforeSave']) && ! $this->beforeSave())
			return false;
	
		$idName = $this->_id;
		if ($idName && ! empty($this->$idName)) { // update the record
			$query = "";
			foreach ($properties as $key => $value) {
				if ($key == '_table' || $key == '_id')
					continue;
				
				if ($query) 
					$query .= ",";
				
				$query .= $key . "='" . $value . "'";
			}
			$result = $_(": UPDATE {$this->_table} SET {$query} WHERE {$idName} = '{$this->$idName}'");
		}
		else { // create the record
			$insertKey = "";
			$insertValue = "";
			foreach ($properties as $key => $value) {
				if ($key == '_table' || $key == '_id')
					continue;
				
				if ($insertKey) 
					$insertKey .= ",";
				
				if ($insertValue) 
					$insertValue .= ",";
				
				$insertKey .= $key;
				$insertValue .= "'" . $value . "'"; 
			}
			$query = "insertid: INSERT INTO {$this->_table} ({$insertKey}) VALUES ({$insertValue})";
			
			if (trimLower(DATABASE_ADAPTER) == "postgresql") 
				$query .= " RETURNING {$idName}";
			
			$result = $_($query);
			
			if ($result) 
				$this->$idName = $result;
		}
		
		if ($result) {
			if (! $this->load($this->$idName)) 
				return false;
		}
		else
			return false;
		
		if (is_callable([$this, 'afterSave']) && ! $this->afterSave())
			return false;
		
		return true;
	}

	public function delete () 
	{
		global $_;
		
		if (is_callable([$this, 'beforeDelete']) && ! $this->beforeDelete())
			return false;
		
		if (is_callable([$this, 'relate']) && ! $this->relate())
			return false;
		
		if (is_callable([$this, 'cascade']) && ! $this->cascade())
			return false;
		
		$idName = $this->_id;
		if ($idName && ! empty($this->$idName)) { // update the record
			if (! $_(": DELETE FROM {$this->_table} WHERE {$idName} = '?'", [$this->$idName]))
				return false;
		}
		else 
			return false;
		
		if (is_callable([$this, 'afterDelete']) && ! $this->afterDelete())
			return false;
		
		return true;
	}
}

/**
 *
 */
class Controller
{
	public static $config = [], 
				  $url = [], 
				  $routes = [], 
				  $includes = [];
	
	public function getAction ($fullQuery) 
	{
		$query = explode (' ', trim($fullQuery));
		
		if (strrpos($query[0], ':') === 0)
			$action = ':';
		elseif (! empty($query[1]) && $query[1] == ':') 
			$action = $query[0] . $query[1];
		else 
			$action = $query[0];
		
		return trimLower($action);
	}
	
	public function getQuery($query) 
	{
		if (strrpos($query, ':') === 0) 
			return substr(trim($query), 1);

		$parts = explode (' ', trim($query));
		
		unset($parts[0]);
			
		if (! empty($parts[1]) && $parts[1] == ':')
			unset($parts[1]);
		
		return implode(' ', $parts);
	}
	
	public function config($filepath = '') 
	{ 
		$filepath = $filepath ? $filepath . '.json' : 'config.json';
					
		self::$config = json_decode(file_get_contents($filepath), true);
		
		if (empty(self::$config))
			die ('No configuration file or error while parsing it!');

		foreach (self::$config as $key => $value) {
			switch (trimlower($key)) {
				// The main path
				case 'files_base_path':
					DEFINE ('FILES_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/' . $value);
					break;
	
				// the path for the classes to include
				case 'register':
					foreach (self::$config[$key] as $regKey => $regVal) {
						switch (trimlower($regKey)) {
							case 'exceptions':
								self::$includes['EXCEPTIONS'] = $regVal;
								break;
								
							case 'folders':
								self::$includes['FOLDERS'] = $regVal;
								break;
						}
					}
					break;
					
				// set the routes
				case 'routes':
					foreach (self::$config[$key] as $rKey => $rVal) {
						switch (trimlower($regKey)) {
							case 'default':
								self::$routes['DEFAULT'] = $rVal;
								break;
								
							default:
								self::$routes[$rKey] = $rVal;
								break;
						}
					}
					break;
					
				// the layout
				case 'layouts_path': 
					View::$layout_path = $value;
					break;
				
				case 'default_layout': 
					if (! empty($_SESSION['LAYOUT_IN_USE']))
						View::$layout_in_use = $_SESSION['LAYOUT_IN_USE'];
					else
						View::$layout_in_use = $value;
					break;

				// the language
				case 'languages_path': 
					View::$language_path = $value;
					break;
					
				case 'default_language': 
					if (! empty($_SESSION['LANGUAGE_IN_USE']))
						View::$language_in_use = $_SESSION['LANGUAGE_IN_USE'];
					else
						View::$language_in_use = $value;
					break;
					
				// MySQL conection data
				case 'database':
					foreach (self::$config[$key] as $dbKey => $dbVal) {
						switch (trimlower($dbKey)) {
							case 'adapter': 
								Database::$adapter = $dbVal;
								break;
							case 'host':
								Database::$host = $dbVal;
								break;
							case 'port':
								Database::$port = $dbVal;
								break;
							case 'database':
								Database::$database = $dbVal;
								break;
							case 'user':
								Database::$user = $dbVal;
								break;
							case 'password':
								Database::$password = $dbVal;
								break;
						}
					}
					break;
					
				// SMTP email configuration
				case 'email':
					foreach (self::$config[$key] as $eKey => $eVal) {
						switch (trimlower($eKey)) {
							case 'system': 
								Email::$system = $eVal;
								break;
							case 'from':
								Email::$from = $eVal;
								break;
							case 'server':
								Email::$server = $eVal;
								break;
							case 'port':
								Email::$port = $eVal;
								break;
							case 'user':
								Email::$user = $eVal;
								break;
							case 'password':
								Email::$password = $eVal;
								break;
							case 'layout':
								Email::$layout = $eVal;
								break;
							case 'language':
								Email::$language = $eVal;
								break;
						}
					}
					break;
			}
		}
		
		// If the main path is not set, lets set the default
		if (!defined('FILES_BASE_PATH')) 
			DEFINE ('FILES_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	}
	
	protected function register ($folder, $exceptions) 
	{
		if (! $folder)
			return false;
		
		$other_files = [];

		if (! in_array('$_', $exceptions))
			$exceptions[] = '$_';
			
		$files = scandir($folder);
		foreach ($files as $f) {
			if ($f == '.' || $f == '..' || in_array($f, $exceptions))
				continue;
			elseif (is_dir($folder . $f) && $f == 'vendor' && file_exists($folder . 'vendor/autoload.php'))
				require_once($folder . 'vendor/autoload.php'); // this is for the libraries installed with composer
			elseif (is_dir($folder . $f)) 
				$other_files[] = $folder . $f;
			elseif (substr($f, -4) == '.php')
				include_once($folder . $f);
		}

		if (! empty($other_files))
			foreach ($other_files as $of)
				$this->register($of . '/', $exceptions);
	}
	
	public function route ($urlpath = '', $args = []) 
	{
		if (! $urlpath && isset($_REQUEST['_url']))
			$urlpath = $_REQUEST['_url'];
				
		if ($urlpath) {
			$all = explode('/', $urlpath);
			foreach ($all as $value) {
				if ($value) 
					self::$url[] = $value;
			}
		}
		
		if (! $args)
			$args = $_REQUEST;
					
		if (! self::$url) // if there is no _url put the default
			$action = self::$routes['DEFAULT'];
		else {
			$action = self::$routes;
			$index = 0;
			
			while($index < count(self::$url) && is_array($action)) {
				if (! empty($action[trimLower(self::$url[$index])])) { // check for the action
					$action = $action[trimLower(self::$url[$index])];
					$index++;
					
					// private area of the website
					if (isset($action['enforce'])) 
						$enforce = $action['enforce'];
					
					// set the layout
					if (isset($action['layout'])) 
						View::$layout_in_use = $action['layout'];
					
					// set the language
					if (isset($action['language']))	
						View::$language_in_use = $action['language'];
					
					// register any needed classes
					if (isset($action['register']))	
						self::$includes['FOLDERS'] = $action['register'];
					
					// get the arguments if any 
					if (! empty($action['args'])) 
						$args = array_merge($args, json_decode($action['args'], true));
				}
				else {
					$action = self::$routes['404'];
					break;
				}
			}
			
			// if the result is still an array
			if (is_array($action)) {
				if (! empty($action['redirect'])) { // redirect to another url
					header ('Location: ' . $action['redirect']);
					die ();
				}
				elseif (! empty($action['method']) && ! empty($action['class'])) { // preferred way when a class is called
					$class = $action['class'];
					$action = $action['method'];					
				}
				elseif (! empty($action['action'])) // preferred way when the action is just a function
					$action = $action['action'];
				else 
					$action = self::$routes['404'];
			}
		}
		
		// include all the classes
		if (! empty(self::$includes['FOLDERS'])) {
			// get the folders where the classes are
			$folders = explode(';', self::$includes['FOLDERS']);

			// get the exceptions from the configuration
			$exceptions = empty(self::$includes['EXCEPTIONS']) ? [] : explode(';', self::$includes['EXCEPTIONS']);

			// loop the folders
			foreach ($folders as $f) {
				if (! $f) 
					continue;
				$this->register(FILES_BASE_PATH . $f, $exceptions);
			}
		}

		if (is_dir(FILES_BASE_PATH . 'vendor') && file_exists(FILES_BASE_PATH . 'vendor/autoload.php'))
			require_once(FILES_BASE_PATH . 'vendor/autoload.php'); 
		
		if (! empty($enforce) && is_callable($enforce)) // call the function that enforces login
				call_user_func($enforce, $args);
		
		// lets call the main action now
		if (! empty($class) && is_callable([$c = new $class($args), $action]))
			return $c->$action($args);
		elseif (! empty($action) && is_callable($action)) 
			return call_user_func($action, $args); 
		else 
			die('No action method found!'); 
	}
}

/**
 *
 */
class View
{
	public static $layout_path = '', 
				  $layout_in_use = '', 
				  $language_path = '', 
				  $language_in_use = '', 
				  $full_language = '', 
				  $full_view = ''; 
	
	public function setLang ($language = '') 
	{
		$lang = [];
		
		if (! $language) 
			$language = self::$language_path . self::$language_in_use;
		
		$all_lines = file(FILES_BASE_PATH . $language . '.ini');
		
		foreach ($all_lines as $line) {
			$line = trim($line);
			if (empty($line)) 
				continue;

			$key_value = explode('=>', $line);
			
			if ($key_value[0][0] == '#' || empty($key_value[1])) 
				continue;
			
			$lang[trim($key_value[0])] = trim($key_value[1]);
		}
		
		return $lang;
	}
	
	public function getLang ($props = '') 
	{
		if (! self::$full_language)
			self::$full_language = $this->setLang();
		
		if (empty($props))
			$res = self::$full_language;
		elseif (is_array($props)) {
			$res = [];
			foreach ($props as $p) 
				if (isset(self::$full_language[$p]))
					$res[$p] = self::$full_language[$p];
		}
		elseif (isset(self::$full_language[$props]))
			$res = self::$full_language[$props];
		else 
			$res = false;
		
		return $res;
	}
		
	protected function apply ($html, $all_defs) 
	{
		foreach ($all_defs as $name => $content) {
			$content = addcslashes($content, '\\$'); // this is escaping the $ in the string
			$html = preg_replace('/\<:'.$name.'\/\>/', $content, $html);
		}
		return $html;
	}

	public function inject ($filename, $alldef = [], $clean = false) 
	{
		$filename = FILES_BASE_PATH . $filename;

		if (! file_exists($filename))
			return '';

		$code = file_get_contents($filename);

		if ($code === FALSE)
			return '';

		if ($alldef)
			$code = $this->apply($code, $alldef);

		if ($clean)
			$code = preg_replace('/\<:(.*)\/\>/', '', $code);

		return $code;
	}
	
	public function render ($results = [])
	{
		if (! self::$layout_in_use) 
			return $results;
		
		if (! self::$full_language) 
			self::$full_language = $this->setLang();
				
		$all = array_merge(self::$full_language, $results);
		
		self::$full_view = $this->inject(self::$layout_path . self::$layout_in_use . '.html', $all, true);
		
		return self::$full_view;
	}
}

/**
 *
 */
class Email {
	public static $system = '', 
				  $from = '', 
				  $server = '', 
				  $port = '', 
				  $user = '', 
				  $password = '', 
				  $layout = '', 
				  $language = ''; 
	
	function sendEmail ($emailtoFull, $subjectFull, $content) 
	{
		$pattern = ['/\n/', '/\r/', '/content-type:/i', '/to:/i', '/from:/i', '/cc:/i'];
		
		$subject = preg_replace($pattern, '', $subjectFull);
		
		$emailto = preg_replace($pattern, '', $emailtoFull);

		if (self::$layout) {
			$view = new View();
			if (self::$language) 
				$content = array_merge($content, $view->setLang(LANGUAGE_PATH . self::$language));
			$content = $view->inject(LAYOUT_PATH . self::$layout, $content);
		}
		
		$body = wordwrap($content);

		// if smpt email sending is allowed use PHPMailer
		if (class_exists('PHPMailer')) {
			$mail = new PHPMailer();

			// for debug only
			// $mail->SMTPDebug = 3;

			$mail->isSMTP();
			$mail->Host = self::$server;
			$mail->Port = self::$port;
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->CharSet = 'UTF-8';

			// The password and from email
			$mail->Username = self::$user;
			$mail->Password = self::$password;
			$mail->setFrom(self::$system, self::$from);

			// set the email the subject and the content
			$mail->addAddress($emailto);
			$mail->Subject = $subject;
			$mail->IsHTML(true);
			$mail->Body = $body;
			$mail->AltBody = strip_tags(str_replace('<br>', '\n', $body));

			if (! $mail->send()) { // send email
				$mail_sent = false;
				error_log('Message could not be sent');
				error_log('Mailer Error: ' . $mail->ErrorInfo);
			}
			else 
				$mail_sent = true;
		}
		else { // sent the email with php mail
			$from = self::$from . ' <' . self::$system . '>';

			// Create a boundary for the email. This
			$boundary = uniqid('ch');

			// Create header
			$headers = 'MIME-Version: 1.0' . '\n';
			$headers .= 'From: ' . $from . '\n';
			$headers .= 'Reply-To: ' . $from . '\n';
			$headers .= 'Content-Type: multipart/alternative;boundary=' . $boundary . '\n';

			// Create the body and the txt version
			$message = '\n\n--' . $boundary . '\n';
			$message .= 'Content-type: text/plain;charset=utf-8' . '\n\n';
			$message .= strip_tags(str_replace('<br>', '\n', $body));
			$message .= '\n\n--' . $boundary . '\n';
			$message .= 'Content-type: text/html;charset=utf-8' . '\n\n';
			$message .= $body;
			$message .= '\n\n--' . $boundary . '--';

			// Send email
			$mail_sent = mail($emailto, $subject, $message, $headers);
		}

		return $mail_sent;
	}
}

/**
 *
 */
$_ = function ($query = '', $options = [], $extras = '') 
{
	static $controller = NULL, 
		   $database = NULL, 
		   $view = NULL, 
		   $email = NULL;

	$controller = $controller ?? new Controller();
	$database = $database ?? new Database();
	$view = $view ?? new View();
	$email = $email ?? new Email();
	
	// first get the parts of the query into an array
	$action = $controller->getAction($query);
	$query = $controller->getQuery($query);
	
	// check if the action is defined
	switch ($action) 
	{	
		/******************
		* Express Init & Run
		*******************/
			
		case 'run': 
			$controller->config();
			$database->connect();
			echo $view->render($controller->route());			
			break;
		
		/******************
		* CONTROL ACTIONS
		*******************/

		case 'config:':
			$options = $query;
			
		case 'config': 
			return $controller->config($options);
				
		case 'route:':
			$extras = $options;
			$options = $query;
			
		case 'route': 
			return $controller->route($options, $extras);

		/*******************
		* VIEW ACTIONS
		********************/

		case 'setlang:': 
			$options = $query;
			
		case 'setlang': 
			return $view->setlang($options);
		
		case 'getlang:':
			$options = $query;
			
		case 'getlang':
			return $view->getLang($options);
			
		case 'inject:':
			return $view->inject($query, $options, $extras ? false : true);
	
		case 'render':
			return $view->render($options);
	
		/*******************
		* DATABASE ACTIONS
		********************/
		
		case 'connect':
			return $database->connect();
		
		case 'sanitize':
			return $database->sanitize($options);
		
		// run a literal query and overwrites the default return type with the value 
		case 'single:':
		case 'insertid:':
		case 'obj:':
		case 'assoclist:':
		case 'assoc:':
			$extras = substr($action, 0, -1);

		// run a literal query 
		case 'query:':
		case ':':
			return $database->query($query, $options, $extras ? $extras : []);

		/*******************
		* EMAIL ACTIONS
		********************/
		
		case 'mail:': 
			// send email takes an email, the subject and the content
			return $email->sendEmail($query, $options, $extras);
			break;
		
		/******************
		* Unknowed action,
		* return error 
		*******************/

		default:
			error_log('WOW (not an option): ' . $action . ' \n query: ' . print_r($query, true) . '\n options: ' . print_r($options, true));
			return false;
	}
};

/******** TOOLS **********************/

/**
 *
 */
function trimLower ($str) 
{
	return strtolower(trim($str));
}

/**
 *
 */
function htmlOut ($str = '') 
{
	return nl2br(htmlspecialchars($str, ENT_QUOTES)); // just output in html
}

/**
 *
 */
function linkOut ($urlpage = '', $get_args = []) 
{
	$url = rawurlencode($urlpage);
	if (! empty($get_args)) {
		$url .= '?';
		foreach ($get_args as $key => $value)
			$url .= $key . '=' . urlencode($value);
	}

	return $url;
}

/**
 *
 */
function jsOut($str = '') 
{
	return addslashes(preg_replace('/(\r\n|\n|\r)/', '<br>', $str));
}
