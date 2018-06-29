<?php

/******** CONTROLLER ****************/

class Controller {
	public static $config;

	public static function config($filepath = '', $config = []) { 
		if (empty($filepath))
			$filepath = 'config.json';
					
		self::$config = json_decode(file_get_contents($filepath), true);
		
		if (empty(self::$config))
			die ('No configuration file or error while parsing it!');

		self::$config = array_merge(self::$config, $config);
		
		// CONFIG
		if (isset(self::$config['FILES_BASE_PATH']))  
			DEFINE ('FILES_BASE_PATH', self::$config['FILES_BASE_PATH']);
		else
			DEFINE ('FILES_BASE_PATH', '/');

		if (isset(self::$config['DATABASE']['SERVER']))
			DEFINE ('SERVER_CONFIG', self::$config['DATABASE']['SERVER']);

		if (isset(self::$config['DATABASE']['DATABASE']))
			DEFINE ('SERVER_DATABASE_CONFIG', self::$config['DATABASE']['DATABASE']);

		if (isset(self::$config['DATABASE']['USER']))
			DEFINE ('SERVER_DATABASE_USER_CONFIG', self::$config['DATABASE']['USER']);

		if (isset(self::$config['DATABASE']['PASSWORD']))
			DEFINE ('SERVER_DATABASE_PASSWORD_CONFIG', self::$config['DATABASE']['PASSWORD']);

		if (isset(self::$config['LAYOUT']))
			DEFINE ('LAYOUT_FILES_PATH', self::$config['LAYOUT']);

		if (isset(self::$config['DEFAULT_LAYOUT']))
			DEFINE ('DEFAULT_LAYOUT', self::$config['DEFAULT_LAYOUT']);

		if (isset(self::$config['LANGUAGES']))
			DEFINE ('LANGUAGE_FILES_PATH', self::$config['LANGUAGES']);

		if (! DEFINED('LANGUAGE_IN_USE')) {
			if (! empty($_SESSION['LANGUAGE_IN_USE'])) 
				DEFINE ('LANGUAGE_IN_USE', $_SESSION['LANGUAGE_IN_USE']);
			elseif (isset(self::$config['DEFAULT_LANGUAGE'])) 
				DEFINE ('LANGUAGE_IN_USE', self::$config['DEFAULT_LANGUAGE']);
		}
			
		// SET DEFAULT LANGUAGE
		if (DEFINED('LANGUAGE_IN_USE') && DEFINED('LANGUAGE_FILES_PATH'))
			View::setLanguage();
		
		// SET DEFAULT LAYOUT 
		if (DEFINED('LAYOUT_FILES_PATH') && DEFINED('DEFAULT_LAYOUT'))
			View::setLayout();
	}
	
	public static function route($url = '') {
		if (! empty($url))
			$url = explode('/', $url);
		elseif (! empty($_REQUEST['_url'])) {
			$url = explode('/', $_REQUEST['_url']);
			unset($_REQUEST['_url']);
		}

		// REGISTER CLASSES & FILES
		if (! empty(self::$config['REGISTER'])) {
			// get the exceptions from the configuration
			if (! empty(self::$config['REGISTER']['EXCEPTIONS'])) 
				$exceptions = explode(';', self::$config['REGISTER']['EXCEPTIONS']);
			else 
				$exceptions = array();

			// get the folders where the classes are
			if (! empty(self::$config['REGISTER']['FOLDERS'])) 
				$folders = explode(';', self::$config['REGISTER']['FOLDERS']);
			else
				$folders = array('');
			
			// loop the folders
			foreach ($folders as $f) 
				registerClasses(FILES_BASE_PATH . $f, $exceptions);
		}
		
		// ROUTE
		$class = "";
		$enforce = "";
			
		if (! empty(self::$config['MAINTENANCE']))
			$action = self::$config['MAINTENANCE'];
		elseif (empty($url)) // if there is no _url put the default
			$action = self::$config['ROUTES']['DEFAULT'];
		else {
			$action = self::$config['ROUTES'];
			$index = 0;
			do {
				if (! empty($action[trimLower($url[$index])])) {
					$action = $action[trimLower($url[$index])];
					if (! empty($action["enforce"])) 
						$enforce = $action["enforce"];
					$index++;
				}
				elseif (empty($url[$index])) 
					break;
				else {
					$action = self::$config['ROUTES']['404'];
					break;
				}
			} while($index < count($url)); // && is_array($action)
				
			if (is_array($action)) {
				if (! empty($action['redirect'])) { // redirect to another url
					header ('Location: ' . $action['redirect']);
					die ();
				}
				elseif (! empty($action['method']) && ! empty($action['class'])) { // preferred way when a class is called
					$class = $action['class'];
					$action = $action['method'];					
				}
				elseif (! empty($action['action'])) 
					$action = $action['action'];
				elseif (! empty($action['index'])) // preferred way when the action is just a function
					$action = $action['index'];
				else
					$action = self::$config['ROUTES']['404'];					
			}	
		}
		
		if (! empty($enforce) && is_callable($enforce)) // call the function that enforces login
				call_user_func($enforce, $_REQUEST);
				
		if (! empty($class) && is_callable([new $class($_REQUEST), $action]))
			call_user_func([$class, $action], $_REQUEST);
		elseif (! empty($action) && is_callable($action))
			call_user_func($action, $_REQUEST); 
		else
			die("No action method found!"); 
	}

