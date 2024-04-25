<?php
/**
 * Conten-o-mat Einstellungen/Konstanten
 */
 
/**
 * Only define constants if it isn't yet
 * @param string $constant  constant name
 * @param mixed $default   value
 */
function setConstant($constant, $default) {
	if (!defined($constant)) {
		define($constant, $default);
	}
}


////////////////////////
/// Projekt Settings ///
////////////////////////

// Name des Webs
setConstant('WEBNAME', 'CMT');

// PHP Namespace
setConstant('APP_NAMESPACE', 'CMT');

// Error reporting level: One of 'all', 'warning', 'strict' or 'error'
setConstant('CMT_ERRORREPORTING_LEVEL', 'error');

setConstant('MAINTENANCE_MODE', false);

// set the production main URL (needed if used in CLI mode)
setConstant('URL', "https://www.example.com");

///////////////////////////
/// Additional Settings ///
///////////////////////////

/**
 * Localasation settings
 */
// Standardsprache für mehrsprachige Websites
setConstant('DEFAULTLANGUAGE', 'de');

// veraltet: Zeichensatz, der für die Seite verwendet wird
setConstant('CHARSET', 'utf-8');

// Zeichensatz, der verwendet wird, falls kein anderer Zeichensatz angegeben wird
setConstant('CMT_DEFAULTCHARSET', 'utf-8');

// Standardzeitzone für PHP-Funktion 'date'
setConstant('CMT_DEFAULTTIMEZONE', 'Europe/Berlin');


/**
 * Additional Settings
 */

// Set environment in .htacces, e.g. 
// ```
// <IfModule mod_env.c>
//   SetEnv CMT_RUNTIME_ENVIRONMENT production
// </IfModule>
// or as a shell environment variable
// $ CMT_RUNTIME_ENVIRONMENT=dev php index.php controller action
if (isset($_SERVER['CMT_RUNTIME_ENVIRONMENT'])) {
	$cmtRuntimeEnv = $_SERVER['CMT_RUNTIME_ENVIRONMENT'];
} else if (php_sapi_name() == 'cli') {
	$cmtRuntimeEnv = 'cli';
}
else if (getenv('CMT_RUNTIME_ENVIRONMENT')) {
	$cmtRuntimeEnv = getenv('CMT_RUNTIME_ENVIRONMENT');
}
else {
	$cmtRuntimeEnv = '';
}

setConstant('CLI', php_sapi_name() == 'cli');

setConstant('CMT_RUNTIME_ENVIRONMENT', $cmtRuntimeEnv);

setConstant('CMT_DEFAULT_DATABASE', 'default');

// Datenbankfehler Logging
setConstant('CMT_DBERRORLOG', '1');

// Sollen Cookies benutzt werden?
setConstant('CMT_USECOOKIES', '1');

// Cookies nutzen, auch wenn keine Cookies aktiviert sind?
setConstant('CMT_FORCECOOKIES', '1');

// Apache Mod-Rewrite nutzen?
setConstant('CMT_MODREWRITE', '1');

// Base Path for REST API. Comment to disable REST API. Must begin with a slash
// and match the settings in .htaccess!
setConstant('CMT_REST_API_BASE_PATH', '/cmtrestapi');

// Echo the time it took to render the page
setConstant('ECHOEXECUTIONTIME', '0');


/**
 * Paths and Directories
 */
// Pfad, in dem sich der ROOT der Homepage befindet (z.B. "/" bei www.meinedomain.de (Standard) oder 
// "myweb/" bei www.meinedomain/myweb/), immer mit führendem und ggf. abschließendem '/' angeben!
setConstant('WEBROOT', '/');

// Pfad/Ordner zum Admin-Bereich: Standard 'admin/'
setConstant('ADMINPATH', 'contentomat/');

// Zentraler Ordner für Downloads: Standard ist 'downloads/'
setConstant('DOWNLOADPATH', 'downloads/');

// URL des Skriptes
setConstant("SELF", basename($_SERVER['PHP_SELF']));

// aktueller Pfad minus WEBROOT ergibt den aktuellen ROOT
$actPath = preg_replace('/^'.preg_quote(WEBROOT, '/').'/', '', $_SERVER['PHP_SELF']);

// ROOT berechnen
$depth = substr_count ($actPath, '/');
setConstant('ROOT', str_pad('', $depth*3, '../'));

// PATHTOADMIN berechnen
setConstant('PATHTOADMIN', str_replace('//', '/', ROOT.ADMINPATH));

// PATHTODOWNLOADS berechnen
setConstant('PATHTODOWNLOADS', ROOT.DOWNLOADPATH);

// PATHTOWEBROOT berechnen
setConstant('PATHTOWEBROOT', ROOT);

// Include-Pfade für PHP-Skripte (im Idealfall immer absolut angeben!):
// Include-Pfad zum Webroot

// $includePath = empty($_SERVER['DOCUMENT_ROOT']) ? '' : ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
// define ('INCLUDEPATH', $includePath);
setConstant('INCLUDEPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Include-Pfad zum Adminbereich
setConstant('INCLUDEPATHTOADMIN', rtrim(INCLUDEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim(ADMINPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

setConstant('PATHTOTMP', PATHTOWEBROOT . 'tmp' . DIRECTORY_SEPARATOR);

@require_once(PATHTOADMIN . 'includes' . DIRECTORY_SEPARATOR . 'logger.inc');


/**
 * Log Files --needs Paths--
 */
// Log Level (see admin/classes/class_logger.php for available values and their meanings)
// Only log messages with this or higher log level
setConstant('CMT_LOG_LEVEL', LOG_LEVEL_ALL);
	
// Log Target(s)
setConstant('CMT_LOG_TARGET', LOG_TARGET_FILE);
	
// Path to Log File, if log target LOG_TARGET_FILE is selected (must be writable by web server user)
setConstant('CMT_LOG_FILE', PATHTOTMP . 'logs' . DIRECTORY_SEPARATOR . 'contentomat.log');
?>