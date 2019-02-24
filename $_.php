<?php

/*
	Dollar Lib - https://github.com/beovideskevin/dollarlib 
	Copyright (c) 2016 Flow with the Code

	This file is part of Dollar Lib.

    Dollar Lib is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Dollar Lib is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Dollar Lib.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* This is the main file of the Dollar Lib, include this file 
* and you don't need to include anything else 
*/

class QueryClass
{
	protected static $link = NULL, $result = NULL; 

	public function connection () 
	{
		// if the link is not empty, abort the previous connection
		if (! empty(self::$link)) { 
			$this->disconnect();
		}

		// create the new connection
		if (! defined('DATABASE_SERVER') || ! defined('DATABASE_CONFIG') || ! defined('DATABASE_USER_CONFIG') || ! defined('DATABASE_PASSWORD_CONFIG')) {
			die ('No database configuration!');
		}

		$server = DATABASE_SERVER;
		$user = DATABASE_USER_CONFIG;
		$pass = DATABASE_PASSWORD_CONFIG;
		$database = DATABASE_CONFIG;
		
		self::$link = new mysqli($server, $user, $pass, $database);

		if (! self::$link || ! empty(self::$link->connect_errno) || ! empty(self::$link->connect_error)) {
			die('Could not connect: ' . mysqli_error(self::$link) . '<br>' . self::$link->connect_errno . '<br>' . self::$link->connect_error);
		}
	}
	
	protected function disconnect () 
	{
		mysqli_close(self::$link);
	}
	
	public function sanitize ($var) 
	{
		if (empty($var)) {
			return $var;
		}

		if (is_array($var)) {
			foreach ($var as $key => $subvar) {
				$result[$key] = $this->sanitize($subvar);
			}

			$var = $result;
		}
		else {
			$var = mysqli_real_escape_string(self::$link, $var); 
		}
		
		return $var;
	}
		
	public function query ($query, $args = '', $ret = '') 
	{
		if (! empty($args)) {
			$args = $this->sanitize($args);
			$i = 0;
			while(($letter_pos = strpos($query, '?')) !== false) {
				$query = substr_replace($query, $args[$i], $letter_pos, 1);
				$i++;
				if ($i > count($args)) {
					break;
				}
			}
		}
		
		self::$result = self::$link->query($query);

		if (! self::$result) {
			error_log('WOW (query): ' . $query);
			
			return false;
		}
		elseif (! empty($ret)) {
			$res = $this->result($ret);
			
			return $res;
		}

		return true;
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
				while($row = self::$result->fetch_array(MYSQLI_ASSOC)) {
					$rows[] = $row;
				}

				return $rows;

			case 'assoc':
			default:
				return self::$result->fetch_assoc();
		}
	}
}

class MVClass extends QueryClass
{
	protected static $config = [], $full_template = '', $language_in_use = '';
	public static $global_language = ['language_is_empty' => 'Yes']; // you may to access this one from outside
	
	public function getAction ($fullQuery) 
	{
		$query = explode (' ', trim($fullQuery));
		
		if (strrpos($query[0], ':') === 0)
			$action = ":";
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
		
		return implode(" ", $parts);
	}
	