	public static function preProcess() {
		// overwrite this function
	}
}

/******** VIEW **********************/

class View { 
	public static $full_template = '', $language_in_use, $global_language, $enabled = true, $results = [];

	public static function setLanguage ($language_in_use = '') {
		if (empty($language_in_use)) 
			self::$language_in_use = LANGUAGE_IN_USE;
		else 
			self::$language_in_use = $language_in_use;

		self::$global_language = array ('language_is_empty' => 'Yes');

		$all_lines = file(FILES_BASE_PATH . LANGUAGE_FILES_PATH . self::$language_in_use . '.ini');

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

	public static function getLanguage($prop) {
		if (empty($prop))
			return self::$global_language;
		
		if (! isset(self::$global_language[$prop]))
			return false;

		return self::$global_language[$prop];
	}

	public static function setLayout($layout = '', $all_defs = []) {
		if (empty($layout))
			self::$full_template = injectCode(LAYOUT_FILES_PATH . DEFAULT_LAYOUT . ".html", $all_defs);
		else	
			self::$full_template = injectCode(LAYOUT_FILES_PATH . $layout . ".html", $all_defs); 
	}
	
	public static function setValue($key, $val, $overwrite = false) {
		if ($overwrite || ! isset(self::$results[$key]))
			self::$results[$key] = $val;
		else
			self::$results[$key] .= $val;
	}
	
	public static function cleanValues() {
		self::$results = [];
	}
	
	public static function applyLayout($all_defs = []) {
		self::$results = array_merge(self::$results, $all_defs);

		self::$full_template = applyToCode(self::$full_template, self::$results);
	}
	
	public static function echoLayout() {
		self::$full_template = preg_replace('/\<:(.*)\/\>/', "", self::$full_template);
		echo self::$full_template;
	}
	
	public static function disableLayout() {
		self::$enabled = false;
	}
	
	public static function isEnabled() {
		return self::$enabled;
	}
}

/******** MODEL *********************/

class Model {
	public $metainfo = [
						'table_name' => '', 
						'table_id' => '', 
						'table_fields' => []
					];
	public $result;

	public function __construct ($table) {
		global $_;

		if (empty($table)) 
			throw new Exception('Wrong table: ' . $table);

		$this->metainfo['table_name'] = $table;

		$this->result = $_("assoclist: DESCRIBE `{$table}`");

		if (! $this->result) 
			throw new Exception('Wrong table: ' . $table);

		foreach ($this->result as $r) {
			$this->{$r['Field']} = false;
			$this->metainfo['table_fields'][] = $r['Field'];
			if ($r['Extra'] == 'auto_increment') 
				$this->metainfo['table_id'] = $r['Field'];
		}
	}
	
