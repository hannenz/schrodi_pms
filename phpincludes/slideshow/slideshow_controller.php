<?php
/**
 * slideshow_controller
 *
 * @author Johannes Braun <j.braun@agentur-halma.de>
 * @version $Id$
 * @copyright Johannes Braun <j.braun@agentur-halma.de>, 17 Juli, 2017
 * @package pms
 */
namespace PMS;

use Contentomat\PsrAutoloader;
use Contentomat\Controller;
use Contentomat\Contentomat;
use Contentomat\Gallery\Gallery;
use Contentomat\Debug;

class SlideshowController extends \Contentomat\Controller {

	/**
	 * @var Object
	 */
	protected $Gallery;

	/**
	 * @var int		ID of the category used for the slideshow images
	 */
	protected $categoryId = 3;


	public function init() {
		parent::init();
		$this->Gallery = new Gallery();
		$this->templatesPath = PATHTOWEBROOT . 'templates/slideshow/';

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
		$this->images = $this->Gallery->getImagesInCategory($this->categoryId, $options);

		$this->parser->setParserVar('slideshow_images', $this->images);
		$this->content = $this->parser->parseTemplate($this->templatesPath . 'slideshow.tpl');
	}
}

$autoLoad = new PsrAutoloader();
$autoLoad->addNamespace('Contentomat', PATHTOADMIN . 'classes');
$autoLoad->addNamespace('Contentomat\Gallery', PATHTOADMIN . 'classes/app_gallery');

$ctl = new SlideshowController();
$content = $ctl->work();
?>
