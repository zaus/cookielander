<?php
/*

Plugin Name: Forms-3rdparty Submission Reformat
Plugin URI: https://github.com/zaus/forms-3rdparty-submission-format
Description: Reformat specific field submission
Author: zaus
Version: 0.2
Author URI: http://drzaus.com
Changelog:
	0.1	initial
	0.2 options-based
*/

class F3iFieldFormat {

	const N = 'F3iFieldFormat';
	const B = 'Forms3rdPartyIntegration';

	public function __construct() {
		// hook early to clean stuff out before other plugins
		add_filter(self::B.'_get_submission', array(&$this, 'field_format'), 22, 3);
	}

	public function field_format($submission, $form, $service) {
		$settings = F3iFieldFormatOptions::settings();

		$fields = explode(F3iFieldFormatOptions::FIELD_DELIM, $settings[F3iFieldFormatOptions::F_FIELDS]);

		// regex - pattern, replace
		$pattern = explode(F3iFieldFormatOptions::REGEX_DELIM, $settings[F3iFieldFormatOptions::F_PATTERNS]); // '/(\d+)\/(\d+)\/(\d+)/';
		$replace = explode(F3iFieldFormatOptions::REGEX_DELIM, $settings[F3iFieldFormatOptions::F_REPLACEMENTS]); //'$2-$1-$3';

		### _log('bouwgenius-date', $fields, $submission); 

		foreach($fields as $field) {
			if(isset($submission[$field]) && !empty($submission[$field])) {
				$x = preg_replace($pattern, $replace, $submission[$field]);

				### _log($submission[$field], $x, $field);

				$submission[$field] = $x;
			}
		}

		return $submission;
	}//--	fn	date_format
}//---	class	BouwgeniusDateFormat

// engage!
new F3iFieldFormat();

require('f3i-ff-options.php');
new F3iFieldFormatOptions(__FILE__);