	public static function getInstance($table) {
		if (empty($table)) 
			return false;
		
		return new Model($table);
	}
	
	private function populateModel ($row, $create = false) {
		foreach ($row as $k => $v) {
			if ($k == 'result' || $k == 'metainfo') 
				continue;

			if (property_exists($this, $k) || $create) 
				$this->$k = $v;
		}
	}

	public function load ($fields, $values) {
		global $_;

		$link = $_('link');

		$where = '';
		$i = 0;
		$j = 0;

		if (count($fields) < count($values) && ! empty($this->metainfo['table_id']))
			$where .= $this->metainfo['table_id'] . " = '" . queryOut($values[$j++], $link) . "'";

		for (; $i < count($fields) && $j < count($values); $i++, $j++) {
			if (! empty($where)) 
				$where .= ' AND ';

			$where .= '`' . queryOut($fields[$i], $link) . "` = '" . queryOut($values[$j], $link) . "'";
		}

		$this->result = $_("assoc: SELECT * FROM `{$this->metainfo['table_name']}` WHERE {$where}");

		if (! empty($this->result)) {
			$this->populateModel($this->result);
			return true;
		}
		else 
			return false;
	}

    public function exist ($fields, $values) {
		global $_;

		$link = $_('link');

		$where = '';
		$i = 0;
		$j = 0;

		if (count($fields) < count($values) && ! empty($this->metainfo['table_id'])) 
			$where .= $this->metainfo['table_id'] . " = '" . queryOut($values[$j++], $link) . "'";

		for (; $i < count($fields) && $j < count($values); $i++, $j++) {
			if (! empty($where)) 
				$where .= ' AND ';
			
			$where .= '`' . queryOut($fields[$i], $link) . "` = '" . queryOut($values[$j], $link) . "'";
		}

        $this->listCount([$where]);

        return $this->result;
    }

	public function listCount ($where = []) {
		$this->listRows('count(*)', $where, 'single');

		return $this->result;
	}

	public function listAll ($where = []) {
		$this->listRows('*', $where);

		return $this->result;
	}

	public function listRows ($what, $where = [], $returns = '') {
		global $_;

		$link = $_('link');

		if (empty($what)) 
			return false;

		$query = '';
		if (is_array($what)) {
			foreach ($what as $v) {
				if (! empty($query)) 
					$query .= ', ';

				$query .= $v;
			}
		}
		else 
			$query = queryOut($what, $link);

		$query = "SELECT {$query} FROM `{$this->metainfo['table_name']}`";

		if (! empty($returns))
			$query = $returns . ': ' . $query;
		else
			$query = 'assoclist: ' . $query;

		$query_where = '';
		$query_group = '';
		$query_having = '';
		$query_order = '';
		$query_limit = '';
		$query_joins = '';
		if (is_array($where)) {
			if (! empty($where['group'])) {
				$query_group = ' GROUP BY ' . queryOut($where['group'], $link);
				unset($where['group']);
			}

			if (! empty($where['having'])) {
				$query_having = ' HAVING ' . queryOut($where['having'], $link);
				unset($where['having']);
			}

			if (! empty($where['order'])) {
				$query_order = ' ORDER BY ' . queryOut($where['order'], $link);
				unset($where['order']);
			}

			if (! empty($where['limit'])) {
				$query_limit = ' LIMIT ' . queryOut($where['limit'], $link);
				unset($where['limit']);
			}

			if (! empty($where))
				foreach ($where as $k => $v) 
					if (strtolower($k) == 'inner join' || strtolower($k) == 'inner') 
						$query_joins .= ' INNER JOIN ' . queryOut($v, $link);
					elseif (strtolower($k) == 'left join' || strtolower($k) == 'left') 
						$query_joins .= ' LEFT JOIN ' . queryOut($v, $link);
					elseif (strtolower($k) == 'right join' || strtolower($k) == 'right') 
						$query_joins .= ' RIGHT JOIN ' . queryOut($v, $link);
					else 
						$query_where .= queryOut($v, $link);
		}
		else 
			$query_where = $where;

		if (! empty($query_where)) 
			$query_where = ' WHERE ' . $query_where;
		else
			$query_where = ' WHERE 1 ';

		$this->result = $_("{$query} {$query_joins} {$query_where} {$query_group} {$query_having} {$query_order} {$query_limit}");
		
		return $this->result;
	}

