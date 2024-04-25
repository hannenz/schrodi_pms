<?php
/**
 * Controller for displaying an OpenStreetMap
 *
 * @package schrodi-pms
 * @author Johannes Braun <johannes.braun@hannenz.de> 
 */
namespace PMS;

use Contentomat\Controller;
use Contentomat\Contentomat;

class MapsController extends Controller {

	/**
	 * @var Array
	 * Dummy coords, will be overriden by init
	 */
	protected $coords = [
		'lat' => 49,
		'lon' => 9
	];

	/**
	 * @var string
	 * HTML to be displayed as Popup on the Map
	 */
	protected $popupHTML = '';


	/**
	 * @var string
	 * Height of the map as CSS length value (e.g. "380px")
	 */
	protected $mapHeight = '';

	/**
	 * Get coordinates (lat/lon) from
	 * content object config (head1 => latitude, head2 => longitude)
	 */
	public function init() {

		parent::init();

		$cmt = Contentomat::getContentomat();
		$applicationData = $cmt->getVar('applicationData');
		if ($applicationData['cmt_showname'] == 'Layout') {
			$this->parser->setParserVar('is_layoutmode', true);
		}
		$contentData = $cmt->getVar('cmtContentData');
		if (!empty($contentData)) {
			if (!empty($contentData['head1'])) {
				$this->coords['lat'] = trim($contentData['head1']);
			}
			if (!empty($contentData['head2'])) {
				$this->coords['lon'] = trim($contentData['head2']);
			}
			if (!empty($contentData['head3'])) {
				$this->mapHeight = $contentData['head3'];
			}
			if (!empty($contentData['head4'])) {
				$this->popupHTML = $contentData['head4'];
			}
		}
		$this->templatesPath = PATHTOWEBROOT . 'templates/map/';
		
	}


	/**
	 * Render markup for the map
	 */
	public function actionDefault () {
		$this->parser->setMultipleParserVars($this->coords);
		if (!empty($this->mapHeight)) {
			$this->parser->setParserVar('mapHeight', $this->mapHeight);
		}
		if (!empty($this->popupHTML)) {
			$this->parser->setParserVar('popupHTML', $this->popupHTML);
		}
		$this->content = $this->parser->parseTemplate($this->templatesPath . 'map.tpl');
	}
}
$content = (new MapsController())->work();
