<?php
/**
 * Content-o-mat: CMS & Web Application Framework (https://www.content-o-mat.de)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Josef Hahn, Johannes Braun, Carsten Coull
 * @link      https://www.content-o-mat.de Content-o-mat Project
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @package Contentomat
 * @version 2022-02-15
 */

use \Contentomat\Contentomat;
use \Contentomat\Parser;
use \Contentomat\AppParser;
use \Contentomat\CmtPage;
use \Contentomat\SessionHandler;
use \Contentomat\User;
use \Contentomat\DBCex;
use \Contentomat\CLIUtils;
use \Contentomat\CLIColors;
use \Contentomat\RestApi;
use \Contentomat\Logger;

/**
 * Bootstrap: This class launches the website's frontend

 * Refactored to Bootstrap class on 2020-07-21,
 * Execution either by HTTP / Web Server or CLI (command line interface)
 *
 * @class Bootstrap
 * @author Johannes Braun <j.braun@agentur-halma.de>
 * @package Contentomat
 * @version 2022-02-15
 */
class Bootstrap {

	/**
	 * @var \Contentomat\Contentomat
	 */
	protected $Contentomat;

	/**
	 * @var \Contentomat\CmtPage
	 */
	protected $CmtPage;

	/**
	 * @var \Contentomat\Session
	 */
	protected $Session;

	/**
	 * @var \Contentomat\DBcex
	 */
	protected $db;


	// For CLI only

	/**
	 * @var array 		The arguments passed from command line
	 */
	protected $argv;

	/**
	 * @var string 		The name of the controller to run
	 */
	protected $controllerName;

	/**
	 * @var string 		The name of the action to execute
	 */
	protected $actionName = 'default';

	/**
	 * @var Contentomat\CLIUtils
	 */
	protected $CLIUtils;


	/**
	 * Constructor
	 */
	public function __construct() {

		/*
		 * Common intialisation
		 */
		try {
			require_once('cmt_constants.inc'); // `require_once` will stop execution if it fails to include the file anyway, no need to check.

			// Setup Autoloading
			$autoload = require(PATHTOADMIN . 'vendor/autoload.php');
			$autoload->addPsr4('Contentomat\\', [
				PATHTOWEBROOT . 'phpincludes/classes/',
				PATHTOADMIN . 'classes/'
			]);
			$autoload->addPsr4(APP_NAMESPACE . '\\', [
				PATHTOWEBROOT . 'phpincludes/classes/',
				PATHTOWEBROOT . 'phpincludes/Controller/'
			]);

			// Session überprüfen
			$this->Contentomat = Contentomat::getContentomat();
			$this->Session = SessionHandler::getSession();

			// Must be declared AFTER Session Init.
			setConstant('SELFURL', 
				(CMT_FORCECOOKIES == '0') 
					? SELF.'?sid='.SID 
					: SELF
			);

			// Check for maintenance mode
			if (defined('MAINTENANCE_MODE') && MAINTENANCE_MODE) {
				$user = new User($this->Session->getSessionID());
				if ($user->getUserType() != 'admin') {
					http_response_code(503);
					die("Site is in maintenance mode. Please try again later!");
				}
			}

			$this->db = new DBCex();


			switch (CMT_RUNTIME_ENVIRONMENT) {
				case "cli":
					$this->bootstrapCli();
					break;

				case "cmtrestapi":
					$this->bootstrapRestApi();
					break;

				default:
					$this->bootstrapHttp();
					break;
			}
		} catch (Exception $e) {
			http_response_code(500);

			switch (CMT_ERRORREPORTING_LEVEL) {
				case 'all':
				case 'warning':
				case 'error':
					$message = $e->xdebug_message ?: $e->getMessage();
					echo '<table>' . $message . '</table>';
					break;

				default:
					$this->CmtPage->displayErrorPage(500);
					break;
			}

			ob_end_flush();
		}
	}


