<?php

// --
date_default_timezone_set("America/Fortaleza");

// -- Define variables of system
$url = explode("/", @$_REQUEST["url"]);

define("ENVIRONMENT", "localhost"); // localhost || homologation || production
define("SQL", "hide"); // show || hide

/*
 * -- ERROS
 */
if(ENVIRONMENT == "localhost" || ENVIRONMENT == "homologation"){
	ini_set("display_errors",1);
	ini_set("display_startup_erros",1);
	error_reporting(E_ALL);
}

/*
 * -- LIBRARY --- ---
 */
if(ENVIRONMENT == "localhost"){
    define("DOMAIN", "http://conexaophp.lan/");
    define("BAR", "\\");
    define("ROOT_FILES", "C:".BAR."xampp".BAR."htdocs".BAR."conexaophp".BAR);
	define("DBS", ROOT_FILES."library".BAR."dbs".BAR);
	define("CONTROLLERS", ROOT_FILES."library".BAR."controllers".BAR);
	define("HTML", ROOT_FILES."public_html".BAR);
	define("TESTS", CONTROLLERS."tests".BAR);
} else if(ENVIRONMENT == "homologation"){
    define("DOMAIN", "http://homolog.conexaophp.com/");
    define("BAR", "/");
	define("ROOT_FILES", BAR."home".BAR."conexaophp".BAR);
	define("HTML", ROOT_FILES."public_html".BAR);
	define("DBS", ROOT_FILES."library".BAR."dbs".BAR);
	define("CONTROLLERS", ROOT_FILES."library".BAR."controllers".BAR);
} else if(ENVIRONMENT == "production"){
    define("ROOT", "/");
    define("BAR", "/");
	define("ROOT_FILES", BAR."home".BAR."conexaophp".BAR);
	define("HTML", ROOT_FILES."public_html".BAR);
	define("DBS", ROOT_FILES."library".BAR."dbs".BAR);
	define("CONTROLLERS", ROOT_FILES."library".BAR."controllers".BAR);
} 

// ---
// -- AutoLoader to classes
function autoLoader($className){

	$directories = array(
		CONTROLLERS
	);
	 
	foreach($directories as $directory){

		$path = $directory.sprintf('%s.php', $className);
		
		if(file_exists($path)){
			include_once $path;
			return;
		}
	}
}

spl_autoload_register('autoLoader');

?>