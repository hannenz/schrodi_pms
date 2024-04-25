<?php
namespace Contentomat;


/**
 * This class can be used to extend Contentomat's parser with project specific
 * macros.
 * See the dummy macro on how to do this
 *
 * @class AppParser
 * @author Johannes Braun <j.braun@agentur-halma.de>
 * @package Contentomat
 * @version 2021-04-16
 */
class AppParser extends Parser {
		
	/**
	 * executes before page is parsed
	 */
	public function preParser($content = '') {
		return $content;
	}
	
	
	/**
	 * executes after page is parsed put before output
	 */
	public function postParser($content = '') {
		return $content;
	}
	
	
	/**
	 * Dummy Macro for demonstration purposes
	 *
	 * @param string $value 		This is the first parameter to the macro
	 * 								after the first colon, e.g. {DUMMY:foo:bar} $value will be `foo`
	 * @param params Array 			Anny firther colon-separated parameters are
	 * 								passed in this array
	 * @return string 				Return a string with which the macro
	 * 								definition will be replaced
	 *
	 * @access public
	 * @example
	 *
	 * If in a template the macro is called like this
	 * ```
	 * {DUMMY:foo:bar:baz}
	 * ```
	 * This method will be passed
	 *
	 * $value = 'foo'
	 * $params = [
	 * 	'bar',
	 * 	'baz'
	 * ];
	 */
	public function macro_DUMMY($value, $params) {
		$retval = sprintf("I am the dummy macro called with \$value='%s' and \$params=[%s]",
			$value,
			join(',', $params)
		);
		return $retval;
	}
	
}