	public function insertRow ($skip_all = true) {
		global $_;

		$link = $_('link');

		if (! empty($this->metainfo['table_id'])) 
			$use_id = $this->metainfo['table_id'];
		else 
			$use_id = '';

		$skip = array();
		if (is_array($skip_all)) 
			$skip = $skip_all;
		elseif ($skip_all)
			foreach ($this->metainfo['table_fields'] as $f)
				if ($this->$f === false) 
					$skip[] = $f;

		$query_fields = '';
		$query_values = '';
		foreach ($this->metainfo['table_fields'] as $key) {
			if ($use_id == $key || in_array($key, $skip)) 
				continue;

			if (! empty($query_fields)) {
				$query_fields .= ', ';
				$query_values .= ', ';
			}
			
			$query_fields .= '`$key`';
			$query_values .= "'" . queryOut($this->$key, $link) . "'";
		}

		$insert_id = $_("insertid: INSERT INTO `{$this->metainfo['table_name']}` ({$query_fields}) VALUES ({$query_values})");

		if ($insert_id) {
			if (! empty($use_id)) 
				$this->loadById($insert_id);

			return $insert_id;
		}
		else 
			return false;
	}

	public function updateRow ($skip_all = true) {
		global $_;

		$link = $_('link');

		if (empty($this->metainfo['table_id']))
			return false;

		$use_id = $this->metainfo['table_id'];
		$use_value = queryOut($this->$use_id, $link);
		if (empty($use_value))
			return false;

		$skip = array();
		if (is_array($skip_all)) 
			$skip = $skip_all;
		else if ($skip_all)
			foreach ($this->metainfo['table_fields'] as $f)
				if ($this->$f === false) 
					$skip[] = $f;

		$query = "UPDATE `{$this->metainfo['table_name']}`";

		$query_values = '';
		foreach ($this->metainfo['table_fields'] as $key) {
			if ($use_id == $key || in_array($key, $skip)) 
				continue;
			
			if (! empty($query_values)) 
				$query_values .= ', ';

			$query_values .= "`{$key}` = '". queryOut($this->$key, $link) ."'";
		}
		$query_values = ' SET ' . $query_values;

		$this->result = $_(": {$query} {$query_values} WHERE `{$use_id}` = '{$use_value}'");

		if ($this->result) {
			$this->loadById($this->$use_id);
			return true;
		}
		else 
			return false;
	}

