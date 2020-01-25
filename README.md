# My two cents

My2Cents is a simple but feature rich php framework. You can see a full working example here: <a href="https://my2cents.eldiletante.com/">my2cents</a>

After cloning the repository add a file named "config.json" to the root folder with the following code:
```
{
	"FILES_BASE_PATH": "/",
	
	"TEMPLATE": {
		"LANGUAGE_PATH": "app/languages/",
		"DEFAULT_LANGUAGE": "en",
		"LAYOUT_PATH": "app/layouts/",
		"DEFAULT_LAYOUT": "example"
	},
	
	"DATABASE": {
		"ADAPTER": "MYSQL",
		"HOST": "localhost",
		"PORT": "3306",
		"DATABASE": "my2cents",
		"USER": "yourusername",
		"PASSWORD": "yourpassword"
	},
	
	"EMAIL": {
		"SYSTEM": "my2cents@gmail.com",
		"FROM": "My2Cents",
		"SERVER": "",
		"PORT": "",
		"USER": "",
		"PASSWORD": "",
		"LAYOUT": "email"
	},
	
	"REGISTER": {
		"EXCEPTIONS": "",
		"FOLDERS": "app/"
	},
	
	"ROUTES": {
		"DEFAULT": {
			"redirect": "/quotes"
		},
		"404": "notFound",
		"example1": "hello",
		"example2": {
			"class": "ExampleClass",
			"method": "exampleMethod"
		},
		"example3": {
			"redirect": "/example1",
			"hello": "hello" 
		},
		"example4": {
			"action": "publicArea",
			"private": {
				"enforce": "enforce",
				"action": "privateArea"
			}
		},
		"example5" : {
			"action": "arguments",
			"args": "{\"test\": \"1\", \"outside\": {\"inside\": \"1\"}}"
		},
		"example6": {
			"action": "hello",
			"arguments" : {
				"action": "arguments",
				"args": "{\"test\": \"1\", \"outside\": {\"inside\": \"1\"}}"
			}
		},
		"login": {
			"action": "login"
		},
		"logout": "logout",
		"ajax": {
			"action": "ajax",
			"layout": "simple",
			"admin": {
				"enforce": "enforce"
			}
		},
		"btc": {
			"action": "BTCFullExample",
			"refresh": {
				"action": "refreshBTCPrice",
				"layout": "simple"
			},
			"save": { 
				"action": "saveBTCPrice",
				"layout": "simple"
			}
		},
		"quotes": {
			"action": "Quotes\\showQuotes",
			"layout": "quotes",
			"language": "quotes",
			"signin": {
				"action": "Quotes\\signInQuotes"
			},
			"signup": {
				"action": "Quotes\\signupQuotes"
			},
			"logout": "Quotes\\logoutQuote",
			"dashboard": {
				"enforce": "Quotes\\enforceQuotes",
				"action": "Quotes\\dashboardQuotes",
				"new": "Quotes\\newQuotes",
				"edit": "Quotes\\editQuotes"
			},
			"ajax": {
				"layout": "simple",
				"get": "Quotes\\Ajax\\getQuotes",
				"admin": {
					"enforce": "Quotes\\Ajax\\enforceAjaxQuotes",	
					"add": "Quotes\\Ajax\\addQuotes",
					"del": "Quotes\\Ajax\\delQuotes"
				}
			}
		}
	}
}
```

I developed this framkework using using WAMP. All the examples should work in an Apache virtual host. If you are using IIS or Nginx you will need to make some changes in the configuration files of your server (read ".htaccess" and reproduce that logic). In order to make all the examples work you will need to import into the database the scripts located in the folder "migrartions". These were created for MySQL, if you are using PostgreSQL or somethng else, you may need to make some changes.

