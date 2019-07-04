# My two cents

File structure:
/
	.htaccess
	config.json
	index.php
/app/
	/controllers/
		index.php
	/layouts/
		home.html
	/languages/
		en.ini

/.htaccess:
	<IfModule mod_rewrite.c>
		RewriteEngine On
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteRule ^((?s).*)$ index.php?_url=$1 [QSA,L]
	</IfModule>

	<Files "config.json">
		Order Allow,Deny
		Deny from all
	</Files>

	Options -Indexes

/config.json:
	{
		"BASE_PATH": "/",

		"LANGUAGES_PATH": "app/languages/",
		"DEFAULT_LANGUAGE": "en",

		"LAYOUT_PATH": "app/layouts/",
		"DEFAULT_LAYOUT": "home",

		"REGISTER":
		{
			"EXCEPTIONS": "",
			"FOLDERS": "app/"
		},

		"ROUTES":
		{
			"DEFAULT": "index",
			"404": "notFound"
		}
	}

/index.php
    <?php
		session_start();

		require_once('$_.php');

		$_('run');

/app/contollers/index.php:
    <?php
		function index($args) {
			return ["OUTPUT" => "hello, world"];
		}

		function notFound() {
			die("404");
		}

/app/layout/home.html:
	<:TITLE/>
	<:OUTPUT/>

/app/language/en.ini
	TITLE=>Hello

Layouts
	Adding HTML
	Adding css
	Adding javascript
	Injecting code
		HTML
		css
		javascript

Languages
	Adding various languages

Registering

Routing
	indexes, action, sub-routes
	classes
	passing arguments
	privacy
		enforce
		login
		logout
		ajax calls
	adding registering
	changing templates and language
	redirection

Connecting to a database and doing queries
	include here the mysql code

Working with models
	
	validation of card number
    /**
     * Credit card number patterns used to figure out the credit card network
     * @var array
     */
    const CARD_PATTERNS = array(
      'AMEX'     => '/(^34|^37)\d{13}/',
      'VISA'     => '/(^4)(\d{15}|\d{12})/',
      'MC'       => '/((^5[1-5])(\d{17}|\d{14}))|((^2[2-7])(\d{17}|\d{14}))/',
      'DISCOVER' => '/^30[0-5]\d{5}|^3095\d{4}|^35(2[8-9]\d{4}|[3-8]\d{5})|' .
                    '^36|^38|^39|^64|^65|^6011|^62(2(1(2[6-9]\d{2}|' .
                    '[3-9]\d{2}|[3-9]\d{3})|[2-9]\d{4})|[3-6]\d{5})|' .
                    '^628[2-8]\d{4}/'
    );

Working with emails