	public function deleteRow () {
		global $_;

		$link = $_('link');

		if (! empty($this->metainfo['table_id'])) 
			return false;

		$use_id = $this->metainfo['table_id'];

		$use_value = queryOut($this->$use_id, $link);
		
		if (empty($use_value)) 
			return false;
		
		$this->result = $_(": DELETE FROM `{$this->metainfo['table_name']}` WHERE `{$use_id}` = '{$use_value}'");

		return $this->result;
	}
}

/******** MySQL *********************/

$_ = function ($query = '', $options = '', $extras = '') {
	static $query_obj;

	// create the queryObject
	if (empty($query_obj)) 
		$query_obj = new ExternalQuery();
	
	// first get the parts of the query into an array
	$args = cleanArguments($query);

	// get the main action, the first word, and unset it 
	$action = strtolower($args[0]);
	array_splice($args, 0, 1);

	// this is tricky...
	if (empty($options) && strpos($action, ':') === false && $action != 'query') {
		$options = $args;
		$repeated_args = true;
	}
	else 
		$repeated_args = false;

	// check if the action is defined
	switch ($action) {
		// connect to a database
		case 'connect':
			return $query_obj->connection($options);
			break;

		// disconnect from the database
		case 'disconnect':
			$query_obj->disconnect($options);
			break;
			
		// get the link to the database
		case 'link':
			return $query_obj->link();
			break;

		// run a literal query and overwrites the default return type with the value 
		case 'single:':
		case 'insertid:':
		case 'obj:':
		case 'assoclist:':
		case 'assoc:':
			$options = substr($action, 0, -1);

		// run a literal query 
		case ':':
			return $query_obj->query($args, $options);
			break;

		// short hand action for getting everything (*) from a table
		case '*':
			$tmp_args[0] = 'SELECT * FROM ';
			$tmp_args[1] = $args[0];
			$tmp_args[2] = ' WHERE ';

			for ($index = 1; $index < count($args); $index++) 
				$tmp_args[2] .= $args[$index] . ' ';

			if (! $repeated_args) {
				if (is_array($options)) 
					foreach ($options as $o) 
						$tmp_args[2] .= $o . ' ';
				else 
					$tmp_args[2] .= $options;
			}

			$tmp_args[3] = $extras;
			$args = $tmp_args;
			return $query_obj->query($args, 'default');
			break;

		// short hand action for getting count(*) from a table
		case 'count(*)':
			$tmp_args[0] = 'SELECT COUNT(*) FROM ';
			$tmp_args[1] = $args[0];
			$tmp_args[2] = ' WHERE ';

			for ($index = 1; $index < count($args); $index++) 
				$tmp_args[2] .= $args[$index] . ' ';

			if (! $repeated_args) {
				if (is_array($options)) 
					foreach ($options as $o) 
						$tmp_args[2] .= $o . ' ';
				else 
					$tmp_args[2] .= $options;
			}

			$tmp_args[3] = $extras;
			$args = $tmp_args;
			return $query_obj->query($args, 'single');
			break;

		// unknowed action, return error
		default:
			error_log('WOW (not an option): ' . $action . ' \n query: ' . print_r($query, true) . 
						'\n options: ' . print_r($options, true) . '\n extras: ' . print_r($extras, true));
			return false;
			break;
	}
};

class ExternalQuery {
	static $link = NULL;
	static $result = NULL;
	static $ret = 'assoc'; 

	// a do nothing constructor, internalQuery takes care of whatever needs to be done
	public function __construct () {
		 self::connection();
	}

	public static function connection ($args = '') {
		// if the link is not empty, abort the previous connection
		if (! empty(self::$link)) 
			mysqli_close(self::$link);

		// create the new connection
		if (! defined('SERVER_CONFIG') || ! defined('SERVER_DATABASE_CONFIG') || ! defined('SERVER_DATABASE_USER_CONFIG') || ! defined('SERVER_DATABASE_PASSWORD_CONFIG'))
			die ('No database configuration!');

		$server = SERVER_CONFIG;
		$user = SERVER_DATABASE_USER_CONFIG;
		$pass = SERVER_DATABASE_PASSWORD_CONFIG;
		$database = SERVER_DATABASE_CONFIG;
		
		self::$link = @new mysqli($server, $user, $pass, $database);

		if (! self::$link || ! empty(self::$link->connect_errno) || ! empty(self::$link->connect_error)) 
			die('Could not connect: ' . @mysqli_error(self::$link) . '<br>' . 
				self::$link->connect_errno . '<br>' . self::$link->connect_error);

		// return the link
		return self::$link;
	}
	
	public static function disconnect ($args) {
		// check if args is empty and disconnect the current link 
		if (empty($args)) 
			mysqli_close(self::$link);
		else  // disconnect the link in args 
			mysqli_close($args);
	}
	
	public static function link () {
		return self::$link;
	}
	
	public static function query ($args, $opts = '') {
		$q = implode(' ', $args);

		self::$result = self::$link->query($q);

		if (! self::$result) {
			error_log('WOW (query): ' . $q);
			
			return false;
		}
		elseif (! empty($opts)) {
			$res = self::result($opts);
			
			return $res;
		}

		return true;
	}
	