	public function config($filepath = '') 
	{ 
		if (empty($filepath))
			$filepath = 'config.json';
					
		self::$config = json_decode(file_get_contents($filepath), true);
		
		if (empty(self::$config))
			die ('No configuration file or error while parsing it!');

		// the main path
		if (isset(self::$config['FILES_BASE_PATH'])) 
			DEFINE ('FILES_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/' . self::$config['FILES_BASE_PATH']);
		else 
			DEFINE ('FILES_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');

		// MySQL conection data
		if (isset(self::$config['DATABASE']['DRIVER'])) 
			DEFINE ('DATABASE_DRIVER', self::$config['DATABASE']['DRIVER']);
		
		if (isset(self::$config['DATABASE']['SERVER'])) 
			DEFINE ('DATABASE_SERVER', self::$config['DATABASE']['SERVER']);

		if (isset(self::$config['DATABASE']['DATABASE'])) 
			DEFINE ('DATABASE_CONFIG', self::$config['DATABASE']['DATABASE']);

		if (isset(self::$config['DATABASE']['USER'])) 
			DEFINE ('DATABASE_USER_CONFIG', self::$config['DATABASE']['USER']);

		if (isset(self::$config['DATABASE']['PASSWORD'])) 
			DEFINE ('DATABASE_PASSWORD_CONFIG', self::$config['DATABASE']['PASSWORD']);
		
		// SMTP email configuration
		if (isset(self::$config['EMAIL']['SMTP_EMAIL'])) 
			DEFINE ('SMTP_EMAIL', self::$config['EMAIL']['SMTP_EMAIL']);
		
		if (isset(self::$config['EMAIL']['SMTP_SERVER'])) 
			DEFINE ('SMTP_SERVER', self::$config['EMAIL']['SMTP_SERVER']);
		
		if (isset(self::$config['EMAIL']['SMTP_PORT'])) 
			DEFINE ('SMTP_PORT', self::$config['EMAIL']['SMTP_PORT']);
		
		if (isset(self::$config['EMAIL']['SMTP_USER'])) 
			DEFINE ('SMTP_USER', self::$config['EMAIL']['SMTP_USER']);
		
		if (isset(self::$config['EMAIL']['SMTP_PASSWORD'])) 
			DEFINE ('SMTP_PASSWORD', self::$config['EMAIL']['SMTP_PASSWORD']);
		
		if (isset(self::$config['EMAIL']['SYSTEM_EMAIL'])) 
			DEFINE ('SYSTEM_EMAIL', self::$config['EMAIL']['SYSTEM_EMAIL']);

		if (isset(self::$config['EMAIL']['SYSTEM_FROM'])) 
			DEFINE ('SYSTEM_FROM', self::$config['EMAIL']['SYSTEM_FROM']);

		// the layout
		if (isset(self::$config['LAYOUT_PATH'])) 
			DEFINE ('LAYOUT_PATH', self::$config['LAYOUT_PATH']);

		if (! empty($_SESSION['LAYOUT_IN_USE']))
			DEFINE ('LAYOUT_IN_USE', $_SESSION['LAYOUT_IN_USE']);
		elseif (isset(self::$config['DEFAULT_LAYOUT'])) 
			DEFINE ('LAYOUT_IN_USE', self::$config['DEFAULT_LAYOUT']);
		
		// the language
		if (isset(self::$config['LANGUAGES_PATH']))
			DEFINE ('LANGUAGE_PATH', self::$config['LANGUAGES_PATH']);

		if (! empty($_SESSION['LANGUAGE_IN_USE']))
			DEFINE ('LANGUAGE_IN_USE', $_SESSION['LANGUAGE_IN_USE']);
		elseif (isset(self::$config['DEFAULT_LANGUAGE']))
			DEFINE ('LANGUAGE_IN_USE', self::$config['DEFAULT_LANGUAGE']);
	}
	
	public function register() 
	{
		// REGISTER CLASSES & FILES
		if (! empty(self::$config['REGISTER'])) {
			// get the exceptions from the configuration
			if (! empty(self::$config['REGISTER']['EXCEPTIONS'])) 
				$exceptions = explode(';', self::$config['REGISTER']['EXCEPTIONS']);
			else 
				$exceptions = [];

			// get the folders where the classes are
			if (! empty(self::$config['REGISTER']['FOLDERS']))
				$folders = explode(';', self::$config['REGISTER']['FOLDERS']);
			else 
				$folders = [''];
			
			// loop the folders
			foreach ($folders as $f) {
				if (empty($f)) 
					continue;
				$this->reginclude(FILES_BASE_PATH . $f, $exceptions);
			}
			
			if (is_dir(FILES_BASE_PATH . "vendor") && file_exists(FILES_BASE_PATH . 'vendor/autoload.php'))
				require_once(FILES_BASE_PATH . 'vendor/autoload.php'); 
		}
	}
	
	protected function reginclude ($folder, $exceptions) 
	{
		if (empty($folder))
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
				$this->reginclude($of . '/', $exceptions);
	}
	
	public function route() 
	{
		$class = "";
		$enforce = "";
		
		if (! empty($_REQUEST['_url'])) {
			$url = explode('/', $_REQUEST['_url']);
			unset($_REQUEST['_url']);
		}
		else
			$url = [];
		
		$args = $_REQUEST;
			
		if (empty($url)) // if there is no _url put the default
			$action = self::$config['ROUTES']['DEFAULT'];
		else {
			$action = self::$config['ROUTES'];
			$index = 0;
			
			while($index < count($url) && is_array($action)) {
				if (empty($url[$index])) // this takes care of the trailing /
					break;
				elseif (! empty($action[trimLower($url[$index])])) { // check for the action
					$action = $action[trimLower($url[$index])];
					if (! empty($action["enforce"])) 
						$enforce = $action["enforce"];
					$index++;
				}
				else {
					$action = self::$config['ROUTES']['404'];
					break;
				}
			}
			
			// if the result is still an array
			if (is_array($action)) {
				if (! empty($action['args'])) // get the arguments if any 
					$args = array_merge($args, json_decode($action['args'], true));

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
					$action = self::$config['ROUTES']['404'];
			}
		}
		
		if (! empty($enforce) && is_callable($enforce)) // call the function that enforces login
				call_user_func($enforce, $args);
		
		// lets call the main action now
		if (! empty($class) && is_callable([new $class($args), $action]))
			call_user_func([$class, $action], $args);
		elseif (! empty($action) && is_callable($action)) 
			call_user_func($action, $args); 
		else 
			die("No action method found!"); 
	}
	