	/*
	 * Bootstrap the application if called through HTTP Request ("Web")
	 */
	public function bootstrapHttp() {
		/* --------------------------------------------------
			Sichtbarkeit- und Domainüberprüfung
			-------------------------------------------------- */
		// Check for /handle pageless actions
		$this->Page = new CmtPage();

		if (!isset($_REQUEST['ctl'])) {
			$content = $this->Page->getPageToDisplay();
			$pageData = $this->Page->getPageData();
		}

		$this->Parser = new AppParser();

		// Seitendaten an Parser übergeben
		$this->Parser->setPagesTable($this->Page->getPagesTable());
		$this->Parser->setContentsTable($this->Page->getContentsTable());
		$this->Parser->setLinksTable($this->Page->getLinksTable());
		$this->Parser->setPathToWebroot(PATHTOWEBROOT);

		$this->Contentomat->setPage($this->Page);
		$this->Contentomat->setParser($this->Parser);

		// Handle pageless actions.
		// Pageless actions are Routes that are directly connected to a certain
		// controller's action. The routing is done via the URL:
		// `/{lang}/{controller}/{action}`
		// .f.e. `/en/UserGroups/show/7` 
		if (isset($_REQUEST['ctl'])) {
			$pageData = [];
			$lang = $this->Page->getPageLang();

			define('PAGEID', 0);
			define('PARENTID', 0);
			define('PAGELANG', $lang);

			// Get controller and action
			$ctlName = '\\' . APP_NAMESPACE . '\\' . $_REQUEST['ctl'] . 'Controller';

			if (!class_exists($ctlName)) {
				$this->Page->displayErrorPage();
				throw new \Exception("Controller not found `{$ctlName}`.");
			}

			// Execute controller
			$ctl = new $ctlName();
			$pageSource = $ctl->work();

			define('PAGETITLE', $ctl->getPageTitle());

			// Where to render the output in which page template
			$templateId = $ctl->getPageTemplateID() ?? 1;
			$group = $ctl->getPageTemplateGroup() ?? 1;

			$this->Parser->contentGroup = $group;
			$this->Parser->content_data = $pageSource;

			// Inject controller action response into page template
			$content = $this->Page->getPageTemplate(0, $lang, $templateId);
		}

		$this->Contentomat->render($content, $pageData);
	}


	/*
	 * Bootstrap the application if called by CLI (command line interface)
	 */
	public function bootstrapCli() {

		// We dont want HTML errors
		ini_set("html_errors", 0);

		$this->argv = $GLOBALS['argv'];
		$this->CLIUtils = new CLIUtils();

		$this->Contentomat->setParser(new AppParser());

		try {
			if (!empty($_SERVER['CMT_DEBUG'])) {
				$this->CLIUtils->out('Initializing …', 'info');
				$this->CLIUtils->out('ROOT:               ' . ROOT);
				$this->CLIUtils->out('INCLUDEPATH:        ' . INCLUDEPATH);
				$this->CLIUtils->out('INCLUDEPATHTOADMIN: ' . INCLUDEPATHTOADMIN);
				$this->CLIUtils->out('PATHTOWEBROOT:      ' . PATHTOWEBROOT);
				$this->CLIUtils->out('PATHTOADMIN:        ' . PATHTOADMIN);
				$this->CLIUtils->out('DOWNLOADPATH:       ' . DOWNLOADPATH);
			}

			// Discard first arg
			array_shift($this->argv);

			if (count($this->argv) == 0) {
				throw new Exception('No controller specified');
			}

			$this->controllerName = array_shift($this->argv);

			if (count($this->argv) > 0) {
				$this->actionName = array_shift($this->argv);
			}

			if (false) {
				$this->CLIUtils->out('Controller: ' . $this->controllerName);
				$this->CLIUtils->out('Action:     ' . $this->actionName);
				$this->CLIUtils->out('Remaining arguments');
				print_r($this->argv);
			}

			// Try to load controller
			$className = $this->controllerName . 'Controller';
			$actionName = 'action' . ucwords($this->actionName);

			$filename = $className . '.php'; //strtolower ($this->controllerName) . '_controller.php';
			$filepath = INCLUDEPATH . 'phpincludes' . DIRECTORY_SEPARATOR . $filename;

			if (!file_exists($filepath)) {
				$filepath = INCLUDEPATH . 'phpincludes' . DIRECTORY_SEPARATOR . strtolower($this->controllerName) . DIRECTORY_SEPARATOR . $filename;
				if (!file_exists($filepath)) {
					$filepath = INCLUDEPATH . 'phpincludes/Controller' . DIRECTORY_SEPARATOR . $filename;
					if (!file_exists($filepath)) {
						throw new Exception('Could not load controller from file: ' . $filepath);
					}
				}
			}

			if (!preg_match('/namespace (.*)\;/', file_get_contents($filepath), $matches)) {
				throw new Exception('Could not determine namespace from file: ' . $filepath);
			}

			// Workaround, for we cannot call the action directly: Pass action
			// and args as Contentomat vars and eact to these in initActions method
			$this->Contentomat->setVar('cliAction', $this->actionName);
			$this->Contentomat->setVar('cliArgs', $this->argv);

			if (!require_once($filepath)) {
				throw new Exception('Could not load controller from file: ' . $filepath);
			}

			$ns = $matches[1];
			$className = $ns . '\\' . $className;
			$ctl = new $className();
			$content = $ctl->work();
		}
		catch (Exception $e) {
			$this->CLIUtils->out('CLI init: ERROR: ' . $e->getMessage(), 'error');
		}
	}

	public function bootstrapRestApi() {
		// We dont want HTML errors
		ini_set("html_errors", 0);

		if (!defined('CMT_REST_API_BASE_PATH')) {
			die("REST API is disabled\n");
		}

		$this->RestApi = new RestApi([
			'basePath' => CMT_REST_API_BASE_PATH,
			'adminIsAllowed' => (
				defined('CMT_REST_API_ALLOW_ADMIN') && 
				(boolval(CMT_REST_API_ALLOW_ADMIN) === true)
			)
		]);
		$this->RestApi->run();
	}
}

new Bootstrap();