	public static function result ($args = '', $opts = '') {
		$act = self::$ret;
		if (! empty($args)) 
			$act = trimLower($args);
		elseif (! empty($opts)) 
			$act = trimLower($opts);

		switch ($act) {
			case 'single':
				$tmp = self::$result->fetch_row();
				return $tmp[0];
				break;

			case 'insertid':
				return self::$link->insert_id;
				break;

			case 'obj':
				return self::$result->fetch_object();
				break;
				
			case 'assoclist':
				$rows = [];
				while($row = self::$result->fetch_array(MYSQLI_ASSOC))
					$rows[] = $row;

				return $rows; // mysqli_fetch_array(self::$result, MYSQLI_ASSOC); //->fetch_all(MYSQLI_ASSOC);
				break;

			case 'assoc':
			default:
				return self::$result->fetch_assoc();
				break;
		}
	}
}

/******** EMAILS ********************/

function sendEmail ($subject, $content, $emailto) {
	$pattern = array('/\n/', '/\r/', '/content-type:/i', '/to:/i', '/from:/i', '/cc:/i');
	
	$subject = preg_replace($pattern, '', $subject);
	
	$emailto = preg_replace($pattern, '', $emailto);

	$body = wordwrap($content);

    // if smpt email sending is allowed use PHPMailer
	if (SMTP_EMAIL && class_exists('PHPMailer')) {
		$mail = new PHPMailer;

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
        $mail->setFrom(SYSTEM_EMAIL, 'PAMISLA');

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
		$mail_sent = @mail($emailto, $subject, $message, $headers);
	}

    return $mail_sent;
}

/******** TOOLS *********************/

function registerClasses ($folder, $exceptions) {
	if (empty($folder))
		return false;
	
	$other_files = array();

    $exceptions[] = '$_';
    $exceptions[] = '2cents';
	$exceptions[] = 'index.php';
				
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
            registerClasses($of . '/', $exceptions);
}

function cleanArguments ($query) {
    $query = explode (' ', trim($query));

    if (strrpos($query[0], ':') !== false) {
        $tmp = explode(':', $query[0]);
        if (count($tmp) > 1 && ! empty($tmp[1])) {
            unset($query[0]);
            array_unshift($query, $tmp[0], $tmp[1]);
        }
    }

    $final = array();
    foreach ($query as $q) {
        if ($q == ':' && count($final) == 1) { // special case
            $final[0] .= $q;
            continue;
        }
        $final[] = $q;
    }

    return $final;
}

function sanitizeValues ($var, $link = null) {
	if (empty($var)) 
		return $var;

    if (is_array($var)) {
        foreach ($var as $key => $subvar)
            $result[$key] = sanitizeValues($subvar, $link);

        $var = $result;
    }
    else 
		$var = queryOut($var, $link); //htmlentities

    return $var;
}

function queryOut($str = '', $link = NULL) {
    global $_;

    if ($link == NULL)
        $link = $_('link');

    return mysqli_real_escape_string($link, $str);
}

function trimLower ($str) {
 	return strtolower(trim($str));
}

function jsOut($str = '') {
    return addslashes(preg_replace('/(\r\n|\n|\r)/', '<br>', $str));
}

function htmlOut ($str = '') {
    return nl2br(htmlspecialchars($str, ENT_QUOTES)); // just output in html
}

function linkOut ($urlpage = '', $get_args = array()) {
    $url = rawurlencode($urlpage);
    if (! empty($get_args)) {
        $url .= '?';
        foreach ($get_args as $key => $value) 
            $url .= $key . '=' . urlencode($value);
    }
	
    return $url;
}

function applyToCode ($html, $all_defs) {
    foreach ($all_defs as $name => $content) {
        $content = addcslashes($content, '\\$'); // this is escaping the $ in the string
        $html = preg_replace('/\<:'.$name.'\/\>/', $content, $html);
    }
    return $html;
}

function injectCode ($filename, $alldef = array(), $clean = false) {
 	$filename = FILES_BASE_PATH . $filename;

	if (! file_exists($filename)) 
        return '';

    $code = file_get_contents($filename);

	if ($code === FALSE) 
		return '';

    if (! empty($alldef)) 
        $code = applyToCode($code, $alldef);

    if ($clean)
        $code = preg_replace('/\<:(.*)\/\>/', "", $code);

	return $code;
}
