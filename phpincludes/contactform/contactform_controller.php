<?php
namespace PMS;

use \Contentomat\Debug;
use \Contentomat\PsrAutoloader;
use \Contentomat\Controller;
use \Contentomat\Mail;
use \Exception;

class ContactformController extends Controller {

	/*
	 * @var string
	 * Contact mail recipient address
	 */
	protected $recipientEmail = 'johannes.braun@hannenz.de';	
	
	/*
	 * @var Object
	 */
	protected $Mail;

	public function init() {
		parent::init();

		$this->Mail = new Mail();

		$this->templatesPath = PATHTOWEBROOT . 'templates' . DIRECTORY_SEPARATOR . 'contactform' . DIRECTORY_SEPARATOR;
	}



	public function actionDefault () {

		try {
			if (!empty($this->postvars)) {

				// Validate
				$success = true;
				if (empty($this->postvars['lastname'])) {
					$this->parser->setParserVar('validation-error-lastname', true);
					$success = false;
				}
				if (empty($this->postvars['email']) || !filter_var($this->postvars['email'], FILTER_VALIDATE_EMAIL)){
					$this->parser->setParserVar('validation-error-email', true);
					$success = false;
				}
				if (empty($this->postvars['message'])) {
					$this->parser->setParserVar('validation-error-message', true);
					$success = false;
				}


				if ($success) {
					$this->parser->setMultipleParserVars($this->postvars);
					$this->parser->setMultipleParserVars(array(
						'date' => strftime('%F %T'),
						'host' => $_SERVER['SERVER_NAME']
					));
					$text = $this->parser->parseTemplate($this->templatesPath . 'mailbody.tpl');
					$html = $this->parser->parseTemplate($this->templatesPath . 'mailbody.html.tpl');

					$ret = $this->Mail->send(array(
						'recipient' => $this->recipientEmail,
						'subject' => 'Kontaktanfrage SCOPE Architekten Webformular',
						'text' => $text,
						'html' => $html
					));

					if (!$ret) {
						throw new Exception('Sending Email failed');
					}

					$this->parser->setParserVar('success', true);
				}
			}

			$this->parser->setMultipleParserVars($this->postvars);
			$this->content = $this->parser->parseTemplate($this->templatesPath . 'form.tpl');
		}
		catch (Exception $e) {
			$this->content = 'Ein Fehler ist aufgetreten: ' . $e->getMessage();
		}
	}
}

$autoLoad = new PsrAutoloader();
$autoLoad->addNamespace('Contentomat', INCLUDEPATHTOADMIN . 'classes');
$autoLoad->addNamespace('Scope', INCLUDEPATH . 'phpincludes/classes');

$ctl = new ContactformController();
$content .= $ctl->work();
?>