	public function language ($language_in_use = '') 
	{
		if (empty($language_in_use))
			self::$language_in_use = LANGUAGE_IN_USE;
		else 
			self::$language_in_use = $language_in_use;

		self::$global_language = ['language_is_empty' => 'Yes'];

		$all_lines = file(FILES_BASE_PATH . LANGUAGE_PATH . self::$language_in_use . '.ini');

		foreach ($all_lines as $line) {
			$line = trim($line);
			if (empty($line)) 
				continue;

			$key_value = explode('=>', $line);
			
			if ($key_value[0][0] == '#' || empty($key_value[1])) 
				continue;
			
			self::$global_language[trim($key_value[0])] = trim($key_value[1]);
		}
	}
	
	public function getLang($props = '') 
	{
		$res = [];
		
		if (empty($props))
			$res = self::$global_language;
		elseif (is_array($props)) {
			foreach ($props as $p) 
				if (isset(self::$global_language[$p]))
					$res[$p] = self::$global_language[$p];
		}
		elseif (isset(self::$global_language[$props]))
			$res[$props] = self::$global_language[$props];
		
		return $res;
	}

	public function layout ($layout = '') 
	{
		if (empty($layout)) 
			self::$full_template = $this->inject(LAYOUT_PATH . LAYOUT_IN_USE . ".html");
		else 
			self::$full_template = $this->inject(LAYOUT_PATH . $layout . ".html"); 
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

		if (! empty($alldef))
			$code = $this->apply($code, $alldef);

		if ($clean)
			$code = preg_replace('/\<:(.*)\/\>/', "", $code);

		return $code;
	}
	
	public function render ($results = []) 
	{
		$all = array_merge(self::$global_language, $results);
		
		self::$full_template = $this->apply(self::$full_template, $all);
		
		self::$full_template = preg_replace('/\<:(.*)\/\>/', "", self::$full_template);
		
		return self::$full_template;
	} 
}

$_ = function ($query = '', $options = [], $extras = '') 
{
	static $query_obj = NULL;

	// create the queryObject
	if (empty($query_obj)) 
		$query_obj = new MVClass();
	
	// first get the parts of the query into an array
	$action = $query_obj->getAction($query);
	$query = $query_obj->getQuery($query);
	
	// check if the action is defined
	switch ($action) {
		// express initiaization
		case 'init':
			$query_obj->config();
			$query_obj->register();
			$query_obj->connection();
			$query_obj->language();
			$query_obj->layout();
			$query_obj->route();
			break;
		
		// set the config file
		case 'config:':
			$options = $query;
			
		case 'config': 
			return $query_obj->config($options);
		
		case 'register': 
			return $query_obj->register();
		
		case 'route': 
			return $query_obj->route();

		// set the default language
		case 'language:': 
			$options = $query;
			
		case 'language': 
			return $query_obj->language($options);
		
		case 'getlang:':
			$options = $query;
			
		case 'getlang':
			return $query_obj->getLang($options);
			
		// set thed efault layout
		case 'layout:': 
			$options = $query;
			
		case 'layout': 
			return $query_obj->layout($options);
	
		case 'inject:':
			return $query->inject($query, $options, empty($extras) ? false : true);
	
		// render the layout with the language
		case 'render':
			return $query_obj->render($options);
	
		// connect to a database
		case 'connect':
			return $query_obj->connection();
		
		case 'sanitize':
			return $query_obj->sanitize($options);
		
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
			return $query_obj->query($query, $options, $extras);

		// unknowed action, return error
		default:
			error_log('WOW (not an option): ' . $action . ' \n query: ' . print_r($query, true) . '\n options: ' . print_r($options, true));
			return false;
	}
};

/******** TOOLS **********************/

function trimLower ($str) 
{
	return strtolower(trim($str));
}

function htmlOut ($str = '') 
{
	return nl2br(htmlspecialchars($str, ENT_QUOTES)); // just output in html
}

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

function jsOut($str = '') 
{
	return addslashes(preg_replace('/(\r\n|\n|\r)/', '<br>', $str));
}

/******** EMAILS ********************/

function sendEmail ($subjectFull, $content, $emailtoFull) 
{
	$pattern = ['/\n/', '/\r/', '/content-type:/i', '/to:/i', '/from:/i', '/cc:/i'];
	
	$subject = preg_replace($pattern, '', $subjectFull);
	
	$emailto = preg_replace($pattern, '', $emailtoFull);

	$body = wordwrap($content);

    // if smpt email sending is allowed use PHPMailer
	if (SMTP_EMAIL && class_exists('PHPMailer')) {
		$mail = new PHPMailer();

        // for debug only
        // $mail->SMTPDebug = 3;

        $mail->isSMTP();
        $mail->Host = SMTP_SERVER;
        $mail->Port = SMTP_PORT;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
		$mail->CharSet = 'UTF-8';

        // The password and from email
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->setFrom(SYSTEM_EMAIL, SYSTEM_FROM);

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
        else {
            $mail_sent = true;
		}
	}
	else { // sent the email with php mail
		$from = SYSTEM_FROM . ' <' . SYSTEM_EMAIL. '>';

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