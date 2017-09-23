<?php
/**
 * references_controller
 *
 * @author Johannes Braun <j.braun@agentur-halma.de>
 * @version $Id$
 * @copyright Johannes Braun <j.braun@agentur-halma.de>, 16 Sep, 2017
 * @package pms
 */
namespace PMS;

use Contentomat\PsrAutoloader;
use Contentomat\Controller;
use Contentomat\Contentomat;
use Contentomat\Gallery\Gallery;
use Contentomat\Debug;

class ReferencesController extends \Contentomat\Controller {

	/**
	 * @var Object
	 */
	protected $Gallery;

	/**
	 * @var int		ID of the category used for the slideshow images
	 */
	protected $categoryId = 4;


	public function init() {
		parent::init();
		$this->Gallery = new Gallery();
		$this->templatesPath = PATHTOWEBROOT . 'templates/references/';

		$cmt = Contentomat::getContentomat();
		$applicationData = $cmt->getVar('applicationData');
		if ($applicationData['cmt_showname'] == 'Layout') {
			$this->parser->setParserVar('is_layoutmode', true);
		}
	}

	public function initActions() {
		parent::initActions();
	}

	public function actionDefault() {
		$options = [
			'orderBy' => 'gallery_image_position',
			'orderDir' => 'ASC'
		];
		$references = $this->Gallery->getImagesInCategory($this->categoryId, $options);

		$this->parser->setParserVar('references', $references);
		$this->content = $this->parser->parseTemplate($this->templatesPath . 'default.tpl');
	}
}

$autoLoad = new PsrAutoloader();
$autoLoad->addNamespace('Contentomat', PATHTOADMIN . 'classes');
$autoLoad->addNamespace('Contentomat\Gallery', PATHTOADMIN . 'classes/app_gallery');

$ctl = new ReferencesController ();
$content = $ctl->work();
?>

